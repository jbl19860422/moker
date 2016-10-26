<!DOCTYPE html>
<html> 
<head>
	<base href="<?php  echo base_url();?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>陌客</title>
    <meta name="description" content="陌客">
    <meta name="keywords" content="陌客">
    <link href="application/views/css/animate.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="application/views/css/common.css?v=2016092605"/>
    <link rel="stylesheet" type="text/css" href="application/views/css/mobile.css?v=2016092605"/>
    <script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
    <script type="text/javascript" src="application/views/js/jquery.cookie.js?v=2016080301"></script>
    <script type="text/javascript" src="application/views/js/common.js?v=2016081302"></script>
</head>
<body>
<!-- 我的：start====================================================================== -->
<div class="page-center bg-gray hd-h3">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">我的墨客</div>
		<a class="icon" href="javascript:void(0);"><img src="application/views/images/icon-33.png" /></a>
	</div>
	<div class="diy-userinfo text-c bg-white margin-bottom-15">
		<div class="headimg margin-bottom-15"><img class="radius-100"/></div>
		<div class="nickname hd-h3 margin-bottom"><div style="display:inline"></div>
			<img src="http://o95rd8icu.bkt.clouddn.com/男性.png" / ><!-- 男：icon-35.png 女：icon-34.png -->
		</div>
		<div>
			<span class="collect radius-5">0</span>
			<span class="zan"></span>
		</div>
	</div>
	<div class="diy-usermsg bg-white margin-bottom-15">
		<a href="javascript:void(0);" class="priv-msg">
			<div class="title hd-h5">个人消息</div>
			<div class="introl text-gray"></div>
			<div class="num text-red hd-h5">0</div>
		</a>
		<a href="javascript:void(0);" class="newest-bar-msg">
			<div class="title hd-h5">商家消息</div>
			<div class="introl text-gray"></div>
			<!--<div class="num text-red hd-h5 barmessage">3</div>-->
		</a>
		<a href="javascript:void(0);" class="sys-msg">
			<div class="title hd-h5">系统消息</div>
			<div class="introl text-gray"></div>
			<!-- <div class="num text-red hd-h5">3</div> -->
		</a>
	</div>
	<div class="diy-usermsg bg-white margin-bottom-15">
		<a href="javascript:void(0);" class="pay">
			<div class="item hd-h5">八刻币充值</div>
			<div class="money-num num text-gray hd-h5"></div>
		</a>
	</div>
	<div class="diy-usermsg bg-white">
		<a href="javascript:void(0);" id="ID_activity">
			<div class="item hd-h5">HI现场</div>
		</a>
		<a href="javascript:void(0);">
			<div class="item hd-h5">帮助和反馈</div>
		</a>
		<a href="javascript:void(0);">
			<div class="item hd-h5">商务合作</div>
		</a>
	</div>

	<div class="hd-grid">&nbsp;</div>
</div>
<!-- 我的：start====================================================================== -->

<script type="text/javascript">
$(function(){
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".diymodel > div ")});	 //榜单模板切换
	commonJS.diychoose({"chooseobj":$(".diybill-menu > a"),"diyobj":$(".diybill-model > div ")});	 //我的账单模板切换
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".alluser-list > ul ")});	 //我的账单模板切换
});
</script>
<script type="text/javascript" src="application/views/js/API.js?v=2017081949"></script>
<script type="text/javascript" src="application/views/js/my.js?v=2017081951"></script>
<script type="text/javascript">
$(function() {
	$(".nickname div").html($.cookie("nickname"));
	$(".headimg img").attr('src', $.cookie('headimg'));
	$(".goback").click(function(){history.back(-1);});
	//获取到最近的一条酒吧消息
	API.query_bar_msg($.cookie('bar_id'), function(data) {
		last_msg = {};
		for(var p in data) {
			last_msg = data[p];
		}
		$(".newest-bar-msg .introl").html(JSON.parse(last_msg).title);
	});
	//获取最近的系统消息
	API.query_sys_msg($.cookie('bar_id'), function(data) {
			var msgs = [];
			for(var p in data) {
				msgs.push({
					time:p,
					data:data[p]
				});
			}

			if(msgs.length > 0) {
				$(".page-center .sys-msg .introl").html(JSON.parse(msgs[msgs.length-1].data).title);
			}
	});

	API.query_activity($.cookie('bar_id'), function(actInfo) {
		GUI.updateActivity(actInfo);
	});

	API.query_user_info($.cookie('user_id'), $.cookie('user_id'), function(userInfo) {
		GUI.updateUserInfo(userInfo);
	});

	API.query_unviewed_privmsg($.cookie('user_id'), function(data) {
		$(".priv-msg .num").html(data.length);
		if(data.length > 0) {
			data.sort(function(a,b) {
				return b.timestamp - a.timestamp;
			});
			$(".priv-msg .introl").html(data[0].content);
		}
	});

	$(".newest-bar-msg").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/barmsg";
	});

	$(".page-center .sys-msg").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/sysmsg";
	});

	$(".pay").click(function() {
		window.location.href="http://dream.waimaipu.cn/index.php/user/paypage";
	});

	$(".priv-msg").click(function() {
		window.location.href="http://dream.waimaipu.cn/index.php/user/privmsg_page";
	});
});
</script>
</body>
</html>
