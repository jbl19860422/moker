<!DOCTYPE html>
<!-- saved from url=(0032)http://kl1yg.com.cn/mobile/index -->
<html>
<head>
	<base href="<?php  echo base_url();?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
	<title><?php echo $barinfo['name'];?></title>
	<meta content="app-id=518966501" name="apple-itunes-app">
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="black" name="apple-mobile-web-app-status-bar-style">
	<meta content="telephone=no" name="format-detection">
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="0">
	<link href="application/views/css/tjsc_comm.css" rel="stylesheet" type="text/css">
	<link href="application/views/css/tjsc_index.css" rel="stylesheet" type="text/css">
	<link href="application/views/css/tjsc_10.css" rel="stylesheet" type="text/css">
	<link href="application/views/css/tjsc_comm(1).css?v=3" rel="stylesheet" type="text/css">
    <link href="application/views/css/tjsc_index(1).css?v=1" rel="stylesheet" type="text/css">
	<style>
		html {
			font-size:100px;
		}
		
		.video_header {
			width:100%;
			height:auto;
		}
		
		video {
			width:100%;
			height:auto;
			display:block;
		}
		
		.video_header .bk-img {
			width:100%;position:relative;margin-left:auto;margin-right:auto;border-radius:8px;border:2px solid white;
		}
		
		.video_header .living {
			position:absolute;right:0.1rem;top:0.1rem;background-color:#CE0000;font-size:0.12rem;padding:2px;border:1px solid #CE0000;border-radius:8px;
		}
		
		.video_header .title {
			position:absolute;bottom:0.3rem;right:0rem;font-size:0.12rem;
		}
		
		.logo-area {
			position:absolute;width:1.4rem;height:0.4rem;background-color:rgba(64, 74, 88, 0.5);left:0.34rem;top:0.4rem;
		}
		
		.logo-area img {
			width:0.3rem;height:0.3rem;margin:0.04rem 0.04rem;border-radius:100%;position:absolute;
		}
		
		.logo-area .video-time {
			font-size:0.12rem;line-height:0.15rem;margin-left:0.4rem;margin-bottom:0.02rem;margin-top:4px;
		}
		
		.logo-area .view-count {
			font-size:0.1rem;line-height:0.15rem;margin-left:0.4rem;margin-top:0.02rem;margin-bottom:0.02rem;
		}
		
		.seller-header {
			width:100%;
			height:0.3rem;
			text-align:center;
		}
		
		.selller-header span {
			font-size:20px;
			margin:auto;
		}
		
		.box-shadow-1{
		  -webkit-box-shadow: 3px 3px 3px;  
		  -moz-box-shadow: 3px 3px 3px;  
		  box-shadow:2px 2px 10px #909090;
		}
		
		.video-info {
			height:0.5rem;
			width:100%;
			background-color:white;
			position:relative;
			display:-webkit-flex
		}
		
		.fl {
			float:left;
		}
		
		.goods-info {
		}
		
		.goods-info img {
			width:30px;
			height:30px;
			margin-top:10px;
			margin-left:20px;
		}
		
		.goods-info .name {
			position:absolute;
			left:60px;
			top:15px;
			font-size:15px;
			color:black;
		}
		.img-round {
			border-radius:100%;
		}
		
		.fr {
			float:right;
		}
		
		body {
			background-color:white;
		}
	</style>
</head>
<body scroll="no">
<div class="seller-header box-shadow-1" style="background-color:black;">
	<span style="font-size:20px;color:white;"><?php echo $barinfo['name'];?></span>
</div>
<div class="video_header">
	<video webkit-playsinline x-webkit-airplay="allow" 
		src="<?php echo $barinfo['live_play_hls_url'];?>" 
		controls="controls" poster="<?php echo $barinfo['live_cover'];?>	"></video>
	<span class="living">直播中</span>
</div>
<div class="video-info" style="margin-bottom:5px;">
	<div class="goods-info fl">
		<a href="javascript:void(0)">
			<img src="<?php echo $barinfo['barimg'];?>" class="img-round"></img>
			<div class="name"><?php echo $barinfo['name'];?></div>
		</a>
	</div>
	<div style="position:absolute;right:60px;top:5px;border-right:1px solid black;padding-right:20px;">
		<!-- <img src="http://o95rd8icu.bkt.clouddn.com/share.png" style="width:30px;height:30px"></img> -->
		<div style="height:30px;background:url(http://o95rd8icu.bkt.clouddn.com/share.png) no-repeat center;width:100%;background-size:80%;"></div>
		<span style="color:black">分享</span>
	</div>
	<div style="position:absolute;right:20px;top:5px;" id="ID_follow">
		<div class="follow-icon"></div>
		<!-- <img src="http://o95rd8icu.bkt.clouddn.com/heart.png" style="width:30px;height:30px"></img> -->
		<span style="color:black">收藏</span>
	</div>
</div>
<div style="background-color:lightgray;height:15px;width:100%;">
</div>
</body>
</html>