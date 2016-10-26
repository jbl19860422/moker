var g_socket = null;
var g_singer = null;

function initWebSocket() {
	g_socket = io.connect('ws://119.29.10.176:3000');
	
	g_socket.on('bar_login', function(obj) {
		g_singer = obj.singer;
		alert(JSON.stringify(g_singer));
		updateSingerView(g_singer);
	});
	
	g_socket.emit('bar_login', g_bar);
	
	//礼物消息
	g_socket.on('giftMessage', function(obj) {
		$("#ID_giftMsg").append('<li> \
									<img src="'+obj.headimg+'" style="width: 30px;"> \
									<p style="color: darkorange;font-size: 12px;margin-left: 70px;margin-top: -55px;">'+obj.nickname+'---第'+obj.desk_id+'桌</p> \
									<p style="color: #27A4B0;font-size: 12px;margin-left: 70px;margin-top: -12px;">'+obj.item_name+'</p> \
									<p style="margin-left: 100px;margin-top: -5px;"><img src="'+obj.item_img+'" style="width: 20px;"><span style="position:absolute;margin-top: -30px;">X'+obj.item_count+'</span></p> \
								</li>');
								  
		scrollBottom();
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
		alert('addNormalMsg');
		if(obj.type == 'normsg') {
			addNormalMsg(obj, false);
		} else {
			addBarrageMsg(obj, false);
		}
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
	$("#ID_singerHeadImg").attr('src', singer.headimg);
	$("#ID_singerName").html(singer.nickname);
	$("#ID_love").html(singer.lovecount);
	$("#ID_liveness").html(singer.liveness);
}

window.onload = function() {
	initWebSocket();
	setInterval(query_rank, 4000);
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

				
			},
			"json"
	);
}

