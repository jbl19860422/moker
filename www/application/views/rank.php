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
<div class="page-bangdan bg-gray">
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
			<ul id="ID_online_rank">
			</ul>
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
<script type="text/javascript" src="application/views/js/API.js?v=2017081949"></script>
<script type="text/javascript" src="application/views/js/rank.js?v=2017081949"></script> 
<script type="text/javascript">
	$(function() {
		var bar_id = $.cookie('bar_id');
		var user_id = $.cookie('user_id');
		API.query_givegift_rank(bar_id, user_id, function(data) {
			GUI.updateGiveGiftRank(data);
		});

		API.query_online_rank(user_id, function(data) {
			GUI.updateOnlineRank(data);
		});

		API.query_gotgift_rank(bar_id, user_id, function(data) {
			GUI.updateGotGiftRank(data);
		});

		API.query_gotlove_rank(bar_id, user_id, function(data) {
			GUI.updateGotLoveRank(data);
		});

		$(".goback").click(function() {
			history.back(-1);
		})
	});
</script>
</body>
</html>
