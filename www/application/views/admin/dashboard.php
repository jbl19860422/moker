<!DOCTYPE html>
<html lang="">
<head>
	<base href="<?php  echo base_url();?>"/>

	<script type="text/javascript">
		var g_bar = {};
		g_bar.bar_id = <?php echo $bar_id; ?>;
		g_bar.barname = '<?php echo $barname; ?>';
		g_bar.barimg = '<?php echo $barimg; ?>';
	</script>
	<meta charset="utf-8">
	<title>墨客 管理</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" type="text/css" href="application/views/admin/css/jquery-ui.css" />
	<style type="text/css">
		a{color:#007bc4/*#424242*/; text-decoration:none;}
		a:hover{text-decoration:underline}
		ol,ul{list-style:none}
		body{height:100%; font:12px/18px Tahoma, Helvetica, Arial, Verdana, "\5b8b\4f53", sans-serif; color:#51555C;}
		img{border:none}
		.demo{width:500px; margin:20px auto}
		.demo h4{height:32px; line-height:32px; font-size:14px}
		.demo h4 span{font-weight:500; font-size:12px}
		.demo p{line-height:28px;}
		input{width:200px; height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3}
		pre{padding:6px 0 0 0; color:#666; line-height:20px; background:#f7f7f7}

		.ui-timepicker-div .ui-widget-header { margin-bottom: 8px;}
		.ui-timepicker-div dl { text-align: left; }
		.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }
		.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }
		.ui-timepicker-div td { font-size: 90%; }
		.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }
		.ui_tpicker_hour_label,.ui_tpicker_minute_label,.ui_tpicker_second_label,.ui_tpicker_millisec_label,.ui_tpicker_time_label{padding-left:20px}
	</style>

	<link rel="stylesheet" href="application/views/admin/css/style.css?v=9" media="all" />
	<!--<link rel="stylesheet" type="text/css" href="application/views/admin/css/jquery-ui.css" />-->
	<!--<link rel="stylesheet" type="text/css" href="application/views/admin/css/jquery.datetimepicker.css"/ >-->
	<script src="http://imoke.live/plhwin/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
	<script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
	<script type="text/javascript" src="application/views/admin/js/script.js?v=7"></script>
	<!--<script type="text/javascript" src="application/views/admin/js/jquery.js?v=5"></script>
	<script type="text/javascript" src="application/views/admin/js/jquery.datetimepicker.full.min.js?v=4"></script>-->
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.js"></script>
	
	<script   src="http://code.jquery.com/ui/1.12.0/jquery-ui.js"   integrity="sha256-0YPKAwZP7Mp3ALMRVB2i8GXeEndvCq3eSl/WsAl1Ryk="   crossorigin="anonymous"></script>
	<script type="text/javascript" src="application/views/admin/js/jquery-ui-slide.min.js"></script>
	<script type="text/javascript" src="application/views/admin/js/jquery-ui-timepicker-addon.js"></script>
	<!--<script type="text/javascript" src="application/views/admin/js/jquery-ui.js"></script>
	<script type="text/javascript" src="application/views/admin/js/jquery-ui-slide.min.js"></script>
	<script type="text/javascript" src="application/views/admin/js/jquery-ui-timepicker-addon.js"></script>-->
</head>
<body>
<div class="testing">
<header class="main">
	<h1><strong>默吧</strong> 互动</h1>
	<input type="text" value="search" />
</header>
<section class="user">
	<div class="profile-img">
		<p><img src="<?php echo $headimg; ?>" alt="" height="40" width="40" /> 欢迎 <?php echo $nickname; ?></p>
	</div>
	<ul class="nav" style="position:absolute;left:210px;top:40px;list-style:none;">
		<li class="nav-bar nav-sel" nav-id="home"><a href="javascript:void(0);">首    页</a></li>
		<li class="nav-bar" nav-id="member"><a href="javascript:void(0);">人    员</a></li>
		<li class="nav-bar" nav-id="service"><a href="javascript:void(0);">业    务</a></li>
		<li class="nav-bar" nav-id="order"><a href="javascript:void(0);">订    单</a></li>
		<li class="nav-bar" nav-id="money"><a href="javascript:void(0);">资    金</a></li>
		<li class="nav-bar" nav-id="data"><a href="javascript:void(0);">数    据</a></li>
	</ul>

	<div class="buttons">
		<button class="ico-font">&#9206;</button>
		<span style="color:#5daced;font-size:20px;">
			<?php echo $barname; ?>
		</span> 
		<span class="button dropdown">
			<a href="#">消息 <span class="pip">6</span></a>
		</span> 
		<span class="button">管理员</span>
		<span class="button">设置</span>
		<span class="button blue"><a href="http://dream.waimaipu.cn/index.php/admin/logout">退出</a></span>
	</div>
</section>
<!-- </div> -->
<nav>
	<ul id="ID_leftNav_home" class="leftNav">
	</ul>
	<ul id="ID_leftNav_member" class="hide leftNav">
		<li class="subnav-sel subnav" subnav-id="singer"><a href="javascript:void(0)"><span class="icon">歌手</span></a></li>
		<li class="subnav" subnav-id="server"><a href="javascript:void(0)"><span class="icon">营销员</span></a></li>
		<li class="subnav" subnav-id="guest"><a href="javascript:void(0)"><span class="icon">用户</span></a></li>
		<li class="subnav" subnav-id="desk"><a href="javascript:void(0)"><span class="icon">餐桌</span></a></li>
	</ul>
	<ul id="ID_leftNav_service" class="hide leftNav">
		<li class="subnav subnav-sel" subnav-id="singer-switch"><a href="javascript:void(0)"><span class="icon">歌手切换</span></a></li>
		<li class="subnav" subnav-id="goods"><a href="javascript:void(0)"><span class="icon">商品</span></a></li>
		<li class="subnav" subnav-id="notice"><a href="javascript:void(0)"><span class="icon">公告</span></a></li>
	</ul>
	<ul id="ID_leftNav_order" class="hide leftNav">
		<li class="subnav-sel" subnav-id="order-info"><a href="javascript:void(0)"><span class="icon">订单概况</span></a></li>
		<li subnav-id="all-order"><a href="javascript:void(0)"><span class="icon">所有订单</span></a></li>
	</ul>
	<ul id="ID_leftNav_money" class="hide leftNav">
		<li class="subnav-sel" subnav-id="my-income"><a href="javascript:void(0)"><span class="icon">我的收入</span></a></li>
		<li subnav-id="transaction-record"><a href="javascript:void(0)"><span class="icon">交易记录</span></a></li>
		<li subnav-id="statement-query"><a href="javascript:void(0)"><span class="icon">结算单查询</span></a></li>
		<li subnav-id="percentage"><a href="javascript:void(0)"><span class="icon">提成</span></a></li>
	</ul>
</nav>

<section class="content div-content" id="ID_content_home">
	<div class="widget-container">
		<section class="widget small">
			<header>
				<span class="icon">&#59168;</span>
				<hgroup>
					<h1>互动趋势</h1>
				</hgroup>
			</header>
			<div class="content">
				<section class="stats-wrapper">
					<div class="stats">
						<p><span>64</span></p>
						<p>交易金额</p>
					</div>
					<div class="stats">
						<p><span id="ID_onlineCount">362</span></p>
						<p>在线人数</p>
					</div>
				</section>
				<section class="stats-wrapper">
					<div class="stats">
						<p><span>7</span></p>
						<p>评论</p>
					</div>
					<div class="stats">
						<p><span>927</span></p>
						<p>礼物</p>
					</div>
				</section>
				<section class="stats-wrapper">
					<div class="stats">
						<p><span>7</span></p>
						<p>爱心</p>
					</div>
					<div class="stats">
						<p><span>927</span></p>
						<p>弹幕</p>
					</div>
				</section>
			</div>
		</section>
		
		<section class="widget 	small">
			<header>
				<span class="icon">&#128319;</span>
				<hgroup>
					<h1>待办事项</h1>
				</hgroup>
				<p style="float:right;padding-top:15px;">
					<span>
						<a href="#" style="font-size:15px;">更多>></a>
					</span>
				</p>
			</header>
			<table id="myTable" border="0" width="100">
				<thead>
					<tr>
						<th class="header">事项内容</th>
						<th class="header">时间</th>
					</tr>
				</thead>
					<tbody>
						<tr class="odd">
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>01/3/2013</td>
						</tr>
						<tr>
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>01/3/2013</td>
						</tr>
						<tr class="odd">
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>02/3/2013</td>
						</tr>
						<tr class="odd">
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>02/3/2013</td>
						</tr>
						<tr class="odd">
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>02/3/2013</td>
						</tr>
						<tr class="odd">
							<td>小李-第三桌 赠送娜娜-第四桌 酒水套餐2份</td>
							<td>02/3/2013</td>
						</tr>
					</tbody>
				</table>
		</section>
	</div>
	
	<div class="widget-container">
		<section class="widget small">
			<header> 
				<span class="icon">&#128318;</span>
				<hgroup>
					<h1>礼券兑换</h1>
				</hgroup>
			</header>
			<div class="content">
				<div class="field-wrap">
					<input type="text" value="" placeholder="请输入兑换码">
				</div>
				<button type="submit" class="green" style="float:right">验证</button>
			</div>
		</section>
		
		<section class="widget small">
			<header> 
				<span class="icon">&#128363;</span>
				<hgroup>
					<h1>歌手切换</h1>
				</hgroup>
			</header>
			<table id="myTable" border="0" width="100">
				<thead>
					<tr>
						<th class="header">歌手名称</th>
						<th class="header">操作</th>
					</tr>
				</thead>
					<tbody id="ID_singerList">
						<tr class="odd">
							<td>beyond</td>
							<td><button type="submit" class="green" style="width:80%">切换</button></td>
						</tr>
						<tr class="odd">
							<td>刘德华</td>
							<td><button type="submit" class="green" style="width:80%">切换</button></td>
						</tr>
					</tbody>
				</table>
		</section>
	</div>

	<div class="widget-container">
		<section class="widget small">
			<header> 
				<span class="icon">&#128318;</span>
				<hgroup>
					<h1>商家活动</h1>
				</hgroup>
			</header>
			<div class="content">
				<div class="field-wrap">
					<input type="text" value="" placeholder="活动名" id="ID_activityName"></input>
				</div>
				<div class="green">	
					<p>开始时间：<input type="text" value="" id="ID_acTimeStart" style="width:70%" placeholder="开始时间"></p>
					<p>结束时间：<input type="text" value="" id="ID_acTimeEnd" style="width:70%" placeholder="结束时间"></p>
				</div>

				<div class="orange">	
					<p>手机端地址：<input type="text" value="" style="width:70%" id="ID_phoneurl" placeholder="手机端地址"></p>
					<p>&nbsp;&nbsp;PC端地址：<input type="text" value="" id="ID_pcurl" style="width:70%" placeholder="电视端地址"></p>
				</div>
				<button type="submit" class="green" style="float:right" id="ID_updateActivity">提交修改</button>
			</div>
		</section>

		<section class="widget small">
			<header> 
				<span class="icon">&#128318;</span>
				<hgroup>
					<h1>商家直播</h1>
				</hgroup>
			</header>
			<div class="content">
				<div class="field-wrap">
					<input type="text" value="" placeholder="请填写直播名" id="ID_liveName"></input>
				</div>

				<div class="orange">	
					<p>推流地址:<input type="text" value="" style="width:70%" id="ID_pushUrl" placeholder="推流地址" readonly="true"></p>
					<p>直播状态:<input type="text" value="" style="width:70%" id="ID_liveStatus" placeholder="未开始" readonly="true"></p>
				</div>
				<button type="submit" class="red" style="float:right;margin-left:10px;" id="ID_stopLive">结束直播</button>
				<button type="submit" class="green" style="float:right;margin-left:10px;" id="ID_startLive">开始直播</button>
				<button type="submit" class="green" style="float:right;margin-left:10px;" id="ID_createLive">创建新直播</button>
			</div>
		</section>
	</div>
	
	<div class="widget-container">
		<div style="height:80px">
		Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a> - More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a>
		</div>
	</div>
</section>

<section class="content div-content hide" id="ID_content_member">
	<div id="ID_content_singer" class="member-content">
		<ul class="widget-container" style="border-bottom:1px solid #208ed3;margin-bottom:10px;">
			<li class="nav-subbar nav-subbar-sel" id="verifiedSinger"><a href="javascript:void(0)">已审核</a></span>
			<li class="nav-subbar" id="unverifiedSinger"><a href="javascript:void(0)">未审核</a></span>
		</ul>
		
		<div class="content">
			<div class="field-wrap">
				微信昵称：
				<input type="text" style="width:20%" placeholder="nick">
				姓名：
				<input type="text" style="width:20%" placeholder="name">
				手机号：
				<input type="text" style="width:20%" placeholder="phone">

				<button type="submit" style="margin-left:20px" class="green">筛选</button> 
				<button type="submit" class="none">导出</button>
			</div>

			<table id="verifiedSingerTable" class="norTable hide" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">用户id</th>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">姓名</th>
						<th class="header" style="background-image:none">手机号</th>
						<th class="header" style="background-image:none">状态</th>
						<th class="header" style="background-image:none">时间</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
					<tbody id="verifiedSingerTbody">
					</tbody>
			</table>

			<table id="unverifiedSingerTable" class="norTable hide" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">用户id</th>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">姓名</th>
						<th class="header" style="background-image:none">手机号</th>
						<th class="header" style="background-image:none">状态</th>
						<th class="header" style="background-image:none">时间</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="unverifiedSingerTbody">
				</tbody>
			</table>
		</div>
	</div>

	<div id="ID_content_server" class="member-content hide">
		<ul class="widget-container" style="border-bottom:1px solid #208ed3;margin-bottom:10px;">
			<li class="nav-subbar nav-subbar-sel" id="verifiedServer"><a href="javascript:void(0)">已审核</a></span>
			<li class="nav-subbar" id="unverifiedServer"><a href="javascript:void(0)">未审核</a></span>
		</ul>
		
		<div class="content">
			<div class="field-wrap">
				微信昵称：
				<input type="text" style="width:20%" placeholder="nick">
				姓名：
				<input type="text" style="width:20%" placeholder="name">
				手机号：
				<input type="text" style="width:20%" placeholder="phone">

				<button type="submit" style="margin-left:20px" class="green">筛选</button> 
				<button type="submit" class="none">导出</button>
			</div>

			<table id="verifiedServerTable" class="norTable hide" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">用户id</th>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">姓名</th>
						<th class="header" style="background-image:none">手机号</th>
						<th class="header" style="background-image:none">状态</th>
						<th class="header" style="background-image:none">时间</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
					<tbody id="verifiedServerTbody">
					</tbody>
			</table>

			<table id="unverifiedServerTable" class="norTable hide" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">用户id</th>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">姓名</th>
						<th class="header" style="background-image:none">手机号</th>
						<th class="header" style="background-image:none">状态</th>
						<th class="header" style="background-image:none">时间</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="unverifiedServerTbody">
				</tbody>
			</table>
		</div>
	</div>

	<div id="ID_content_guest" class="member-content hide">
		<ul class="widget-container" style="border-bottom:1px solid #208ed3;margin-bottom:10px;">
			<li class="nav-subbar nav-subbar-sel" id="onlineGuest"><a href="javascript:void(0)">在线用户</a></span>
			<li class="nav-subbar" id="offlineGuest"><a href="javascript:void(0)">所有用户</a></span>
		</ul>
		
		<div class="content">
			<div class="field-wrap">
				微信昵称：
				<input type="text" style="width:20%" placeholder="nick">
				姓名：
				<input type="text" style="width:20%" placeholder="name">
				手机号：
				<input type="text" style="width:20%" placeholder="phone">

				<button type="submit" style="margin-left:20px" class="green">筛选</button> 
				<button type="submit" class="none">导出</button>
			</div>

			<table id="onlineGuestTable" class="norTable" border="0" width="100">
				<thead>
					<tr>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">所在餐桌</th>
						<th class="header" style="background-image:none">上线时间</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="onlineGuestTbody">
				</tbody>
			</table>

			<table id="offlineGuestTable" class="norTable hide" border="0" width="100">
				<thead>
					<tr>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">微信昵称</th>
						<th class="header" style="background-image:none">性别</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="offlineGuestTbody">
				</tbody>
			</table>
		</div>
	</div>

	<div id="ID_content_desk" class="member-content hide">
		<div class="content">
			<div class="field-wrap hide">
				餐桌ID：
				<input type="text" style="width:20%" placeholder="id">
				餐桌名称：
				<input type="text" style="width:20%" placeholder="name">

				<button type="submit" style="margin-left:20px" class="green">筛选</button> 
				<button type="submit" class="none">导出</button>
			</div>

			<table class="norTable" style="margin-bottom:40px" border="0" width="100">
				<thead>
					<tr id="deskTable">
						<th class="header" style="background-image:none;">餐桌ID</th>
						<th class="header" style="background-image:none">餐桌名称</th>
						<th class="header avatar
						" style="background-image:none">餐桌二维码</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
					<tbody id="deskTbody">
					</tbody>
			</table>

			<table id="deskUserTable" class="norTable" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">餐桌id</th>
						<th class="avatar header" style="background-image:none">用户头像</th>
						<th class="header" style="background-image:none">餐桌名称</th>
						<th class="header avatar
						" style="background-image:none">客人</th>
						<th class="header" style="background-image:none">二维码图片地址</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="deskUserTbody">
				</tbody>
			</table>
		</div>
	</div>
</section>


<section class="content div-content hide" id="ID_content_service" style="min-height:40px;">
	<div class="widget-container" style="min-height:40px;">
		<section class="widget small" style="min-height:40px;display:block">
			<header> 
				<span class="icon">&#128318;</span>
				<hgroup>
					<h1>当前歌手</h1>
				</hgroup>
			</header>
			<div class="content" style="min-height:20px;">
				<div class="field-wrap" style="font-size:15px" id="ID_curSinger">
					<img src="" id="ID_curSingerImg" style="width:40px;height:40px;border-radius:100%;"></img>
					<label id="ID_curSingerName" style="margin-right:100px;font-size:15px;"></label>
					切换时间：
					<label id="ID_curSingerLogintime" style="font-size:15px;"></label>
				</div>
			</div>
		</section>
	</div>
	<div class="widget-container" style="min-height:40px;">
		<section class="widget small" style="min-height:40px;">
			<header> 
				<span class="icon">&#128318;</span>
				<hgroup>
					<h1>所有歌手</h1>
				</hgroup>
			</header>
			<table id="ID_singerTable" class="norTable" border="0" width="100">
				<thead>
					<tr>
						<th class="header" style="background-image:none;">歌手名称</th>
						<th class="avatar header" style="background-image:none">歌手头像</th>
						<th class="header" style="background-image:none">操作</th>
					</tr>
				</thead>
				<tbody id="ID_singerTBody">
				</tbody>
			</table>
		</section>
	</div>
</section>


<script src="application/views/admin/js/jquery.wysiwyg.js"></script>
<!-- <script src="application/views/admin/js/custom.js"></script> -->
<script src="application/views/admin/js/jquery.checkbox.min.js"></script>
<script src="application/views/admin/js/flot.js"></script>
<script src="application/views/admin/js/flot.resize.js"></script>
<script src="application/views/admin/js/flot-time.js"></script>
<script src="application/views/admin/js/flot-pie.js"></script>
<script src="application/views/admin/js/flot-graphs.js"></script>
<script src="application/views/admin/js/cycle.js"></script>
<script src="application/views/admin/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
// Feature slider for graphs
$('.cycle').cycle({
	fx: "scrollHorz",
	timeout: 0,
    slideResize: 0,
    prev:    '.left-btn', 
    next:    '.right-btn'
});
</script>
</body>
</html>