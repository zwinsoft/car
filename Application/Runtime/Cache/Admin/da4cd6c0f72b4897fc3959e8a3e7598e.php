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
            addressInit('cmbProvince', 'cmbCity', 'cmbArea', '天津', '天津市', '南开区');
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
        <!--start-->
        <fieldset>
        <legend>我的任务</legend>
        <form class="well  form-inline" method="get" action="?"/>
            <input type="hidden" name="m" value="Admin">
            <input type="hidden" name="c" value="Index">
            <input type="hidden" name="a" value="Index">

        <div class="control-group">
            <div class="controls">
<!--
                水源地址: <input type="text" class="input-large" placeholder="水源名称" id="title" name='title' value="<?php echo ($_GET['title']); ?>">
                <?php if(!empty($_GET['type'])): ?><script>
                        $("#title").val('<?php echo ($_GET['title']); ?>');
                    </script><?php endif; ?>
-->
                <select name='type' id="type" style="width:15%">
                    <option value="">－请选择水源类型－</option>
                    <option value="1">地上式</option>
                    <option value="2">地下式</option>
                    <option value="3">地上水鹤</option>
                    <option value="4">消防水池</option>
                    <option value="5">天然水源</option>
                </select>
                <?php if(!empty($_GET['type'])): ?><script>
                        $("#type").val('<?php echo ($_GET['type']); ?>');
                    </script><?php endif; ?>

            	<select name='status' id="status" style="width:15%">
            		<option value="">－请选择水源情况－</option>
                    <option value="1">全天有水</option>
                    <option value="2">非全天有水</option>
                    <option value="3">损坏</option>
            	</select>
                <?php if(!empty($_GET['status'])): ?><script>
                        $("#status").val('<?php echo ($_GET['status']); ?>');
                    </script><?php endif; ?>
                
                <select name='pressure' id="pressure" style="width:15%">
            		<option value="">－请选择水压情况－</option>
                    <option value="1">正常</option>
                    <option value="2">不足</option>
            	</select>
                <?php if(!empty($_GET['pressure'])): ?><script>
                    $("#pressure").val('<?php echo ($_GET['pressure']); ?>');
                </script><?php endif; ?>

                采集员: <input type="text" class="input-large" placeholder="采集员" id="collector" name='collector' value="<?php echo ($_GET['collector']); ?>">
                <?php if(!empty($_GET['collector'])): ?><script>
                        $("#collector").val('<?php echo ($_GET['collector']); ?>');
                    </script><?php endif; ?>

                <select name='confirm' id="confirm" style="width:15%">
                    <option value="">－请选择审核状态－</option>
                    <option value="2">待审核</option>
                    <option value="1">已通过</option>
                    <option value="-1">已驳回</option>
                </select>
                <?php if(!empty($_GET['confirm'])): ?><script>
                        $("#confirm").val('<?php echo ($_GET['confirm']); ?>');
                    </script><?php endif; ?>

                <br>
                <div class="controls input-append date form_date createTime" data-date="" data-date-format="yyyy-mm-dd" data-link-field="createTime" data-link-format="yyyy-mm-dd">
                    起始时间：
                    <input size="16" type="text" value="" readonly id="beginDate" name="beginDate" placeholder="单击选择时间">
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <?php if(!empty($_GET['beginDate'])): ?><script>
                        $("#beginDate").val('<?php echo ($_GET['beginDate']); ?>');
                    </script><?php endif; ?>
                <div class="controls input-append date form_date createTime" data-date="" data-date-format="yyyy-mm-dd" data-link-field="createTime" data-link-format="yyyy-mm-dd">
                    结束时间：
                    <input size="16" type="text" value="" readonly id="endDate" name="endDate" placeholder="单击选择时间">
                    <span class="add-on"><i class="icon-remove"></i></span>
                    <span class="add-on"><i class="icon-th"></i></span>
                </div>
                <?php if(!empty($_GET['endDate'])): ?><script>
                        $("#endDate").val('<?php echo ($_GET['endDate']); ?>');
                    </script><?php endif; ?>

            	<button  class="btn btn-primary btn-block"><i class="icon-search"></i> 查 找 </button>
            </div>
        </div>
		</form>

            <table class="table table-bordered table-striped ">
                <tr>
                    <td>序号</td>
                    <td>水源位置</td>
                    <td>水源类型</td>
                    <td>水源状态</td>
                    <td>水压</td>
                    <td>采集员</td>
                    <td>检查日期</td>
                    <td>审核状态</td>
                    <td colspan="2">操作</td>
                </tr>

            <?php if(is_array($data)): $k = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><tr id='tr_<?php echo ($vo["id"]); ?>'>
                <td><?php echo ($k); ?></td>
                <td><?php echo ($vo["title"]); ?></td>
                <td>
                    <?php if(($vo["type"] == 1)): ?>地上式
                    <?php elseif(($vo["type"] == 2)): ?>
                        地下式
                    <?php elseif(($vo["type"] == 3)): ?>
                        消防水鹤
                    <?php elseif(($vo["type"] == 4)): ?>
                        消防水池
                    <?php elseif(($vo["type"] == 5)): ?>
                        天然水源
                    <?php else: ?>类型暂无<?php endif; ?>
                </td>
                <td>
                    <?php if(($vo["status"] == 1)): ?><font color="red">全天有水</font>
                    <?php elseif(($vo["status"] == 2)): ?>
                        <font color="#D6BE05">非全天有水</font>
                    <?php elseif(($vo["status"] == 3)): ?>
                        <font color="grey">损坏</font>
                    <?php else: endif; ?>
                </td>
                <td>
                    <?php if(($vo["pressure"] == 1)): ?><font color="red">水压正常</font>
                    <?php elseif(($vo["pressure"] == 2)): ?>
                    <font color="#D6BE05">水压不足</font>
                    <?php else: endif; ?>
                </td>
                <td><?php echo ($vo["userRealname"]); ?></td>
                <td><?php echo ($vo["date"]); ?></td>
                <td>
                    <?php if($vo["confirm"] == 0): ?><font color='red'>待审核</font>
                    <?php elseif($vo["confirm"] == -1): ?>
                        <font color='grey'>未通过</font>
                    <?php elseif($vo["confirm"] == 1): ?>
                        <font color='green'>已通过</font><?php endif; ?>
                </td>
                <td><a href='?m=Admin&c=Index&a=detail&id=<?php echo ($vo["id"]); ?>'>查看</a></td>
                <td><a href="javascript:void(0);" onclick="ajax_delete(<?php echo ($vo["id"]); ?>)">删除</a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>

            <tr>
                <td>汇总统计</td>
                <td colspan="9">
                    已通过水源<a href="?m=Admin&c=Index&a=Index&confirm=1"><?php echo ($waterConfirmed); ?></a>个，
                    待审核<a href="?m=Admin&c=Index&a=Index&confirm=2"><?php echo ($waterWaiting); ?></a>个，
                    未通过<a href="?m=Admin&c=Index&a=Index&confirm=-1"><?php echo ($waterRefused); ?></a>个，
                    合计<a href="?m=Admin&c=Index&a=Index"><?php echo ($waters); ?></a>个
                    &nbsp;&nbsp;符合本次筛选条件的水源有<?php echo ($all); ?>个
                </td>
            </tr>

		</table>
        
        <div style="width:100%;text-align:right;">
        <?php?>
		<div class="pagination pagination-right">
            <ul>
                <li>
                    <a href="?m=Admin&c=Index&a=Index&title=<?php echo ($_GET['title']); ?>&type=<?php echo ($_GET['type']); ?>&status=<?php echo ($_GET['status']); ?>&pressure=<?php echo ($_GET['pressure']); ?>&page=1">
                    <i class="icon-fast-backward"></i></a>
                </li>
                <li class="<?php echo ($page==1 || !isset($page))?"disabled":"";?>">
                    <a href="?m=Admin&c=Index&a=Index&title=<?php echo ($_GET['title']); ?>&type=<?php echo ($_GET['type']); ?>&status=<?php echo ($_GET['status']); ?>&pressure=<?php echo ($_GET['pressure']); ?>&page=<?php echo ($page-1); ?>">前一页</a>
                </li>
                <li class="<?php echo $page==($maxpage-1)?"disabled":"";?>">
                    <a href="?m=Admin&c=Index&a=Index&title=<?php echo ($_GET['title']); ?>&type=<?php echo ($_GET['type']); ?>&status=<?php echo ($_GET['status']); ?>&pressure=<?php echo ($_GET['pressure']); ?>&page=<?php echo ($page+1); ?>">下一页</a>
                </li>
                <li>
                    <a href="?m=Admin&c=Index&a=Index&title=<?php echo ($_GET['title']); ?>&type=<?php echo ($_GET['type']); ?>&status=<?php echo ($_GET['status']); ?>&pressure=<?php echo ($_GET['pressure']); ?>&page=<?php echo ($maxpage-1); ?>"><i class="icon-fast-forward"></i></a>
                </li>
                <li>
                    <select class="input-small" style="line-height:34px;height:36px;" onchange="location.href='/car/index.php?m=Admin&c=Index&a=Index&st=<?php echo ($_GET['st']); ?>&et=<?php echo ($_GET['et']); ?>&comp=<?php echo ($_GET['comp']); ?>&status=<?php echo ($_GET['status']); ?>&page='+this.value">
					<?php $__FOR_START_1831131316__=1;$__FOR_END_1831131316__=$maxpage;for($n=$__FOR_START_1831131316__;$n < $__FOR_END_1831131316__;$n+=1){ if($page == $n ): ?><option value="<?php echo ($n); ?>" selected>第<?php echo ($n); ?>页</option>
						<?php else: ?>
						  <option value="<?php echo ($n); ?>" >第<?php echo ($n); ?>页</option><?php endif; } ?>
					</select>
				</li>
            </ul>
        </div>
			
		</div>
		<!--end-->
	</div>
</div>
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