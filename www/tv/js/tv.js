var g_socket = null;
var g_singer = null;

// 接入融云 by Integ
RongIMClient.init("pwe86ga5epbz6");		// 初始化。
var RongIMToken = "Xl+P3HjdoJO8Dv67LJpTtCafUS7OOlCmDPx/wanIUbCQQhkeingXFMLJUemt6NTG49aBdYtaac/CVJ0mYVRSuQ==";
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
    }});

 // 消息监听器
 RongIMClient.setOnReceiveMessageListener({
    // 接收到的消息
    onReceived: function (message) {
        // 判断消息类型
        console.log(message);
        switch(message.messageType){
            case RongIMClient.MessageType.TextMessage:
                // 普通消息
                if(message.extra != 'danmu') {
					var messageEl = $(YC.template.getMessage(message));
					YC.params.els.talkSPace.append(messageEl);
					var talks = YC.params.els.talkSPace.find('.talk-item');
					if(talks.length > YC.config.maxTalks){
						talks.eq(0).remove();
					}
					YC.scrollBottom(YC.params.els.talkSPace);
					addNormalMsg(message, false);
				} else {	// 弹幕消息
					var barrageEl = $(YC.template.getBarrage(message));
            		YC.params.els.barrageSpace.append(barrageEl);
            		YC.timeToRemove(barrageEl,YC.config.barrageTime);
            		addBarrageMsg(message, false);
				}
                break;
            case RongIMClient.MessageType.VoiceMessage:
                // 对声音进行预加载                
                // message.content.content 格式为 AMR 格式的 base64 码
                // RongIMLib.RongIMVoice.preLoaded(message.content.content);
                break;
            case RongIMClient.MessageType.ImageMessage:
                // do something...
                break;
            case RongIMClient.MessageType.DiscussionNotificationMessage:
                // do something...
                break;
            case RongIMClient.MessageType.LocationMessage:
                // do something...
                break;
            case RongIMClient.MessageType.RichContentMessage:
                // do something...
                break;
            case RongIMClient.MessageType.DiscussionNotificationMessage:
                // do something...
                break;
            case RongIMClient.MessageType.InformationNotificationMessage:
                // 房间消息
                g_singer = JSON.parse(message.content.extra);
				updateSingerView(g_singer);
                break;
            case RongIMClient.MessageType.ContactNotificationMessage:
                // do something...
                break;
            case RongIMClient.MessageType.ProfileNotificationMessage:
                // do something...
                break;
            case RongIMClient.MessageType.CommandNotificationMessage:
                if(message.name == "AtPerson") {		// @通知消息
					addNormalMsg(message, true);
				} else if(message.name == "Gift") {		// 礼物消息
					showGift(message);
				} else if(message.name == "Singer") {	// 歌手消息(加爱心...)
                	g_singer = message.content.data;
					updateSingerView(g_singer);
                }
                break;
            case RongIMClient.MessageType.CommandMessage:
                // if
                break;
            case RongIMClient.MessageType.UnknownMessage:
                // do something...
                break;
            default:
                // 自定义消息
                // do something...
        }
    }
});

// 连接融云服务器。
RongIMClient.connect(RongIMToken, {
    onSuccess: function(userId) {
        console.log("Login successfully." + userId);
        joinRoom();
        getRoomInfo();
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

function getRoomInfo(rid) {
	var chatRoomId = rid || 'room1';
	var count = 10; // 获取聊天室人数 （范围 0-20 ）
	var order = RongIMLib.GetChatRoomType.REVERSE;// 排序方式。
	RongIMClient.getInstance().getChatRoomInfo(chatRoomId, count, order, {
		onSuccess: function(chatRoom) {
		    // chatRoom => 聊天室信息。
			// chatRoom.userInfos => 返回聊天室成员。
		 	// chatRoom.userTotalNums => 当前聊天室总人数。
		 	
		},
		onError: function(error) {
			// 获取聊天室信息失败。
		}
	});
}

function joinRoom(rid) {
	var chatRoomId = rid || 'room1'; // 聊天室 Id。
	var count = 10;// 拉取最近聊天最多 50 条。
	RongIMClient.getInstance().joinChatRoom(chatRoomId, count, {
  		onSuccess: function() {
       		console.log('加入聊天室成功。');
       		sendBarInfo(g_bar);
  		},
  		onError: function(error) {
    		// 加入聊天室失败
  		}
	});
}

function sendBarInfo(barInfo) {
	// 定义消息类型,文字消息使用 RongIMLib.TextMessage
	var msg = new RongIMLib.InformationNotificationMessage({content:"barInfo", extra:JSON.stringify(barInfo)});
	var conversationtype = RongIMLib.ConversationType.SYSTEM; // 系统消息
	var targetId = "system"; // 目标 Id
	RongIMClient.getInstance().sendMessage(conversationtype, targetId, msg, {
        // 发送消息成功
        onSuccess: function (message) {
            //message 为发送的消息对象并且包含服务器返回的消息唯一Id和发送消息时间戳
            console.log("Send successfully");
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
                    info = x;
                    break;
            }
            console.log('发送失败:' + info);
        }
    });
}

function showGift(obj) {
	var giftEl = $(YC.template.getGift(obj));
	YC.params.els.giftSpace.append(giftEl);
	if(obj.item_count > 1){
        setTimeout(function(){
            giftEl.removeClass('msg-in-out');
            var numEl = giftEl.find('em.gift-num'),num=1,inT;
            numEl.removeClass('bounceIn delay-2'),reNum = function(){
                numEl.removeClass('bounceIn');
                num++;
                if(num > obj.item_count){
                    clearInterval(inT);
                    giftEl.addClass('msg-out');
                    YC.timeToRemove(giftEl, YC.config.giftTime);
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
        YC.timeToRemove(giftEl,YC.config.giftTime);
    }
    YC.scrollBottom(YC.params.els.giftSpace);
}

/*
function initWebSocket() {
	g_socket = io.connect('ws://imoke.live:3000');
	
	g_socket.on('bar_login', function(obj) {
		g_singer = obj.singer;
		updateSingerView(g_singer);
	});
	
	g_socket.emit('bar_login', g_bar);
	
	//礼物消息
	g_socket.on('giftMessage', function(obj) {		
		var giftEl = $(YC.template.getGift(obj));
		YC.params.els.giftSpace.append(giftEl);
		if(obj.item_count > 1){
            setTimeout(function(){
                giftEl.removeClass('msg-in-out');
                var numEl = giftEl.find('em.gift-num'),num=1,inT;
                numEl.removeClass('bounceIn delay-2'),reNum = function(){
                    numEl.removeClass('bounceIn');
                    num++;
                    if(num > obj.item_count){
                        clearInterval(inT);
                        giftEl.addClass('msg-out');
                        YC.timeToRemove(giftEl, YC.config.giftTime);
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
            YC.timeToRemove(giftEl,YC.config.giftTime);
        }
        YC.scrollBottom(YC.params.els.giftSpace);
	});
	//歌手切换消息
	g_socket.on('singerSwitch', function(obj) {
		g_singer = obj;
		updateSingerView(g_singer);
	});
	//爱心值修改消息
	g_socket.on('addlove', function(obj) {
		g_singer = obj;
		updateSingerView(g_singer);
	});
	//普通切换消息
	g_socket.on('message', function(obj) {
		if(obj.type == 'normsg') {
			var messageEl = $(YC.template.getMessage(obj));
			YC.params.els.talkSPace.append(messageEl);
			var talks = YC.params.els.talkSPace.find('.talk-item');
			if(talks.length > YC.config.maxTalks){
				talks.eq(0).remove();
			}
			YC.scrollBottom(YC.params.els.talkSPace);
		} else {
			var barrageEl = $(YC.template.getBarrage(obj));
            YC.params.els.barrageSpace.append(barrageEl);
            YC.timeToRemove(barrageEl,YC.config.barrageTime);
		}
		/*
		if(obj.type == 'normsg') {
			addNormalMsg(obj, false);
		} else {
			addBarrageMsg(obj, false);
		}
		*/
	//});
	//@消息
	/*
	g_socket.on('@message', function(obj) {
		if(obj.type == "normsg") {
			addNormalMsg(obj, true);
		} else {
			addBarrageMsg(obj, true);
		}
	});
}
*/
function addNormalMsg(message, bAtMsg) {
	var msg;
	if(bAtMsg) {
		msg = "@"+message.target_user_nickname+" "+message.content;
	} else {
		msg = message.content;
	}

	$("#ID_userMsg").append('<a href="##" class="list-group-item" style="border:none;background-color: #1b0b3c;"><span class="badge">10</span><span style="color: #f7dd3e;">'+message.nickname+'-第'+message.desk_id+'桌：</span><span style="color: white;">'+message.content+'</span></a>');
	scrollBottom();
}

function scrollBottom() {
	
}

function updateSingerView(singer) {
	if(singer) {
		$("#ID_singerHeadImg").attr('src', singer.headimg);
		$("#ID_singerName").html(singer.nickname);
		$("#ID_love").html(singer.lovecount);
		$("#ID_liveness").html(singer.liveness);
	}
}

window.onload = function() {
	//initWebSocket();
	setInterval(query_all_rank, 4000);
}

function query_all_rank() {
	$.post(
			"http://dream.waimaipu.cn/index.php/user/query_all_rank",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {
					return;
				}
				
				$("#ID_giveGiftRank").html("");
				$("#ID_giveGiftRank").append('<div class="item-title">送礼</div>');
				for(var i = 0; i < json.data_givegift.length && i < 3; i++) {
					var data = json.data_givegift[i];
					$("#ID_giveGiftRank").append(' \
						<div class="item-user"> \
							'+(i+1)+'<img src="'+data.headimg+'" class="item-user-header"/> \
							<div class="item-info-space text-warp"> \
								<p>'+data.nick+'-第二桌</p> \
								<p>'+data.givemoney+'币</p> \
							</div> \
						</div> \
					');
				}
				
				
				$("#ID_gotGiftRank").html("");
				$("#ID_gotGiftRank").append('<div class="item-title">收礼</div>');
				for(var i = 0; i < json.data_gotgift.length && i < 3; i++) {
					var data = json.data_gotgift[i];
					$("#ID_gotGiftRank").append(' \
						<div class="item-user"> \
							'+(i+1)+'<img src="'+data.headimg+'" class="item-user-header"/> \
							<div class="item-info-space text-warp"> \
								<p>'+data.nick+'-第二桌</p> \
								<p>'+data.gotmoney+'币</p> \
							</div> \
						</div> \
					');
				}
				
				$("#ID_giveLove").html("");
				$("#ID_giveLove").append('<div class="item-title">爱心</div>');
				for(var i = 0; i < json.data_givelove.length && i < 3; i++) {
					var data = json.data_givelove[i];
					$("#ID_giveLove").append(' \
						<div class="item-user"> \
							'+(i+1)+'<img src="'+data.headimg+'" class="item-user-header"/> \
							<div class="item-info-space text-warp"> \
								<p>'+data.nick+'-第二桌</p> \
								<p>'+data.givelove+'爱心</p> \
							</div> \
						</div> \
					');
				}
				
				$("#ID_timeRank").html("");
				$("#ID_timeRank").append('<div class="item-title">参与</div>');
				for(var i = 0; i < json.data_online.length && i < 3; i++) {
					var data = json.data_online[i];
					var timeSec = parseInt(data["time"]);
					var timeHour = Math.floor(timeSec/3600);
					var timeMin = Math.floor((timeSec%3600)/60);
					var timeSec = timeSec%60;
					var time = timeHour+'小时'+timeMin+'分钟'+timeSec+'秒';
					$("#ID_timeRank").append(' \
						<div class="item-user"> \
							'+(i+1)+'<img src="'+data.headimg+'" class="item-user-header"/> \
							<div class="item-info-space text-warp"> \
								<p>'+data.nick+'-第二桌</p> \
								<p>'+time+'</p> \
							</div> \
						</div> \
					');
				}
				
			},
			"json"
		);
}

