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
<div class="page-bill">
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


<script type="text/javascript">
$(function(){
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".diymodel > div ")});	 //榜单模板切换
	commonJS.diychoose({"chooseobj":$(".diybill-menu > a"),"diyobj":$(".diybill-model > div ")});	 //我的账单模板切换
	commonJS.diychoose({"chooseobj":$(".diymenu > a"),"diyobj":$(".alluser-list > ul ")});	 //我的账单模板切换
});
</script>
<script type="text/javascript">
	
</script>
<script type="text/javascript" src="application/views/js/API.js?v=2017081951"></script>
<script type="text/javascript" src="application/views/js/payrecord.js?v=2017081950"></script>
<script type="text/javascript">
/*common function for alert,loading add by yangchao ---start*/
var FN = {
	params: {alertTime: null},
	alert: function(info,time){
		info = info?info:'this alert info!';
		time = time?time:2000;
		var toast = $('.toast');
		if(toast.length){
			clearTimeout(FN.params.alertTime);
			toast.text(info);
		} else {
			$('body').append('<div class="toast">'+info+'</div>');
		}
		FN.params.alertTime = setTimeout(function(){
			$('.toast').remove();
		},time);
	},
	backdrop: {
		show: function(html){
			html = html?html:'';
			var backdrop = $('.backdrop');
			if(backdrop.length){
				backdrop.html(html);
			} else {
				$('body').append('<div class="backdrop">'+html+'</div>');
			}
		},
		hide: function(){
			$('.backdrop').remove();
		}
	},
	loading: {
		show: function(info){
			info = info?info:'加载中，请稍候！';
			var loading = $('.loading');
			if(loading.length){
				loading.text(info);
			} else {
				FN.backdrop.show('<div class="loading">'+info+'</div>');
			}
		},
		hide: function(){
			FN.backdrop.hide();
		}
	},
	backdrop_clickhide: {
		show: function(html){
			html = html?html:'';
			var backdrop = $('.backdrop');
			if(backdrop.length){
				backdrop.html(html);
			} else {
				$('body').append('<div class="backdrop" onclick="(function(){FN.div_clickhide.hide();})();">'+html+'</div>');
			}
		},
		hide: function(){
			$('.backdrop').remove();
		}
	},
	div_clickhide: {
		show: function(info){
			info = info?info:'加载中，请稍候！';
			var loading = $('.div-clickhide');
			if(loading.length){
				loading.text(info);
			} else {
				FN.backdrop_clickhide.show('<div class="div-clickhide">'+info+'</div>');
			}
		},
		hide: function(){
			FN.backdrop_clickhide.hide();
		}
	}
};


$(function() {
	$('.goback').click(function() {
		history.back(-1);
	});

	API.query_giftrecv($.cookie('user_id'), -1, -1, function(data) {
		GUI.updateGiftRecv(data);
	});

	API.query_giftsend($.cookie('user_id'), -1, -1, function(data) {
		GUI.updateGiftSend(data);
	});

	API.query_bill($.cookie('user_id'), function(data) {
		GUI.updatePayBill(data);
	});
});
</script>
</body>
</html>
