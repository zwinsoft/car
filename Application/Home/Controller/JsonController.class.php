<?php
namespace Home\Controller;
use Think\Controller;
/**
 * Json类，接受安卓端传来的json信息，并且存入数据库
 * 
 * 
 */
class JsonController extends Controller {

    /**
     * sync方法：ajax方式接受安卓端传来的json信息，并且存入数据库
     * @return 存入结果
     */

    public function sync() {

        if(IS_POST) {
            /*
            //临时查找问题时候用的txt日志文件
            $post = $_POST;
            $str = implode('----',$post);
            
            file_put_contents('Public/log.txt',"被调用一次".$str."\r\n",FILE_APPEND);
            */
            $post = $_POST;
            if(!$post) { //如果POST传来的数据为空
                $this->ajaxReturn(array('status'=>"0")); //返回1表示上传失败
                exit;
            }
            $water = M('water');
            
            $data = array();
            foreach($post as $key => $value ) {
                $data[$key] = $post[$key];
            }
            $data['upload_date'] = date('Y-m-d');
            
            $res = $water -> add($data);
            if($res) {
                $this->ajaxReturn(array('status'=>$res)); //返回0表示上传成功
            }
            else {
                $this->ajaxReturn(array('status'=>"0")); //返回1表示上传失败
            }
            
            
            
            /*             
            file_put_contents('Public/log.txt',"被调用一次".$str."\r\n",FILE_APPEND);
            这是针对多条数据的，暂时上传一条数据
            //接受产来的POST数据
            $json = $_POST['data'];
            $data =  json_decode($json); //解析json

            echo '<pre>';
            var_dump ($data);
            echo '</pre>';
            echo count((array)$data);

            $water = M('water');
            $res=array();

            
            echo '<pre>';
            print_r($res);
            echo '</pre>';
            */
            
        }
        else {
            $this->ajaxReturn(array('status'=>"1")); //返回1表示上传失败
        }
        
    }
    
    //该类结束
}
