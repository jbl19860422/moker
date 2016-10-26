<!DOCTYPE html>
<html> 
<head>
	<base href="<?php  echo base_url();?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>默客-直播室</title>
    <meta name="description" content="默客">
    <meta name="keywords" content="默客">
    <link href="application/views/css/animate.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="application/views/css/common_react.css"/>
    <link rel="stylesheet" type="text/css" href="application/views/css/mobile_react.css"/>
    <script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
    <script type="text/javascript" src="application/views/js/jquery.cookie.js?v=2016080301"></script>
    <script type="text/javascript" src="application/views/js/item.js?v=2016081407"></script>
	<script src ="http://cdn.ronghub.com/RongIMLib-2.2.4.min.js"></script>
	<script type="text/javascript" src="application/views/react/react.min.js"></script>
	<script type="text/javascript" src="application/views/react/react-dom.min.js"></script>
</head>
<body>
<!-- 首页：start====================================================================== -->
<div class="page-home">
	<div id="header"></div>
	<div id="gift"></div>
	<div id="danmu"></div>
	<div id="message"></div>
	<div id="love"></div>
	<div id="footer"></div>

	<!-- 礼物弹框 -->
	<div id="giftModal"></div>

	<!-- 聊天弹框 -->
	<div id="messageModal"></div>

	<!-- 用户信息弹框 -->
	<div id="userModal"></div>
	
	<!-- 密语弹框 -->
	<div id="chatModal"></div>
</div>
<script type="text/javascript">
	var g_bar = {
		bar_id:<?php echo $barinfo['bar_id']; ?>,
		desk_id:<?php echo $desk_id; ?>,
		barimg:"<?php echo $barinfo['barimg'] ?>",
		name:"<?php echo $barinfo['name'] ?>",
		singer:<?php echo json_encode($singer_info); ?>
	};

	var g_guest = {		user_id:<?php echo $user_info['user_id'];?>,
		rc_token:"<?php echo $rc_token;?>",
		nickname:"<?php echo $user_info['nickname'];?>",
		desk_id:<?php echo $desk_id; ?>,
		headimg:"<?php echo $user_info['headimg'];?>",
		role:"<?php echo $user_info['role'];?>",
		sex:<?php echo $user_info['sex']; ?>,
		bShowBarrageAlert:<?php echo $user_info['barrage_alert'];?>
	};
	g_room = "room"+g_bar.bar_id;
</script>
<script type="text/javascript" src="application/views/js/API.js?v=2017081967"></script>
<script type="text/javascript" src="application/views/js/common_rong.js?v=2016081311"></script>
<script type="text/javascript" src="application/views/js/index_rong.js?v=20170839"></script>
<script type="text/javascript" src="application/views/react/app.bundle.js"></script>
<script type="text/javascript">
	BAR.init(g_bar.bar_id, g_bar.name, g_bar.barimg, g_bar.singer);
	GUEST.init(g_guest.user_id, g_guest.desk_id, g_guest.nickname, g_guest.headimg, g_guest.role, g_guest.sex, g_guest.bShowBarrageAlert, g_guest.rc_token);
</script>
<script type="text/javascript">
$(function(){
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".diymodel > div ")});	 //榜单模板切换
	commonJS.diychoose({"chooseobj":$(".diybill-menu > a"),"diyobj":$(".diybill-model > div ")});	 //我的账单模板切换
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".alluser-list > ul ")});	 //我的账单模板切换
});
</script>
</body>
</html>
