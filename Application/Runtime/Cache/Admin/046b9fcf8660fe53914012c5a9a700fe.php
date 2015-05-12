<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
<title><?php echo C('WEB_SITE_TITLE');?></title>
<script type="text/javascript" src="/car/Public/static/jquery/jquery-1.8.3.min.js" charset="UTF-8"></script>
<script type="text/javascript" src="/car/Public/static/js/bootstrap.js"></script>
<link rel="stylesheet" type="text/css" href="/car/Public/static/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/car/Public/static/css/bootstrap-responsive.css">
<link rel="stylesheet" type="text/css" href="/car/Public/static/css/index.css">

<!--[if lte IE 6]>
<link rel="stylesheet" type="text/css" href="/car/Public/static/css/bootstrap-ie6.css">
<script type="text/javascript" src="/car/Public/static/js/bootstrap-ie.js"></script>
<![endif]-->
<!--[if lte IE 7]>
<link rel="stylesheet" type="text/css" href="/car/Public/static/css/ie.css">
<script type="text/javascript" src="/car/Public/static/js/bootstrap-ie.js"></script>
<![endif]-->







</script>



<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->


</head>
<body>
	<!-- 头部 -->
	<!-- 导航条
================================================== -->
<div id="head">
    <div class="container" style="width:1127px;">
    		
        <div style="float:left;"><h1 class="logoTitle" style="font-size:30px;">天津市快E车盟远程定损系统</h1></div>
        <?php if(!empty($_SESSION['tjac_fengxian_']['user_name'])): ?><div class="welcome">
        		<div class="welcomeleft">
        		</div>
        		<div class="welcomemid" title="<?php echo (session('user_name')); ?>"><?php echo (substr(session('user_name'),0,40)); ?>   | <a href="?m=Admin&c=Index&a=logout" >退出</a>
        		</div>
        		<div class="welcomeright">
        		</div>	
        	</div><?php endif; ?>
    </div>
</div>

	<!-- /头部 -->
<div class="container bodymiddle" style="">
	
	
	<!-- 主体 -->
	
<div class="row mainContent">
	<div class="span2 mainContentLeft">
		
		<ul class="nav nav-pills nav-stacked">
			<li class="nav-header">
			    后台管理
			</li>

						
			<?php if($_SESSION['tjac_fengxian_']['user_level']== 9): ?><li <?php echo ($waterLise); ?>><a href="?m=Admin&c=Index&a=Index" >定损列表管理</a></li>
            
            
            <li class="nav-header">用户管理</li>
            <li <?php echo ($Manageall); ?>><a href="?m=Admin&c=User&a=ManageAll" >用户管理</a></li>
			<li class="nav-header">通知公告</li>
			<li <?php echo ($m7); ?>><a href="<?php echo U('Admin/Notic/Manage');?>" >公告管理</a></li>
			<li <?php echo ($m6); ?>><a href="<?php echo U('Admin/Notic/Add');?>" >新增公告</a></li>
			<li <?php echo ($m12); ?>><a href="<?php echo U('Admin/SMS/Index');?>" >短信管理</a></li>
			<li <?php echo ($m13); ?>><a href="<?php echo U('Admin/SMS/Send');?>" >发送短信</a></li><?php endif; ?>

		
			<li <?php echo ($m8); ?>><a href="<?php echo U('Admin/User/Profile');?>" >修改密码</a></li>
			<li <?php echo ($m11); ?>><a href="<?php echo U('Home/User/Logout');?>" >退出系统</a></li>
		</ul>
</div>

	<div class="span7">
		<fieldset>
				<legend>用户管理&nbsp;&nbsp;
                    <a class="btn btn-small" data-toggle="modal"data-target="#addUser" >增加用户</a>
                    <div class="modal fade" id="addUser" tabindex="-1" role="dialog"aria-labelledby="myModalLabel" aria-hidden="true">

                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    &times;
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel">
                                    添加用户
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <form action="?m=Admin&c=User&a=AddUser" method="post">

                                        <table class="table table-bordered">
                                            <tr>
                                                <td>用户名</td>
                                                <td><input type="text" name="username"></td>
                                            </tr>
                                            <tr>
                                                <td>密码</td>
                                                <td><input type="text" name="password"></td>
                                            </tr>
                                            <tr>
                                                <td>手机</td>
                                                <td><input type="text" name="IPone"></td>
                                            </tr>
                                            <tr>
                                                <td>电子邮箱</td>
                                                <td><input type="text" name="Email"></td>
                                            </tr>
                                            <tr>
                                                <td>真实姓名</td>
                                                <td><input type="text" name="realname"></td>
                                            </tr>
                                        </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    <input type="submit" class="btn btn-primary" value="确认添加" />
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </legend>
		<table class="table table-bordered table-striped ">
			<tr>
				<td>编号</td>
                <td>用户名</td>
				<td>电话</td>
				<td>姓名</td>
				<td colspan='3'>操作</td>
			</tr>
			
			<?php $n=0;?>
			<?php if(is_array($data)): foreach($data as $key=>$vo): $n=$n+1;?>
			<tr>
				
				<td><?php echo ($vo["id"]); ?></td>
				<td><?php echo ($vo["username"]); ?></td>
				<td><?php echo ($vo["IPone"]); ?></td>
				<td><?php echo ($vo["realname"]); ?></td>
				<td>
                    <a class="btn btn-small btn-warning" href="javascript:void(0)" onclick="ajaxResetPwd(<?php echo ($vo["id"]); ?>)">重置密码</a>
					<a class="btn btn-small btn-warning" href="javascript:void(0)" onclick="ajaxDeleteUser(<?php echo ($vo["id"]); ?>)">删除</a>
				</td>
			</tr><?php endforeach; endif; ?>
		</table>
        <div style="width:100%;text-align:right;">
            <?php?>
            <div class="pagination pagination-right">
                <ul>
                    <li>
                        <a href="?m=Admin&c=User&a=ManageAll&page=1">
                    <i class="icon-fast-backward"></i></a>
                    </li>
                    <li class="<?php echo ($page==1 || !isset($page))?"disabled":"";?>">
                    <a href="?m=Admin&c=User&a=ManageAll&page=<?php echo ($page-1); ?>">前一页</a>
                    </li>
                    <li class="<?php echo $page==($maxpage-1)?"disabled":"";?>">
                    <a href="?m=Admin&c=User&a=ManageAll&page=<?php echo ($page+1); ?>">下一页</a>
                    </li>
                    <li>
                    <a href="?m=Admin&c=User&a=ManageAll&page=<?php echo ($maxpage-1); ?>"><i class="icon-fast-forward"></i></a>
                    </li>
                    <li>
                    <select class="input-small" style="line-height:34px;height:36px;" onchange="location.href='?m=Admin&c=User&a=ManageAll&page='+this.value">
                    <?php $__FOR_START_708870400__=1;$__FOR_END_708870400__=$maxpage;for($n=$__FOR_START_708870400__;$n < $__FOR_END_708870400__;$n+=1){ if($page == $n ): ?><option value="<?php echo ($n); ?>" selected>第<?php echo ($n); ?>页</option>
                    <?php else: ?>
                    <option value="<?php echo ($n); ?>" >第<?php echo ($n); ?>页</option><?php endif; } ?>
                    </select>
                    </li>
                </ul>
            </div>
        </div>
		</fieldset>
	</div>
</div>
        <script>
            //ajax方式删除用户
            function ajaxDeleteUser(id) {
                r=confirm("确认删除该用户吗？");
                if(!r) {
                    return;
                }

                url="?m=Admin&c=User&a=ajaxDeleteUser";
                data={id:id};
                callback=function(res) {
                    if(res=='success') {
                        alert('删除成功！');
                        window.location.reload();
                    }
                    else if(res=='failure') {
                        alert('删除失败，请重试！');
                    }
                }
                $.post(url,data,callback);
            }

            //ajax方式重置密码
            function ajaxResetPwd(id) {
                r=confirm("确认将该用户密码初始化为原始密码（123456）吗？");
                if(!r) {
                    return;
                }

                url="?m=Admin&c=User&a=ajaxResetPwd";
                data={id:id};
                callback=function(res) {
                    if(res=='success') {
                        alert('密码已经初始化为123456！');
                        window.location.reload();
                    }
                    else if(res=='failure') {
                        alert('密码初始化失败，请重试！');
                    }
                }
                $.post(url,data,callback);
            }
                        
            
        </script>


	<!-- /主体 -->

	
</div>
<!-- 底部 -->
	
    <!-- 底部
    ================================================== -->
    <div class="footer">
      <div class="container">
          <p>版权所有:天津快E车盟 2014-2015 All Right Reserved <br>备案号：天津ICP备******号
		  本站由 <strong> <a href="http://www.zwin.mobi">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  </p>
      </div>
    </div>

<script type="text/javascript">
(function(){
	var ThinkPHP = window.Think = {
		"ROOT"   : "/car", //当前网站地址
		"APP"    : "/car/index.php", //当前项目地址
		"PUBLIC" : "/car/Public", //项目公共目录地址
		"DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
		"MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
		"VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
	}
})();
</script>
 <!-- 用于加载js代码 -->
<!-- 页面footer钩子，一般用于加载插件JS文件和JS代码 -->

<div class="hidden"><!-- 用于加载统计代码等隐藏元素 -->
	


	<p><strong> <a href="http://www.zwin.mobi">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  </p>
	<p><strong> <a href="http://www.zwinsoft.com">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  </p>
	<p><strong> <a href="http://www.17qunawan.com" alt="去哪玩景点门票">去哪玩景点门票 </a></strong> 提供服务  </p>

	
</div>

	<!-- /底部 -->
	<script>
		$(".mainContentLeft").height(Math.max($(".span7").height(),600));
	</script>
</body>
</html>