<?php
//
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	$this->assign('m1','class="active"');
    	if(is_Complogin())
    	{
    		$map['isDeleted'] = array('eq',0);	
				$notics=M('Notic');
				$n = M('Notic');
				$pagenumber=8;
				
				$maxpage = ceil($n->where($map)->count()/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$company= M('Company')->where('id='.session("user_id"))->find();
				
				$data = $notics->page($page,$pagenumber)->field(true)->where($map)->order('important desc,creatTime desc')->select();
				$nav1 = "stepno";
				$nav2 = "stepno";
				$nav3 = "stepno";
				$nav4 = "stepno";
				$nav5 = "stepno";

				
				
				if($company['status'] >= -1)
				{
					$nav1 = "stepok";
					$nav2 = "stepon";
					$nav3 =	"stepno";
					$nav4 = "stepno";
					$nav5 = "stepno";
				}

				
				
				if($company['status'] == 1) #资质审核未通过
				{
					$nav2 = "stepon";
					$nav3 =	"stepon";
					$nav4 = "stepon";
					$nav5 = "stepsuccess";
				}
				
				if($company['status'] > 1) #提交资质待审核
				{
					$nav2 = "stepok";
					$nav3 =	"stepok";
					$nav4 = "stepok";
					$nav5 = "stepon";
				}
				if($company['status'] ==4) #企业承诺书提交
				{
					$nav2 = "stepok";
					$nav3 =	"stepon";
					$nav4 = "stepon";
					$nav5 = "stepno";

				}
				if($company['status'] == 5) #项目审核未通过
				{
					$nav3 =	"stepon";
					$nav4 = "stepon";
					$nav5 = "stepsuccess";
					

				}
				
				if($company['status'] == 6) #项目提交审核
				{
					$nav3 =	"stepok";
					$nav4 = "stepok";
					$nav5 = "stepon";
				}



				if($company['status'] == 7) #项目审核通过
				{
					$nav3 =	"stepok";
					$nav4 = "stepok";
					$nav5 = "stepok";

				}

				$this->assign('page',$_GET['page']);
				$this->assign('maxpage',$maxpage);
				$this->assign('data',$data);
				$this->assign('nav1',$nav1);
				$this->assign('nav2',$nav2);
				$this->assign('nav3',$nav3);
				$this->assign('nav4',$nav4);
				$this->assign('nav5',$nav5);

				$this->assign('errormsg','['.$company['refuseTime'].'] : '.$company['refuseReason']);
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
				$this->redirect('Admin/Guest/Index');
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