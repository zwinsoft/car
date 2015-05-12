<?php
//
namespace Admin\Controller;
use Think\Controller;
class DataController extends Controller {
    public function statics(){
    	$this->assign('m4','class="active"');

    	if(is_Complogin()|| is_GuestLogin() ||is_OperLogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() )
			{
				$map['isDeleted']=0;
				$map['status']=7;
				$map['reviewFlag']=1;
				$map['isArchived']=array('neq',1);

				
				$company = M('Company');
				$pagenumber=10;//每页条数
				$all=$company->where($map)->count();
				$maxpage = ceil($all/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$companys = $company->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();
				//dump($companys);
				$cs = array();
				foreach($companys as $row)
				{
					  
						$row["project"] = M('project')->where('id = '.$row['id'])->find();						
						$row["history"] = M('history')->where('uid='.$row['id'])->order('id desc')->select();
						$row["review"] = M('review')->where('isSubmit=1  and companyid='.$row['id'])->order('id desc')->select();


						//dump($row);
						array_push($cs, $row);
				}
				$this->assign('admin',M('user')->where('isdeleted =0 and level in (8)')->order('username')->select());
				//print_r($cs);
				$this->assign('data',$cs);
				$this->assign('page',$page);
				$this->assign('maxpage',$maxpage);
				$this->assign('pagenumber',$pagenumber);
				$this->assign('all',$all);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('Home/User/Login'));
    }

    public function getXML(){

		$map['isDeleted']=0;
		$map['status']=7;
		$map['reviewFlag']=1;		
		
		$company = M('Company');

		$companys = $company->where($map)->order('id desc')->select();

		$cs = array();
		foreach($companys as $row)
		{
			  
				$row["project"] = M('project')->where('id = '.$row['id'])->find();						
				$row["history"] = M('history')->where('uid='.$row['id'])->order('id desc')->select();
				$row["review"] = M('review')->where('isSubmit=1  and companyid='.$row['id'])->order('id desc')->select();

				$reviewMark ="";
				$reviewStatus="";
				$reviewYes = 0;
				$reviewNo = 0;				

				$reviews = M('review')->where('isSubmit=1  and companyid='.$row['id'])->order('id desc')->select();
				//dump($reviews);
				foreach($reviews as $review)
				{
				
					if($review["isSubmit"] ==1)
					{
						
						if($review["value1"] == 0)
						{
							$reviewMark = $reviewMark."不建议立项:".$review["memo1"].";";
							$reviewNo = $reviewNo+1;
							if($reviewNo>=2)
							{
								$reviewStatus="不通过";

							}
						}
						else if($review["value1"] == 1)
						{
						
							$reviewMark = $reviewMark."建议立项:".$review["memo1"].";";
							$reviewYes = $reviewYes+1;
							if($reviewYes>=2)
							{
								$reviewStatus="通过";

							}
						}

					}

				}
				
				//dump($reviewMark);
				//dump($reviewStatus);
				M('Company')->where('id = '.$row['id'])->setField('reviewMark',$reviewMark);							
				M('Company')->where('id = '.$row['id'])->setField('reviewStatus',$reviewStatus);
				
				//dump($row);
				array_push($cs, $row);
		}
		$this->assign('admin',M('user')->where('isdeleted =0 and level in (8)')->order('username')->select());
		//print_r($cs);
		$this->assign('data',$cs);
		$this->display();
		return;
		
    }



    public function archive(){
    	$this->assign('m5','class="active"');

    	if(is_Complogin()|| is_GuestLogin() ||is_OperLogin())
    	{
    		$this->redirect('Home/Index/Index');
				$this->display();
				return;
			}
			if(is_AdminLogin() )
			{
				$map['isDeleted']=0;
				$map['isArchived']=1;
				$map['status']=7;
				
				$company = M('Company');
				$pagenumber=10;//每页条数
				$all=$company->where($map)->count();
				$maxpage = ceil($all/$pagenumber)+1;
				
				$page=$_GET['page'];
				
				if(!isset($page))
				{$page=1;}
				$companys = $company->page($page,$pagenumber)->field(true)->where($map)->order('id desc')->select();
				//dump($companys);
				$cs = array();
				foreach($companys as $row)
				{
					  
						$row["project"] = M('project')->where('id = '.$row['id'])->find();						
						$row["history"] = M('history')->where('uid='.$row['id'])->order('id desc')->select();
						$row["review"] = M('review')->where('companyid='.$row['id'])->order('id desc')->select();


						
						//dump($row);
						array_push($cs, $row);
				}
				$this->assign('admin',M('user')->where('isdeleted =0 and level in (8)')->order('username')->select());
				//print_r($cs);
				$this->assign('data',$cs);
				$this->assign('page',$page);
				$this->assign('maxpage',$maxpage);
				$this->assign('pagenumber',$pagenumber);
				$this->assign('all',$all);
				$this->display();
				return;
			}
			
			$this->error('您还没有登录，请先登录！', U('Home/User/Login'));
    }


	public function saveReview()
	{

		$xmlurl="xml=http://app.tjacco.com/fengxian/index.php?m=Admin%26c=Data%26a=getXML";

		$pdfUrl="http://www.pdfdo.com//tjac/topdf5.aspx?".$xmlurl;
		add_log('pdfdo', '$pdfUrl:'.$pdfUrl,null );
		//$pdfLocalUrl=str_replace("Uploads","DownloadsPDF",$fileUrl);
		
		$pdfLocalPath = $_SERVER['DOCUMENT_ROOT']."/fengxian/Public/Review/";
		//if(!is_dir($pdfLocalPath)){
			mkdir($pdfLocalPath, 0700, true);
		//}


		$rs = request_by_curl($pdfUrl);
		//$rs = "http://www.pdfdo.com/tjac/Download/20141021230114.pdf";
		$rs1= substr($rs,20,strlen($rs));
		$r = substr($rs,0,20+strpos($rs1,'.pdf'));
		$msg=$r;
		if(isset($r))
		{
			add_log('pdfdo', '$r:'.$r,null );
			add_log('pdfdo', '$pdfLocalPath:'.$pdfLocalPath,null );
			$pdfLocalUrl=str_replace("http://www.pdfdo.com/tjac/Download/",$pdfLocalPath,$r.".pdf");
			add_log('pdfdo', '$LocalpdfUrl:'.$pdfLocalUrl,null );


			download_pdf($pdfLocalUrl,$f["mime"],$r.".pdf");
			$msg="生成评审结果确认单(PDF格式)成功。".$pdfLocalUrl;

			$pdfLocalUrl=substr($pdfLocalUrl,strlen($_SERVER['DOCUMENT_ROOT']),strlen($pdfLocalUrl));

			$data['status'] = 0;
			$data['file'] = $pdfLocalUrl;

			$this->ajaxReturn($data);
			return;
		}



		$data['status'] = 1;
		$data['file'] = "";
		$this->ajaxReturn($data);
		return;

	}


}