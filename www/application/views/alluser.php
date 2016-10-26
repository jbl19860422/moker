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
    <script src="http://cdn.ronghub.com/RongIMLib-2.2.4.min.js"></script>
    <script type="text/javascript" src="application/views/js/common_rong.js?v=2016081302"></script>
</head>
<body>
<!-- 所有人-->
<div class="page-allusers">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<input type="text"  style="background-color:#dfdfdf;border-radius:0.05rem;margin-top:0.1rem;height:0.3rem;" class="title fl-l"></input>
		<a class="icon" href="javascript:void(0);" onclick="SearchUser()"><img src="application/views/images/search.jpg" /></a>
	</div>
	<div class="page-menu diymenu text-c bg-white margin-bottom">
		<a diy="diy-alluser-singer" class="hd-col-xs-e4 current" href="javascript:void(0)">歌手</a>
		<a diy="diy-alluser-server" class="hd-col-xs-e4" href="javascript:void(0)">服务员</a>
		<a diy="diy-alluser-guest" class="hd-col-xs-e4" href="javascript:void(0)">客人</a>
		<a diy="diy-alluser-outter" class="hd-col-xs-e4" href="javascript:void(0)">场外</a>
	</div>
	<div class='alluser-list'>
		<ul class="user-list singer-list hide">
			
		</ul>
		<ul class="user-list server-list hide">
			
		</ul>
		<ul class="user-list guest-list hide">
			
		</ul>
		<ul class="user-list outter-list hide">
			
		</ul>
	</div>

	<!-- 个人信息 -->
	<div class="diy-user-detail-all hd-h3 hide" style="opacity:100">
		<img class="headimg radius-100" src="" />
		<div class="nickname text-c hd-h3 text-black margin-bottom"></div>
		<div class="text-c margin-bottom-15">
			<span class="collect radius-5">3</span>
			<img class="audio margin-big-left" src="" />
		</div>
		<div class="links text-c">
			<a class="hd-col-xs-e4 zan" href="javascript:void(0);" style="margin-top:0px;"><em>0</em></a>
			<a class="hd-col-xs-e4 ta" href="javascript:void(0);">@TA</a>
			<a class="hd-col-xs-e4 gift" href="javascript:void(0);"><img src="http://o95rd8icu.bkt.clouddn.com/礼物.png"/></a>
			<a class="hd-col-xs-e4 priv" href="javascript:void(0);">密语</a>
		</div>
	</div>
</div>

<script type="text/javascript">
$(function(){
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".diymodel > div ")});	 //榜单模板切换
	commonJS.diychoose({"chooseobj":$(".diybill-menu > a"),"diyobj":$(".diybill-model > div ")});	 //我的账单模板切换
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".alluser-list > ul ")});	 //我的账单模板切换
});
</script>
<script type="text/javascript" src="application/views/js/API.js?v=2017081951"></script>
<script type="text/javascript">
	var g_RAppKey = "x18ywvqf8xdcc";
	var g_room = 'room'+$.cookie('bar_id');
	var g_bar = {bar_id:$.cookie('bar_id')};
	var g_guest = {user_id:0, desk_id:0, nickname:"", headimg:"", role:"", sex:"0"};
	$(function(){
		$('.goback').click(function() {
			history.back(-1);
		});

		var rongSocket = {emit: rongCloudEmit, on: rongCloudOn, messageHandler:{}}

		function rongCloudOn(type, callback) {
			rongSocket.messageHandler[type] = callback;
		}

		rongSocket.on('login', function(obj) {
			setOnlineUsers();
		});

		var onlineUsers = [];
		function setOnlineUsers() {
			getRoomInfo(function(userIDs) {
				for(var i = 0; i < userIDs.length; i++) {
					if(userIDs[i].indexOf('bar') > 0) {
						userIDs.splice(i, 1);
					}
				}

				API.query_users_info($.cookie('user_id'), userIDs, function(data) {
					onlineUsers = data;
					$(".page-allusers .user-list").html("");
					for(var i = 0; i < onlineUsers.length; i++) {
						var user = onlineUsers[i];
						var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
						if(user.role.indexOf("a") > 0) {
							roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
						} else if(user.role.indexOf("s") > 0) {
							roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
						}

						if(user.role.indexOf("a") >= 0) {
							$(".page-allusers .singer-list").append('<li> \
															<a href="javascript:void(0)" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																<img class="role-icon" src="'+roleImg+'"></img> \
																<img class="user-head" src="'+user.headimg+'"></img> \
																<p class="user-name">'+user.nickname+'</p> \
																<span class="zan fl-r">'+user.love+'</span> \
																<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
															  </a></li>');
						} else if(user.role.indexOf("s") >= 0) {
							$(".page-allusers .server-list").append('<li> \
															<a href="javascript:void(0);" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																<img class="role-icon" src="'+roleImg+'"></img> \
																<img class="user-head" src="'+user.headimg+'"></img> \
																<p class="user-name">'+user.nickname+'</p> \
																<span class="zan fl-r">'+user.love+'</span> \
																<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
															  </a></li>');
						} else if(user.role.indexOf("g") >= 0) {
							$(".page-allusers .guest-list").append('<li> \
															<a href="javascript:void(0);" style="width:100%;height:1border:1px solid red" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																<img class="role-icon" src="'+roleImg+'"></img> \
																<img class="user-head" src="'+user.headimg+'"></img> \
																<p class="user-name">'+user.nickname+'</p> \
																<span class="zan fl-r">'+user.love+'</span> \
																<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
															  </a></li>');
						}
						$(".page-allusers .singer-list").removeClass('hide');
					}
				});
			});
		}

		API.get_bar_rctoken($.cookie('bar_id'), function(rctoken) {
			rongCloudInit(g_RAppKey, rctoken, rongSocket.messageHandler, setOnlineUsers);
		});
	});
</script>
</body>
</html>
