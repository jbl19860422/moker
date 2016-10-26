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
<!---- 充值-->
<div class="page-recharge">
	<div class="page-header text-c bg-white">
		<a class="goback fl-l" href="javascript:void(0);" style="width:20%;"></a>
		<div class="title fl-l" style="width:60%;">我的八客币</div>
		<a class="icon" href="javascript:void(0);" style="width:20%;text-decoration: none;color:black;">充值记录</a>
	</div>
	<div class="balance">
		<p>hi币余额</p>
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
	<div style="position:fixed;bottom:0px;width:100%;text-align:center;font-size:20px;height:40px;color:white;background-color: rgba(0,0,0,.5);" id="ID_followBtn"><img src="http://o95rd8icu.bkt.clouddn.com/qrcode.jpg" style="width:30px;height:30px;margin-right:10px;padding-top:5px;"></img><p style="display:inline;position:relative;top:-7px;">关注陌客</p></div>
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
<script type="text/javascript" src="application/views/js/API.js?v=2017081950"></script>
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

function choose_paycount() {
	$(this).addClass('selected').siblings().removeClass('selected');
	if($(this).hasClass('input-money')) {//处理输入逻辑
		$('.recharge-choice .input-money').html('<input type="text" maxlength="5" id="ID_inputMoney" style="text-align:center;margin-top:0.05rem;height:70%;border:1px solid #262626;;width:80%;color:white;background-color:#262626"></input><p class="gray money">￥0元</p>');
		$('.recharge-choice .input-money input').focus();
		$('.recharge-choice .input-money input').keyup(function(event) {
			if($(this).val() == "") {
				$(".input-money .money").html("￥"+0+"元");
			} else {
				var count = parseInt($(this).val());
				var payCount = count/10;
				$(".input-money .money").html("￥"+payCount+"元");
			}
		});
	} else {
		$('.recharge-choice .input-money').html('<p class="input">输入币数</p><p class="gray">￥0元</p>');
	}
}

var jsApiParameters;
var coin_count;

function ifUndef(data) {
	return data == null || data == undefined || data == "";
}

function pay() {
	order_id = null;
	if($(".choice .selected").hasClass('input-money')) {
		coin_count = $('.recharge-choice .input-money input').val();
		$('.recharge-choice .input-money input').blur();
	} else {
		coin_count = $(".choice .selected").attr("data-item");
	}

	if(ifUndef($.cookie('bar_id')) || ifUndef($.cookie('desk_id')) || ifUndef($.cookie('user_id'))) {
		FN.alert('参数错误，请重新打开页面!');
		return;
	}
	commonJS.cover(0.6);
	/* 添加支付前的加载提示*/
	FN.loading.show('正在火速启动微信支付');
	$.post(
			"http://dream.waimaipu.cn/index.php/user/pay",
			{
				bar_id:$.cookie('bar_id'),
				desk_id:$.cookie('desk_id'),
				coin_count:coin_count
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					/* 删除支付前的加载提示*/
					FN.loading.hide();
					FN.alert('呀，支付出错啦！');
					return;
				}
				jsApiParameters = json.jsApiParameters;
				order_id = json.order_id;
				callpay();
			},
			"json"
	);
}

function jsApiCall()
{
	WeixinJSBridge.invoke(
		'getBrandWCPayRequest',
		JSON.parse(jsApiParameters),
		function(res) {
			/* 删除支付前的加载提示*/
			FN.loading.hide();
			if(res.err_msg == "get_brand_wcpay_request:ok")  {
				FN.alert('支付成功！');
				API.query_money($.cookie('user_id'), function(money) {
					$(".balance .count").html(money);
				});
			} else {
				FN.alert('呀，支付出错啦！');
			}
			$(".cover").remove();
			
		}
	);
}

function callpay() {
	if (typeof WeixinJSBridge == "undefined"){
		if( document.addEventListener ){
			document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		}else if (document.attachEvent){
			document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		}
	}else{
		jsApiCall();
	}
}

function pad(num, n) {
    var len = num.toString().length;
    while(len < n) {
        num = '0' + num;
        len++;
    }
    return num;
}

$(function() {
	API.query_money($.cookie('user_id'), function(money) {
		$(".balance .count").html(money);
	});

	$('.goback').click(function() {
		history.back(-1);
	});

	$("#ID_followBtn").click(function() {
		FN.div_clickhide.show('<div style="font-size:12px;text-align:center">长按识别二维码</div><img src="http://o95rd8icu.bkt.clouddn.com/qrcode.jpg" style="width:140px;height:140px;margin-left:10px;margin-top:0px;"></img>');
	});

	$(".choice li").click(choose_paycount);

	$(".page-recharge .page-header .icon").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/payreordpage";
	});

	$(".recharge-btn").click(pay);
});
</script>
</body>
</html>
