//(bar_id, desk_id, user_id, nickname, headimg, role, sex, bShowBarrageAlert)
var GUEST = {
	info: {
		user_id:null,
		desk_id:null,
		nickname:"",
		role:"",
		sex:""	
	}
	this.desk_id = desk_id;
	this.user_id = user_id;
	this.nickname = nickname;
	this.role = role;
	this.sex = sex;
	this.privMsgs = [];//密语消息
	this.unviewdPrivMsgCunt = 0;//未读密语消息数量

	this.socket = io.connect('ws://imoke.live:3000');
	this.recvUser = null;
	this.bShowBarrageAlert = bShowBarrageAlert;

	var _this = this;
	/*
	* 连接上后，发送login给聊天服务器
	*/
	this.socket.on('connect', function() {
				_this.socket.emit('login', {
					bar_id:bar_id,
					user_id:user_id,
					desk_id:desk_id,
					nickname:nickname,
					headimg:headimg,
					role:role,
					sex:sex
				});
		});
	/*
	* 重新连接上后
	*/
	this.socket.on('reconnect', function() {
		//发送登录消息给服务器
		_this.socket.emit('login', {
			bar_id:bar_id,
			user_id:user_id,
			desk_id:desk_id,
			nickname:nickname,
			headimg:headimg,
			role:role,
			sex:sex
		});
	});

	/*
	* 新用户登录
	*/
	this.socket.on('login', function(obj) {
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

	/*
	* 设置消息或者礼物的接收人,可以设置为null
	*/
	this.setRecvUser = function(recvUser) {
		this.recvUser = recvUser;
	}
	/*
	* 发送普通评论消息
	* @param isBarrage 是否弹幕
	* @param content 消息内容
	*/
	this.sendMessage = function(isBarrage, content) {
		var msgType = this.recvUser?"@message":"message";
		var msgSubType = isBarrage?"danmumsg":"normsg";
		var msg = {
					type:msgSubType,
					user_id:this.user_id,
					bar_id:this.bar_id,
					desk_id:this.desk_id,
					nickname:this.nickname,
					headimg:this.headimg,
					content:content
				};

		if(this.recvUser) {//是向某个目标用户发送的
			msg.targe_user_id = this.recvUser.user_id;
			msg.target_user_nickname = this.recvUser.nickname;
		}

		if(isBarrage) {
			this.consumeCoin(1, function(msg) {
									_this.socket.emit(msgType, msg);
								}, 
								msg
							);
		} else {
			_this.socket.emit(msgType, msg);
		}
		
	}

	/*
	* 发送at消息
	* @param isBarrage 是否弹幕
	* @param recvUser 接收消息用户
	* @param content　消息内容
	*/
	/*
	this.sendAtMessage = function(isBarrage, recvUser, content) {
		var msgType = isBarrage?"danmumsg":"normsg";
		var msg = {
					type:msgType,
					user_id:this.user_id,
					bar_id:this.bar_id,
					desk_id:this.desk_id,
					nickname:this.nickname,
					headimg:this.headimg,
					targe_user_id:recvUser.user_id,
					target_user_nickname:recvUser.nickname,
					content:content
				};
		if(isBarrage) {
			this.consumeCoin(1, function(msg) {
									priv_socket.emit('@message', msg);
								}, 
								msg
							);
		} else {
			priv_socket.emit('@message', msg);
		}
	}
	*/
	/*
	* 消耗陌客币
	* @param count  消耗个数
	* @param callback 成功回调函数(现在一般设置成发送弹幕)
	* @param param 回调参数
	*/
	this.consumeCoin = function(count, callback, param) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/consume_money",
				{
					moneycount:count,
					user_id:this.user_id
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
	}
	/*
	* 发送密语
	* @param recvUser 接收用户
	* @param content 消息内容
	*/
	this.sendPrivMessage = function(recvUser, content) {
		_this.socket.emit('priv_msg', {
								type:'priv_msg',
								user_id:this.user_id,
								bar_id:this.bar_id,
								desk_id:this.desk_id,
								nickname:this.nickname,
								headimg:this.headimg,
								viewed:false,
								target_user_id:recvUser.user_id,
								target_user_nickname:recvUser.nickname,
								content:content
							});
	}

	/*
	* 设置不再显示弹幕alert
	*/
	this.setUnalertBarrage = function() {
		this.bNotAlergAgain = false;
		$.post(
			"http://dream.waimaipu.cn/index.php/user/close_barrage_alert",
			{
				user_id:this.user_id
			},
			function(json){
			},
			"json"
		);
	}
}

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
	}

	/*
	* 隐藏聊天面板
	*/
	hideChatPanel:function() {
		$(".diy-chat").addClass("hide");
	}

	/*
	* 显示密语面板
	* @param recvUser 密语对象
	*/
	showPrivChatPanel:function(recvUser) {
		commonJS.cover();
		if(recvUser) {
			$(".diy-cryptolalia").removeClass("hide");
			$(".diy-cryptolalia .diy-cryp-title").html(g_chatter_user.nickname);
			$(".diy-cryptolalia .diy-cryp-close").attr("src", g_chatter_user.headimg);
			$(".diy-cryp-data").html("");

			for(var i = 0; i < this.privMsgs.length; i++) {
				if(this.privMsgs[i].user_id == recvUser.user_id) {
					for(var j = 0; j < this.privMsgs[i].msgs.length; j++) {
						if(!this.privMsgs[i].msgs[j].viewed) {
							this.privMsgs[i].msgs[j].viewed = true;
							this.unviewdPrivMsgCunt--;
						}

						$(".diy-cryp-data").append('<div class="diy-cryp-item"> \
														<p class="time">20:10</p> \
														<div class="diy-cryp-item"><img class="width-40 height-40 fl-l radius-100 margin-right" src="'+this.privMsgs[i].msgs[j].headimg+'" /><span class="fl-l">'+this.privMsgs[i].msgs[j].content+'</span></div> \
													</div>');
					}
				}
			}
		}
	}

	/*
	* 隐藏密语面板
	*/
	hidePrivChatPanel:function() {
		$(".diy-cryptolalia").addClass("hide");
	}

	/*
	* 加载表情
	*/
	loadBrow:function() {
		var html = "";
		for(var i = 0; i < 30; i++){
			html += '<img src="http://o95rd8icu.bkt.clouddn.com/'+i+'.png" />';
		}
		$(".brow-imgs").html(html);
	}

	/*
	* 显示表情模块
	*/
	showBrow:function() {
		$(".brow-imgs").slideToggle();
	}

	/*
	* 选择表情
	*/
	chooseBrow:function() {
		var clone_html = $(this).clone();
		$(".chat").append(clone_html);
	}
}

function CONTROLLER(bar, guest) {
	//bar_id, desk_id, user_id, nickname, headimg, role, sex, bShowBarrageAlert
	this.guest = new GUEST(bar.bar_id, guest.desk_id, guest.user_id, guest.nickname, guest.headimg, guest.role, guest.sex, guest.bShowBarrageAlert);
	this.bar = bar;
	this.gui.loadBrow();

	var _this = this;
	this.showBrowBtn = $(".brow-btn");
	this.browImgs = $(".brow-imgs img");
	this.showChatPanelBtn = $(".diy-img-chat");
	this.sendMsgBtn = $(".diy-chat .sendmsg-btn");

	this.initAction = function() {
		_this.showBrowBtn.on('click', _this.gui.showBrow);//显示表情
		_this.browImgs.on('click', _this.gui.chooseBrow);//选择表情
		_this.showChatPanelBtn.on('click', function() { //显示聊天面板
			GUI.showChatPanel(_this.guest.recvUser);
		});
		_this.sendMsgBtn.on('click', _this.sendMsg);
	}

	this.sendMsg = function() {
		var chatContent = $(".diy-chat .chat").html();
		var bBarrage = $(".barrage-btn").hasClass("open");//是否弹幕
		if(chatContent != "") {
			if(bBarrage) {
				if(_this.guest.bShowBarrageAlert) {
					commonJS.confirm('本次支付需花费1八刻币，支付吗？', function(bNotAlergAgain) {
						if(bNotAlergAgain) {//不再显示提醒
							_this.guest.setUnalertBarrage();
						}
						_this.guest.sendMessage();
					}, function() {});
				} else {
					_this.guest.sendMessage();
				}
			} else {
				_this.guest.sendMessage();
			}
			GUI.hideChatPanel();//隐藏聊天面板
		}
	}
	
	$(".brow-btn").on("click", _this.gui.showBrow);
	//选择表情
	$(".brow-imgs img").on("click", _this.gui.chooseBrow);
	//显示聊天模块
	$(".diy-img-chat").on("click", function() {
		_this.gui.showChatPanel();
	});
	//发送聊天信息
	$(".diy-chat .sendmsg-btn").on("click", _this.sendMsg);
}

$(function() {
	var controller = new CONTROLLER(g_bar, g_guest);
});