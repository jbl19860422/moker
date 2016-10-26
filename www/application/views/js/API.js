var API = {
	query_user_info:function(user_id, target_user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_user_info",
				{
					user_id:user_id,
					target_user_id:target_user_id
				},
				function(json) {
					if(json.code != 0) {
						return;
					}

					succeed_callback(json.data);
				},
				"json"
		);
	},

	add_love:function(user, count) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/add_love",
				{
					bar_id:BAR.barinfo.bar_id,
					user_id:GUEST.userinfo.user_id,
					target_userid:user.user_id,
					count:count
				},
				function(json) {
					if(json.code != 0) {
						return;
					}
				},
				"json"
		);
	},

	close_barrage_alert:function(user_id) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/close_barrage_alert",
				{
					user_id:user_id
				},
				function(json){
				},
				"json"
			);
	},

	sendGift:function(bar, user, recvUser, iteminfo, succeed_callback) {
		$.post(
			"http://dream.waimaipu.cn/index.php/user/send_gift",
			{
				user_id:user.user_id,
				bar_id:bar.bar_id,
				desk_id:user.desk_id,
				target_userid:recvUser.user_id,
				item_id:iteminfo.item_id,
				item_count:iteminfo.item_count,
				leave_word:'send you a gift'
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					if(json.code == -1003) {
						commonJS.alert("您八客币余额不足，请充值!");
					}
					console.log("API Error:", json);
					return;
				}
				succeed_callback();
			},
			"json"
		);
	},
	//查询赠送礼物排行榜接口
	query_givegift_rank : function(bar_id, user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_givegift_rank", 
				{
					bar_id:bar_id,
					user_id:user_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询在线用户时间排行
	query_online_rank : function(user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_user_online_rank", 
				{
					user_id:user_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询获得礼物排行
	query_gotgift_rank : function(bar_id, user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_gotgift_rank", 
				{
					user_id:user_id,
					bar_id:bar_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询给与爱心排行榜
	query_gotlove_rank :function(bar_id, user_id, succeed_callback) {
		$.post(
			"http://dream.waimaipu.cn/index.php/user/query_gotlove_rank", 
			{
				user_id:user_id,
				bar_id:bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				succeed_callback(json.data);
			},
			"json"
		);
	},
	//查询最新酒吧消息
	query_bar_msg : function(bar_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_bar_msg",
				{
					bar_id:bar_id
				},
				function(json) {
					if(json.code != 0) {
						return;
					}
					succeed_callback(json.data);
				},
				"json"
			);
	},
	//查询最新的系统消息
	query_sys_msg : function(bar_id, succeed_callback) {
		$("#ID_sysMsgs").html("");
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_sys_msg",
				{
					bar_id:bar_id
				},
				function(json) {
					if(json.code != 0) {
						return;
					}
					succeed_callback(json.data);
				},
				"json"
			);
	},
	//查询商家活动
	query_activity : function(bar_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_activity",
				{
					bar_id:bar_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.activity_info);
				},
				"json"
		);
	},
	//查询余额
	query_money : function(user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_money",
				{
					user_id:user_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.money);
				},
				"json"
		);
	},
	//查询接收的礼物
	query_giftrecv : function(user_id, time_start, time_end, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_gift_recv", 
				{
					userid:user_id,
					time_start:time_start,
					time_end:time_end
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					json.data.sort(function(a,b) {
						return b.timestamp - a.timestamp;
					});

					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询发送的礼物
	query_giftsend : function(user_id, time_start, time_end, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_gift_send", 
				{
					user_id:user_id,
					time_start:-1,
					time_end:-1
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					json.data.sort(function(a,b) {
						return b.timestamp - a.timestamp;
					});

					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询用户支付记录
	query_bill : function(user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_bill",
				{
					user_id:user_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}

					json.data.sort(function(a,b) {
						return b.timestamp - a.timestamp;
					});

					succeed_callback(json.data);
				},
				"json"
		);
	},
	//写密语消息
	send_priv_msg : function(bar_id, desk_id, from_user_id, from_nickname, from_headimg, to_user_id, to_nickname, to_headimg, content, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/send_priv_msg",
				{
					bar_id:bar_id,
					desk_id:desk_id,
					from_user_id:from_user_id,
					from_user_nickname:from_nickname,
					from_user_headimg:from_headimg,
					to_user_id:to_user_id,
					to_user_nickname:to_nickname,
					to_user_headimg:to_headimg,
					content:content
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback();
				},
				"json"
		);
	},
	//查询某个用户的未读密语 消息
	query_unviewed_privmsg : function(to_user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_unviewed_privmsg",
				{
					to_user_id:to_user_id
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.data);
				},
				"json"
		);
	},
	//查询某个用户的密语并返回用户基本信息
	query_user_privmsg : function(from_user_id, to_user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_user_privmsg",
				{
					from_user_id:from_user_id,
					to_user_id:to_user_id,
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					succeed_callback(json.user_info, json.records);
				},
				"json"
		);
	},
	//设置某个人的消息已查看
	set_chatrecord_viewed:function(from_user_id, to_user_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/set_chatrecord_viewed",
				{
					from_user_id:from_user_id,
					to_user_id:to_user_id,
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					if(succeed_callback) {
						succeed_callback();
					}
				},
				"json"
		);
	},
	get_rctoken:function(user_id, nickname, headimg) {
		$.post(
				"http://dream.waimaipu.cn/index/user/get_rctoken",
				{
					"user_id":user_id,
					"nickname":nickname,
					"headimg":headimg
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					if(succeed_callback) {
						succeed_callback(json.token);
					}
				},
				"json"
		);
	},
	//查询多个用户信息
	query_users_info : function(user_id, target_user_ids, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/query_users_info",
				{
					"user_id":user_id,
					"target_user_ids":target_user_ids.join("|")
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					if(succeed_callback) {
						succeed_callback(json.data);
					}
				},
				"json"
		);
	},
	//获得酒吧token
	get_bar_rctoken : function(bar_id, succeed_callback) {
		$.post(
				"http://dream.waimaipu.cn/index.php/user/get_bar_rctoken",
				{
					"bar_id":bar_id,
				},
				function(json) {
					if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
						return;
					}
					
					if(succeed_callback) {
						succeed_callback(json.rc_token);
					}
				},
				"json"
		);
	}
};