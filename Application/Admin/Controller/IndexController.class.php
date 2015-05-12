<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Index类
 *
 *
 *
 */
class IndexController extends CommonController {
    /**
     * index方法：显示水源列表
     * @return 水源列表页面
     */                         
    public function Index() {
        //改变左侧栏目相关按钮
        $this->assign('waterLise','class="active"');

        //设置检索条件
        $map=array();
        $map['isDelete']=0;
        
        if(I('get.title') != "") {
            $map['title']= array('like', '%'.I('get.title').'%');
        }
        if(I('get.collector')) {
            $userInfoArr=M('user')->where('realname="'.I('get.collector').'"')->find();
            if(!$userInfoArr) {
                $this->error('该采集员不存在，请核实');
            }
            $map['userId']=$userInfoArr['id'];
        }
        if( I('get.type') !="" ) {
            $map['type'] = array('eq',I('get.type'));	
        }
        if(I('get.status') != "") {
            $map['status'] = array('eq',I('get.status'));	
        }
        if(I('get.pressure') != "") {
            $map['pressure'] = array('eq',I('get.pressure'));
        }
        if(I('get.confirm') != "") {
            switch (I('get.confirm')) {
                case 2:
                    $map['confirm'] = array('eq','0');
                    break;
                case -1:
                    $map['confirm'] = array('eq',I('get.confirm'));
                    break;
                case 1:
                    $map['confirm'] = array('eq',I('get.confirm'));
                    break;
            }
        }

        //只有起始日期，没有结束日期
        if(I('get.beginDate')!="" and I('get.endDate')=='') {
            $map['date']=array('egt',I('get.beginDate')." 00:00:01");
        }
        //没有起始日期，只有结束日期
        if(I('get.beginDate')=="" and I('get.endDate')!='') {
            $map['date']=array('elt',I('get.endDate')." 23:59:59");
        }
        //有起始日期，也有结束日期
        if(I('get.beginDate')!="" and I('get.endDate')!='') {
            $map['date']=array('between',array(I('get.beginDate')." 00:00:01",I('get.endDate')." 23:59:59"));
        }

        //开始分页输出数据
        $water = M('water');
        $pagenumber = 10; //每页条数
        $all = $water->where($map)->count();
        $maxpage = ceil($all/$pagenumber)+1;
        
        $page=$_GET['page'];
        
        //如果没有传来分页数据，则为第一页
        if(!isset($page)) {
            $page=1;
        }

        $cs = $water->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();

        //查询采集员姓名
        $user=M('user')->field('id,realname')->select();
        $userKey=array();
        $userValue=array();
        for($a=0;$a<count($user);$a++) {
            $userKey[$a]=$user[$a]['id'];
            $userValue[$a]=$user[$a]['realname'];
        }
        $userArr=array_combine($userKey,$userValue);

        for($i=0;$i<count($cs);$i++) {
            $cs[$i]['userRealname']=$userArr[$cs[$i]['userId']];
        }

        //水源统计
        $waters=M('water')->where('isDelete=0')->count();
        $waterConfirmed=M('water')->where('confirm=1 and isDelete=0')->count();
        $waterWaiting=M('water')->where('confirm=0 and isDelete=0')->count();
        $waterRefused=M('water')->where('confirm=-1 and isDelete=0')->count();

        $this->assign('waters',$waters);
        $this->assign('waterConfirmed',$waterConfirmed);
        $this->assign('waterWaiting',$waterWaiting);
        $this->assign('waterRefused',$waterRefused);



        $this->assign('data',$cs);
        $this->assign('page',$page);
        $this->assign('maxpage',$maxpage);
        $this->assign('pagenumber',$pagenumber);
        $this->assign('all',$all);
        $this->display();
    }

    /**
     * map方法：在百度地图上显示水源位置
     * @return 百度地图页面
     */
    public function Map() {
        $this->assign('waterMap','class="active"');

        //从数据库取数据
        $water = M('water');
        $map=array();
        $map['isDelete']=0;
        $list = $water ->where($map)-> select();

        //采集员信息
        $user=M('user')->field('id,realname')->select();
        $userKey=array();
        $userValue=array();
        for($a=0;$a<count($user);$a++) {
            $userKey[$a]=$user[$a]['id'];
            $userValue[$a]=$user[$a]['realname'];
        }
        $userArr=array_combine($userKey,$userValue);


        //将结果转化为json数据格式
        $res = array();
        for($i=0;$i<count($list);$i++) {
            $res[$i]['lat']=$list[$i]['latitude'];
            $res[$i]['lng']=$list[$i]['longitude'];
            //每行字符最好不超过80个，故做如下处理
            $type = $list[$i]['type'];

            //根据不同的类型进行处理


            $type=$list[$i]['type'];
            $content='';
            if($type==1 or $type==2 or $type==3) { //地上式或者地下式
                switch($type) {
                    case 1:
                        $content.='地上式消防栓';
                        break;
                    case 2:
                        $content.='地下式消防栓';
                        break;
                    case 3:
                        $content.='消防水鹤';
                        break;
                }
                if($type==1) { //水源编号只有地上式才有
                    $content.='<br>水源编号：'.$list[$i]['hydrantId'];
                }
                if($list[$i]['belong']==1) {
                    $content.='<br>所属单位：'.$list[$i]['unit'];
                }
                else {
                    $content.='<br>所属单位：市政';
                }
                if($type==1 or $type==2) { //地上式与地下式有管径，消防水鹤没管径
                    //管径
                    switch($list['calibre']) {
                        case '0':
                            $content.='<br>管径：65mm/100mm';
                            break;
                        case '1':
                            $content.='<br>管径：其他';
                            break;
                        default:
                            $content.='';
                    }
                }
                if($type==3) { //只有消防水鹤有高度
                    $content.='<br>高度：'.$list[$i]['height'].'米';
                }

                //水压
                switch($list[$i]['pressure']) {
                    case '1':
                        $content.='<br>水压：<font color="green">正常</font>';
                        break;
                    case '2':
                        $content.='<br>水压：<font color="red">不足</font>';
                        break;
                    default:
                        $content.='<br>水压：<font color="">未知</font>';
                }
                //判断是否全天有水
                switch($list[$i]['status']) {
                    case '1':
                        $content.='<br>状态：<font color="green">全天有水</font>';
                        break;
                    case '2':
                        $content.='<br>状态：<font color="green">非全天有水</font>';
                        break;
                    case '3':
                        $content.='<br>状态：<font color="#808080">损坏</font>';
                        break;
                    default:
                        $content.='<br>全天有水：未知';
                }
                $content.='<br>联系人：'.$list[$i]['contacts'];
                $content.='<br>联系电话：'.$list[$i]['phone'];
                $content.='<br>最近更新：'.$list[$i]['date'];
            }
            else if($type==4) { //消防水池
                $content.='消防水池';
                $content.='<br>蓄水量：'.$list[$i]['capacity'].'吨';
                $content.='<br>联系人：'.$list[$i]['contacts'];
                $content.='<br>联系电话：'.$list[$i]['phone'];
                $content.='<br>最近更新：'.$list[$i]['date'];
            }
            else if($type==5) { //天然水源
                $content.='天然水源';
                $content.='<br>取水距离：'.$list[$i]['height'].'米';
                $content.='<br>联系人：'.$list[$i]['contacts'];
                $content.='<br>联系电话：'.$list[$i]['phone'];
                $content.='<br>最近更新：'.$list[$i]['date'];
            }

            $content.='<br>采集员：'.$userArr[$list[$i]['userId']];

            $res[$i]['title']   =  $list[$i]['title'];
            $res[$i]['content'] =  $content.'<br><a href="?m=Admin&c=Index&a=detail&id='.$list[$i]['id'].'" target="_blank">查看详情</a><br><a href="javascript:void(0);" onclick="ajax_delete('.$list[$i]['id'].')">删除</a>';//信息尚未补全

            $res[$i]['point']   =  $list[$i]['longitude'].'|'.$list[$i]['latitude'];
            $res[$i]['isOpen']  =  0;

            //根据审核状态（confirm字段）调整地图指针图标的样式
            if($list[$i]['confirm'] == 0) { //待审核状态
                if($list[$i]['status']==3) { //损坏
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_icon.png";
                }
                else if($list[$i]['status']==2 or $list[$i]['pressure']==2) { //非全天有水或者水压低
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_icon.png";
                }
                else {
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_icon.png";
                }
            }
            if($list[$i]['confirm'] == -1) { //未通过状态
                if($list[$i]['status']==3) { //损坏
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_icon.png";
                }
                else if($list[$i]['status']==2 or $list[$i]['pressure']==2) { //非全天有水或者水压低
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_icon.png";
                }
                else {
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_icon.png";
                }
            }
            if($list[$i]['confirm'] == 1) { //已通过状态
                if($list[$i]['status']==3) { //损坏
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_pin_icon.png";
                }
                else if($list[$i]['status']==2 or $list[$i]['pressure']==2) { //非全天有水或者水压低
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_pin_icon.png";
                }
                else {
                    $res[$i]['icon']    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_pin_icon.png";
                }
            }
        }

        $json = json_encode($res);//将数组结果转化为json格式

        $lat=38.982752;
        $lng=117.033713;
        $this->assign('lat',$lat);
        $this->assign('lng',$lng);
        $this -> assign('json',$json);
        $this -> display();
    }

    /**
     * detail方法：显示水源详情
     * @return 详情页面
     */
    public function detail() {
        $id = I('get.id');
        if(!is_numeric($id)) { //如果GET的数据不是数字类型，则报错
            $this -> error('请检查你的网址是否正确');
        }
        //查询数据表
        $list = M('water') -> where('id='.$id)->find();
        if(!$list) { //如果查询结果为空
            $this -> error('您要查找的水源不存在');
        }

        //采集员信息
        $user=M('user')->field('id,realname')->select();
        $userKey=array();
        $userValue=array();
        for($a=0;$a<count($user);$a++) {
            $userKey[$a]=$user[$a]['id'];
            $userValue[$a]=$user[$a]['realname'];
        }
        $userArr=array_combine($userKey,$userValue);

        //根据水源类型$type
        $type=$list['type'];
        $content='';
        if($type==1 or $type==2 or $type==3) { //地上式或者地下式
            switch($type) {
                case 1:
                    $content.='地上式消防栓';
                    break;
                case 2:
                    $content.='地下式消防栓';
                    break;
                case 3:
                    $content.='消防水鹤';
                    break;
            }
            if($type==1) { //水源编号只有地上式才有
                $content.='<br>水源编号：'.$list['hydrantId'];
            }
            if($list['belong']==1) {
                $content.='<br>所属单位：'.$list['unit'];
            }
            else {
                $content.='<br>所属单位：市政';
            }
            if($type==1 or $type==2) { //地上式与地下式有管径，消防水鹤没管径
                //管径
                switch($list['calibre']) {
                    case '0':
                        $content.='<br>管径：65mm/100mm';
                        break;
                    case '1':
                        $content.='<br>管径：其他';
                        break;
                    default:
                        $content.='';
                }
            }
            if($type==3) { //只有消防水鹤有高度
                $content.='<br>高度：'.$list['height'].'米';
            }

            //水压
            switch($list['pressure']) {
                case '1':
                    $content.='<br>水压：<font color="green">正常</font>';
                    break;
                case '2':
                    $content.='<br>水压：<font color="red">不足</font>';
                    break;
                default:
                    $content.='<br>水压：<font color="">未知</font>';
            }
            //判断是否全天有水
            switch($list['status']) {
                case '1':
                    $content.='<br>状态：<font color="green">全天有水</font>';
                    break;
                case '2':
                    $content.='<br>状态：<font color="green">非全天有水</font>';
                    break;
                case '3':
                    $content.='<br>状态：<font color="#808080">损坏</font>';
                    break;
                default:
                    $content.='<br>全天有水：未知';
            }
            $content.='<br>联系人：'.$list['contacts'];
            $content.='<br>联系电话：'.$list['phone'];
            $content.='<br>最近更新：'.$list['date'];

        }
        else if($type==4) { //消防水池
            $content.='消防水池';
            $content.='<br>蓄水量：'.$list['capacity'].'吨';
            $content.='<br>联系人：'.$list['contacts'];
            $content.='<br>联系电话：'.$list['phone'];
            $content.='<br>最近更新：'.$list['date'];
        }
        else if($type==5) { //天然水源
            $content.='天然水源';
            $content.='<br>取水距离：'.$list['height'].'米';
            $content.='<br>联系人：'.$list['contacts'];
            $content.='<br>联系电话：'.$list['phone'];
            $content.='<br>最近更新：'.$list['date'];
        }

        $content.='<br>采集员：'.$userArr[$list['userId']];

        $res[0]['title']=$list['address'];
        $res[0]['point'] = $list['longitude'].'|'.$list['latitude'];


        //改变图标状态
        switch($list['confirm']) {
            case '0': //待审核状态
                if($list['status']==3) { //损坏
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_icon.png";
                }
                else if($list['status']==2 or $list['pressure']==2) { //非全天有水或者水压低
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_icon.png";
                }
                else { //正常
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_icon.png";
                }

                break;
            case '1': //已通过
                if($list['status']==3) { //损坏
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_pin_icon.png";
                }
                else if($list['status']==2 or $list['pressure']==2) { //非全天有水或者水压低
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_pin_icon.png";
                }
                else { //正常
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_pin_icon.png";
                }
                break;
            case '-1': //被驳回
                if($list['status']==3) { //损坏
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/gray_icon.png";
                }
                else if($list['status']==2 or $list['pressure']==2) { //非全天有水或者水压低
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/yellow_icon.png";
                }
                else { //正常
                    $waterIcon    =  "http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_icon.png";
                }
                break;
            default:
                $waterIcon="http://www.zwin.mobi/xiaofang/Public/static/BaiduMapIcon/red_icon.png";
                break;
        }

        $json = json_encode($res);//将数组结果转化为json格式

        //查询采集员姓名
        $collectorInfo=M('user')->where('id='.$list['userId'])->find();
        $realname=$collectorInfo['realname'];
        $phone=$collectorInfo['IPone'];

        //查询图片
        $pic = M('file') -> where('uid='.$id) -> select();
        //生成中心点经纬度信息
        $lng=$list['longitude'];
        $lat=$list['latitude'];

        $this -> assign('lng',$lng);
        $this -> assign('lat',$lat);

        $this->assign('waterIcon',$waterIcon);
        $this->assign('waterContent',$content);
        $this->assign('title',$res[0]['address']);

        $this->assign('phone',$phone);
        $this->assign('realname',$realname);
        $this -> assign('pic_vo',$pic);
        $this -> assign('water_id',$id);
        $this -> assign('list',$list);
        $this -> assign('json',$json);
        $this ->display();
    }

    /**
     * update方法：
     * @param 
     * @return 
     */
    public function update() {
        
/*
        //这是更新图片功能，由于甲方暂时不需要该功能，故先注释掉
        $pic_arr = I('post.pic');
        if($pic_arr[1]) {
            unlink("Uploads/pic/14/1.jpg");
            $res1 = copy ($pic_arr[1],"Uploads/pic/14/1.jpg");
        }

        if($pic_arr[2]) {
            unlink("Uploads/pic/14/2.jpg");
            $res = copy ($pic_arr[2],"Uploads/pic/14/2.jpg");
        }
*/

        $water = M('water');
        $date = $water -> create();
        $date['confirm'] = '1';
        unset($data['id']);
        if(I('post.result')=='pass') {
            $date['confirm']=1;
        }
        if(I('post.result')=='failPass') {
            $date['confirm']=-1;
        }
        $res = $water -> where('id='.I('post.id')) -> save($date);
        if($res or $res1 or $res2) {
            $this -> success('更新成功','?m=Admin&c=Index&a=Index');
        }
        else {
            $this -> error('数据未更新');
        }
    }

    /**
     * ajax_delete方法：ajax方式删除水源
     * @return 删除结果
     */
    public function ajax_delete() {
        $id=I('get.id');
        $water = M('water');
        $res = $water -> where('id ='.$id) -> setField('isDelete',1);
        if($res) {
            $data['str'] = '删除成功';
        }
        else {
            $data['str'] = '删除失败';
        }
        $this -> ajaxReturn($data);
    }


    /**
     * upload方法
     *
     */
    public function upload() {
        $id = I('get.water_id');
        $this -> assign('water_id',$id);
        $this -> display();
    }
    
    /**
     * upload_pic方法：实现图片上传
     * 
     */
	public function upload_pic() {
		//开始上传		
		$upload = new \Think\Upload();// 实例化上传类
		$upload->maxSize   =     3145728 ;// 设置附件上传大小
		$upload->exts      =     array('jpg', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath  =     "./Uploads/"; // 设置附件上传根目录
		$upload->savePath  =     "temp/"; // 设置附件上传（子）目录
		$upload->saveName  =     I('get.water_id').time();//以用户的uid命名，方便查找和删除
		$upload->autoSub   =     false;
		// 上传文件
		$info = $upload->uploadOne($_FILES['pic']);//采用uploadOne方法
        
		if(!$info) {// 上传错误提示错误信息
			echo '<script>parent.showPic("'.$upload->getError().'");</script>';
			exit;
		}
		else{// 上传成功
            echo '<script>parent.showPic("上传成功","'.$info['savepath'].$info['savename'].'");</script>';
		}
	}

    
    //该类结束
}
