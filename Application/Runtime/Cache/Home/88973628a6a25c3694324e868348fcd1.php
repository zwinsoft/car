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



	<style>
		.login-content
		{
			width:100%;
			}
		.bodymiddle{
			border:0px;
			}
			.form-horizontal
			{
				width:350px;
				}
				.reg-bottom
				{
					text-align:center;
					}
					.prompt{
						color:#666666;
						}
	</style>





<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->


<style>
<!--[if IE 7 ]>.span7{width:700px;}<![endif]-->
<!--[if IE 8 ]>.span7{width:700px;}<![endif]-->
</style>
</head>
<body>
	<!-- 头部 -->
	


<!-- 导航条
================================================== -->
<!--[if lt IE 7]>
<style>
/*ie6提示*/
#ie6-warning{width:100%;position:absolute;top:0;left:0;background:#ffffe1;padding:5px 0;font-size:12px}
#ie6-warning p{width:960px;margin:0 auto;}
</style>
<div id="ie6-warning"><p>您正在使用 Internet Explorer 6，在本页面的显示效果可能有差异。建议您升级到 <a href="http://www.microsoft.com/china/windows/internet-explorer/" target="_blank">Internet Explorer 8</a> 或以下浏览器：
<a href="http://www.mozillaonline.com/">Firefox</a> / <a href="http://www.google.com/chrome/?hl=zh-CN">Chrome</a> / <a href="http://www.apple.com.cn/safari/">Safari</a> / <a href="http://www.operachina.com/">Opera</a></p></div>
jQuery(function ($) {
	if ( jQuery.browser.msie && ( jQuery.browser.version == "6.0" ) && !jQuery.support.style ){
		jQuery('#ie6-warning').css({'top':jQuery(this).scrollTop()+'px'});
	}
});
<![endif]-->
<div id="head">
    <div class="container" style="width:1170px;">
    		
        <div style="float:left;"><h1 class="logoTitle" style="font-size:30px;">天津市快E车盟远程定损系统</h1></div>
        <?php if(!empty($_SESSION['tjac_fengxian_']['user_name'])): ?><div class="welcome">
        		<div class="welcomeleft">
        		</div>
        		<div class="welcomemid" title="<?php echo (session('company')); ?>"><?php echo (substr(session('company'),0,42)); ?>   | <a href="<?php echo U('Home/User/Logout');?>" >退出</a>
        		</div>
        		<div class="welcomeright">
        		</div>	
        	</div><?php endif; ?>
    </div>
</div>

	<!-- /头部 -->
<div class="container bodymiddle" style="">
	
	
	<!-- 主体 -->
	
<div style="min-height:500px;">
            <div class="login-title">
                <h2>用 户 登 录</h2>
            </div>
            <div class="login-content">
            	
                	<h3>请登录</h3>
                	<hr />
                	<p class="prompt">如果您在本站已拥有账号，请使用已有的账号信息直接进行登录即可，不需要重复注册</p>
                	
                	<form class=" form-horizontal" action="/car/index.php?m=Home&c=User&a=Login" method="post">
                		
                    <div class="control-group">
                        <label class="control-label" for="input01">用户名</label>
                        <div class="controls">
                            <input type="text" name="uname" class="input-medium" id="uname" placeholder="请输入登录名称">
                            
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="input01">密码</label>
                        <div class="controls">
                            <input type="password" name="pwd" class="input-medium" id="pwd" placeholder="请输入密码">
                            
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="input01">验证码</label>
                        <div class="controls">
                            <input type="text" autocomplete="off"  name="code" class="input-medium" id="code" placeholder="" datatype="*5-5" nullmsg="请填写验证码" >
                            <img style="width:150px;height:50px;cursor:pointer;" alt="点击切换" onclick="this.src='<?php echo U('verify');?>'" src="<?php echo U('verify');?>">
                        </div>
                    </div>
                    <div class="reg-bottom">
                        <label class="control-label" for="input01">&nbsp;</label>
						<button type="submit" class="btn btn-primary btn-block btn-large input-medium">登 录</button>
                        &nbsp;

                        <br />
                        <div style="padding:30px;"/>
                        
 						</div>
                </form>
                </div> 
                <!--
                <h3>还没有注册吗？</h3>
                <hr />
                <p class="prompt">如果还没有本站的通行证账号，请先注册一个属于自己的账号吧。</p>
                <a href="<?php echo U('Home/User/Reg1');?>" class="btn btn-block btn-large">注册新用户</a>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo U('Home/User/Findpwd');?>" class="btn btn-large input-small">忘记密码</a>
                 -->

				 

            </div>
        </div>
         </div> 


	<!-- /主体 -->

	
</div>
<!-- 底部 -->
	
    <!-- 底部
    ================================================== -->
    <div class="footer">
      <div class="container">
          <p>版权所有:天津市快E车盟远程定损系统 2014-2015 All Right Reserved <br>备案号：天津ICP备******号
		  <!-- 本站由 <strong> <a href="http://www.zwinsoft.com">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  ?--></p>
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
	
	<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5852020'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s5.cnzz.com/stat.php%3Fid%3D5852020%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>
	<p><strong> <a href="http://www.zwin.mobi">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  </p>
	<p><strong> <a href="http://www.zwinsoft.com">天津智易时代科技发展有限公司 </a></strong> 提供技术支持  </p>
	<p><strong> <a href="http://www.17qunawan.com" alt="去哪玩景点门票">去哪玩景点门票 </a></strong> 提供服务  </p>
	
</div>
<script language="javascript">
var qqFloatObj={
	color : '#0074cc',//百变由我 颜色自由定义 推荐 #000 #CCC #C30202 #48ff00
	openTitle :"客服中心",//关闭后右侧的浮动提示窗的题目
	closeTitle :"联<br/>系<br/>我<br/>们",//qq浮动窗口的题目
	style:"right:0px; top:150px;position: absolute;",//这里是css定义浮动窗口的位置，顶部距离
	width:"width:175px;",//这里定义浮动窗口的宽度
	qqstyle:7,//貌似1-9这里就是qq在线图片的显示样式
	isOpen:true, //默认展开：true,默认收缩：false
	other:"电话:<br />58785818转8022<br />QQ群：<br/>59166411",//这里可以编辑html
	qqlist:"客服QQ：|349902328" //多个qq用,隔开,QQ和客服名用|隔开
};
</script>
<script src="/car/Public/static/js/qqFloatWin1.0.js" type="text/javascript"></script>



	<!-- /底部 -->
	<script>
		$(".mainContentLeft").height(Math.max($(".span7").height(),600));
	</script>
</body>
</html>