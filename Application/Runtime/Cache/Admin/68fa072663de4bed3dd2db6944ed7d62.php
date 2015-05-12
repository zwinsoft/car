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



	<script type="text/javascript" src="/car/Public/static/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
	<script type="text/javascript" src="/car/Public/static/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
	<script type="text/javascript" src="/car/Public/static/js/jsAddress.js" charset="UTF-8"></script>
	<script>
	$(function(){
		$('.form_date').datetimepicker({
			language:  'zh-CN',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0
			});

			
	 });

    </script>


<!-- 页面header钩子，一般用于加载插件CSS文件和代码 -->


</head>
<body>
	<!-- 头部 -->
	<!-- 导航条
================================================== -->
<div id="head">
    <div class="container" style="width:1127px;">
    		
        <div style="float:left;"><h1 class="logoTitle" style="font-size:30px;">天津市静海消防局消防水源系统</h1></div>
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
			<?php if($_SESSION['tjac_fengxian_']['user_level']== 8): ?><li <?php echo ($m1); ?>><a href="<?php echo U('Admin/Project/Index');?>" >我的评审</a></li><?php endif; ?>
						
			<?php if($_SESSION['tjac_fengxian_']['user_level']== 9): ?><li <?php echo ($waterLise); ?>><a href="?m=Admin&c=Index&a=Index" >水源列表管理</a></li>
            <li <?php echo ($waterMap); ?>><a href="?m=Admin&c=Index&a=Map" >水源地图显示</a></li>
            <li class="nav-header">消防实战</li>
            <li ><a href="" >车辆列表显示</a></li>
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


        <!--引用百度地图API-->
        <style type="text/css">
            html,body{width: 100%;height: 100%;margin:0;padding:0;}
            .iw_poi_title {color:#CC5522;font-size:14px;font-weight:bold;overflow:hidden;padding-right:13px;white-space:nowrap}
            .iw_poi_content {font:12px arial,sans-serif;overflow:visible;padding-top:4px;white-space:-moz-pre-wrap;word-wrap:break-word}
        </style>
        <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=A4749739227af1618f7b0d1b588c0e85"></script>


        <!--百度地图容器-->
        <div style="width: 80%;height:600px;" id="dituContent"></div>

    <script type="text/javascript">
        //创建和初始化地图函数：
        function initMap(){
            createMap();//创建地图
            addMapControl();//向地图添加控件
            setMapEvent();//设置地图事件
            addMarker();//向地图中添加marker
            map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
        }

        //创建地图函数：
        function createMap(){
            var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图
            var point = new BMap.Point(116.944587,38.93771);//定义一个中心点坐标
            map.centerAndZoom(point,16);//设定地图的中心点和坐标并将地图显示在地图容器中
            window.map = map;//将map变量存储在全局
            map.enableScrollWheelZoom();
        }

        //地图事件设置函数：
        function setMapEvent(){
            map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
            map.enableScrollWheelZoom();//启用地图滚轮放大缩小
            map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
            map.enableKeyboard();//启用键盘上下左右键移动地图
        }

        //地图控件添加函数：
        function addMapControl(){
            //向地图中添加缩放控件
            var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
            map.addControl(ctrl_nav);
            //向地图中添加缩略图控件
            /*
             var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
             map.addControl(ctrl_ove);
             */
            //向地图中添加比例尺控件
            var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
            map.addControl(ctrl_sca);
        }

        //标注点数组
        var markerArr = eval(<?php echo ($json); ?>);
        //console.log(markerArr);

        //创建marker
        function addMarker(){
            for(var i=0;i<markerArr.length;i++){
                var json = markerArr[i];
                var p0 = json.point.split("|")[0];
                var p1 = json.point.split("|")[1];
                var point = new BMap.Point(p0,p1);

                markerArr[i].icon = json.icon;

                //console.log(json);

                var iconImg = new BMap.Icon(markerArr[i].icon, new BMap.Size(21,25));

                var marker = new BMap.Marker(point,{icon:iconImg});
                var iw = createInfoWindow(i);
                var label = new BMap.Label('',{"offset":new BMap.Size(json.icon.lb-json.icon.x+10,-20)});
                marker.setLabel(label);
                map.addOverlay(marker);
                label.setStyle({
                    borderColor:"#808080",
                    color:"#333",
                    cursor:"pointer"
                });

                (function(){
                    var index = i;
                    var _iw = createInfoWindow(i);
                    var _marker = marker;
                    _marker.addEventListener("click",function(){
                        this.openInfoWindow(_iw);
                    });
                    _iw.addEventListener("open",function(){
                        _marker.getLabel().hide();
                    })
                    _iw.addEventListener("close",function(){
                        _marker.getLabel().show();
                    })
                    label.addEventListener("click",function(){
                        _marker.openInfoWindow(_iw);
                    })
                    if(!!json.isOpen){
                        label.hide();
                        _marker.openInfoWindow(_iw);
                    }
                })()
            }
        }
        //创建InfoWindow
        function createInfoWindow(i){
            var json = markerArr[i];
            var iw = new BMap.InfoWindow("<b class='iw_poi_title' title='" + json.title + "'>" + json.title + "</b><div class='iw_poi_content'>"+json.content+"</div>");
            return iw;
        }
        //创建一个Icon
        function createIcon(json){
            var icon = new BMap.Icon("http://app.baidu.com/map/images/us_mk_icon.png", new BMap.Size(json.w,json.h),{imageOffset: new BMap.Size(-json.l,-json.t),infoWindowOffset:new BMap.Size(json.lb+5,1),offset:new BMap.Size(json.x,json.h)})
            return icon;
        }

        initMap();//创建和初始化地图
    </script>

<script>
    //ajax删除水源信息
    function ajax_delete(id) {
        if(confirm('您确定要删除该水源信息吗？')) {
            URL = '?m=Admin&c=Index&a=ajax_delete&id='+id;
            callback = function (json) {
                obj = eval(json);
                alert(obj.str);
                window.location.reload();
            }
            $.get(URL,callback);
        }        
    }

</script>
</div>


	<!-- /主体 -->

	
</div>
<!-- 底部 -->
	
    <!-- 底部
    ================================================== -->
    <div class="footer">
      <div class="container">
          <p>版权所有:天津市静海消防局 2011-2014 All Right Reserved <br>备案号：天津ICP备******号
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