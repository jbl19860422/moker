
g_RAppKey = "x18ywvqf8xdcc";
//(bar_id, desk_id, user_id, nickname, headimg, role, sex, bShowBarrageAlert)
/*************************公共方法begin****************************/
//时间转换
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
// 获取随机颜色 
function getRandomColor() { 
	return "#"+("00000"+((Math.random()*16777215+0.5)>>0).toString(16)).slice(-6); 
} 

/*
* 看门狗单例，超时将回调设置的回调函数
*/
var WatchDog = (function() {
	var _instance;
	function init() {
		return {
			//喂狗
			eat : function() {
				this.lastEatTime = new Date().getTime();
				if(this.eatCount == 0) { //启动
					setTimeout(WatchDog.getInstance().timeoutJudge, this.intervalTime);
				}
				this.eatCount++;
			},
			//判断超时
			timeoutJudge : function() {
				curr_time = new Date().getTime();
				if((curr_time - WatchDog.getInstance().lastEatTime) >= WatchDog.getInstance().maxTimeout && WatchDog.getInstance().eatCount > 0) {
					if(WatchDog.getInstance().timeoutCallback) {
						WatchDog.getInstance().timeoutCallback(WatchDog.getInstance().eatCount, WatchDog.getInstance().param);
					}
					WatchDog.getInstance().eatCount = 0;
				} else {//没超时
					setTimeout(WatchDog.getInstance().timeoutJudge, WatchDog.getInstance().intervalTime);
				}
			},
			setTimeoutCallback : function(timeoutCallback, param) {
				this.timeoutCallback  = timeoutCallback;
				this.param = param;
			},
			timeoutCallback:null,
			param:null,
			eatCount:0,
			lastEatTime:0,
			intervalTime:200,//检测间隔(ms)
			maxTimeout:500 //超时间隔(ms)
		}
	}

	return {
		getInstance:function() {
			if(!_instance) {
				_instance = init();
			}
			return _instance;
		}
	}
})();
/*
* 连发礼物判断器，只在最后一次发送礼物请求，避免多次请求
*/
var GiftRepeator = {
	tickTime: 100,
	lastClickTime:0,
	maxTime: 1000,
	detecting:false,
	clickedCount:0,
	lastItemId:-1,
	changeGift:function(item_id) {
		if(lastItemId != -1 && lastItemId != item_id) {
			GiftRepeator.detecting = false;
			return true;
		}
		return false;
	},
	startDetectTick:function(item_id, startCallback, restartCallback, timeoutCallback) {
		if(!GiftRepeator.detecting) {
			GiftRepeator.detecting = true;
			GiftRepeator.timeoutFun = timeoutCallback;
			GiftRepeator.clickedCount = 1;
			GiftRepeator.lastClickTime = new Date().getTime();
			setTimeout(GiftRepeator.tickFunction, GiftRepeator.tickTime);
			startCallback();
			GiftRepeator.lastItemId = item_id;
		} else {
			GiftRepeator.clickedCount++;
			GiftRepeator.lastClickTime = new Date().getTime();
			restartCallback();
		}
	},
	tickFunction:function() {
		var now = new Date().getTime(),disTime = now - GiftRepeator.lastClickTime;
		if(disTime < GiftRepeator.maxTime) {
			setTimeout(GiftRepeator.tickFunction, GiftRepeator.tickTime);
			$(".gift-send").html("连发<em>+"+GiftRepeator.clickedCount+"</em>("+(GiftRepeator.maxTime-disTime)+")");
		} else {
			if(GiftRepeator.detecting) {
				GiftRepeator.detecting = false;
				GiftRepeator.timeoutFun();
			}
		}
	}
};

/***********************公共方法over************************/

var BAR = {
	barinfo:{
		bar_id:null,
		bar_name:"",
		bar_img:"",
		bar_singer:null,
		bar_onlineUsers:[] //在线用户信息
	},

	init:function(bar_id, bar_name, bar_img, bar_singer) {
		BAR.barinfo.bar_id = bar_id;
		BAR.barinfo.bar_name = bar_name;
		BAR.barinfo.bar_img = bar_img;
		BAR.barinfo.bar_singer = bar_singer;
		GUI.updateSinger(bar_singer);
	},

	setSinger:function(singer) {
		BAR.barinfo.bar_singer = singer;
		GUI.updateSinger(singer);
	},

	setOnlineUsers:function() {
		getRoomInfo(function(userIDs) {
			var onlineUsers = new Array();
			API.query_users_info(GUEST.userinfo.user_id, userIDs, function(data) {
				BAR.barinfo.onlineUsers = data;
				GUI.updateOnlineUsers(BAR.barinfo.onlineUsers);
			});
			// userIDs.forEach(function(id, index) {
			// 	API.query_user_info(g_guest.user_id, id, function(info) {	
			// 		onlineUsers.push(info);
			// 		if(onlineUsers.length === userIDs.length) {
			// 			BAR.barinfo.onlineUsers = onlineUsers;
			// 			GUI.updateOnlineUsers(onlineUsers);
			// 		}	
			// 	});
			// });
		});//更新在线人数
	}
};

var GUEST = {
	userinfo: {
		user_id:null,
		desk_id:null,
		nickname:"",
		headimg:"",
		role:"",
		sex:"",
		bShowBarrageAlert:true	
	},

	recvUser:null,
	rongSocket:null,
	/*
	* 初始化
	*/
	init:function(user_id, desk_id, nickname, headimg, role, sex, bShowBarrageAlert, rc_token) {
		GUEST.userinfo.user_id = user_id;
		GUEST.userinfo.desk_id = desk_id;
		GUEST.userinfo.nickname = nickname;
		GUEST.userinfo.role = role;
		GUEST.userinfo.headimg = headimg;
		GUEST.userinfo.sex = sex;
		GUEST.userinfo.bShowBarrageAlert = bShowBarrageAlert;
		GUEST.rc_token = rc_token;
		GUEST.rongSocket = {emit: rongCloudEmit, on: rongCloudOn, messageHandler:{}}

		function rongCloudOn(type, callback) {
			GUEST.rongSocket.messageHandler[type] = callback;
		}

		//rongSocket 消息
		/*GUEST.rongSocket.on('connect', function() {
			GUEST.rongSocket.emit('login', {
				bar_id:BAR.barinfo.bar_id,
				user_id:GUEST.userinfo.user_id,
				desk_id:GUEST.userinfo.desk_id,
				nickname:GUEST.userinfo.nickname,
				headimg:GUEST.userinfo.headimg,
				role:GUEST.userinfo.role,
				sex:GUEST.userinfo.sex
			});
		});*/

		/*
		* 重新连接上后
		*/
		/*GUEST.rongSocket.on('reconnect', function() {
			//发送登录消息给服务器
			GUEST.rongSocket.emit('login', {
				bar_id:BAR.bar_id,
				user_id:GUEST.user_id,
				desk_id:GUEST.desk_id,
				nickname:GUEST.nickname,
				headimg:GUEST.headimg,
				role:GUEST.role,
				sex:GUEST.sex
			});
		});*/

		/*
		* 新用户登录
		*/
		GUEST.rongSocket.on('login', function(obj) {
			BAR.setOnlineUsers();
			// if(obj.singer) {//如果有歌手信息
			// 	obj.singer.role = 'a';
			// 	BAR.setSinger(obj.singer);
			// } else {
			// 	GUI.updateBarView();
			// }
			// if(obj.user) {
				GUEST.getLoginUser(obj);
			// }
		});

		/*
		*	消息
		*/
		GUEST.rongSocket.on('message', function(obj) {
			if(obj.type == 'normsg') {
				GUI.addNormalMsg(obj, false);
			} else {
				GUI.addBarrageMsg(obj, false);
			}
		});

		GUEST.rongSocket.on('@message', function(obj) {
			if(obj.type == 'normsg') {
				GUI.addNormalMsg(obj, true);
			} else {
				GUI.addBarrageMsg(obj, true);
			}
		});

		/*
		* 歌手切换
		*/
		GUEST.rongSocket.on('singerSwitch', function(obj) {
			obj.role = 'a';
			GUI.updateSinger(obj);
		});
		/*
		*  爱心值增加
		*/
		GUEST.rongSocket.on('addlove_msg', function(obj) {
			GUI.addLoveMsg(obj);
		});

		//爱心值修改消息
		GUEST.rongSocket.on('addlove', function(obj) {
			if(BAR.barinfo.bar_singer) {
				BAR.barinfo.bar_singer.love = parseInt(BAR.barinfo.bar_singer.love)+parseInt(obj.count);
			}
			GUI.updateSinger(BAR.barinfo.bar_singer);
		});
		//礼物消息
		GUEST.rongSocket.on('giftMessage', function(obj) {
			GUI.addGiftMessage(obj);
		});
		//密语消息
		GUEST.rongSocket.on('priv_msg', function(message) {
			//放到对应的消息数组中
			if(GUEST.recvUser && GUI.isShowingPrivChatPanel()) {//当前正显示密语聊天窗口时
				if(message.from_user_id == GUEST.recvUser.user_id) {//发送给对方的消息
					//填充消息
	    			var timeStr = Time2Str(message.timestamp);
					$(".diy-cryp-data").append(
										'<div class="diy-cryp-item"> \
											<p class="time">'+timeStr+'</p> \
											<div class="diy-cryp-item"> \
												<img class="width-40 height-40 fl-l radius-100 margin-right" src="'+message.from_user_headimg+'" /> \
												<span class="fl-l">'+message.content+'</span> \
												</div> \
										</div>');
					//设置消息已经查看过，并更新为查看信息数量
					API.set_chatrecord_viewed(message.from_user_id, message.to_user_id, function() {
						API.query_unviewed_privmsg(GUEST.userinfo.user_id, function(data) {
						GUEST.unviewdPrivMsgCount = data.length;
						$("#ID_msg_count").html(GUEST.unviewdPrivMsgCount);
					});});
				} else if(message.from_user_id == GUEST.userinfo.user_id) {//自己发送给别人的消息，自己收到
					var timeStr = Time2Str(message.timestamp);
					$(".diy-cryp-data").append(
										'<div class="diy-cryp-item"> \
											<p class="time">'+timeStr+'</p> \
											<div class="diy-cryp-item"> \
												<img class="width-40 height-40 fl-r radius-100 margin-left" src="'+message.from_user_headimg+'" /> \
												<span class="fl-r">'+message.content+'</span> \
											</div> \
										</div>');
				} else {
					GUEST.addToPrivMsg(message);
					GUEST.unviewdPrivMsgCount++;
				}
			}
			else {//否则，增加未读消息个数
				GUEST.addToPrivMsg(message);
				GUEST.unviewdPrivMsgCount++;
			}

			$("#ID_msg_count").html(GUEST.unviewdPrivMsgCount);
			scrollBottom();
		});

		// 接入融云 by Integ
		g_RAppKey = g_RAppKey || "pwe86ga5epbz6";
		// g_RToken = GUEST. || "Xl+P3HjdoJO8Dv67LJpTtCafUS7OOlCmDPx/wanIUbCQQhkeingXFMLJUemt6NTG49aBdYtaac/CVJ0mYVRSuQ==";
		rongCloudInit(g_RAppKey, GUEST.rc_token, GUEST.rongSocket.messageHandler, BAR.setOnlineUsers);
	},
	//将收到的密语消息放入privMsgs中，以便查找
	addToPrivMsg : function(message) {
		var bFind = false;
		for(var i = 0;i < GUEST.privMsgs.length; i++) {
			if(GUEST.privMsgs[i].from_user_id == message.from_user_id) {//该用户的密语消息已经存在，则添加
				GUEST.privMsgs[i].msgs.push(message);//默认新的密语消息在老的后面
				if(GUEST.privMsgs[i].newest_time < message.timestamp) {
					GUEST.privMsgs[i].newest_time = message.timestamp;
				}
				bFind = true;
				break;
			}
		}

		if(!bFind) {	//如果还没有该用户的密语消息，则新增
			GUEST.privMsgs.push({
				from_user_id:message.from_user_id,
				msgs:[message],
				newest_time:message.timestamp
			});
		}
		//对密语消息进行重新排序
		GUEST.privMsgs.sort(function(a,b) {
			return b.newest_time - a.newest_time;
		});
	},
	//删除某个用户的密语，以便 重新拉取时方便
	delFromPrivMsg : function(from_user_id) {
		for(var i = 0; i < GUEST.privMsgs.length; i++) {
			if(GUEST.privMsgs[i].from_user_id == from_user_id) {
				GUEST.privMsgs.splice(i, 1);
				break;
			}
		}
	},

	privMsgs: [],			//密语消息
	unviewdPrivMsgCount:0,	//未读密语消息数量
	action:{
		/*
		* 发送普通评论消息
		* @param isBarrage 是否弹幕
		* @param content 消息内容
		*/
		sendMessage:function(isBarrage, content) {
			var msgType = GUEST.recvUser?"@message":"message";
			var msgSubType = isBarrage?"danmumsg":"normsg";
			var msg = {
						type:msgSubType,
						user_id:GUEST.userinfo.user_id,
						bar_id:BAR.barinfo.bar_id,
						desk_id:GUEST.userinfo.desk_id,
						nickname:GUEST.userinfo.nickname,
						headimg:GUEST.userinfo.headimg,
						content:content
					};

			if(GUEST.recvUser) {//是向某个目标用户发送的
				msg.targe_user_id = GUEST.recvUser.user_id;
				msg.target_user_nickname = GUEST.recvUser.nickname;
			}

			if(isBarrage) {
				GUEST.action.consumeCoin(1, function(msg) {
										GUEST.rongSocket.emit(msgType, msg, GUEST.rongSocket.messageHandler[msgType]);
									}, 
									msg
								);
			} else {
				GUEST.rongSocket.emit(msgType, msg, GUEST.rongSocket.messageHandler[msgType]);
			}
		},

		/*
		* 消耗陌客币
		* @param count  消耗个数
		* @param callback 成功回调函数(现在一般设置成发送弹幕)
		* @param param 回调参数
		*/
		consumeCoin:function(count, callback, param) {
			$.post(
					"http://dream.waimaipu.cn/index.php/user/consume_money",
					{
						moneycount:count,
						user_id:GUEST.userinfo.user_id
					},
					function(json){
						if(json.code == -1003) {
							commonJS.alert('您余额不足！');
						} else if(json.code == 0) {
							callback(param);
						} else {
							commonJS.alert('系统繁忙！请稍候再试！');
						}
					},
					"json"
				);
		},

		/*
		* 发送密语
		* @param recvUser 接收用户
		* @param content 消息内容
		*/
		sendPrivMessage:function(recvUser, content) {
			API.send_priv_msg(
								BAR.barinfo.bar_id,
								GUEST.userinfo.desk_id,
								GUEST.userinfo.user_id, 
								GUEST.userinfo.nickname,
								GUEST.userinfo.headimg,
								recvUser.user_id,
								recvUser.nickname,
								recvUser.headimg,
								content, 
								function() {
										GUEST.rongSocket.emit('priv_msg', {
																type:'priv_msg',
																bar_id:BAR.barinfo.bar_id,
																desk_id:GUEST.userinfo.desk_id,
																from_user_id:GUEST.userinfo.user_id,
																from_user_nickname:GUEST.userinfo.nickname,
																from_user_headimg:GUEST.userinfo.headimg,
																to_user_id:recvUser.user_id,
																to_user_nickname:recvUser.nickname,
																to_user_headimg:recvUser.headimg,
																viewed:false,
																timestamp: Math.round(Date.now()/1e3),
																content:content
															}, GUEST.rongSocket.messageHandler.priv_msg, recvUser.user_id);
							});
		},

		/*
		* 设置不再显示弹幕alert
		*/
		setUnalertBarrage:function() {
			GUEST.userinfo.bNotAlergAgain = false;
			API.close_barrage_alert(GUEST.userinfo.user_id);
		},
		/*
		* 发送歌手爱心增加消息,将更新歌手的爱心值
		*/
		sendSingerAddLoveMsg: function(count) {
			GUEST.rongSocket.emit("addlove", {
				bar_id:BAR.barinfo.bar_id,
				count:count
			}, GUEST.rongSocket.messageHandler['addlove']);
		},
		/*
		* 发送爱心值增加消息，将显示在普通评论区
		*/
		sendLoveAddMsg: function(recvUser, count) {
			GUEST.rongSocket.emit('addlove_msg', {
				bar_id:BAR.barinfo.bar_id,
				user_id:recvUser.user_id,
				count:count,
				desk_id:GUEST.userinfo.desk_id,
				nickname:GUEST.userinfo.nickname,
				headimg:recvUser.headimg,
				target_userid:recvUser.user_id,
				target_nickname:recvUser.nickname
			},GUEST.rongSocket.messageHandler['addlove_msg']);
		}
	},

	getLoginUser:function(user) {
		GUI.addLoginMsg(user);
	},

	setRecvUser:function(recvUser) {
		GUEST.recvUser = recvUser;
	},
};

var YC = {/*本地控制版本号*/
    version: '1.0.0',
    /*基本配置*/
    config: {
        giftTime: 6000,  /*礼物删除时间*/
        barrageTime: 10000, /*弹幕删除时间*/
        maxTalks: 100,    /*聊天消息最多显示多少条*/
        barrageNum: 0,  /*弹幕消息轨道，从0开始*/
        maxbarrageNum: 3    /*弹幕消息轨道最大值,允许最大值9条,当前5条*/
    }
};

var GUI = {
	/*
	* 显示底部聊天面板
	* @param recvUser 如果是和歌手或者某个客人聊天，则该参数不为null
	*/
	showChatPanel:function(recvUser) {
		commonJS.cover();
		if(recvUser) {
			$(".diy-chat .chat").html("");
			$(".diy-chat .user").html("@"+recvUser.nickname);
			$(".diy-chat").attr("user-id", recvUser.user_id);
		} else {
			$(".diy-chat .chat").html("");
			$(".diy-chat .user").html("");
			$(".diy-chat").attr("user-id", "");
		}
		$(".diy-chat").removeClass("hide");
	},

	/*
	* 隐藏聊天面板
	*/
	hideChatPanel:function() {
		$(".diy-chat").addClass("hide");
	},
	/*
	* 显示密语面板
	*/
	showPrivChatPanel:function(recvUser) {
		if(recvUser.user_id == GUEST.userinfo.user_id) {//不能和自己密语
			return;
		}
		commonJS.cover();
		if(recvUser) {
			$(".diy-cryptolalia").removeClass("hide");
			$(".diy-cryptolalia .diy-cryp-title").html(recvUser.nickname);
			$(".diy-cryptolalia .diy-cryp-close").attr("src", recvUser.headimg);
			$(".diy-cryp-data").html("");
			var showMsgs = [];//要展示的消息数组
			for(var i = 0; i < GUEST.privMsgs.length; i++) {
				if(GUEST.privMsgs[i].from_user_id == recvUser.user_id) {
					for(var j = 0; j < GUEST.privMsgs[i].msgs.length; j++) {
						if(!GUEST.privMsgs[i].msgs[j].viewed) {
							GUEST.privMsgs[i].msgs[j].viewed = true;
							GUEST.unviewdPrivMsgCount--;
						}
						showMsgs.push(GUEST.privMsgs[i].msgs[j]);
					}
				} else if(GUEST.privMsgs[i].from_user_id == GUEST.userinfo.user_id) { //自己发出的消息,看看接收者是不是对方
					for(var j = 0; j < GUEST.privMsgs[i].msgs.length; j++) {
						if(GUEST.privMsgs[i].msgs[j].to_user_id == recvUser.user_id) {
							showMsgs.push(GUEST.privMsgs[i].msgs[j]);
						}
					}
				}
			}

			$("#ID_msg_count").html(GUEST.unviewdPrivMsgCount);

			//按时间先后排序
			showMsgs.sort(function(a,b) {
				return a.timestamp - b.timestamp;
			});
			//显示
			var bSetViewed = false;
			for(var i = 0; i < showMsgs.length; i++) {
				var message = showMsgs[i];
				if(message.from_user_id == recvUser.user_id) {//是对方发送来的
					var timeStr = Time2Str(message.timestamp);
					$(".diy-cryp-data").append('<div class="diy-cryp-item"> \
													<p class="time">'+timeStr+'</p> \
													<div class="diy-cryp-item"><img class="width-40 height-40 fl-l radius-100 margin-right" src="'+message.from_user_headimg+'" /><span class="fl-l">'+message.content+'</span></div> \
												</div>');
					//如果有对方发送来的消息，则全部置为已经查看
					if(!bSetViewed) {
						API.set_chatrecord_viewed(message.from_user_id, message.to_user_id, null);
						bSetViewed = true;
					}
					
				} else {//否则是我发送给对方的
					var timeStr = Time2Str(message.timestamp);
					$(".diy-cryp-data").append('<div class="diy-cryp-item"> \
													<p class="time">'+timeStr+'</p> \
													<div class="diy-cryp-item"> \
														<img class="width-40 height-40 fl-r radius-100 margin-right" src="'+message.from_user_headimg+'" /> \
														<span class="fl-r">'+message.content+'</span> \
													</div> \
												</div>');
				}
			}
			scrollBottom();
		}
	},
	/*
	* 隐藏密语面板
	*/
	hidePrivChatPanel:function() {
		$(".diy-cryptolalia").addClass("hide");
	},
	//是否正显示密语聊天面板
	isShowingPrivChatPanel:function() {
		return !($(".diy-cryptolalia").hasClass('hide'));
	},

	/*
	* 加载表情
	*/
	loadBrow:function() {
		var html = "";
		for(var i = 0; i < 30; i++){
			html += '<img src="http://o95rd8icu.bkt.clouddn.com/'+i+'.png" />';
		}
		$(".brow-imgs").html(html);
	},

	/*
	* 显示表情模块
	*/
	showBrow:function() {
		$(".brow-imgs").slideToggle();
	},

	/*
	* 选择表情
	*/
	chooseBrow:function() {
		var clone_html = $(this).clone();
		$(".chat").append(clone_html);
	},
	/*
	* 更新歌手信息
	*/
	updateSinger:function(singer) {
		$(".diy-user img").attr("src", singer.headimg);
		$(".diy-user .uname").html(singer.nick);
		$(".diy-user .collect").html(singer.liveness);
		$(".diy-user .zan").html(singer.love);
		$(".diy-user .collect").show();
		$(".diy-user .zan").show();
		$(".diy-user .audio").show();
	},

	/*
	* 更新在线人数
	*/
	updateOnlineUsers:function(onlineUsers) {
		$(".diy-online-user a").html(onlineUsers.length);
		$(".diy-online-imgs div").html("");
		for(var i = 0; i < onlineUsers.length; i++) {
			$(".diy-online-imgs div").append('<img class="radius-100 width-25 height-25" index="'+i+'" user-id="'+onlineUsers[i].user_id+'" src="'+onlineUsers[i].headimg+'" />');
		}

		$(".diy-online-imgs div img").click(function() {
			var index = parseInt($(this).attr("index"));
			GUI.showUserInfo(onlineUsers[index]);
		});
	},

	updateBarView:function() {
		$(".diy-user img").attr("src", BAR.barinfo.bar_img);
		$(".diy-user .uname").html(BAR.barinfo.bar_name);
		$(".diy-user .collect").hide();
		$(".diy-user .zan").hide();
		$(".diy-user .audio").hide();
	},

	/*
	* 显示某个用户的信息
	*/
	showUserInfo:function(user) {
		if(user) {
            var userId = user.user_id || user;  //可以直接传入 userID
			commonJS.cover();
			API.query_user_info(GUEST.userinfo.user_id, userId, function(data) {
				var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
				if(data.role.indexOf("a") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
				} else if(data.role.indexOf("s") > 0) {
					roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
				}		
				$(".diy-user-detail .audio").attr('src', roleImg);
				$(".diy-user-detail .zan em").html(data.love);
				$(".diy-user-detail .headimg").attr('src', data.headimg);
				$(".diy-user-detail .nickname").html(data.nickname);
				$(".diy-user-detail").attr("user-id", data.user_id);
				$(".diy-user-detail .collect").html(data.liveness);
				$(".diy-user-detail").removeClass("hide");
                GUEST.setRecvUser(data);
			});
		}
	},
	//隐藏用户信息
	hideUserInfo : function() {
		$(".diy-user-detail").addClass("hide");
	},
	//添加普通评论消息
	addNormalMsg:function(message, bAtMsg) {
		var msg;
		if(bAtMsg) {
			msg = "@"+message.target_user_nickname+" "+message.content;
		} else {
			msg = message.content;
		}

		$(".diy-msg").append('<li class="margin-bottom user-message animated bounceInLeft" msg-id="'+message.message_id+'" user-id="'+
								message.user_id+'" onclick="GUI.showUserInfo('+message.user_id+')">'+
								message.nickname+'-第'+message.desk_id+'桌：<em class="text-white">'+
								msg+'</em></li>');
		scrollBottom();
	},
	//添加弹幕消息
	addBarrageMsg:function(message, bAtMsg) {
		var msg;
		if(bAtMsg) {
			msg = "@"+message.target_user_nickname+" "+message.content;
		} else {
			msg = message.content;
		}

		var barrageNumClass = '';
	    if(YC.config.barrageNum >= YC.config.maxbarrageNum){
	        YC.config.barrageNum = 0;
	    }
	    barrageNumClass = YC.config.barrageNum?'p'+YC.config.barrageNum:'';
	    YC.config.barrageNum++;
	    var html = '<div class="barrage-item barrage-in-out '+barrageNumClass+'">' +
	                    '<img src="'+message.headimg+'" class="barrage-header-img"/>'+
	                    '<span class="text-yew">'+message.nickname+'-第'+message.desk_id+'桌：</span>' +
	                    '<span class="text-white">'+msg+'</span>'+
	               '</div>';
	    var barrageSpace = $('.barrage-space');
	    barrageSpace.append(html);
		scrollBottom();
	},
	//添加登陆消息
	addLoginMsg:function(user) {
		$(".diy-msg").append('<li class="margin-bottom animated bounceInLeft">'+user.nickname+'-第'+user.desk_id+'桌 上线了</li>');
		scrollBottom();
	},
	//添加礼物消息
	addGiftMessage: function(obj) {
		var addEl = $('<li class="diy-sys-3 margin-bottom user-message msg-in-out" onclick="GUI.showUserInfo('+obj.user_id+')"> \
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
	},
	//开关弹幕按钮
	switchBarrage:function() {
		if($(".barrage-btn").hasClass("open")) {
			$(".barrage-btn").removeClass("open");
			$(".barrage-btn div").css("float", "right");
		} else {
			$(".barrage-btn").addClass("open");
			$(".barrage-btn div").css("float", "");
		}
	},
	//添加增加爱心消息
	addLoveMsg:function(obj) {
		$(".diy-msg").append('<li class="margin-bottom user-message animated bounceInLeft" user-id="'+obj.user_id+'">'+obj.nickname+'-第'+obj.desk_id+'桌：<em class="text-white">'+"给"+obj.target_nickname+"加了"+obj.count+"个爱心"+'</em></li>');
		$(".user-message").click(function() {
			GUI.showUserInfo(obj);
		});
		scrollBottom();
	},

	//生成点赞动画
	genLoveCartoon:function() {
		var bubble = document.getElementById("bubble_div");
		var color = getRandomColor();
		var svg = document.createElementNS('http://www.w3.org/2000/svg','svg'); 
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
			if(svg.dirLeft == 1) {
				svg.right = svg.right - 0.02;
				if(svg.right <= 0.04) {
					svg.dirLeft = 0;
				}
			}
			else {
				svg.right = svg.right + 0.02;
				if(svg.right >= 0.5) {
					svg.dirLeft = 1;
				}
			}
			
			svg.setAttribute("style", "right:" + svg.right+"rem;bottom:" + svg.bottom+"rem;opacity:"+svg.opacity+"; width: 0.32rem; height: 0.32rem;");
			if(obj.opacity <= 0.1) {
				clearInterval(sh);
				bubble.removeChild(svg);
			}
		}, time);
	},

	//显示礼物面板
	showGiftPanel : function() {
		if(!GUEST.recvUser) {
			return;
		}
		$(".diy-cryptolalia").addClass("hide");
		$(".diy-gift ul").html("");
		$(".diy-gift .gift-send").removeClass("gift-selected");
		for(var i = 0; i < items.length; i++) {
			if(GUEST.recvUser.role.indexOf(items[i].type) >= 0) {
				if(items[i].price > 0) {
					$(".diy-gift ul").append('<li data-item="'+items[i].item_id+'" '+(items[i].gif?('data-gif="'+items[i].gif+'"'):'')+'> \
											<div class="img"> \
												<img src="'+items[i].img+'" class="'+items[i].dir+'"/> \
											</div> \
											<span>'+items[i].price+'币</span> \
											<span class="item-name">'+items[i].name+'</span> \
										</li>');
				}
				else {
					$(".diy-gift ul").append('<li data-item="'+items[i].item_id+'"> \
											<div class="img"> \
												<img src="'+items[i].img+'" class="'+items[i].dir+'"/> \
											</div> \
											<span class="item-name">'+items[i].name+'</span> \
										</li>');
				}
			} else {
			}
		}
		commonJS.cover();
		//礼物：选择事件
		$(".diy-gift li").on("click", GUI.chooseGift);
		$(".diy-gift").removeClass("hide");
	},
	//选择礼物处理
	chooseGift : function() {
		function switchSrc(el){
			var gifSrc = $(el).attr('data-gif');
			var imgEl = $(el).find('img');
			if(gifSrc){
				$(el).attr('data-gif',imgEl.attr('src'));
				imgEl.attr('src',gifSrc);
			} else {
				imgEl.toggleClass('animated pulse infinite');
			}
		}

		if(!$(this).hasClass('choose')) {
			var preChose = $(".diy-gift li.choose");
			preChose.removeClass('choose');
			switchSrc(preChose);
			$(this).addClass('choose');
			switchSrc(this);
		}
		$(".diy-gift .gift-send").addClass("gift-selected");
	},
	//关闭礼物提示
	closePrompt : function() {
		$(this).parent().remove();
	}
}

/************************爱心值添加器*******************/
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
			API.add_love({user_id:target_user_id}, adder.loveCount);
			var isSinger = false;//目标是否是歌手
			var target_user = null;
			if(BAR.barinfo.bar_singer && target_user_id == BAR.barinfo.bar_singer.user_id) {
				isSinger = true;
				target_user = BAR.barinfo.bar_singer;
			} else {
				for(var i = 0; i < BAR.barinfo.bar_onlineUsers.length; i++) {
					if(BAR.barinfo.bar_onlineUsers[i].user_id == target_user_id) {
						target_user = g_onlineUsers[i];
					}
				}
			}

			if(target_user) {
				if(isSinger) {
					GUEST.action.sendSingerAddLoveMsg(adder.loveCount);
				}

				GUEST.action.sendLoveAddMsg(target_user, adder.loveCount);
			}

			clearInterval(sh);
			delete g_arrLoveAdder[target_user_id];
		} 
	}, 200);
}
/*********************爱心值添加器结束*******************/
function CONTROLLER() {
	this.showBrowBtn = $(".brow-btn");
	this.browImgs = $(".brow-imgs img");
	this.showChatPanelBtn = $(".diy-img-chat");
	this.sendMsgBtn = $(".diy-chat .sendmsg-btn");
	var _this = this;
	this.sendMsg = function() {
		var chatContent = $(".diy-chat .chat").html();
		var bBarrage = $(".barrage-btn").hasClass("open");//是否弹幕
		if(chatContent != "") {
			if(bBarrage) {
				if(GUEST.userinfo.bShowBarrageAlert) {
					commonJS.confirm('本次支付需花费1八刻币，支付吗？', function(bNotAlergAgain) {
						if(bNotAlergAgain) {//不再显示提醒
							GUEST.action.setUnalertBarrage();
						}
						GUEST.action.sendMessage(bBarrage, chatContent);
					}, function() {});
				} else {
					GUEST.action.sendMessage(bBarrage, chatContent);
				}
			} else {
				GUEST.action.sendMessage(bBarrage, chatContent);
			}
			GUI.hideChatPanel();//隐藏聊天面板
		}
	};

	this.addSingerLove = function() {
		GUI.genLoveCartoon();
		if(BAR.barinfo.bar_singer) {//y有歌手时
			var wd = WatchDog.getInstance();
			wd.setTimeoutCallback(function(count, param) {
				API.add_love(BAR.barinfo.bar_singer, count);
				GUEST.action.sendSingerAddLoveMsg(count);
				GUEST.action.sendLoveAddMsg(BAR.barinfo.bar_singer, count);
			},null);
			wd.eat();
		}
	};

	this.sendGift = function() {
		if($(".diy-gift .choose").length==0){
			commonJS.alert('请选择赠送的礼物！');
			return;
		}
		var item_id = $(".diy-gift .choose").attr("data-item");
		var item_name = $(".diy-gift .choose .item-name").html();
		var item_img = $(".diy-gift .choose img").attr("src");
		var item;
		for(var i = 0; i < items.length; i++) {
			if(items[i].item_id == item_id) {
				item = items[i];
				break;
			}
		}

		if(item.price > 0) {//普通物品
			if(item.repeatable) {//可连发
				GiftRepeator.startDetectTick(item.item_id,function() {//第一次点击时触发调用
					$(".gift-send").css("background-color","red");
				},function() {//连续点击时会触发该调用
					
				},function() {
					//后台发送礼物，成功后发送聊天室 信息
					API.sendGift(BAR.barinfo, GUEST.userinfo, GUEST.recvUser, {item_id:item_id, item_count:GiftRepeator.clickedCount}, function() {
						GUEST.rongSocket.emit('giftMessage', {
									type:'giftMessage',
									bar_id:BAR.barinfo.bar_id,//<?php echo $barinfo['bar_id'];?>,
									user_id:GUEST.userinfo.user_id,//<?php echo $userid;?>,
									nickname:GUEST.userinfo.nickname,
									headimg:GUEST.userinfo.headimg,
									desk_id:GUEST.userinfo.desk_id,
									
									target_userid:GUEST.recvUser.user_id,
									target_nickname:GUEST.recvUser.nickname,
									
									item_id:item.item_id,
									item_name:item_name,
									item_count:GiftRepeator.clickedCount,
									item_img:item_img
								}, GUEST.rongSocket.messageHandler['giftMessage']);
					});
					$(".gift-send").removeAttr('style').html('发送');
				});
			} else {
				//无连击动作的
				API.sendGift(BAR.barinfo, GUEST.userinfo, GUEST.recvUser, {item_id:item_id, item_count:1}, function() {
						GUEST.rongSocket.emit('giftMessage', {
									type:'giftMessage',
									bar_id:BAR.barinfo.bar_id,//<?php echo $barinfo['bar_id'];?>,
									user_id:GUEST.userinfo.user_id,//<?php echo $userid;?>,
									nickname:GUEST.userinfo.nickname,
									headimg:GUEST.userinfo.headimg,
									desk_id:GUEST.userinfo.desk_id,
									
									target_userid:GUEST.recvUser.user_id,
									target_nickname:GUEST.recvUser.nickname,
									
									item_id:item.item_id,
									item_name:item_name,
									item_count:1,
									item_img:item_img
								}, GUEST.rongSocket.messageHandler['giftMessage']);
					});
				$(".gift-send").removeAttr('style').html('发送');
			}
		} else {
			if(item.name == "红包") {//跳转到红包页面
				//switchToRedPacket();
			} else if (item.name == "礼券") {//跳转到礼券页面
				
			}
		}
	};

	$(".brow-btn").click(GUI.showBrow);
	//选择表情
	$(".brow-imgs img").click(GUI.chooseBrow);
	//显示聊天模块
	$(".diy-img-chat").click(function() {
		GUEST.setRecvUser(null);
		GUI.showChatPanel();
	});
	//发送聊天信息
	$(".diy-chat .sendmsg-btn").click(_this.sendMsg);
	//弹幕开关
	$(".barrage-btn").on("click", GUI.switchBarrage);
	//增加歌手爱心值
	$(".diy-zan-btn").click(_this.addSingerLove);
	//打开歌手礼物面板
	$(".diy-img-gift").click(function() {
		GUEST.setRecvUser(BAR.barinfo.bar_singer);
		GUI.showGiftPanel();
	});
	//礼物：知道了事件
	$(".diy-gift .prompt-box a").click(GUI.closePrompt);
	//礼物：发送事件
	$(".diy-gift .gift-send").click(function() {
		GUEST.setRecvUser(BAR.barinfo.bar_singer);
		if(!GUEST.recvUser) {
			commonJS.alert('当前没有歌手表演!');
			return;
		}
		_this.sendGift();
	});
	//个人信息面板，点击礼物事件
	$(".diy-user-detail .gift").click(function() {
		$(".diy-user-detail ").addClass("hide");
		GUI.showGiftPanel(GUEST.recvUser);
	});
	//个人信息面板，加爱心值
	$(".diy-user-detail .zan").click(function() {
		var tmp = parseInt($(".diy-user-detail .zan em").html());
		tmp++;
		$(".diy-user-detail .zan em").html(tmp);
		var target_user_id = $(".diy-user-detail").attr("user-id");
		if(!g_arrLoveAdder.hasOwnProperty(target_user_id)) {
			g_arrLoveAdder[target_user_id] = new LoveAdder(target_user_id, $(this));
		} else {
			g_arrLoveAdder[target_user_id].clickTime = new Date().getTime();
			g_arrLoveAdder[target_user_id].loveCount++;
		}
	});
	//个人信息面板，@ta
	$(".diy-user-detail .ta").click(function() {
		GUI.hideUserInfo();
		GUI.showChatPanel(GUEST.recvUser);
	});
	//排行榜
	$(".diy-img-trophy").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/rank";
	});
	//我的
	$(".diy-img-user").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/my"
	});
	//密语按钮显示密语面板
	$(".diy-user-detail .priv").click(function() {
		GUI.hideUserInfo();
		GUI.showPrivChatPanel(GUEST.recvUser);//显示用户密语面板
	});
	//发送密语消息
	$(".diy-cryptolalia .sendmsg-btn").click(function() {
		GUEST.action.sendPrivMessage(GUEST.recvUser, $(".cryptolalia-chat").html());
		$(".cryptolalia-chat").html('')	
	});
	//充值
	$(".buy-coin").click(function() {
		window.location.href = "http://dream.waimaipu.cn/index.php/user/paypage";
	});
}

$(function() {
	GUI.loadBrow();//加载表情
	//查询未读密语消息
	API.query_unviewed_privmsg(GUEST.userinfo.user_id, function(data) {
		GUEST.unviewdPrivMsgCount = data.length;
		$("#ID_msg_count").html(GUEST.unviewdPrivMsgCount);
		//是否显示和某个用户的密语
		if($.cookie('privmsg_user') && $.cookie('privmsg_user')!="null") {
			API.query_user_privmsg(GUEST.userinfo.user_id, $.cookie('privmsg_user'), function(userinfo, records) {
				$.cookie("privmsg_user",null,{path:"/"});//cookie设置为无效
				GUEST.setRecvUser(userinfo);
				for(var i = 0; i < records.length; i++) {
					GUEST.addToPrivMsg(records[i]);
				}
				GUI.showPrivChatPanel(GUEST.recvUser);
			});
		}
	});  
	
	var controller = new CONTROLLER();
});