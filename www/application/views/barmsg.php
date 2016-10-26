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
<div class="page-seller-msg">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">商家消息</div>
	</div>
	<div class="diy-datamsg hd-h3 msg-item bg-gray">
		<ul id="ID_barMsgs">
		</ul>
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
<script type="text/javascript" src="application/views/js/API.js?v=2017081950"></script>
<script type="text/javascript" src="application/views/js/barmsg.js?v=2017081952"></script>
<script type="text/javascript">
	var g_barMessage = [];
	var g_barMsgMaxShowCount = 3;

	function pad(num, n) {
	    var len = num.toString().length;
	    while(len < n) {
	        num = '0' + num;
	        len++;
	    }
	    return num;
	}

	function Time2Str(timestamp) {
		var dateObj = new Date(timestamp*1000);
	    var timeStr = dateObj.getFullYear() + '-' + pad((dateObj.getMonth() +1 ),2) + '-' + 
	    					pad(dateObj.getDate(),2)+ ' ' + pad(dateObj.getHours(),2) + ':' + 
	    					pad(dateObj.getMinutes(),2) + ':' + pad(dateObj.getSeconds(),2);
	    return timeStr;
	}


	$(function() {
		API.query_bar_msg($.cookie('bar_id'), function(data) {
			g_barMessage = [];
			for(var p in data) {
				g_barMessage.push({
					time:p,
					data:data[p]
				});
			}

			var i = 0;
			while(g_barMessage.length > 0 && i < g_barMsgMaxShowCount) {
				i++;
				var msg = g_barMessage.pop();
				var timeStr = Time2Str(msg.time);
				var msgContent = JSON.parse(msg.data);
				$("#ID_barMsgs").append('<li>'+ 
											'<div class="time hd-h4 text-gray">'+timeStr+'</div>'+
											'<div class="data-item bg-white radius-5">'+
												'<div class="title hd-h3 text-black text-ellipsis">'+msgContent.title+'</div>'+
												'<img src="'+msgContent.pic+'" />'+
												'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
											'</div>'+
										'</li>');
			}
		});
	});

	function LoadBarMsg() {
		if(g_barMessage.length > 0) {//load new msg
			var maxCount = g_barMessage.length < 3? g_barMessage.length:3;
			for(var i = 0; i < maxCount; i++) {
				var msg = g_barMessage.pop();
				var timeStr = Time2Str(parseInt(msg.time));
				var msgContent = JSON.parse(msg.data);
				$("#ID_barMsgs").append('<li>'+ 
											'<div class="time hd-h4 text-gray">'+timeStr+'</div>'+
											'<div class="data-item bg-white radius-5">'+
												'<div class="title hd-h3 text-black text-ellipsis">'+msgContent.title+'</div>'+
												'<img src="'+msgContent.pic+'" />'+
												'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
											'</div>'+
										'</li>');
			}
			g_barMsgMaxShowCount += 3;
		}
	}


	$(document).ready(function() {
	    $(window).scroll(function() {
	        if ($(document).scrollTop() <= 0) {//到达顶部
	        }

	        if ($(document).scrollTop() >= $(document).height() - $(window).height()) {//到达底部
	        	LoadBarMsg();
	        }
	    });
	});

	$(".goback").click(function() {
		history.back(-1);
	});
</script>
</body>
</html>
