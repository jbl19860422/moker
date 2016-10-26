$(function(){
	init();
})

// 融云初始化
function rongCloudInit(appKey, RongIMToken, messageHandler, connectedCallback) {
	RongIMClient.init(appKey);		// 初始化。
	// 必须设置监听器后，再连接融云服务器
	// 设置连接监听状态 （ status 标识当前连接状态）
	// 连接状态监听器
	RongIMClient.setConnectionStatusListener({
		onChanged: function (status) {
			switch (status) {
				//链接成功
				case RongIMLib.ConnectionStatus.CONNECTED:
					console.log('链接成功');
					break;
				//正在链接
				case RongIMLib.ConnectionStatus.CONNECTING:
					console.log('正在链接');
					break;
				//重新链接
				case RongIMLib.ConnectionStatus.DISCONNECTED:
					console.log('断开连接');
					break;
				//其他设备登录
				case RongIMLib.ConnectionStatus.KICKED_OFFLINE_BY_OTHER_CLIENT:
					console.log('其他设备登录');
					break;
				//网络不可用
				case RongIMLib.ConnectionStatus.NETWORK_UNAVAILABLE:
					console.log('网络不可用');
					break;
			}
		}
	});
	// 消息监听器
	RongIMClient.setOnReceiveMessageListener({
		// 接收到的消息
		onReceived: function (message) {
			// 判断消息类型
			if(message.content.extra) {
				var extra = JSON.parse(message.content.extra);
				console.log(message, extra);			
			} else {
				console.log(message);
			}

			switch(message.messageType){
				case RongIMClient.MessageType.TextMessage:
					// 普通消息
					messageHandler[message.content.content](extra);
					break;
				case RongIMClient.MessageType.InformationNotificationMessage:
					// 登录消息
					messageHandler[message.content.name](data);
					break;
				case RongIMClient.MessageType.ProfileNotificationMessage:
					// 资料通知消息
					messageHandler[message.content.operation](message.content.data);
					break;
				case RongIMClient.MessageType.CommandNotificationMessage:
					// 通用命令通知消息
					messageHandler[message.content.name](message.content.data);
					break;
				case RongIMClient.MessageType.CommandMessage:
					// 命令消息
					messageHandler[message.content.name](message.content.data);
					break;
				case RongIMClient.MessageType.UnknownMessage:
					// 未知消息
					messageHandler[message.content.type](message.content.data);
					break;
				/*case RongIMClient.MessageType.VoiceMessage:
					// 语音消息
					break;
				case RongIMClient.MessageType.ImageMessage:
					// 图片消息
					break;
				case RongIMClient.MessageType.DiscussionNotificationMessage:
					// 讨论组通知消息
					break;
				case RongIMClient.MessageType.LocationMessage:
					// 定位消息
					break;
				case RongIMClient.MessageType.RichContentMessage:
					// 富文本消息
					break;
				case RongIMClient.MessageType.ContactNotificationMessage:
					// 添加联系人消息
					break;*/
				default:
					// 自定义消息
					// do something...
					console.log('未定义的消息类型');
			}
		}
	});
	// 连接融云服务器。
	RongIMClient.connect(RongIMToken, {
		onSuccess: function(userId) {
			console.log("Login successfully." + userId);
			if (connectedCallback) {
				connectedCallback();
			}
		},
		onTokenIncorrect: function() {
			console.log('token无效');
		},
		onError:function(errorCode){
			var info = '';
			switch (errorCode) {
				case RongIMLib.ErrorCode.TIMEOUT:
					info = '超时';
					break;
				case RongIMLib.ErrorCode.UNKNOWN_ERROR:
					info = '未知错误';
					break;
				case RongIMLib.ErrorCode.UNACCEPTABLE_PaROTOCOL_VERSION:
					info = '不可接受的协议版本';
					break;
				case RongIMLib.ErrorCode.IDENTIFIER_REJECTED:
					info = 'appkey不正确';
					break;
				case RongIMLib.ErrorCode.SERVER_UNAVAILABLE:
					info = '服务器不可用';
					break;
			}
			console.log(errorCode);
		}
	});
}

function getUnreadPrivMsgCount(room, succeed_callback) {
	var conversationType = RongIMLib.ConversationType.PRIVATE;
	RongIMClient.getInstance().getUnreadCount(conversationType,room,{
	    onSuccess:function(count){
	        // count => 指定会话的总未读数。
	        alert(count);
	        succeed_callback(count);
	    },
	    onError:function(){
	        // error => 获取指定会话未读数错误码。
	    }
	});	
}

// 融云发送消息的通用方法
function rongCloudEmit(type, data, callback, target) {
	var	conversationtype = RongIMLib.ConversationType.CHATROOM;
	var targetId = target || g_room;
	switch (type) {
		case 'login':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);		
			break;
		case 'logout':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);		
			break;
		case 'giftMessage':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'addlove':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'message':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case '@message':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'priv_msg':
			conversationtype = RongIMLib.ConversationType.PRIVATE;
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'barMessage':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'sysMessage':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'singerSwitch':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'addlove_msg':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'delMessage':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'retOnlineUsers':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		case 'getOnlineUsers':
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
		default:
			var content = {name: type, data: JSON.stringify(data)};
			var msg = new RongIMLib.CommandNotificationMessage(content);
			break;
	}

 	RongIMClient.getInstance().sendMessage(conversationtype, targetId, msg, {
		// 发送消息成功
		onSuccess: function (message) {
			//message 为发送的消息对象并且包含服务器返回的消息唯一Id和发送消息时间戳
			console.log("Send successfully");
			if(callback) {
				callback(data);
			}
		},
		onError: function (errorCode,message) {
			var info = '';
			switch (errorCode) {
				case RongIMLib.ErrorCode.TIMEOUT:
					info = '超时';
					break;
				case RongIMLib.ErrorCode.UNKNOWN_ERROR:
					info = '未知错误';
					break;
				case RongIMLib.ErrorCode.REJECTED_BY_BLACKLIST:
					info = '在黑名单中，无法向对方发送消息';
					break;
				case RongIMLib.ErrorCode.NOT_IN_DISCUSSION:
					info = '不在讨论组中';
					break;
				case RongIMLib.ErrorCode.NOT_IN_GROUP:
					info = '不在群组中';
					break;
				case RongIMLib.ErrorCode.NOT_IN_CHATROOM:
					info = '不在聊天室中';
					break;
				default :
					info = errorCode;
					break;
			}
			console.log('发送失败:' + info);
		}
	});
}

// 获取聊天室人员信息
function getRoomInfo(cb, rid) {
	var chatRoomId = rid || g_room;
	var count = 10; // 获取聊天室人数 （范围 0-20 ）
	var order = RongIMLib.GetChatRoomType.REVERSE;// 排序方式。
	RongIMClient.getInstance().getChatRoomInfo(chatRoomId, count, order, {
		onSuccess: function(chatRoom) {
		    // chatRoom => 聊天室信息。
			// chatRoom.userInfos => 返回聊天室成员。
		 	// chatRoom.userTotalNums => 当前聊天室总人数。
		 	var userIDs = [];
		 	chatRoom.userInfos.forEach(function(item) {
		 		userIDs.push(item.id);
		 	});
		 	console.log(userIDs);
			if(cb) {
				cb(userIDs);
			}
		},
		onError: function(error) {
			// 获取聊天室信息失败。
		}
	});
}

function joinRoom(callback, rid) {
	var chatRoomId = rid || g_room; // 聊天室 Id。
	var count = 10;// 拉取最近聊天最多 50 条。
	RongIMClient.getInstance().joinChatRoom(chatRoomId, count, {
  		onSuccess: function() {
       		console.log('加入聊天室成功。');
			if(callback) {
				callback();
			}
  		},
  		onError: function(error) {
    		// 加入聊天室失败
  		}
	});
}

//初始函数
function init(){
	scrollBottom();	//滚动条默认底部
	user_detail(); 	//显示用户详情
}

//显示用户详情
function user_detail(){
	$(".show-user-detail").on("click",function(){
		commonJS.cover();
		$(".diy-user-detail").removeClass("hide");
	});
}

//滚动条默认底部
function scrollBottom(){
	$(".scrollBottom").each(function(){
		this.scrollTop = this.scrollHeight;
	});
}

var commonJS={
	//遮盖层
	cover:function(opa){
		if($(".cover").length) {
			$(".cover").remove();
		} else {
			var opacity = opa || 0.6;
			var html = "<div class='cover' style='opacity:"+opacity+";'></div>";
			$(".cover").remove();
			$("body").append(html);
			$(".cover").click(function(){
				$(".diy-gift , .diy-chat , .diy-user-detail, .diy-cryptolalia").addClass('hide');
				$(this).remove();
				g_chatter_user = null;
			})
		}
	},
	//底部模态弹出框
	modeWindow:function(opt,type){
		opt=$.extend({
			message:'',						//弹出框内容
			type:"alert",					//alert：只有确定按钮|confirm：包括确定和取消按钮
			confirmCallback:function(check){},	//确认按钮的回调函数
			cancelCallback:function(){},		//取消按钮的回调函数
			check:true
		},opt || {});
		
		show();
		
		//显示弹窗
		function show(){
			var html = '';
			html += '<div class="alertBox">';
			html += '<div class="f-mask" style=+"display:block"></div>';
			if(type=='confirm'){
				html += '<div class="f-box1" style="display:block">';
			}else {
				html += '<div class="f-box" style="display:block">';
			}
			html += '<p class="message">' + opt.message + '</p>';

			if(type=='confirm'){
				html +=		"<div class='status check'><em><img src='http://o95rd8icu.bkt.clouddn.com/confirm.png' /></em>不再提示</div>";
			}
			if(opt.type.length > 0){
				if(opt.type == 'confirm'){
					html += '<a class="do-submit no">取消</a>';
					html += '<a class="do-submit yes">确定</a>';
				}else{
					html += '<a class="do-submit yes" style="width:100%;">确定</a>';
				}
			}
			html += '</div></div>';
			$("body").append(html);
			
			$(".alertBox .yes").click(function(){
				destroy();
				opt.confirmCallback(opt.check);
			});

			$(".alertBox .no").click(function(){
				destroy();
				opt.cancelCallback();
			});

			$(".alertBox .status").click(function(){
				if($(".alertBox .status img").length == 1){
					$(".alertBox .status").removeClass("check");
					$(".alertBox .status img").remove();
					opt.check = false;
				}else {
					$(".alertBox .status").addClass("check");
					$(".alertBox .status em").append("<img src='http://o95rd8icu.bkt.clouddn.com/confirm.png' />");
					opt.check = true;
				}
			});
		}
		
		//销毁弹窗
		function destroy(){
			$(".alertBox").remove();
		}
	},
	//普通模态弹出框
	alert:function(msg,callback,time){
		var opt = {
				message : msg,
				type: "alert",
				confirmCallback: callback
		};
		
		commonJS.modeWindow(opt,'alert');
		var time = time>0 ? time : 0;
		if(time!=0){
			setTimeout(function(){
				$(".alertBox").remove();
			},time);
		}
	},
	//确认模态弹出框
	confirm:function(msg, confirmCallback, cancelCallback){
		var opt = {
				message : msg,
				type: "confirm",
				confirmCallback: confirmCallback,	
				cancelCallback: cancelCallback
		};
		
		commonJS.modeWindow(opt,'confirm');
	},
	//模板切换
	diychoose:function(optarg){
		var opt = {
			'chooseobj' : $(".diymenu > a"),
			'diyobj'	: $(".diymodel > div ")
		};
		opt = $.extend(opt,optarg);
		opt.chooseobj.click(function(){
			var eq = $(this).index();
			$(this).addClass("current").siblings(".current").removeClass("current");
			opt.diyobj.eq(eq).removeClass("hide").siblings().addClass("hide");
		});
	}
	
};
