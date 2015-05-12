<?php
namespace AndroidApi\Controller;
use Think\Controller;
class UserController extends Controller {
    
    /**
     * Login方法：实现用户登陆功能
     * @param $username,$pwd
     * @return String
     */
    
    public function Login($username,$pwd) {
        //
        if(IS_POST){ //登录验证
            if(D('User')->login($username,$pwd)) {
                $userInfo=M('user')->where('username="'.$username.'" and password="'.$pwd.'"')->find();
                
                echo $userInfo['id'];
                
            }
            else {
                echo 0;
            }
        }
    }
    
    /**
     * GetUserInfo
     * 获取用户的基本信息
     */
    public function GetUserInfo($username,$pwd) {
        if(IS_POST){ //登录验证
            if(D('User')->login($username,$pwd)) {
                $map=array();
                $map['username']=$username;
                $map['password']=$pwd;
                $map['isDelete']=0;
                $userInfo=M('user')->where($map)->field('id,username,password,level')->find();
                $userInfo=json_encode($userInfo);
                echo $userInfo;
            }
            else {
                echo 0;
            }
        }
    }
    
    /**
     * GetSql
     * 获取在sqlite数据库里添加用户信息的sql语句
     */
    public function GetSql($username,$pwd) {
        if(IS_POST){ //登录验证
            $sql=null;
            if(D('User')->login($username,$pwd)) {
                $map=array();
                $map['username']=$username;
                $map['password']=$pwd;
                $map['isDelete']=0;
                $userInfo=M('user')->where($map)->field('id,username,password,level')->find();
                if($userInfo) {
                    $sql_1='insert into xiaofang_user(id,username,password,level) values("';
                    $sql_2=$userInfo['id'].'","'.$userInfo['username'].'","'.$userInfo['password'].'","'.$userInfo['level'].'");';
                    $sql=$sql_1.$sql_2;
                }
            }
            else {
                
            }
            echo $sql;
        }
    }
    
    
    //class end
}
