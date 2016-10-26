var g_socket = null;
var g_singer = null;

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

		// YC.timeToRemove(giftEl, YC.config.giftTime);
		// YC.scrollBottom(YC.params.els.giftSpace);
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
	});
	//@消息
	g_socket.on('@message', function(obj) {
		if(obj.type == "normsg") {
			addNormalMsg(obj, true);
		} else {
			addBarrageMsg(obj, true);
		}
	});
}

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
	initWebSocket();
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

