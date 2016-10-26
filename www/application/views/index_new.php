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
    <script type="text/javascript" src="application/views/js/item.js?v=2016081407"></script>
    <script type="text/javascript" src="application/views/js/common.js?v=2016081302"></script>
	<script src="http://imoke.live/plhwin/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
	<style type="text/css">
		.bubble {
			width:0.8rem;
			right:0px;
			bottom:1rem;
			height:2rem;
			position:absolute;
			margin:0px;
			padding:0px;
			font-size:12px;
			-webkit-font-smoothing:antialiased;
			line-height:1.5;
		}
		
		svg {
			position:absolute;
			overflow:hidden;
			font-size:12px;
			-webkit-font-smoothing:antialiased;
			line-height:1.5;
			bottom:0px;
		}
	</style>
</head>
<body>
<!-- 首页：start====================================================================== -->
<div class="page-home">
	<!-- <img src="application/views/images/test1.gif" style="width:100px;height:100px;z-index:999;position:absolute;top:0px;left:0px;"></img> -->
	<!-- 用户信息 -->
	<div class="diy-user text-white hd-h4 fl-l">
		<a href="javascript:void(0)" onclick="showUserInfo(g_singer)"><img class="radius-100 width-45 height-45 fl-l margin-small-right" src="http://wx.qlogo.cn/mmopen/NGA89eK6LL4T8f79altDxEPxfNzRcbjGRlj3rf8g3ibh6ibteZNbEic3Q7ic2meYNL6ZbHpJ85zm1QCcEa56zJsqdg/0" id="ID_singerImg"/></a>
		<span class="uname fl-l margin-small-right padding-small" id="ID_singerName">x</span>
		<span class="collect radius-10 text-c fl-l margin-small-top" id="ID_singerLiveness">3</span>
		<span class="zan fl-l margin-small-left" id="ID_singerLove">805</span>
		<span class="audio fl-l" id="ID_microphone"></span>
	</div>

	<!-- 在线用户 -->
	<div class="diy-online-user text-r fl-r margin-top" id="ID_onlineUsers">
		<a class="radius-5 text-white fl-r margin-right hd-h4" href="javascript:void(0);" id="ID_onlineCount">680</a>
		<div class="diy-online-imgs fl-r">
			<div id="ID_onlineHeaders">
				<img class="radius-100 width-25 height-25" src="http://o95rd8icu.bkt.clouddn.com/headimg.jpg" />
			</div>
		</div>
	</div>

	<div class="hd-grid"></div>

	<!-- 弹幕及系统消息 -->
	<ul class="diy-sys text-or scrollBottom">
	</ul>
	<div class="barrage-space"></div>
	<!-- 消息区 -->
	<ul class="diy-msg text-yew scrollBottom">
	</ul>
	<!-- 点赞 -->
	<div class="diy-zan">
		<img class="diy-zan-btn" src="application/views/images/icon-8.png" />
	</div>
	
	<div id="player-praises" style="width:72px;height:337px;position:fixed;-webkit-font-smoothing:antialiased;bottom:40px;right:5px;margin:0px;padding:0px;font-size:12px;line-height:1.5;">
		<div class="bubble" id="bubble_div">
			
		</div>
	</div>

	<!-- 底部导航 -->
	<div class="diy-footer">
		<a class="fl-l diy-img-chat" href="javascript:void(0);"></a>
		<a class="fl-l diy-img-gift" href="javascript:void(0);"></a>
		<a class="fl-r diy-img-user" href="javascript:void(0);" style="text-decoration:none">
			<div style="background-color:#d0361a;border-radius:10px;width=10px;height=10px;width: 20px;height: 20px;text-align: center;text-decoration: none;font-color: white;color: white;position: relative;left: 30px;top: -7px;">
				<div style="padding-top:2px;" id="ID_msg_count">0</div>
			</div>
		</a>
		<a class="fl-r diy-img-trophy" href="javascript:void(0);"></a>
	</div>

	<!-- 礼物 -->
	<div class="diy-gift hide">
		<div class="prompt-box">
			<p>礼券在有效期内可兑换商家商品，除礼券外，其它礼物赠送后，服务员会主动赠送商品。</p>
			<a href="javascript:void(0);">知道了</a>
		</div>
		<ul></ul>
		<a class="buy-coin" href="javascript:void(0);">充值：<em>100币</em></a>
		<a class="gift-send" href="javascript:void(0);">发送</a>
	</div>

	<!-- 聊天 -->
	<div class="diy-chat hide">
		<div class="brow-imgs" style="display: none;"></div>
		<div class="barrage-btn fl-l open"><div>弹幕</div></div><!-- open:开启弹幕状态，默认情况下不要open -->
		<div class='user' style="float:left;margin-left:2px;border-top:1px solid #eee;border-bottom:1px solid #eee;border-left:1px solid #eee;height:24px;padding-top:5px;border-top-left-radius:3px;border-bottom-left-radius:3px;min-width:3px"></div>
		<div class="chat" contenteditable=true></div>
		<div class="sendmsg-btn fl-r">发送</div>
		<div class="brow-btn fl-r"><img src="http://o95rd8icu.bkt.clouddn.com/0.png" /></div>
	</div>

	<!-- 个人信息 -->
	<div class="diy-user-detail hd-h3 hide" style="opacity:100">
		<img class="headimg radius-100" src="" />
		<div class="nickname text-c hd-h4 text-black margin-bottom"></div>
		<div class="text-c margin-bottom-15">
			<span class="collect radius-5">3</span>
			<img class="audio margin-big-left" src="" />
		</div>
		<div class="links text-c">
			<a class="hd-col-xs-e4 zan" href="javascript:void(0);"><em>0</em></a>
			<a class="hd-col-xs-e4 ta" href="javascript:void(0);">@TA</a>
			<a class="hd-col-xs-e4 gift" href="javascript:void(0);"><img src="http://o95rd8icu.bkt.clouddn.com/礼物.png"/></a>
			<a class="hd-col-xs-e4 priv" href="javascript:void(0);">密语</a>
		</div>
	</div>
	
	<!-- 密语：start -->
	<div class="diy-cryptolalia bg-white hide">
		<div class="diy-cryp-title">悠悠乐队<img class="diy-cryp-close" src="images/icon-40.png" /></div>
		<div class="diy-cryp-introl">安全沟通，肆意畅聊，所有聊天记录都不保存，关闭即焚</div>
		<div class="diy-cryp-data scrollBottom" style="height:1.5rem;overflow-y:auto;overflow-x:hidden;">
		</div>	
		<!--
		<div class="diy-cryp-cont" style="height:1.5rem;overflow-y:auto;overflow-x:hidden;">
		</div>
		-->
		<div class="brow-imgs" style="display: none;"></div>
		<div style="width:100%; height:0.25rem;">
			<div class="fl-l margin user-gift">
				<img class="width-25 height-25" src="http://o95rd8icu.bkt.clouddn.com/礼物.png" />
			</div>
			<div class="chat cryptolalia-chat bg-gray" contenteditable=true style="width:50%"></div>
			<div class="sendmsg-btn fl-r margin-top">发送</div>
			<div class="brow-btn margin-top  fl-r"><img class="width-25 height-25" src="http://o95rd8icu.bkt.clouddn.com/笑脸.png" /></div>
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
<script type="text/javascript" src="application/views/js/API.js?v=2017081962"></script>
<script type="text/javascript" src="application/views/js/index_new.js?v=20170825"></script>
<script type="text/javascript">
	BAR.init(g_bar.bar_id, g_bar.name, g_bar.barimg, g_bar.singer);
	GUEST.init(g_guest.user_id, g_guest.desk_id, g_guest.nickname, g_guest.headimg, g_guest.role, g_guest.sex, g_guest.bShowBarrageAlert);
</script>
</body>
</html>
