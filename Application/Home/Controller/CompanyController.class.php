<?php
//
namespace Home\Controller;
use Think\Controller;
class CompanyController extends Controller {
    public function Base(){
        $this->assign('m2','class="active"');
        if(is_Complogin()) {
        if(!IS_POST) {
                $company = M("Company");
                $map = array('id' => session("user_id"));
            
                $data = $company->where($map)->select();
                $this->assign('data',$data[0]);
                $this->display();
        }
        else {
                $company = M('Company');
                //$data["id"] = I("post.id");
                $data["company"] = I('post.company');
                $data["where"] = I('post.cmbProvince').'-'.I('post.cmbCity').'-'.I('post.cmbArea');
                $data["subWhere"] = I('post.subWhere');
                $data["bLicence"] = I('post.bLicence');
                $data["orgID"] = I('post.orgID');
                $data["buildTime"] = I('post.buildTime');
                $data["regCapital"] = I('post.regCapital');
                $data["paidUpCapital"] = I('post.paidUpCapital');
                $data["comDirector"] = I('post.comDirector');
                $data["mobile"] =I('post.mobile') ;
                $data["email"] = I('post.email');
                //$data["mainBusiness"] = I('post.mainBusiness');
                //$data["passRegconition"] = I('post.passRegconition');
                $data["allCapital"] = I('post.allCapital');
                $data["netAssets"] = I('post.netAssets');
                $data["salesIncome"] = I('post.salesIncome');
                $data["netProfit"] = I('post.netProfit');
                $data["loginTime"] = time_format(NOW_TIME);
                $data["regIp"] = get_client_ip(0);
                $data["isDeleted"] = 0;
                $data["isArchived"] = 0;
                //dump($data);
                    if($company->where('id='.I('post.id'))->save($data)==1)
                    {
                        if(I('post.isnext')=="1")
                        {
                            $this->success("修改成功", U('Company/Files'));    
                        }else
                        {
                            $this->success("修改成功");
                        }
                    }else
                    {
                        $this->error('更新失败');
                    }
                    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
            
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    public function files(){
        $this->assign('m16','class="active"');
        if(is_Complogin())
        {
            if(IS_POST)
            {
                $filenum = M('file')->where('companyid='.session("user_id").' and type in (1,2,3,4,5,6,7,8,10,11)')->count();
                if($filenum<10)
                {
                    $this->error('上传资料不全，请补充后再提交');
                    return;
                }

                    
                $this->success('提交成功，请您仔细检查项目申请资料然后提交审核',U('Company/Apply'),3);
            }else
            {
            $company =M('company')->where('id='.session("user_id"))->find();
            $this->assign('data',$company);
            $this->display();
            }
            return;
        }
        if(is_AdminLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_GuestLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_OperLogin())
        {
            $this->redirect('Review/Index/Index');
            return;
        }
        
        $this->error('您还没有登录，请先登录！', U('User/Login'));
    }
    public function info1(){
            $this->assign('m6','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);


                        //dump($qiyetedian);

                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            //var_dump($info);
                            $info->auto($rules)->save();
                            
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }

                        $qiyetedian = '';
                        $qiyetedians = $_POST['qiyetedian'];
                        for($i=0;$i < count($qiyetedians);$i++){
                            $qiyetedian = $qiyetedian.','.$qiyetedians[$i];
                        }

                        //dump($qiyetedian);


                        $info->where("id=".session("user_id"))->setField('qiyetedian',$qiyetedian); //企业特点多选
                        
                        

                        if(I('post.isnext')=="1")
                        {
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag1','1');                            


                            $this->success("提交成功，请填写项目基本情况", U('Project/Info1'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                    
                    $vo = M('info')->where("id=".session("user_id"))->find();
                    $c = M("company")->where("id=".session("user_id"))->find();
                    if(!isset($vo))
                    {
                        $vo["qiyemingcheng"] = $c["company"];
                        $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                        $vo["zuzhijigoudaima"] = $c["orgID"];
                        $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                        $vo["zuceziben"] = $c["regCapital"];
                        $vo["shishouziben"] = $c["paidUpCapital"];
                        
                    }
                    $vo["comDirector"] = $c["comDirector"];
                    $vo["mobile"] = $c["mobile"];
                    $vo["email"] = $c["email"];
                    $this->assign("vo",$vo);
                    $company =M('company')->where('id='.session("user_id"))->find();
                    $this->assign('data',$company);
                    //var_dump($company);

                    //var_dump($vo);
                    $qiyetedian = $vo["qiyetedian"];
                    //$qiyetedian='认定的高新技术企业,留学人员办的企业';
                    $tedianarray = explode(',',$qiyetedian);
                    for($i=0;$i < count($tedianarray);$i++){
                        if($tedianarray[$i]=='认定的高新技术企业'){
                         $this->assign('gaoxin','checked');
                        }
                        if($tedianarray[$i]=='大专院校办的企业'){
                         $this->assign('yuanxiao','checked');
                        }
                        if($tedianarray[$i]=='科研院所办的企业'){
                         $this->assign('keyan','checked');
                        }
                        if($tedianarray[$i]=='留学人员办的企业'){
                         $this->assign('liuxue','checked');
                        }
                        if($tedianarray[$i]=='科研院所整体转制企业'){
                         $this->assign('zhuanzhi','checked');
                        }
                        if($tedianarray[$i]=='高新园区（开发区）内的企业'){
                         $this->assign('gaoxinqu','checked');
                        }
                        if($tedianarray[$i]=='创业服务中心内的企业'){
                         $this->assign('chuangye','checked');
                        }
                    }
                    
        
                    
                    $this->display();
        
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    public function save()
    {
        if(!is_Complogin())
        {
                $this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
            }
              if(IS_POST)
            {
                $data = $_POST ;
                $data["updateTime"] = time_format(NOW_TIME);
            $info = M("infoGudong");
                
                $info->create($data);
                if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
                {
                    $info->save();
                }
                else
                {
                    $this->ajaxReturn(array('status'=>'这不是你的项目'));
                }
            }
            $this->ajaxReturn(array('status'=>'OK'));
    }
    public function del()
    {
        if(!is_Complogin())
        {
                $this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
            }
        
            if(IS_POST)
            {
                    $data = $_POST ;
                    $data["updateTime"] = time_format(NOW_TIME);
                    $data["isDeleted"] = 1;
                $info = M("infoGudong");
                                
                    $info->create($data);
                    if($info->where("id=".I("post.id")." and uid=".session("user_id"))->count()>0)
                    {
                        $info->save();
                    }
                    else
                    {
                        $this->ajaxReturn(array('status'=>'这不是你的项目'));
                    }
            }
            $this->ajaxReturn(array('status'=>'OK'));
       }
    
    public function add()
    {
        if(!is_Complogin())
        {
                $this->ajaxReturn(array('status'=>'未登录，或账户不正确'));
            }
          if(IS_POST)
            {
                $data = $_POST ;
                $data["updateTime"] = time_format(NOW_TIME);
                $info = M("infoGudong");
                $data["uid"]=session("user_id");
                $data["isDeleted"] = 0;
                $info->create($data);
                $info->auto($rules)->add();
                
            }
            $this->ajaxReturn(array('status'=>'OK'));
      }


    public function info2(){
        $this->assign('m18','class="active"');
        if(is_Complogin())
        {
            $company =M('company')->where('id='.session("user_id"))->find();
            $this->assign('data',$company);
            $this->display();
         
            return;
        }
        if(is_AdminLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_GuestLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_OperLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        
        $this->error('您还没有登录，请先登录！', U('User/Login'));
    }
 
    
    public function filedetail($type)
    {
        $uid=I('get.userid');
        if($uid=="")
        {
            $uid=    session("user_id");
        }
        $file = M('file');
        $f = $file->where('isDeleted=0 and type = '.$type.' and uploaduser='.$uid)->find();    
        //dump($f);
        if($f)
        {
            $this->show("<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a>");    
        }else
        {
            $this->show('未上传文件');     
        }
    }
    public function filesdetails($type)
    {
        $uid=I('get.userid');
        if($uid=="")
        {
            $uid=    session("user_id");
        }
        if(I("get.act")=="del")
        {
            $d["isDeleted"]=1;
            M('file')->where("uid='".I("get.uid")."'")->save($d);
        }
        $company =M('company')->where('id='.$uid)->find();
        $file = M('file');
        $fs = $file->where('isDeleted=0 and type = '.$type.' and uploaduser='.$uid)->select();    
        //dump($f);
        if($fs)
        {
            $content="";
            foreach($fs as $f)
            {
                //dump($company.status);
                if($company["status"] <= 5)
                {
                    $content=$content."<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a> | <a href='{:U(Company/FilesDetails)}&type=".$type."&act=del&uid=".$f['uid']."'>删除此附件</a><br/>";    
                }    else
                {
                    $content=$content."<a target='_blank' href='/Public/".$f['url']."'>".$f['title']."</a> <br/>";
                }
            }
            $this->show($content);
        }else
        {
            $this->show('未上传文件');    
        }
        
    }
    public function upload()
    {
        
            /* 调用文件上传组件上传文件 */
            $config = array(
                'maxSize'    =>    10240000,
                'savePath'   =>    './Uploads/',
                'rootPath'     =>         './Public/',
                'saveRule'   =>    com_create_guid,
                'exts'       =>    array('jpg', 'gif', 'png', 'jpeg','doc','docx','xls','xlsx','zip','rar'),
                'autoSub'    =>    true,
                'subName'    =>    'get_user_id',
            );
            $upload = new \Think\Upload($config);// 实例化上传类
            if($_FILES['file1'])
            {
                $info =   $upload->uploadOne($_FILES['file1']);
                $type = 1;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach1','1'); //企业承诺书
            }
            if($_FILES['file2'])
            {
                $info =   $upload->uploadOne($_FILES['file2']);
                $type = 2;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach2','1'); //营业执照
            }
            if($_FILES['file3'])
            {
                $info =   $upload->uploadOne($_FILES['file3']);
                $type = 3;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach3','1');//组织机构
            }
            if($_FILES['file4'])
            {
                $info =   $upload->uploadOne($_FILES['file4']); //国税
                $type = 4;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach4','1');
            }
            if($_FILES['file5']) //地税
            {
                $info =   $upload->uploadOne($_FILES['file5']);
                $type = 5;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach5','1');
            }
            if($_FILES['file6'])//天津市科技型中小企业认定证书
            {
                $info =   $upload->uploadOne($_FILES['file6']);
                $type = 6;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach6','1');
            }
            if($_FILES['file7'])//企业章程
            {
                $info =   $upload->uploadOne($_FILES['file7']);
                $type = 7;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach7','1');
            }
            if($_FILES['file8'])//公司成立验资报告
            {
                $info =   $upload->uploadOne($_FILES['file8']);
                $type = 8;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach8','1');
            }
            if($_FILES['file9'])
            {
                $info =   $upload->uploadOne($_FILES['file9']);
                $type = 9;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach9','1');
            }
            if($_FILES['file10'])//前一个月资产负债表
            {
                $info =   $upload->uploadOne($_FILES['file10']);
                $type = 10;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach10','1');
            }
            if($_FILES['file11'])//前一个月损益表
            {
                $info =   $upload->uploadOne($_FILES['file11']);
                $type = 11;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach11','1');
            }
            if($_FILES['file12'])//专利证书
            {
                $info =   $upload->uploadOne($_FILES['file12']);
                $type = 12;
                    $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach12','1');
            }
            if($_FILES['file13'])//高新证书
            {
                $info =   $upload->uploadOne($_FILES['file13']);
                $type = 13;
                    $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach13','1');
            }
            if($_FILES['file14'])//银行信用等级
            {
                $info =   $upload->uploadOne($_FILES['file14']);
                $type = 14;
                    $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach14','1');
            }
            if($_FILES['file15'])//其它附件一
            {
                $info =   $upload->uploadOne($_FILES['file15']);
                $type = 15;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach15','1');
            }
            if($_FILES['file16'])//其它附件二
            {
                $info =   $upload->uploadOne($_FILES['file16']);
                $type = 16;
                $company = M('company');
                $company->where("id=".session("user_id"))->setField('attach16','1');
            }
            if(!$info) {// 上传错误提示错误信息
                //dump($upload);
               $this->show("<script>alert('".$upload->getError()."')</script>");
               $this->assign("msg",$upload->getError());
          }else{// 上传成功 获取上传文件信息
                //dump($info);;
            if($type>14)
            {
                $files = M("file");
                $f = $files->where('type = '.$type.' and uploaduser='.session("user_id"))->find();
                        
                        $data['uid'] = $info['savename'];
                        $data['type'] = $type;
                        $data['url'] = $info['savepath'].$info['savename'];
                        $data['size'] = $info['size'];
                        $data['uploadUser'] = session("user_id");
                        $data['mime'] = $info['ext'];
                        $data['companyId'] =  session("user_id");
                        $data['projectId'] = session("user_id");
                        $data['title'] = $info['name'];
                        $data['isDeleted'] = 0;
                        $files->add($data);
                        
            }else
            {
                $files = M("file"); //取数据
                $f = $files->where('type = '.$type.' and uploaduser='.session("user_id"))->find();
                        
                        $data['uid'] = $info['savename'];
                        $data['type'] = $type;
                        $data['url'] = $info['savepath'].$info['savename'];
                        $data['size'] = $info['size'];
                        $data['uploadUser'] = session("user_id");
                        $data['mime'] = $info['ext'];
                        $data['companyId'] =  session("user_id");
                        $data['projectId'] = session("user_id");
                        $data['title'] = $info['name'];
                        $data['isDeleted'] = 0;
                        if($f)
                        {
                            $files->where('type = '.$type.' and uploaduser='.session("user_id"))->save($data);
                        }else
                        {
                            $files->add($data);
                        }
                    }
                    if($type>14) 
                    {
                        $this->redirect('Company/filesdetails',array('type'=>$type,'status'=>'sucess'));
                    }
                    else
                    {
                        $this->redirect('Company/filedetail',array('type'=>$type,'status'=>'sucess'));
                    }
        }
      }
      public function attach1(){
            $this->assign('m10','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag4','1');
                            
                            $this->success("提交成功，请继续填写附表2", U('Company/Attach2'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    public function attach2(){
            $this->assign('m11','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag5','1');
                            $this->success("提交成功，请继续填写附表3", U('Company/Attach3'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

      public function attach3(){
            $this->assign('m12','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag6','1');
                            
                            $this->success("提交成功，请继续填写附表4", U('Company/Attach4'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

      public function attach4(){
            $this->assign('m13','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                        
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag7','1');
                            
                            $this->success("提交成功，请继续填写附表5", U('Company/Attach5'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

      public function attach5(){
            $this->assign('m14','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        //dump($_POST["touzishijianT2"]);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                        
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag8','1');
                            
                            $this->success("提交成功，请继续填写附表6", U('Company/Attach6'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

      public function attach6(){
            $this->assign('m15','class="active"');
            if(is_Complogin())
            {
                if(IS_POST)
                {
                    if($_POST["id"]==session("user_id"))
                    {
                        $info = M("info");
                        
                        $rules = array ( 
                                array('updateTime', time_format, 3,'function',NOW_TIME),
                            );
                        $info->create($_POST);
                        if($info->where("id=".session("user_id"))->count()>0)
                        {
                            $info->auto($rules)->save();
                        }
                        else
                        {
                            $info->auto($rules)->add();
                        }
                        if(I('post.isnext')=="1")
                        {
                            $company = M('company');
                            $company->where("id=".session("user_id"))->setField('flag9','1');
                            
                            $this->success("提交成功，请继续填写相关附件", U('Company/Files'));    
                        }else
                        {
                            $this->success("保存成功");
                        }
                      
                    }else
                    {
                        $this->error("超时,或者参数错误");
                    }
                }else
                {
                
                $i =M("info");
                $vo = $i->where("id=".session("user_id"))->find();
                $c = M("company")->where("id=".session("user_id"))->find();
                if(!isset($vo))
                {
                    $vo["qiyemingcheng"] = $c["company"];
                    $vo["yingyezhizhaohaoma"] = $c["bLicence"];
                    $vo["zuzhijigoudaima"] = $c["orgID"];
                    $vo["qiyechengliTime"] =  substr($c["buildTime"],0,strlen($c["buildTime"])-9);
                    $vo["zuceziben"] = $c["regCapital"];
                    $vo["shishouziben"] = $c["paidUpCapital"];
                    
                }
                $vo["comDirector"] = $c["comDirector"];
                $vo["mobile"] = $c["mobile"];
                $vo["email"] = $c["email"];
                $this->assign("vo",$vo);
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                //$this->display();
    
                $infoGudong = M("infoGudong")->where("isDeleted=0 and uid=".session("user_id"))->select();        
                //var_dump($infoGudong);
                $this->assign("infoGudong",$infoGudong);
                $this->display();
    
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
    
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    public function apply(){
        $this->assign('m17','class="active"');
        if(is_Complogin())
        {
            if(IS_POST)
            {
                    /* $filenum = M('file')->where('companyid='.session("user_id").' and type in (1,2,3,4,5,6,7,8,10,11)')->count();
                    if($filenum<10)
                    {
                        $this->error('上传资料不全，请补充后再提交');
                        return;
                    }*/

                        $company = M('company');
                        $c = M("company")->where("id=".session("user_id"))->find();
                        if($c["flag1"] != 1)
                        {
                            $this->success("企业基本情況沒有提交，请填写企业基本情況信息", U('Company/Info1'));
                            return;
                        }
                        if($c["flag2"] != 1)
                        {
                            $this->success("项目基本情况没有提交，请填写项目基本情况信息", U('Project/Info1'));
                            return;
                        }
                        if($c["flag3"] != 1)
                        {
                            $this->success("项目资金还款计划还没有提交，请填写项目资金还款计划信息", U('Project/Info2'));
                            return;
                        }
                        if($c["flag4"] != 1)
                        {
                            $this->success("附表1还没有提交，请填写附表1的信息", U('Company/Attach1'));
                            return;
                        }
                        if($c["flag5"] != 1)
                        {
                            $this->success("附表2还没有提交，请填写附表2的信息", U('Company/Attach2'));
                            return;
                        }
                        if($c["flag6"] != 1)
                        {
                            $this->success("附表3还没有提交，请填写附表3的信息", U('Company/Attach3'));
                            return;
                        }
                        if($c["flag7"] != 1)
                        {
                            $this->success("附表4还没有提交，请填写附表4的信息", U('Company/Attach4'));
                            return;
                        }
                        if($c["flag8"] != 1)
                        {
                            $this->success("附表5还没有提交，请填写附表5的信息", U('Company/Attach5'));
                            return;
                        }
                        if($c["flag9"] != 1)
                        {
                            $this->success("附表6还没有提交，请填写附表6的信息", U('Company/Attach6'));
                            return;
                        }
                        
                        $company->sbTime = time_format(NOW_TIME);
                        $company->status = 6; //提交公司项目资料待审核
                        $company->where('id='.session("user_id"))->save();
                        $history = M('history');
                        $history->create();
                        $history->uId = session("user_id");
                        $history->createTime = time_format(NOW_TIME);
                        $history->operUser = session("user_id");
                        $history->operIP = get_client_ip(0);
                        $history->type="提交审核公司资料";
                        $history->message= "[".session("user_name")."]将公司项目资料资质提交审核";
                        $history->add();
                        $this->success('提交项目审核成功，请您耐心等待审核。',U('Home/Index/Index'),3);
                }else
                {
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);

                
                //dump($file2flag);

                if((M('file')->where('companyId='.session("user_id").'and type = 2' )->count()) > 0)
                {
                    $file2flag =1;
                    $this->assign('file2flag',$file2flag);
                    dump($file2flag);
                }            
                

                $this->display();
            }

            return;
        }

        if(is_AdminLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_GuestLogin())
        {
            $this->redirect('Admin/Index/Index');
            return;
        }
        if(is_OperLogin())
        {
            $this->redirect('Review/Index/Index');
            return;
        }
        
        $this->error('您还没有登录，请先登录！', U('User/Login'));
    }

    public function commit(){
        $this->assign('m3','class="active"');

        if(is_Complogin())
        {
                if(IS_POST)
                {

                        $filenum = M('file')->where('companyid='.session("user_id").' and type = 1')->count();
                        if($filenum<1)
                        {
                            $this->error('请先上传企业承诺书，方可进行后续项目填报。');
                            return;
                        }

                        $company = M('company');
                        $company->sbTime = time_format(NOW_TIME);
                    $company->status = 4; //提交企业承诺书资料
                    $company->where('id='.session("user_id"))->save();
                    $history = M('history');
                    $history->create();
                    $history->uId = session("user_id");
                    $history->createTime = time_format(NOW_TIME);
                    $history->operUser = session("user_id");
                    $history->operIP = get_client_ip(0);
                    $history->type="提交企业承诺书资料";
                    $history->message= "[".session("user_name")."]将公司资质提交审核";
                        $history->add();
                    $this->success('提交企业承诺书成功，请您进行后续项目填报',U('Company/Info1'),3);
                }else
                {
                $company =M('company')->where('id='.session("user_id"))->find();
                $this->assign('data',$company);
                $this->display();
                }
                return;
            }
            if(is_AdminLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_GuestLogin())
            {
                $this->redirect('Admin/Index/Index');
                return;
            }
            if(is_OperLogin())
            {
                $this->redirect('Review/Index/Index');
                return;
            }
            
            $this->error('您还没有登录，请先登录！', U('User/Login'));
    }
        
}