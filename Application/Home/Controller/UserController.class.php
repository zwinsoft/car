<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    //public function login(){
		//	$this->display();
    //}
    /* 验证码，用于登录和注册 */
		public function verify(){
			$verify = new \Think\Verify();
			//ob_clean();
			ob_end_clean();
			$verify->useCurve = false;
			$verify->entry(2);
		}
		public function profile(){
    	$this->assign('m4','class="active"');
    	if(is_Complogin())
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
			if(is_AdminLogin())
			{
				$this->redirect('Admin/Index/index');
				return;
			}
			if(is_GuestLogin())
			{
				$this->redirect('Admin/Index/Index');
				return;
			}
			if(is_OperLogin())
			{
				$this->redirect('Admin/Review/Index');
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('User/Login'));
		    }
		public function Login($uname = '', $pwd = '', $code = ''){
			if(IS_POST){ //登录验证
				$ckcode = check_verify($code,2);

				if(!$ckcode)
				{
					$this->error('验证码输入错误！', U('User/Login'));
				}else
				{
					if(D('User')->login($uname,$pwd))
					{
						$this->success('登录成功！',U('Home/Index/index'));
					}else
					{
						$this->error('登录失败', U('User/Login'));
					}
				}
			}else
			{
				$this->display();
			}
		}
		public function Logout(){
				D('User')->logout();
				$this->redirect('User/Login');
		}
		public function reg1(){
			$this->display();	
		}
		public function Register(){
			if(IS_POST)
			{
				$this->display();
			}
		}
		public function Confirm(){
			if(IS_POST)
			{
				if(D('User')->isExists(I('post.username')))
				{
						$this->error("注册失败,用户名已经存在.");
						return;
				}
				if(M('Company')->where("company='".I('post.company')."'")->count()>0)
				{
						$this->error("注册失败,公司名称已经存在.");
						return;
				}
				if(M('Company')->where("bLicence='".I('post.bLicence')."'")->count()>0)
				{
						$this->error("注册失败,营业执照号码已经存在.");
						return;
				}
				if(M('Company')->where("orgID='".I('post.orgID')."'")->count()>0)
				{
						$this->error("注册失败,组织机构代码证号码已经存在.");
						return;
				}
				$user = D('User');
				$data['username'] = $_POST["username"];//I('post.username');
				$data['password'] = $_POST["password"];
				$data['level'] = 0;
				
				$user->create($data);
				$uid = $user->add();
				if($uid > 0)
				{
					$company = M('Company');
					$company->create();
					$company->company = I('post.company');
					$company->where = I('post.cmbProvince').'-'.I('post.cmbCity').'-'.I('post.cmbArea');
					$company->bLicence = I('post.bLicence');
					$company->orgID = I('post.orgID');
					$company->buildTime = I('post.buildTime');
					$company->regCapital = I('post.regCapital');
					$company->paidUpCapital = I('post.paidUpCapital');
					$company->comDirector = I('post.comDirector');
					$company->mobile =I('post.mobile') ;
					$company->email = I('post.email');
					$company->mainBusiness = I('post.mainBusiness');
					$company->passRegconition = I('post.passRegconition');
					$company->allCapital = I('post.allCapital');
					$company->netAssets = I('post.netAssets');
					$company->salesIncome = I('post.salesIncome');
					$company->netProfit = I('post.netProfit');
					$company->id = $uid;
					$company->status = 0;  #公司状态 新注册
					$company->isDeleted = 0;
					$company->isArchived = 0;
					$company->regTime = time_format(NOW_TIME);
					$company->regIp = get_client_ip(0);
					if($company->add() > 0)
					{
						$history = M('history');
			    	$history->create();
			    	$history->uId = $uid;
			    	$history->createTime = time_format(NOW_TIME);
			    	$history->operUser = $uid;
			    	$history->operIP = get_client_ip(0);
			    	$history->type="注册用户";
			    	$history->message= "[".$data["username"]."]注册";
						$history->add();
						D('User')->login($data['username'],$data['password']);
						$this->success("注册成功",U('Home/Index/Index'));
						return;
					}else
					{
						$history = M('history');
				    	$history->create();
				    	$history->uId = $uid;
				    	$history->createTime = time_format(NOW_TIME);
				    	$history->operUser = $uid;
						$history->operIP = get_client_ip(0);
			    		$history->type="注册用户";
				    	$history->message= "[".$data["username"]."]注册";
						$history->add();
						
						D('User')->login($data['username'],$data['password']);
						$this->success("注册成功，企业相关信息添加失败，请登录之后完善信息",U('Home/Index/Index'));
					}
				}else
				{
					add_log('user_confirm', 'registerFailed:'.$user->getDbError().'=='.($user->getLastSql()).'=='.(M()->getLastSql()),null );
					$this->error("注册失败");
				}
			}
		}

        //*********************************
		//Findpwd()
		//Find username / password
		//20140625  taochen
		//*********************************
		public function Findpwd(){
			if(IS_POST)
			{
				
				if(M('Company')->where("company='".I('post.company')."'")->count()>0)
				{
					$company1 = M('Company')->where("company='".I('post.company')."'");						
						
				}
				if(M('Company')->where("bLicence='".I('post.bLicence')."'")->count()>0)
				{
					$company2 = M('Company')->where("bLicence='".I('post.bLicence')."'");		
						
				}
				if(M('Company')->where("orgID='".I('post.orgID')."'")->count()>0)
				{
					$company3 = M('Company')->where("orgID='".I('post.orgID')."'");		
						
				}
				
				if(isset($company1))
				{
					$company = $company1;

				}

				//$us = M('user');
				//$user = M('user')->where("id='".$company->id)->select();

				$map['id'] = $company->id;
    			$us = M('user');
    			$user = $us->where($map)->select();
    			if($user)
				{

					$msg = "用户名:" + $user->username + "密码:" + $user->password + "【科创天使】";					

					$mobile = $company->mobile;
					$msgtype = 0;

					sendSMS($msg,$mobile,$msgtype);
				}

				/*$msg = "您在项目申报系统申请的用户名是taoc,密码是123456【科创天使】";
				$mobile = 13803026441;
				$msgtype = 0;
				sendSMS($msg,$mobile,$msgtype);*/

				$this->success('用户名和密码已以短信发送到您原来登记的手机号码.',U('Home/Index/Index'));

							
			}
			$this->display();
		}
		//end of Findpwd()
}