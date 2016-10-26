var g_curPage = ".page-home";
var g_pageStack = [];
var g_barMessage = [];
var g_barMsgMaxShowCount = 0;
var g_sysMessage = [];
var g_chatter_user = {};
var g_at_msg = [];
var g_priv_msg = [];
var g_unviewed_msg_count = 0;
var g_onlineUsers = [];
var g_sendgift_user = null;
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
	}
};
/*common function for alert,loading add by yangchao ---end*/
g_pageStack.push(g_curPage);
$(function() {
	//初始化websocket
	initWebSocket();
	//初始化点赞点击事件
	$(".diy-zan-btn").click(add_zan);
	
	$("#ID_onlineCount").click(switchToOnlinePage);
	
	$(".buy-coin").click(switchToPayPage);

	$(".page-center .pay").click(switchToPayPage);

	$(".choice li").click(choose_paycount);

	$(".recharge-btn").click(pay);

	$(".page-recharge .page-header .icon").click(switchToPayBill);

	$(".diy-img-trophy").click(switchToRankPage);

	$(".diy-img-user").click(switchToUserInfoPage);

	$(".goback").click(goback);

	$(".newest-bar-msg").click(switchToBarMsgPage);

	$(".page-center .sys-msg").click(switchToSysMsgPage);

	$(".diy-user-detail .zan").click(function() {
		addZan($(".diy-user-detail").attr("user-id"), $(".diy-user-detail .zan em"));
	});

	$(".diy-user-detail .ta").click(function() {
		$(".diy-user-detail").addClass("hide");
		show_chat();
	});

	$(".diy-user-detail .gift").click(function() {
		$(".diy-user-detail ").addClass("hide");
		show_gift(g_chatter_user);
	});

	$(".diy-user-detail .priv").click(function() {
		$(".diy-user-detail").addClass("hide");
		show_priv_chat();
	});

	$(".diy-user-detail-all .zan").click(function() {
		addZan($(".diy-user-detail-all").attr("user-id"), $(".diy-user-detail-all .zan em"));
	});

	$(".diy-user-detail-all .ta").click(function() {
		$(".diy-user-detail-all").addClass("hide");
		show_chat();
	});

	$(".diy-user-detail-all .gift").click(function() {
		$(".diy-user-detail-all").addClass("hide");
		show_gift(g_chatter_user);
	});

	$(".diy-user-detail-all .priv").click(function() {
		$(".diy-user-detail-all").addClass("hide");
		show_priv_chat();
	});

	$(".diy-usermsg .priv-msg").click(switchToPrivMsgPage);

	$(".redpacket-content button").click(sendRedPacket);
});

setInterval(function() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/add_user_online_time",
			{
				user_id:g_guest.user_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
			},
			"json"
	);
}, 10000);

function query_bill() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_bill",
			{
				user_id:g_guest.user_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$("#ID_bill").html("");
				json.data.sort(function(a,b) {
					return b.timestamp - a.timestamp;
				});

				for(var i = 0;i < json.data.length; i++) {
					var order_info = json.data[i]['order_info'];
					var coin_info = order_info.split("&")[1];
					var coin_count = coin_info.split("=")[1];
					var timeStr = Time2Str(json.data[i].timestamp);
					if(json.data[i]['order_status'] == "1") {
						$("#ID_bill").append('<li>'+
												'<div class="title hd-h3">充值'+coin_count+'个八刻币</div>'+
												'<div class="introl text-gray">'+timeStr+'</div>'+
												'<div class="status hd-h3">成功</div>'+
											'</li>');
					}
				}
			},
			"json"
	);
}

function query_giftsend() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_gift_send", 
			{
				user_id:g_guest.user_id,
				time_start:-1,
				time_end:-1
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$("#ID_giftsend").html("");
				json.data.sort(function(a,b) {
					return b.timestamp - a.timestamp;
				});

				for(var i = 0;i < json.data.length; i++) {
					var present_info = json.data[i]['present_info'];
					var infos = splitKeyValue(present_info, '&', '=');
					var timeStr = Time2Str(json.data[i].timestamp);
					if(infos['type'] == "gift") {
						$("#ID_giftsend").append('<li> \
											<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+g_guest.headimg+'" /></div> \
											<div class="title hd-h3">送给<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
											<div class="title hd-h3">'+infos['item_count']+infos['item_unit']+infos['item_name']+'</em></div> \
											<div class="introl text-gray">'+timeStr+'</div> \
											<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["target_userimg"]+'" /></div> \
										</li>');
					} else if(infos['type'] == "redpacket") {
						$("#ID_giftsend").append('<li> \
											<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+g_guest.headimg+'" /></div> \
											<div class="title hd-h3">送给<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
											<div class="title hd-h3">'+'一个'+infos['bakebi_count']+'币的红包'+'</em></div> \
											<div class="introl text-gray">'+timeStr+'</div> \
											<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["target_userimg"]+'" /></div> \
										</li>');
					}
				}
			},
			"json"
	);
}

function query_giftrecv() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_gift_recv", 
			{
				user_id:g_guest.user_id,
				time_start:-1,
				time_end:-1
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$("#ID_giftrecv").html("");
				json.data.sort(function(a,b) {
					return b.timestamp - a.timestamp;
				});

				for(var i = 0;i < json.data.length; i++) {
					//item_id=2&item_count=1&item_name=红包$item_img=http://dream.waimaipu.cn/img/1.jpg
					var present_info = json.data[i]['present_info'];
					var infos = splitKeyValue(present_info, '&', '=');
					var timeStr = Time2Str(json.data[i].timestamp);
					if(infos['type'] == "gift") {
						$("#ID_giftrecv").append('<li> \
												<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+g_guest.headimg+'" /></div> \
												<div class="title hd-h3">收到<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
												<div class="title hd-h3">'+infos['item_count']+infos['item_unit']+infos['item_name']+'</em></div> \
												<div class="introl text-gray">'+timeStr+'</div> \
												<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["sender_headimg"]+'" /></div> \
											</li>');
					} else if(infos['type'] == 'redpacket') {
						$("#ID_giftrecv").append('<li> \
												<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+g_guest.headimg+'" /></div> \
												<div class="title hd-h3">收到<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
												<div class="title hd-h3">一个'+infos['bakebi_count']+'币的红包</em></div> \
												<div class="introl text-gray">'+timeStr+'</div> \
												<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["sender_headimg"]+'" /></div> \
											</li>');
					}
				}
			},
			"json"
	);
}

function query_givegift_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_givegift_rank", 
			{
				user_id:g_guest.user_id,
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				//http://o95rd8icu.bkt.clouddn.com/b3.png
				$("#ID_givegift_rank").html("");
				for(var i = 0; i < json.data.length; i++) {
					var user = json.data[i];
					var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
					if(user["role"].indexOf("a") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
					} else if(user["role"].indexOf("s") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
					}
					var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
					if(i <= 2) {
						sort_img += (i+1)+".png";
					}

					var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
					if(user.sex == 2) {
						sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
					}

					if(i <= 2) {
						$("#ID_givegift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
													<span class="sort text-c"><img src="'+sort_img+'" /></span> \
													<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
													<span class="nickname">'+user["nick"]+'</span> \
													<span style="float:right;margin-right:0.4rem">'+user["givemoney"]+'币</span> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
													</a></li>');
					} else {
						$("#ID_givegift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
													<span class="sort text-c"><img src="'+(i+1)+'" /></span> \
													<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
													<span class="nickname">'+user["nick"]+'</span> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
													<span style="float:right;margin-right:0.4rem">'+user["givemoney"]+'币</span> \
													</a></li>');
					}
				}
				
			},
			"json"
	);
}

function query_online_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_user_online_rank", 
			{
				user_id:g_guest.user_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$("#ID_givelove_rank").html("");
				for(var i = 0; i < json.data.length; i++) {
					var user = json.data[i];
					var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
					if(user["role"].indexOf("a") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
					} else if(user["role"].indexOf("s") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
					}

					var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
					if(i <= 2) {
						sort_img += (i+1)+".png";
					}

					var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
					if(user.sex == 2) {
						sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
					}

					var timeSec = parseInt(user["time"]);
					var timeHour = Math.floor(timeSec/3600);
					var timeMin = Math.floor((timeSec%3600)/60);
					var timeSec = timeSec%60;
					if(i <= 2) {
						$("#ID_givelove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
													<span class="sort text-c"><img src="'+sort_img+'" /></span> \
													<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
													<span class="nickname">'+user["nick"]+'</span> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
													<span style="float:right;margin-right:0.4rem">'+timeHour+'小时'+timeMin+'分钟'+timeSec+'秒'+'</span> \
													</a></li>');
					} else {
						$("#ID_givelove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
													<span class="sort text-c">'+(i+1)+'</span> \
													<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
													<span class="nickname">'+user["nick"]+'</span> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
													<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
													<span style="float:right;margin-right:0.4rem">'+timeHour+'小时'+timeMin+'分钟'+timeSec+'秒'+'</span> \
													</a></li>');
					}
					
				}
				
			},
			"json"
	);
}

function query_gotgift_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_gotgift_rank", 
			{
				user_id:g_guest.user_id,
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}

				$("#ID_gotgift_rank").html("");
				for(var i = 0; i < json.data.length; i++) {
					var user = json.data[i];
					var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
					if(user["role"].indexOf("a") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
					} else if(user["role"].indexOf("s") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
					}

					var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
					if(i <= 2) {
						sort_img += (i+1)+".png";
					}

					var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
					if(user.sex == 2) {
						sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
					}

					if(i <= 2){
						$("#ID_gotgift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
							<span class="sort text-c"><img src="'+sort_img+'" /></span> \
							<span class="headimg"><img class="radius-100" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
							<span style="float:right;margin-right:0.4rem">'+user["gotmoney"]+'币</span> \
						</a></li>');
					} else {
						$("#ID_gotgift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
							<span class="sort text-c">'+(i+1)+'</span> \
							<span class="headimg"><img class="radius-100" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
							<span style="float:right;margin-right:0.4rem">'+user["gotmoney"]+'币</span> \
						</a></li>');
					}
					
				}
				
			},
			"json"
	);
}

function query_gotlove_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_gotlove_rank", 
			{
				user_id:g_guest.user_id,
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}

				$("#ID_gotlove_rank").html("");
				for(var i = 0; i < json.data.length; i++) {
					var user = json.data[i];
					var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
					if(user["role"].indexOf("a") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
					} else if(user["role"].indexOf("s") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
					}

					var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
					if(user.sex == 2) {
						sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
					}

					var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
					if(i <= 2) {
						sort_img += (i+1)+".png";
					}


					if(i <= 2) {
						$("#ID_gotlove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
							<span class="sort text-c"><img src="'+sort_img+'" /></span> \
							<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
							<span class="zan">'+user['gotlove']+'</span> \
						</a></li>');
					} else {
						$("#ID_gotlove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
							<span class="sort text-c">'+(i+1)+'</span> \
							<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
							<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
							<span class="zan">'+user['gotlove']+'</span> \
						</a></li>');
					}
					

					$("#ID_gotlove_rank a").click(addLove);
				}
				
			},
			"json"
	);
}

function query_givelove_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_givelove_rank", 
			{
				user_id:g_guest.user_id,
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}

				$("#ID_givelove_rank").html("");
				for(var i = 0; i < json.data.length; i++) {
					var user = json.data[i];
					var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
					if(user["role"].indexOf("a") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
					} else if(user["role"].indexOf("s") > 0) {
						roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
					}

					var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
					if(user.sex == 0) {
						sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
					}

					var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
					if(i <= 2) {
						sort_img += (i+1)+".png";
					}

					if(i <= 2) {
						$("#ID_givelove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
							<span class="sort text-c"><img src="'+sort_img+'" /></span> \
							<span class="headimg"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<span class="zan">'+user['givelove']+'</span> \
						</a></li>');
					} else {
						$("#ID_givelove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
							<span class="sort text-c">'+(i+1)+'</span> \
							<span class="headimg"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+user["headimg"]+'" /></span> \
							<span class="nickname">'+user["nick"]+'</span> \
							<span class="zan">'+user['givelove']+'</span> \
						</a></li>');
					}
					
				}

				//$("#ID_givelove_rank a").click(addLove);
				
			},
			"json"
	);
}

var g_arrLoveAdder = {};
function LoveAdder(target_user_id, domObj) {
	this.target_user_id = target_user_id;
	this.domObj = domObj;
	this.clickTime = new Date().getTime();
	this.loveCount = 1;
	var adder = this;
	var sh = setInterval(function() {
		curr_time = new Date().getTime();
		if((curr_time - adder.clickTime) >= 500 && adder.loveCount > 0) {
			$.post(
				"http://dream.waimaipu.cn/index.php/user/add_love",
				{
					bar_id:g_bar.bar_id,
					user_id:g_guest.user_id,
					target_userid:target_user_id,
					count:adder.loveCount
				},
				function(json) {
					if(json.code != 0) {
						return;
					}
				},
				"json"
			);

			var target_user = null;
			if(g_singer && target_user_id == g_singer.user_id) {
				target_user = g_singer;
			} else {
				for(var i = 0; i < g_onlineUsers.length; i++) {
					if(g_onlineUsers[i].user_id == target_user_id) {
						target_user = g_onlineUsers[i];
					}
				}
			}

			if(target_user) {
				if(target_user == g_singer) {
					g_socket.emit("addlove", {
						bar_id:g_bar.bar_id,
						count:adder.loveCount
					});
				}

				g_socket.emit('addlove_msg', {
					bar_id:g_bar.bar_id,
					count:adder.loveCount,
					desk_id:g_guest.desk_id,
					nickname:g_guest.nickname,
					target_userid:target_user_id,
					target_nickname:target_user.nickname
				});
			}

			clearInterval(sh);
			delete g_arrLoveAdder[target_user_id];
		} 
	}, 200);
}

function addLove() {
	var target_user_id = $(this).attr("data-item");
	var dom = $(this).find(".zan")[0];
	var tmp = parseInt(dom.innerHTML);
	tmp++;
	dom.innerHTML = tmp;

	if(!g_arrLoveAdder.hasOwnProperty(target_user_id)) {
		g_arrLoveAdder[target_user_id] = new LoveAdder(target_user_id, $(this));
	} else {
		g_arrLoveAdder[target_user_id].clickTime = new Date().getTime();
		g_arrLoveAdder[target_user_id].loveCount++;
	}
}

function addZan(target_user_id, dom) {
	var tmp = parseInt(dom.html());
	tmp++;
	dom.html(tmp);

	if(!g_arrLoveAdder.hasOwnProperty(target_user_id)) {
		g_arrLoveAdder[target_user_id] = new LoveAdder(target_user_id, $(this));
	} else {
		g_arrLoveAdder[target_user_id].clickTime = new Date().getTime();
		g_arrLoveAdder[target_user_id].loveCount++;
	}
}


function query_sys_msg() {
	$("#ID_sysMsgs").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_sys_msg",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}

				g_sysMessage = [];
				for(var p in json.data) {
					g_sysMessage.push({
						time:p,
						data:json.data[p]
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
													'<img src="'+msgContent.pic+'" />'+
													'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
												'</div>'+
											'</li>');
				}
			},
			"json"
		);
}

function query_newest_bar_msg() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_bar_msg",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}
				last_msg = {};
				for(var p in json.data) {
					last_msg = json.data[p];
				}
				$(".newest-bar-msg .introl").html(JSON.parse(last_msg).title);
			},
			"json"
		);
}

function query_bar_msg() {
	$("#ID_barMsgs").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_bar_msg",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}

				g_barMessage = [];
				for(var p in json.data) {
					g_barMessage.push({
						time:p,
						data:json.data[p]
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
			},
			"json"
		);
}


function sendRedPacket() {
	if($(".redpacket-txt1 input").val() == "") {
		commonJS.alert("请填写八刻币数量");
	} else {
		var count = parseInt($(".redpacket-txt1 input").val());
		if(count < 100) {
			commonJS.alert("赠送八刻币数量不得低于100！");
			return;
		}
		var leaveWord = $(".redpacket-txt2").val();
		if(leaveWord=="") {
			leaveWord = $(".redpacket-txt2").attr("placeholder");
		}

		$.post(
			"http://dream.waimaipu.cn/index.php/user/send_redpacket",
			{
				user_id:g_guest.user_id,
				target_user_id:g_sendgift_user.user_id,
				desk_id:g_guest.desk_id,
				bar_id:g_bar.bar_id,
				bakebi_count:count,
				leave_word:leaveWord
			},
			function(json) {
				if(json.code != 0) {
					return;
				}
				commonJS.alert("赠送红包成功！");
			},
			"json"
		);

	}
}

function showUserInfoById(user_id) {
	for(var i = 0; i < g_onlineUsers.length; i++) {
		if(g_onlineUsers[i].user_id == user_id) {
			showUserInfo(g_onlineUsers[i]);
		}
	}
}

function showPrivMsg(user) {
	$(".diy-user-detail").addClass('hide');

}

function showUserInfo(user) {
	// alert(1);
	if(user) {
		commonJS.cover();

		// alert(2);
		g_chatter_user = user;
		// alert(3);
		$(".diy-user-detail").removeClass('hide');
		$(".diy-user-detail .headimg").attr('src', user.headimg);
		$(".diy-user-detail .nickname").html(user.nickname);
		$(".diy-user-detail .collect").html(user.liveness);
		// alert(4);
		$(".diy-user-detail").attr("user-id", user.user_id);
		// alert(5);
		$.post(
			"http://dream.waimaipu.cn/index.php/user/query_user_info",
			{
				user_id:g_guest.user_id,
				target_user_id:user.user_id
			},
			function(json) {
				// alert("query_user_info_ret="+json.code);
				if(json.code != 0) {
					return;
				}

				var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
				if(json.data.role.indexOf("a") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
				} else if(json.data.role.indexOf("s") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
				}		
				$(".diy-user-detail .audio").attr('src', roleImg);
				$(".diy-user-detail .zan em").html(json.data.love);
				$(".diy-user-detail").attr("user-id", user.user_id);
				$(".diy-user-detail .connect").html(json.data.liveness);
				$(".diy-user-detail").removeClass("hide");
			},
			"json"
		);
	}
}

function showAllUserInfo(user) {
	if(user) {
		commonJS.cover();
		g_chatter_user = user;
		alert("showUserAllInfo1");
		$(".diy-user-detail-all").removeClass('hide');
		$(".diy-user-detail-all .headimg").attr('src', user.headimg);
		$(".diy-user-detail-all .nickname").html(user.nickname);
		$(".diy-user-detail-all .collect").html(user.liveness);
		$(".diy-user-detail-all").attr("user-id", user.user_id);
		$.post(
			"http://dream.waimaipu.cn/index.php/user/query_user_info",
			{
				user_id:g_guest.user_id,
				target_user_id:user.user_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}

				var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
				if(json.data.role.indexOf("a") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
				} else if(json.data.role.indexOf("s") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
				}
				$(".diy-user-detail-all .audio").attr('src', roleImg);
				$(".diy-user-detail-all .zan em").html(json.data.love);
				$(".diy-user-detail-all").attr("user-id", user.user_id);
				$(".diy-user-detail-all .connect").html(json.data.liveness);
				$(".diy-user-detail-all").removeClass("hide");
			},
			"json"
		);
	}
}
function showUserInfo_ForAllUser(user_id) {
	for(var i = 0; i < g_all_users.length; i++) {
		if(g_all_users[i].user_id == user_id) {
			showAllUserInfo(g_all_users[i]);
			//switchToHomePage();
		}
	}
}

function splitKeyValue(val, sp1, sp2) {
	var arr = val.split(sp1);
	var kv = {};
	for(var i = 0; i < arr.length; i++) {
		var arr_tmp = arr[i].split(sp2);
		kv[arr_tmp[0]] = arr_tmp[1];
	}
	return kv;
}

function switchToPage (page) {
	$(g_curPage).addClass("hide");
	g_pageStack.push(g_curPage);
	g_curPage = page;
	$(g_curPage).removeClass("hide");
}

function switchToPayBill() {
	switchToPage(".page-bill");
	query_bill();
	query_giftsend();
	query_giftrecv();
}

function switchToRedPacket() {
	$(".redpacket-name span").html(g_sendgift_user.nickname);
	$(".redpacket-content img").attr('src', g_sendgift_user.headimg);
	switchToPage(".page-red-packets");
}

function showUserPrivMsg(user_id) {
	for(var i = 0; i < g_onlineUsers.length; i++) {
		if(g_onlineUsers[i].user_id == user_id) {
			g_chatter_user = g_onlineUsers[i];
		}
	}

	show_priv_chat();
}
function switchToPrivMsgPage() {
	$("#ID_privMsg").html("");
	var currTime = Date.parse(new Date())/1000;

	var timeStr;
	g_priv_msg.sort(function(a,b) {
		return b.newest_time - a.newest_time;
	});

	for(var i = 0; i < g_priv_msg.length; i++) {
		g_priv_msg[i].msgs.sort(function(a,b) {
			return b.timestamp - a.timestamp;
		});
		var msgTime = g_priv_msg[i].msgs[0].timestamp;
		if((currTime-msgTime)<10*60) {
			timeStr = Math.floor((currTime-msgTime)/60)+"分钟前";
		} else {
			var dateObj = new Date(msgTime*1000);
    		timeStr = dateObj.getFullYear() + '-' + (dateObj.getMonth() +1 ) + '-' + dateObj.getDate()+ ' ' + dateObj.getHours() + ':' + dateObj.getMinutes() + ':' + dateObj.getSeconds();
		}
		$("#ID_privMsg").append('<li user-id="'+g_priv_msg[i].user_id+'" onclick="showUserPrivMsg('+g_priv_msg[i].user_id+')"> \
									<div class="headimg fl-l margin-right"><img class="radius-100" src="'+g_priv_msg[i].msgs[0].headimg+'" /></div> \
									<div class="title hd-h4 padding-small-top">'+g_priv_msg[i].msgs[0].nickname+'-第'+g_priv_msg[i].msgs[0].desk_id+'桌</div> \
									<div class="introl text-gray">'+g_priv_msg[i].msgs[0].content+'</div> \
									<div class="time hd-h4 text-gray">'+timeStr+'</div> \
								</li>');
	}
	
	switchToPage(".page-person-msg");
}

function switchToSysMsgPage() {
	query_sys_msg();
	switchToPage(".page-sys-msg");
}

function switchToBarMsgPage() {
	switchToPage(".page-seller-msg");
	g_barMsgMaxShowCount = 3;
	query_bar_msg();
}

function switchToUserInfoPage() {
	query_newest_bar_msg();
	query_sys_msg();

	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_user_info",
			{
				user_id:g_guest.user_id,
				target_user_id:g_guest.user_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}

				$(".diy-userinfo .zan").html(json.data.love);
				$(".diy-userinfo .collect").html(json.data.liveness);
				if(json.data.sex == 1) {
					$(".diy-userinfo .nickname img").attr('src', "http://o95rd8icu.bkt.clouddn.com/男性.png");
				} else {
					$(".diy-userinfo .nickname img").attr('src', "http://o95rd8icu.bkt.clouddn.com/女性.png");
				}
				$(".page-center .diy-usermsg .money-num").html(json.data.money);
			},
			"json"
		);

	if(g_unviewed_msg_count > 0) {
		$(".priv-msg .num").html(g_unviewed_msg_count);
	}
	// if(g_priv_msg.length > 0) {
	// 	$(".priv-msg .num").html(g_priv_msg.length);
	// 	$(".priv-msg .introl").html(g_priv_msg[g_priv_msg.length-1].content);
	// }
	
	switchToPage(".page-center");
	// $(".page-center .barmessage").html(g_barMessage.length);
}

function switchToOnlinePage() {
	g_socket.emit('getOnlineUsers', {bar_id:g_bar.bar_id});
	switchToPage('.page-allusers');
}

function switchToHomePage() {
	$("#ID_msg_count").html(g_unviewed_msg_count);
	switchToPage(".page-home");
}

function switchToRankPage() {
	query_givegift_rank();
	query_gotgift_rank();
	query_gotlove_rank();
	query_online_rank();
	//query_givelove_rank();
	switchToPage(".page-bangdan");
}

function goback() {
	$(g_curPage).addClass("hide");
	g_curPage = g_pageStack.pop();
	$(g_curPage).removeClass("hide");
}
function switchToPayPage() {
	switchToPage(".page-recharge");
	
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_money",
			{
				user_id:g_guest.user_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$(".balance .count").html(json.money);
			},
			"json"
	);
}

$.fn.onlyNum = function () {
      $(this).keypress(function (event) {
          var eventObj = event || e;
          var keyCode = eventObj.keyCode || eventObj.which;
          if ((keyCode >= 48 && keyCode <= 57))
             return true;
          else
             return false;
      }).focus(function () {
	     //禁用输入法
	        this.style.imeMode = 'disabled';
      }).bind("paste", function () {
       //获取剪切板的内容
           var clipboard = window.clipboardData.getData("Text");
           if (/^\d+$/.test(clipboard))
               return true;
           else
               return false;
       });
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
function pay() {
	order_id = null;
	if($(".choice .selected").hasClass('input-money')) {
		coin_count = $('.recharge-choice .input-money input').val();
		$('.recharge-choice .input-money input').blur();
	} else {
		coin_count = $(".choice .selected").attr("data-item");
	}

	commonJS.cover(0.6);
	/* 添加支付前的加载提示*/
	FN.loading.show('正在火速启动微信支付');
	$.post(
			"http://dream.waimaipu.cn/index.php/user/pay",
			{
				bar_id:g_bar.bar_id,
				desk_id:g_bar.desk_id,
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

function Time2Str(timestamp) {
	var dateObj = new Date(timestamp*1000);
    var timeStr = dateObj.getFullYear() + '-' + pad((dateObj.getMonth() +1 ),2) + '-' + 
    					pad(dateObj.getDate(),2)+ ' ' + pad(dateObj.getHours(),2) + ':' + 
    					pad(dateObj.getMinutes(),2) + ':' + pad(dateObj.getSeconds(),2);
    return timeStr;
}

function initWebSocket() {
	g_socket = io.connect('ws://119.29.10.176:3000');
	//发送登录消息给服务器
	g_socket.emit('login', {
			bar_id:g_bar.bar_id,
			user_id:g_guest.user_id,
			desk_id:g_guest.desk_id,
			nickname:g_guest.nickname,
			headimg:g_guest.headimg,
			role:g_guest.role,
			sex:g_guest.sex
		});

	//监听新用户登录
	g_socket.on('login', function(obj){
		//当有用户登录时，login消息会传回
		//填充在线用户头像
		g_onlineUsers = obj.guest;
		fillOnlineUserImgs(obj.guest);
		//如果有在线歌手，则更新歌手信息
		if(obj.singer) {//如果有歌手信息
			g_singer = obj.singer;
			g_singer.role = 'a';
			updateSingerView(g_singer);
		}
		else {
			updateBarView(g_bar);
		}
		
		if(obj.user) {
			$(".diy-msg").append('<li class="margin-bottom animated bounceInLeft">'+obj.user.nickname+'-第'+obj.user.desk_id+'桌 上线了</li>');
		}
		scrollBottom();
	});
	
	g_socket.on('logout', function(obj) {
		var index = -1;
		for(var i = 0; i < g_onlineUsers.length; i++) {
			if(obj.user_id == g_onlineUsers[i].user_id) {
				index = i;
				break;
			}
		}

		if(index >= 0) {
			g_onlineUsers.splice(index, 1);
			fillOnlineUserImgs(g_onlineUsers);
		}
	});
	//礼物消息
	g_socket.on('giftMessage', function(obj) {
		var addEl = $('<li class="diy-sys-3 margin-bottom user-message msg-in-out" onclick="showUserInfoById('+obj.user_id+')"> \
									<img class="radius-100 width-40 height-40 fl-l" src="'+obj.headimg+'" /> \
									<div class="fl-l text-yew">'+obj.nickname+'-第'+obj.desk_id+'桌<b class="text-purple">赠送'+obj.target_nickname+'：</b></div> \
									<div class="padding-left text-blue">'+obj.item_name+'</div>\
									<div class="diy-sys-gift animated bounceInLeft delay-1"><img src="'+obj.item_img+'"/><em class="text-yew hd-h2 animated bounceIn delay-2">x1</em></div> \
								  </li>');
		addEl.appendTo(".diy-sys");
		var removeEL = function(el,time){
			time = time?time:1000;
			setTimeout(function(){
				el.remove();
			},time);
		};
		/*发送的数字*/
		if(obj.item_count > 1){
			setTimeout(function(){
				addEl.removeClass('msg-in-out');
				var numEl = addEl.find('em.text-yew'),num=1,inT;
				numEl.removeClass('bounceIn delay-2'),reNum = function(){
					numEl.removeClass('bounceIn');
					num++;
					if(num > obj.item_count){
						clearInterval(inT);
						addEl.addClass('msg-out');
						removeEL(addEl,5000);
					} else {
						setTimeout(function(){
							numEl.text('x'+num);
							numEl.addClass('bounceIn');
						},100);
					}
				};
				reNum();
				inT = setInterval(reNum,1000);
			},3100);
		} else {
			removeEL(addEl,6000);
		}
		scrollBottom();
	});
	//收到礼物连发消息
	// g_socket.on("giftRepeat", function(obj) {
	// 	console.log("giftRepeat user_id="+obj.user_id+"&item_id="+obj.item_id+"&count="+obj.item_count);
	// });
	//歌手切换消息
	g_socket.on('singerSwitch', function(obj) {
		g_singer = obj;
		g_singer.role = 'a';
		updateSingerView(g_singer);
	});
	//爱心值修改消息
	g_socket.on('addlove', function(obj) {
		g_singer = obj;
		g_singer.role = 'a';
		updateSingerView(g_singer);
	});
	//普通切换消息
	g_socket.on('message', function(obj) {
		if(obj.type == 'normsg') {
			addNormalMsg(obj, false);
		} else {
			addBarrageMsg(obj, false);
		}
	});
	//@消息
	g_socket.on('@message', function(obj) {
		if(obj.target_user_id == g_guest.user_id) {
			g_at_msg.push(obj);
		}

		if(obj.type == "normsg") {
			addNormalMsg(obj, true);
		} else {
			addBarrageMsg(obj, true);
		}
	});
	//私密消息
	g_socket.on('priv_msg', function(message) {
		var bFind = false;
		for(var i = 0;i < g_priv_msg.length; i++) {
			if(g_priv_msg[i].user_id == message.user_id) {
				g_priv_msg[i].msgs.push(message);
				if(g_priv_msg[i].newest_time < message.timestamp) {
					g_priv_msg[i].newest_time = message.timestamp;
				}
				bFind = true;
				break;
			}
		}

		if(!bFind) {
			g_priv_msg[i] = {};
			g_priv_msg[i].user_id = message.user_id;
			g_priv_msg[i].msgs = [];
			g_priv_msg[i].msgs.push(message);
			g_priv_msg[i].newest_time = message.timestamp;
		}

		if(g_chatter_user) {
			if(g_chatter_user.user_id == message.user_id) {
				//填充消息
    			var timeStr = Time2Str(message.timestamp);
				$(".diy-cryp-data").append(
				'<div class="diy-cryp-item"> \
					<p class="time">'+timeStr+'</p> \
					<div class="diy-cryp-item"> \
						<img class="width-40 height-40 fl-l radius-100 margin-right" src="'+message.headimg+'" /> \
						<span class="fl-l">'+message.content+'</span> \
						</div> \
				</div>');
			} else if(message.user_id == g_guest.user_id) {
				var timeStr = Time2Str(message.timestamp);
				$(".diy-cryp-data").append(
						'<div class="diy-cryp-item"> \
							<p class="time">'+timeStr+'</p> \
							<div class="diy-cryp-item"> \
								<img class="width-40 height-40 fl-r radius-100 margin-left" src="'+message.headimg+'" /> \
								<span class="fl-r">'+message.content+'</span> \
							</div> \
						</div>' 
					);
			} else {
				g_unviewed_msg_count++;
			}
		}
		else {
			g_unviewed_msg_count++;
		}

		$("#ID_msg_count").html(g_unviewed_msg_count);
		scrollBottom();
	});

	//商家消息
	g_socket.on('barMessage', function(obj) {
		$(".diy-sys").append('<a href="javascript:void(0)" onclick="switchToBarMsgPage()"><li class="diy-sys-2 text-red padding-left padding-right margin-bottom"> \
								商家公告：'+obj.title+' \
							  </li></a>');
		//g_barMessage.push(obj);
	});
	//系统消息
	g_socket.on('sysMessage', function(obj) {
		$(".diy-sys").append('<a href="javascript:void(0);" onclick="switchToSysMsgPage()"><li class="diy-sys-2 text-red padding-left padding-right margin-bottom"> \
								系统消息：'+obj.title+' \
							  </li></a>');
	});

	//歌手切换消息
	g_socket.on('singerSwitch', function(obj) {
		g_singer = obj;
		g_singer.role = 'a';
		updateSingerView(g_singer);
	});

	//爱心增加消息
	g_socket.on('addlove_msg', function(message) {
		$(".diy-msg").append('<li class="margin-bottom user-message animated bounceInLeft" user-id="'+message.user_id+'" onclick="showUserInfoById('+message.user_id+')">'+message.nickname+'-第'+message.desk_id+'桌：<em class="text-white">'+"给"+message.target_nickname+"加了"+message.count+"个爱心"+'</em></li>');
		scrollBottom();
	});

	g_socket.on('delMessage', function(message) {
		var msg_ids = [];
		// alert('delMessage');
		msg_ids = message.message_ids.split("|");
		// alert(message.message_ids);
		$(".user-message").each(function() {
			for(var i = 0; i < msg_ids.length; i++) {
				if($(this).attr('msg-id').indexOf(msg_ids[i]) >= 0) {
					$(this).remove();
				}
			}
			
			scrollBottom();
		});
	});

	//断开连接
	g_socket.on('disconnect', function(obj) {
		alert('disconect');
	});
	//获取在线人消息
	g_socket.on('retOnlineUsers', function(obj) {
			var userids = [];
			for(var i = 0; i < obj.guest.length; i++) {
				userids.push(obj.guest[i].user_id);
			}

			// alert(g_singer);
			if(g_singer) {
				userids.push(g_singer.user_id);
			}
			// alert(userids.join("|"));
			$.post(
					"http://dream.waimaipu.cn/index.php/user/get_users_info",
					{
						userids:userids.join("|"),
					},
					function(json) {
						if(json.code != 0)
						{//查询失败，后面考虑如何提示(thinklater)
							return;
						}
						g_all_users = json.data;
						$(".page-allusers .user-list").html("");
						for(var i = 0; i < json.data.length; i++)
						{
							var user = json.data[i];
							var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
							if(user.role.indexOf("a") > 0) {
								roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
							} else if(user.role.indexOf("s") > 0) {
								roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
							}

							user.nickname = user.nick;
							if(user.role.indexOf("a") >= 0) {
								$(".page-allusers .singer-list").append('<li> \
																<a href="javascript:void(0)" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																	<img class="role-icon" src="'+roleImg+'"></img> \
																	<img class="user-head" src="'+user.headimg+'"></img> \
																	<p class="user-name">'+user.nick+'</p> \
																	<span class="zan fl-r">'+user.love+'</span> \
																	<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
																  </a></li>');
							} else if(user.role.indexOf("s") >= 0) {
								$(".page-allusers .server-list").append('<li> \
																<a href="javascript:void(0);" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																	<img class="role-icon" src="'+roleImg+'"></img> \
																	<img class="user-head" src="'+user.headimg+'"></img> \
																	<p class="user-name">'+user.nick+'</p> \
																	<span class="zan fl-r">'+user.love+'</span> \
																	<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
																  </a></li>');
							} else if(user.role.indexOf("g") >= 0) {
								$(".page-allusers .guest-list").append('<li> \
																<a href="javascript:void(0);" style="width:100%;height:1border:1px solid red" onclick="showUserInfo_ForAllUser('+user.user_id+')"> \
																	<img class="role-icon" src="'+roleImg+'"></img> \
																	<img class="user-head" src="'+user.headimg+'"></img> \
																	<p class="user-name">'+user.nick+'</p> \
																	<span class="zan fl-r">'+user.love+'</span> \
																	<span class="collect radius-10 text-c fl-r">'+user.liveness+'</span> \
																  </a></li>');
							}
							$(".page-allusers .singer-list").removeClass('hide');
						}
					},
					"json"
			);
		});
}

var g_all_users;

function showPrivMsgbyUserId(user_id) {
	g_chatter_user = null;
	for(var i = 0; i < g_onlineUsers.length; i++) {
		if(g_onlineUsers[i].user_id == user_id) {
			g_chatter_user = g_onlineUsers[i];
			break;
		}
	}

	if(!g_chatter_user) {
		return;
	}

	show_priv_chat();
}

function SearchUser() {
	alert('SearchUser');
}

function addNormalMsg(message, bAtMsg) {
	var msg;
	if(bAtMsg) {
		msg = "@"+message.target_user_nickname+" "+message.content;
	} else {
		msg = message.content;
	}

	$(".diy-msg").append('<li class="margin-bottom user-message animated bounceInLeft" msg-id="'+message.message_id+'" user-id="'+
							message.user_id+'" onclick="showUserInfoById('+message.user_id+')">'+
							message.nickname+'-第'+message.desk_id+'桌：<em class="text-white">'+
							msg+'</em></li>');
	scrollBottom();
}


function addBarrageMsg(message, bAtMsg) {
	var msg;
	if(bAtMsg) {
		msg = "@"+message.target_user_nickname+" "+message.content;
	} else {
		msg = message.content;
	}
	var addEL = $('<li class="diy-sys-1 padding-right text-yew margin-bottom user-message msg-in-out" msg-id="'+message.message_id+'" user-id="'+
							message.user_id+'" onclick="showUserInfoById('+message.user_id+
							')"><img class="radius-100 width-40 height-40 fl-l" src='+
							message.headimg+'/>'+message.nickname+'-第'+message.desk_id+
							'桌：<em class="text-white">'+msg+'</em></li>');

	addEL.appendTo('.diy-sys');
	setTimeout(function(){
		addEL.remove();
	},6000);
	scrollBottom();
}

function fillOnlineUserImgs(guests) {
	$(".diy-online-user a").html(guests.length);
	$(".diy-online-imgs div").html("");
	for(var i = 0; i < guests.length; i++) {
		$(".diy-online-imgs div").append('<img class="radius-100 width-25 height-25" index="'+i+'" user-id="'+guests[i].user_id+'" src="'+guests[i].headimg+'" />');
	}
	$(".diy-online-imgs div img").click(function() {
		var index = parseInt($(this).attr("index"));
		showUserInfo(g_onlineUsers[index]);
	});
}

function updateSingerView(singer) {
	$(".diy-user img").attr("src", g_singer.headimg);
	$(".diy-user .uname").html(g_singer.nickname);
	$(".diy-user .collect").html(g_singer.liveness);
	$(".diy-user .zan").html(g_singer.lovecount);
	$(".diy-user .collect").show();
	$(".diy-user .zan").show();
	$(".diy-user .audio").show();
}

function updateBarView() {
	$(".diy-user img").attr("src", g_bar.barimg);
	$(".diy-user .uname").html(g_bar.name);
	$(".diy-user .collect").hide();
	$(".diy-user .zan").hide();
	$(".diy-user .audio").hide();
}

/* 获取随机颜色 */
function getRandomColor() { 
	return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6); 
} 

/* 点赞动画*/	
function genZanCartoon()
{
	var bubble = document.getElementById("bubble_div");
	var color = getRandomColor();
	var svg=document.createElementNS('http://www.w3.org/2000/svg','svg'); 
	svg.innerHTML = '<path class="svgpath" style="fill:'+color+';stroke: #FFF; stroke-width: 1px;" d="M11.29,2C7-2.4,0,1,0,7.09c0,4.4,4.06,7.53,7.1,9.9,2.11,1.63,3.21,2.41,4,3a1.72,1.72,0,0,0,2.12,0c0.79-.64,1.88-1.44,4-3,3.09-2.32,7.1-5.55,7.1-9.94,0-6-7-9.45-11.29-5.07A1.15,1.15,0,0,1,11.29,2Z"></path>';
	svg.setAttribute("style", "right:0.2rem; bottom: 0.0rem; opacity: 0.9; width: 0.32rem; height: 0.32rem;");
	svg.setAttribute("ViewBox", "-1 -1 27 27");
	
	var obj = bubble.appendChild(svg);
	svg.right = 0.2;
	svg.bottom = 0;
	svg.opacity = 0.9;
	svg.dirLeft = Math.floor(Math.random()*50)%2;
	var time = Math.floor(Math.random()*50+50);//50-100
	var sh = setInterval(function() {
		svg.bottom += 0.04;
		svg.opacity -= 0.01;
		if(svg.dirLeft == 1)
		{
			svg.right = svg.right - 0.02;
			if(svg.right <= 0.04)
			{
				svg.dirLeft = 0;
			}
		}
		else
		{
			svg.right = svg.right + 0.02;
			if(svg.right >= 0.5)
			{
				svg.dirLeft = 1;
			}
		}
		
		svg.setAttribute("style", "right:" + svg.right+"rem;bottom:" + svg.bottom+"rem;opacity:"+svg.opacity+"; width: 0.32rem; height: 0.32rem;");
		if(obj.opacity <= 0.1)
		{
			clearInterval(sh);
			bubble.removeChild(svg);
		}
	}, time);
}

var add_zan_time;
var add_zan_count = 0;
function add_zan() {
	genZanCartoon();
	if(!g_singer) {
		return;
	}
	add_zan_time = new Date().getTime();
	if(add_zan_count == 0) {
		setTimeout(judge_addzan, 200);
	}
	add_zan_count++;
}

function judge_addzan() {
	curr_time = new Date().getTime();
	if((curr_time - add_zan_time) >= 500 && add_zan_count > 0) {
		//调用后台接口增加
		$.post(
				"http://dream.waimaipu.cn/index.php/user/add_love",
				{
					bar_id:1,
					user_id:g_guest.user_id,
					target_userid:g_singer.user_id,
					count:add_zan_count
				},
				function(json) {
					if(json.code != 0) {
						return;
					}
				},
				"json"
		);
		
		g_socket.emit("addlove", {
			bar_id:g_bar.bar_id,
			count:add_zan_count
		});

		g_socket.emit('addlove_msg', {
			bar_id:g_bar.bar_id,
			count:add_zan_count,
			desk_id:g_guest.desk_id,
			nickname:g_guest.nickname,
			target_userid:g_singer.user_id,
			target_nickname:g_singer.nickname
		});

		add_zan_count = 0;
	}
	else {
		setTimeout(judge_addzan, 200);
	}
}


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
											'<img src="'+msgContent.pic+'" />'+
											'<div class="introl hd-h4 text-gray">'+msgContent.content+'</div>'+
										'</div>'+
									'</li>');
		}
	}
}

$(document).ready(function() {
    $(window).scroll(function() {
        //$(document).scrollTop() 获取垂直滚动的距离
        //$(document).scrollLeft() 这是获取水平滚动条的距离
        if ($(document).scrollTop() <= 0) {//到达顶部
        }

        if ($(document).scrollTop() >= $(document).height() - $(window).height()) {//到达底部
        	if(g_curPage == ".page-seller-msg") {
        		LoadBarMsg();
        	} else if(g_curPage == ".page-sys-msg") {
        		LoadSysMsg();
        	}
        }
    });
});