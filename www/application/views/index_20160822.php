<!DOCTYPE html>
<html> 
<head>
	<base href="<?php  echo base_url();?>"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>八客互动</title>
    <meta name="description" content="关键词">
    <meta name="keywords" content="描述">
    <link rel="stylesheet" type="text/css" href="application/views/css/common.css?v=2016081509"/>
    <link rel="stylesheet" type="text/css" href="application/views/css/mobile.css?v=2016081511"/>
    <script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
    <script type="text/javascript" src="application/views/js/item.js?v=2016081405"></script>
    <script type="text/javascript" src="application/views/js/common.js?v=2016081302"></script>
	<script src="http://119.29.10.176/plhwin/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
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
<div class="mask opacity hide" style="positon:fixed;">努力请求中...</div> 
<!-- 首页：start====================================================================== -->
<div class="page-home">
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
	<ul class="diy-sys text-org hd-h4 scrollBottom">
		<!--
		<li class="diy-sys-1 padding-right margin-bottom">
			<img class="radius-100 width-40 height-40 fl-l padding-right" src="./images/headimg.jpg" />
			大阪-第1桌：<em class="text-white">再来一首</em>
		</li>
		<li class="diy-sys-2 text-red padding-left padding-right margin-bottom">
			商家公告：庆祝欧洲杯赛事，全场优惠
		</li>
		<li class="diy-sys-3 margin-bottom">	
			<img class="radius-100 width-40 height-40 fl-l padding-right" src=".//images/headimg.jpg" />
			<div class="fl-l padding-right">大阪-第1桌<b class="text-purple">赠送小利Jily-第2桌：</b></div>
			<div class="padding-left text-blue">玫瑰花</div>
			<div class="diy-sys-gift"><img src=".//images/icon-6.png"/><em class="text-white hd-h2">x10</em></div>
		</li>
		<li class="diy-sys-1 padding-right margin-bottom">
			<img class="radius-100 width-40 height-40 fl-l padding-right" src=".//images/headimg.jpg" />
			大阪-第1桌：<em class="text-white">再来一首</em>
		</li>
		<li class="diy-sys-1 padding-right margin-bottom">
			<img class="radius-100 width-40 height-40 fl-l padding-right" src=".//images/headimg.jpg" />
			大阪-第1桌：<em class="text-white">再来一首</em>
		</li>
		<li class="diy-sys-1 padding-right margin-bottom">
			<img class="radius-100 width-40 height-40 fl-l padding-right" src=".//images/headimg.jpg" />
			大阪-第1桌：<em class="text-white">再来一首</em>
		</li>
		-->
	</ul>

	<!-- 消息区 -->
	<ul class="diy-msg text-yew hd-h4 scrollBottom">

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
		<ul>

		</ul>
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
		<div class="nickname text-c hd-h3 text-black margin-bottom"></div>
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
	
	<!-- 谜语：start -->
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
<!-- end====================================================================== -->
<!-----红包页面开始------>
<div class="page-red-packets hide" style="width:100%;height:100%;background: #fdfaf5;">
	<div class="redpacket-top">
		<img src="http://o95rd8icu.bkt.clouddn.com/rt.png" style="width:0.15rem;height:0.2rem" onclick="goback();">
		<span>发红包</span>
	</div>
	<ul class="redpacket-btn hide">
		<li><a href="javascript:;">送主播</a></li>
		<li><a href="javascript:;">群红包</a></li>
	</ul>

	<div class="redpacket-content">
		<div>
			<div class="redpacket-name">
				<img src="http://o95rd8icu.bkt.clouddn.com/icon.png">
				<span>用户名</span>
			</div>
			<p>Ta将立即获得您发的红包</p>
			
			<div class="redpacket-txt1">
				<span>总发出</span><input type="text" placeholder="填写数量" onkeyup="this.value=this.value.replace(/\D/g,'')">八客币
			</div>
			<p>当前八客币数78</p>
			<input class="redpacket-txt2" type="text" placeholder="恭喜发财，大吉大利">
			<button>塞进红包</button>	
			<h5>充值八刻币>></h5>
		</div>
	</div>
</div>
<!-----红包页面结束------>
<!-- 榜单：start====================================================================== -->
<div class="page-bangdan bg-gray hide">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">榜单</div>
	</div>
	<div class="page-menu diymenu text-c bg-white margin-bottom">
		<a diy="diy-bangdan-recvgift" class="hd-col-xs-e4 current" href="javascript:void(0)">送礼</a>
		<a diy="diy-bangdan-sendgift" class="hd-col-xs-e4" href="javascript:void(0)">收礼</a>
		<a diy="diy-bangdan-recvlove" class="hd-col-xs-e4" href="javascript:void(0)">爱心</a>
		<a diy="diy-bangdan-sendlove" class="hd-col-xs-e4" href="javascript:void(0)">参与</a>
	</div>
	<div class="diy-bangdan diymodel">
		<div class="bg-white">
			<ul id="ID_givegift_rank">
			</ul>
		</div>
		<div class="diy-bangdan bg-white hide">
			<ul id="ID_gotgift_rank">
			</ul>
		</div>
		<div class="diy-bangdan bg-white hide">
			<ul id="ID_gotlove_rank">
			</ul>
		</div>
		<div class="diy-bangdan bg-white hide">
			<ul id="ID_givelove_rank">
			</ul>
		</div>
	</div>
</div>
<!-- 榜单：end====================================================================== -->


<!-- 我的：start====================================================================== -->
<div class="page-center bg-gray hd-h3 hide">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">我的八刻</div>
		<a class="icon" href="javascript:void(0);"><img src="application/views/images/icon-33.png" /></a>
	</div>
	<div class="diy-userinfo text-c bg-white margin-bottom-15">
		<div class="headimg margin-bottom-15"><img class="radius-100" src="<?php echo $headimgurl;?>" /></div>
		<div class="nickname hd-h2 margin-bottom"><?php echo $nickname;?>
			<img src="http://o95rd8icu.bkt.clouddn.com/男性.png" / ><!-- 男：icon-35.png 女：icon-34.png -->
		</div>
		<div>
			<span class="collect radius-5">0</span>
			<span class="zan"></span>
		</div>
	</div>
	<div class="diy-usermsg bg-white margin-bottom-15">
		<a href="javascript:void(0);" class="priv-msg">
			<div class="title hd-h3">个人消息</div>
			<div class="introl text-gray"></div>
			<div class="num text-red hd-h3"></div>
		</a>
		<a href="javascript:void(0);" class="newest-bar-msg">
			<div class="title hd-h3">商家消息</div>
			<div class="introl text-gray"></div>
			<!--<div class="num text-red hd-h3 barmessage">3</div>-->
		</a>
		<a href="javascript:void(0);" class="sys-msg">
			<div class="title hd-h3">系统消息</div>
			<div class="introl text-gray"></div>
			<!-- <div class="num text-red hd-h3">3</div> -->
		</a>
	</div>
	<div class="diy-usermsg bg-white margin-bottom-15">
		<a href="javascript:void(0);" class="pay">
			<div class="item hd-h3">八刻币充值</div>
			<div class="money-num num text-gray hd-h3"></div>
		</a>
	</div>
	<div class="diy-usermsg bg-white">
		<a href="javascript:void(0);">
			<div class="item hd-h3">礼券</div>
		</a>
		<a href="javascript:void(0);">
			<div class="item hd-h3">帮助和反馈</div>
		</a>
		<a href="javascript:void(0);">
			<div class="item hd-h3">商务合作</div>
		</a>
	</div>

	<div class="hd-grid">&nbsp;</div>
</div>
<!-- 我的：start====================================================================== -->

<!-- 我的账单：start====================================================================== -->
<div class="page-bill hide">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">我的账单</div>
	</div>
	<div class="page-menu diybill-menu text-c bg-white">
		<a diy="diy-bangdan-recvgift" class="hd-col-xs-e3 hd-h3 current" href="javascript:void(0)">收到礼物</a>
		<a diy="diy-bangdan-sendgift" class="hd-col-xs-e3 hd-h3" href="javascript:void(0)">送出礼物</a>
		<a diy="diy-bangdan-recharge-record" class="hd-col-xs-e3 hd-h3" href="javascript:void(0)">充值记录</a>
	</div>
	<div class="diy-bill diybill-model list-item">
		<div>
			<ul id="ID_giftrecv">
			</ul>
		</div>
		<div class="hide">
			<ul id="ID_giftsend">
			</ul>
		</div>
		<div class="hide">
			<ul id="ID_bill">
			</ul>
		</div>
	</div>
</div>

<!-- 商家消息：start====================================================================== -->
<div class="page-seller-msg hide">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">商家消息</div>
	</div>

	<div class="diy-datamsg hd-h3 msg-item bg-gray">
		<ul id="ID_barMsgs">
			
		</ul>
	</div>
</div>
<!-- 商家消息：end====================================================================== -->

<!-- 系统消息：start====================================================================== -->
<div class="page-sys-msg hide">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">系统消息</div>
	</div>

	<div class="diy-datamsg hd-h3 msg-item bg-gray">
		<ul id="ID_sysMsgs">
			
		</ul>
	</div>
</div>
<!-- 系统消息：end====================================================================== -->

<!-- 个人消息：start====================================================================== -->
<div class="page-person-msg hide">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">个人消息</div>
	</div>

	<div class="diy-datamsg hd-h3 list-item">
		<ul id="ID_privMsg">
			
		</ul>
	</div>

</div>
<!-- 个人消息：end====================================================================== -->

<div class="page-allusers hide">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<input type="text"  style="background-color:#dfdfdf;border-radius:0.05rem;margin-top:0.1rem;height:0.3rem;" class="title fl-l"></input>
		<a class="icon" href="javascript:void(0);"><img src="application/views/images/search.jpg" /></a>
	</div>
	<ul class="user-list">
		
	</ul>
</div>

<div class="page-recharge hide">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);" style="width:20%;"></a>
		<div class="title fl-l" style="width:60%;">我的八客币</div>
		<a class="icon" href="javascript:void(0);" style="width:20%;text-decoration: none;color:black;">充值记录</a>
	</div>
	<div class="balance">
		<p>八客币余额</p>
		<p class="count">0</p>
	</div>
	<div class="recharge-choice">
		<ul class="choice">
			<li class="selected" data-item="60"><p>60币</p><p class="gray">￥6元</p></li>
			<li data-item="300"><p>300币</p><p class="gray">￥30元</p></li>
			<li data-item="580"><p>580币</p><p class="gray">￥58元</p></li>
			<li data-item="980"><p>980币</p><p class="gray">￥98元</p></li>
			<li data-item="1280"><p>1280币</p><p class="gray">￥128元</p></li>
			<li class="input-money"><p class='input'>输入币数</p><p id="ID_Money" class="gray money">￥0元</p></li>
		</ul>
	</div>
	<div class="pay-channels">
		<ul>
			<li class="selected">微信</li>
			<!--<li class="border-bottom">支付宝</li>-->
		</ul>
	</div>
	<div class="recharge-btn">
		立即充值
	</div>
</div>

<script type="text/javascript">
$(function(){
	//确认提示框: commonJS.confirm('消息','确认回调函数','取消回调函数');
	// commonJS.confirm('本次支付需花费1八刻币<br/>确认支付吗？');
	//普通提示框：commonJS.alert('您已成功支付！','回调函数',停留时长);
	// commonJS.alert('您已成功支付！');

	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".diymodel > div ")});	 //榜单模板切换
	commonJS.diychoose({"chooseobj":$(".diybill-menu > a"),"diyobj":$(".diybill-model > div ")});	 //我的账单模板切换
});
</script>
<script type="text/javascript">
	var g_socket;
	var g_bar = {
		bar_id:<?php echo $barinfo['bar_id']; ?>,
		desk_id:<?php echo $desk_id; ?>,
		barimg:"<?php echo $barinfo['barimg'] ?>",
		name:"<?php echo $barinfo['name'] ?>"
	};

	var g_guest = {
		user_id:<?php echo $user_id;?>,
		nickname:"<?php echo $nickname;?>",
		headimg:"<?php echo $headimgurl;?>",
		role:"<?php echo $role;?>"
	};

	var g_barrage_alert = <?php echo $barrage_alert;?>;
	var g_singer = null;

</script>
<script type="text/javascript" src="application/views/js/global.js?v=2017081903"></script>
<script type="text/javascript" src="application/views/js/gift.js?v=2017081610"></script><!-- 礼物 -->
<script type="text/javascript" src="application/views/js/chat.js?v=2017081901"></script><!-- 聊天 -->

</body>
</html>
