/* *
 * 聊天js事件
 */


$(function(){
	
	// 表情内容
	brow_load();

	//表情按钮点击事件
	$(".brow-btn").on("click",show_brow);
	//选择表情
	$(".brow-imgs img").on("click",choose_brow);

	//显示聊天模块
	$(".diy-img-chat").on("click",function() {
		g_chatter_user = null;
		show_chat();
	});

	//聊天：发送事件
	$(".diy-chat .sendmsg-btn").on("click",send_chat);
	
	$(".diy-cryptolalia .sendmsg-btn").on("click",send_priv_chat);

	//弹幕
	$(".barrage-btn").on("click",switch_barrage);

});
//发送消息
function send_chat(){
	if($(".diy-chat .chat").html()!=""){
		if($(".barrage-btn").hasClass("open")) {
			//扣除八刻币，目前先放前端做(简化设计)，后面考虑放后端,安全
			if(g_barrage_alert) {
				commonJS.confirm('本次支付需花费1八刻币，支付吗？', function(notAlergAgain) {
					if(notAlergAgain)
					{
						g_barrage_alert = false;
						$.post(
							"http://dream.waimaipu.cn/index.php/user/close_barrage_alert",
							{
								user_id:g_guest.user_id
							},
							function(json){
							},
							"json"
						);
					}

					$.post(
						"http://dream.waimaipu.cn/index.php/user/consume_money",
						{
							moneycount:1,
							user_id:g_guest.user_id
						},
						function(json){
							//不关注是否扣除成功
							var msg = {
									type:'danmumsg',
									user_id:g_guest.user_id,
									bar_id:g_bar.bar_id,
									desk_id:1,
									nickname:g_guest.nickname,
									headimg:g_guest.headimg,
									content:$(".diy-chat .chat").html()
								};

							var msgType = "message";
							if(g_chatter_user) {
								msg.target_user_id = g_chatter_user.user_id;
								msg.target_user_nickname = g_chatter_user.nickname;
								msgType = "@message";
							}

							
							if(json.code == 0) {//查询失败，后面考虑如何提示(thinklater)
								g_socket.emit(msgType, msg);
								return;
							}
							else if(json.code == -1003) {
								commonJS.alert('您余额不足！');
							}
						},
						"json"
					);
				}, function() {});
			} else {
				$.post(
						"http://dream.waimaipu.cn/index.php/user/consume_money",
						{
							moneycount:1,
							user_id:g_guest.user_id
						},
						function(json){
							//不关注是否扣除成功
							if(json.code == 0) {//查询失败，后面考虑如何提示(thinklater)
								var msg = {
									type:'danmumsg',
									user_id:g_guest.user_id,
									bar_id:g_bar.bar_id,
									desk_id:1,
									nickname:g_guest.nickname,
									headimg:g_guest.headimg,
									content:$(".diy-chat .chat").html()
								};

								var msgType = "message";
								if(g_chatter_user) {
									msg.target_user_id = g_chatter_user.user_id;
									msg.target_user_nickname = g_chatter_user.nickname;
									msgType = "@message";
								}

								g_socket.emit(msgType, msg);
								return;
							}
							else if(json.code == -1003) {
								commonJS.alert('您余额不足！');
							}
						},
						"json"
					);
			}
		} else {
			var msgType = "message";
			var msg = {
									type:'normsg',
									user_id:g_guest.user_id,
									bar_id:g_bar.bar_id,
									desk_id:1,
									nickname:g_guest.nickname,
									headimg:g_guest.headimg,
									content:$(".diy-chat .chat").html()
								};

			if(g_chatter_user) {
								msg.target_user_id = g_chatter_user.user_id;
								msg.target_user_nickname = g_chatter_user.nickname;
								msgType = "@message";
							}
			g_socket.emit(msgType, msg);
		}
	}
	hide_chat();
}


function send_priv_chat() {
	if($(".cryptolalia-chat").html()!="") {
		g_socket.emit('priv_msg', {
								type:'priv_msg',
								user_id:g_guest.user_id,
								bar_id:g_bar.bar_id,
								desk_id:1,
								viewed:false,
								target_user_id:g_chatter_user.user_id,
								target_user_nickname:g_chatter_user.nickname,
								nickname:g_guest.nickname,
								headimg:g_guest.headimg,
								content:$(".cryptolalia-chat").html()
							});
		content:$(".cryptolalia-chat").html("");
		scrollBottom();
	}
}

function switch_barrage() {
	if($(".barrage-btn").hasClass("open")) {
		$(".barrage-btn").removeClass("open");
		$(".barrage-btn div").css("float", "right");
	} else {
		$(".barrage-btn").addClass("open");
		$(".barrage-btn div").css("float", "");
	}
}
//选择表情
function choose_brow(){
	var clone_html = $(this).clone();
	$(".chat").append(clone_html);
}
//显示聊天模块
function show_chat(){
	commonJS.cover();
	if(g_chatter_user) {
		$(".diy-chat .chat").html("");
		$(".diy-chat .user").html("@"+g_chatter_user.nickname);
		$(".diy-chat").attr("user-id", g_chatter_user.user_id);
	} else {
		$(".diy-chat .chat").html("");
		$(".diy-chat .user").html("");
		$(".diy-chat").attr("user-id", "");
	}
	$(".diy-chat").removeClass("hide");
}

function show_priv_chat() {
	commonJS.cover();
	if(g_chatter_user) {
		$(".diy-cryptolalia").removeClass("hide");
		$(".diy-cryptolalia .diy-cryp-title").html(g_chatter_user.nickname);
		$(".diy-cryptolalia .diy-cryp-close").attr("src", g_chatter_user.headimg);
		$(".diy-cryp-data").html("");

		for(var i = 0; i < g_priv_msg.length; i++) {
			if(g_priv_msg[i].user_id == g_chatter_user.user_id) {
				for(var j = 0; j < g_priv_msg[i].msgs.length; j++) {
					if(!g_priv_msg[i].msgs[j].viewed) {
						g_priv_msg[i].msgs[j].viewed = true;
						g_unviewed_msg_count--;
					}


					$(".diy-cryp-data").append('<div class="diy-cryp-item"> \
													<p class="time">20:10</p> \
													<div class="diy-cryp-item"><img class="width-40 height-40 fl-l radius-100 margin-right" src="'+g_priv_msg[i].msgs[j].headimg+'" /><span class="fl-l">'+g_priv_msg[i].msgs[j].content+'</span></div> \
												</div>');
				}
			}
		}
		switchToHomePage();
	}
}

function hide_chat() {
	$(".diy-chat").addClass("hide");
}

//显示表情模块
function show_brow() {
	$(".brow-imgs").slideToggle();
}

// 表情内容
function brow_load(){
	var html = "";
	for(var i=0;i<30;i++){
		html += '<img src="http://o95rd8icu.bkt.clouddn.com/'+i+'.png" />';
	}
	$(".brow-imgs").html(html);
}