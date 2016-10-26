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
    <script type="text/javascript" src="application/views/js/item.js?v=2016081407"></script>
    <script type="text/javascript" src="application/views/js/common.js?v=2016081302"></script>
	<script src="http://imoke.live/plhwin/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
</head>
<body>
<!-- 我的：start====================================================================== -->
<div class="page-center bg-gray hd-h3 hide">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">我的墨客</div>
		<a class="icon" href="javascript:void(0);"><img src="application/views/images/icon-33.png" /></a>
	</div>
	<div class="diy-userinfo text-c bg-white margin-bottom-15">
		<div class="headimg margin-bottom-15"><img class="radius-100" src="<?php echo $headimgurl;?>" /></div>
		<div class="nickname hd-h3 margin-bottom"><?php echo $nickname;?>
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
			<div class="introl text-gray">一起来做游戏吧</div>
			<div class="num text-red hd-h5">3</div>
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
<script type="text/javascript">
	var g_bar = {
		bar_id:<?php echo $barinfo['bar_id']; ?>,
		desk_id:<?php echo $desk_id; ?>,
		barimg:"<?php echo $barinfo['barimg'] ?>",
		name:"<?php echo $barinfo['name'] ?>",
		singer:null
	};

	var g_guest = {
		user_id:<?php echo $user_id;?>,
		nickname:"<?php echo $nickname;?>",
		desk_id:<?php echo $desk_id; ?>,
		headimg:"<?php echo $headimgurl;?>",
		role:"<?php echo $role;?>",
		sex:<?php echo $sex; ?>,
		bShowBarrageAlert:<?php echo $barrage_alert;?>
	};

</script>
<script type="text/javascript" src="application/views/js/API.js?v=2017081949"></script>
<script type="text/javascript" src="application/views/js/index_new.js?v=2017081951"></script>
<script type="text/javascript">
	BAR.init(g_bar.bar_id, g_bar.name, g_bar.barimg, g_bar.singer);
	GUEST.init(g_guest.user_id, g_guest.desk_id, g_guest.nickname, g_guest.headimg, g_guest.role, g_guest.sex, g_guest.bShowBarrageAlert);
</script>
</body>
</html>
