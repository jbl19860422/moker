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
<div class="page-sys-msg">
	<div class="page-header text-c">
		<a class="goback fl-l" href="javascript:void(0);"></a>
		<div class="title fl-l">系统消息</div>
	</div>

	<div class="diy-datamsg hd-h3 msg-item bg-gray">
		<ul id="ID_sysMsgs">
			
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
	var g_sysMessage = [];

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
		API.query_sys_msg($.cookie('bar_id'), function(data) {
			g_sysMessage = [];
			for(var p in data) {
				g_sysMessage.push({
					time:p,
					data:data[p]
				});
			}
			if(g_sysMessage.length > 0) {
				$(".page-center .sys-msg .introl").html(JSON.parse(g_sysMessage[g_sysMessage.length-1].data).title);
			}

			var i = 0;
			while(g_sysMessage.length > 0 && i < 3) {
				i++;
				var msg = g_sysMessage.pop();
				var timeStr = Time2Str(msg.time);
				var msgContent = JSON.parse(msg.data);
				$("#ID_sysMsgs").append('<li>'+ 
											'<div class="time hd-h4 text-gray">'+timeStr+'</div>'+
											'<div class="data-item bg-white radius-5">'+
												'<div class="title hd-h3 text-black text-ellipsis">'+msgContent.title+'</div>'+
												'<img src="'+'http://o95rd8icu.bkt.clouddn.com/奥运.jpg'+'" />'+
												'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
											'</div>'+
										'</li>');
			}
		});
	});

	function LoadSysMsg() {
		if(g_sysMessage.length > 0) {//load new msg
			var maxCount = g_sysMessage.length < 3? g_sysMessage.length:3;
			for(var i = 0; i < maxCount; i++) {
				var msg = g_sysMessage.pop();
				var timeStr = Time2Str(msg.time);
				var msgContent = JSON.parse(msg.data);
				$("#ID_sysMsgs").append('<li>'+ 
											'<div class="time hd-h4 text-gray">'+timeStr+'</div>'+
											'<div class="data-item bg-white radius-5">'+
												'<div class="title hd-h3 text-black text-ellipsis">'+msgContent.title+'</div>'+
												'<img src="'+'http://o95rd8icu.bkt.clouddn.com/奥运.jpg'+'" />'+
												'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
											'</div>'+
										'</li>');
			}
		}
	}

	$(document).ready(function() {
	    $(window).scroll(function() {
	        if ($(document).scrollTop() <= 0) {//到达顶部
	        }

	        if ($(document).scrollTop() >= $(document).height() - $(window).height()) {//到达底部
	        	LoadSysMsg();
	        }
	    });
	});

	$(".goback").click(function() {
		history.back(-1);
	});
</script>
</body>
</html>
