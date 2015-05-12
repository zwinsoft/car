<?php
//
namespace Admin\Controller;
use Think\Controller;
use Think\Db\Driver\Pdo;

class UserController extends Controller {
	    /* 验证码，用于登录和注册 */
		public function verify(){
			$verify = new \Think\Verify();
			ob_clean();
			$verify->useCurve = false; 
			$verify->entry(2);
		}

		public function login($uname = '', $pwd = '', $code = ''){



			if(IS_POST){ //登录验证
				$ckcode = check_verify($code,2);

				if(!$ckcode)
				{
					//dump($ckcode);
					$this->error('验证码输入错误！', U('Admin/User/Login'));
				}else
				{
					if(D('User')->login($uname,$pwd))
					{
						$this->success('登录成功！',U('Admin/Index/Index'));
					}else
					{
						$this->error('登录失败', U('Admin/User/Login'));
					}
				}
			}else
			{
				$this->display();
			}
		}
	public function Logout(){
				D('User')->logout();
				$this->redirect('Admin/User/Login');
		}

    public function profile(){
    	$this->assign('m8','class="active"');
    	if(is_Complogin())
    	{
    		$this->redirect('Admin/Index/Index');
			$this->display();
			return;
		}

		if(is_AdminLogin() || is_GuestLogin() || is_OperLogin())
		{
			if(IS_POST)
    		{
    			if(I('post.newpwd')==I('post.newpwd2'))
    			{
    				$map['id'] = session("user_id");
    				$us = M('user');
    				$u = $us->where($map)->select();
    				if($u)
    				{
    					//dump($u[0]);
    					if($u[0]['password']	== I('post.password'))
    					{
    							$us->password = I('post.newpwd');
    							$us->where($map)->save();
    							$this->success('修改密码成功');
    					}else
    					{
    						$this->error("修改密码失败:老密码错误");	
    					}
    				}else
    				{
    					$this->error("修改密码失败:无此用户".session("user_id"));	
    				}
    					
    			}
    			
    		}
			
			$this->display();
			return;
		}
			
			
		$this->error('您还没有登录，请先登录！', U('User/Login'));
    }


    /**
     * manageall
     * 用户管理类
     */
    public function manageall(){
    	$this->assign('Manageall','class="active"');
    	if(is_Complogin()) {
    		$this->redirect('Home/Index/Index');
            $this->display();
            return;
		}
		if(is_OperLogin()) {
    		$this->redirect('Review/Index/Index');
			$this->display();
			return;
		}
		if(is_AdminLogin()) {
            $map=array();

            //开始分页输出数据
            $water = M('user');
            $pagenumber = 10; //每页条数
            $all = $water->where($map)->count();
            $maxpage = ceil($all/$pagenumber)+1;

            $page=$_GET['page'];

            //如果没有传来分页数据，则为第一页
            if(!isset($page)) {
                $page=1;
            }

            $u = $water->page($page,$pagenumber)->field(true)->where($map)->select();
/*
			$us = M('user');

			$u = $us->select();
			//dump($u);
*/
			$this->assign('data',$u);
            $this->assign('page',$page);
            $this->assign('maxpage',$maxpage);
            $this->assign('pagenumber',$pagenumber);
            $this->assign('all',$all);
			
			$this->display();
			return;
		}
			
		$this->error('您还没有登录，请先登录！', U('User/Login'));
	}

	function active($id,$checked)
    {
    	if(is_AdminLogin())
		{
			
			$reviewer = M('reviewer');
			$data["activeFlag"] = $checked;
			$data["id"] = $id;
			$reviewer->where('id='.$id)->save($data);
			
			//console.log($id);

			$un=M('reviewer')->where('id='.$id)->find();
			
			
			$history = M('history');
			$history->create();
			$history->uId = (int)$id;
			$history->createTime = time_format(NOW_TIME);
			$history->operUser = session("user_id");
			$history->operIP = get_client_ip(0);
			$history->type="安排评审专家";
			$un=M('reviewer')->where('id='.$id)->find();
			
			$history->message= "[".session("user_name")."]将评审专家[".$id."]安排评审";
				$history->add();
				//dump($id);
				//$this->ajaxReturn(array('status'=>'保存成功'));

			$this->ajaxReturn('',"ok",1);
		}else
		{
			$this->error('没有权限', U('User/Login'));
		}
    }

	function addreviewer($zjname,$zjmobile)
    {

    	if(is_AdminLogin())
		{

			$reviewer = M('reviewer');
	    	$reviewer->create();
			
	    	$reviewer->name = urldecode($zjname);
			$reviewer->mobile = $zjmobile;
			   	
			$id = $reviewer->add();

			$zjNumber="zj000";
			if($id < 10 ) 
			{
				$zjNumber="zj00".$id;
			}
			else
			{
				 if($id < 100)
				 {
					$zjNumber="zj0".$id;
				 }
				 else
				 {
					$zjNumber="zj".$id;
				 }

			}

			$chars='0123456789';
			mt_srand((double)microtime()*1000000*getmypid()); 
			$password="";
			while(strlen($password)<6)
				 $password.=substr($chars,(mt_rand()%strlen($chars)),1);

			$user = M('user');
	    	$user->create();
	    	$user->username = $zjNumber;
			$user->password = $password;
			$user->level = 8;
			$uid = $user->add();

			M('reviewer')->where("id=".$id)->setField('zjNumber',$zjNumber);
			M('reviewer')->where("id=".$id)->setField('uid',$uid);
		
			//console.log($id);
		
			$history = M('history');
			$history->create();
			$history->uId = (int)$id;
			$history->createTime = time_format(NOW_TIME);
			$history->operUser = session("user_id");
			$history->operIP = get_client_ip(0);
			$history->type="增加评审专家";
			$un=M('reviewer')->where('id='.$id)->find();
			
			$history->message= "[".session("user_name")."]增加评审专家[".$zjname."]电话[".$zjmobile."]编号[".$zjNumber."]";
				$history->add();
				//dump($id);
				//$this->ajaxReturn(array('status'=>'保存成功'));

			$this->ajaxReturn('',"ok",1);
		}else
		{
			$this->error('没有权限', U('User/Login'));
		}
    }

    /**
     * ajaxDeleteUser
     * ajax方式删除用户
     */
    public function ajaxDeleteUser($id) {
        $res=M('user')->where('id = '.$id)->setField('isDeleted',1);
        if($res) {
            echo 'success';
        }
        else {
            echo 'failure';
        }
    }

    /**
     * ajaxResetPwd
     * ajax方式重置密码
     */
    public function ajaxResetPwd($id) {
        $res=M('user')->where('id = '.$id)->setField('password',123456);
        if($res) {
            echo 'success';
        }
        else {
            echo 'failure';
        }
    }

    /**
     * AddUser
     * ajax方式添加用户
     */
    public function AddUser() {
        $data=M('user')->create();
        $res=M('user')->add($data);
        if($res) {
            $this->success('添加成功');
        }
        else {
            $this->error('添加失败');
        }
    }


    /**
     * ajaxGetUserinfo
     * ajax方式获取用户信息
     */
    public function ajaxGetUserinfo($id) {
        $userinfo=M('user')->where('id = '.$id)->find();
        $this->ajaxReturn($userinfo);
    }

    /**
     * UpdateUser
     * 修改用户信息
     */
    public function UpdateUser() {
        $id=I('post.id');
        $data=array();
        $data['username']=I('post.username');
        $data['IPone']=I('post.IPone');
        $data['Email']=I('post.Email');
        $data['realname']=I('post.realname');

        $res=M('user')->where('id = '.$id)->save($data);
        if($res) {
            $this->success('添加成功');
        }
        else {
            $this->error('添加失败');
        }
    }


}