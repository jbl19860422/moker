var g_socket;
var g_curSelNav;
var g_onlineUsers;

function formatTen(num) { 
	return num > 9 ? (num + "") : ("0" + num); 
} 

function formatDate(date) { 
	var year = date.getFullYear(); 
	var month = date.getMonth(); 
	var day = date.getDate(); 
	var hour = date.getHours(); 
	var minute = date.getMinutes(); 
	var second = date.getSeconds(); 
	return {year:year,month:month,day:day,hour:hour,minute:minute,second:second};
} 

function toUnixTime(obj) {
	var humanDate = new Date(Date.UTC(obj.year,obj.month,obj.day,obj.hour,obj.minute,obj.second)); 
	return (humanDate.getTime()/1000 - 8*60*60); 
}

$(function() {
	initWebSocket();

	queryBarSinger();
	query_activity();

	$(".nav-bar").click(function() {
		$(this).addClass("nav-sel").siblings(".nav-sel").removeClass("nav-sel");

		$(".leftNav").each(function() {
			$(this).addClass("hide");
		});	

		$(".div-content").each(function() {
			$(this).addClass("hide");
		});

		$("#ID_leftNav_"+$(this).attr("nav-id")).removeClass("hide");
		$("#ID_content_"+$(this).attr("nav-id")).removeClass("hide");

		//queryData($(this).attr("nav-id"));
		showContent();
	});

	$(".nav-subbar").click(function() {
		$(this).addClass("nav-subbar-sel").siblings(".nav-subbar-sel").removeClass("nav-subbar-sel");
		if($(this).attr("id") == "verifiedSinger") {
			$("#verifiedSingerTable").removeClass("hide");
			$("#unverifiedSingerTable").addClass("hide");
		} else if($(this).attr("id") == "unverifiedSinger") {
			$("#unverifiedSingerTable").removeClass("hide");
			$("#verifiedSingerTable").addClass("hide");
		} else if($(this).attr("id") == "verifiedServer") {
			$("#verifiedServerTable").removeClass("hide");
			$("#unverifiedServerTable").addClass("hide");
		} else if($(this).attr("id") == "unverifiedServer") {
			$("#verifiedServerTable").addClass("hide");
			$("#unverifiedServerTable").removeClass("hide");
		} else if($(this).attr("id") == "onlineGuest") {
			$("#onlineGuestTable").removeClass("hide");
			$("#offlineGuestTable").addClass("hide");
		} else if($(this).attr("id") == "offlineGuest") {
			$("#onlineGuestTable").addClass("hide");
			$("#offlineGuestTable").removeClass("hide");
		}
	});

	$(".subnav").click(function() {
		$(this).addClass("subnav-sel").siblings(".subnav-sel").removeClass("subnav-sel");

		$(".member-content").each(function() {
			$(this).addClass("hide");
		});

		$("#ID_content_"+$(this).attr("subnav-id")).removeClass("hide");

		showContent();
	});

	$('#ID_acTimeStart').datetimepicker();
	$('#ID_acTimeEnd').datetimepicker();

	$("#ID_updateActivity").click(function() {
		var timeEnd = toUnixTime(formatDate($("#ID_acTimeEnd").datetimepicker('getDate')));
		var timeStart = toUnixTime(formatDate($("#ID_acTimeStart").datetimepicker('getDate')));
		if(timeEnd < timeStart)
		{
			alert("结束时间不能小于开始时间!");
			return;
		}
		$.post(
			"http://dream.waimaipu.cn/index.php/admin/update_activity",
			{
				bar_id:g_bar.bar_id,
				activity_name:$("#ID_activityName").val(),
				activity_start:toUnixTime(formatDate($("#ID_acTimeStart").datetimepicker('getDate'))),
				activity_end:toUnixTime(formatDate($("#ID_acTimeEnd").datetimepicker('getDate'))),
				activity_phoneurl:$("#ID_phoneurl").val(),
				activity_pcurl:$("#ID_pcurl").val()
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				alert("修改成功");
			},
			"json"
		);
	});

	$("#ID_createLive").click(function() {
		var live_name = $("#ID_liveName").val();
		create_live(live_name, function() {
			query_bar_info(function(data) {
				$("#ID_liveName").val(data.live_name);
				$("#ID_pushUrl").val(data.live_push_url);
				if(parseInt(data.live_status) == 0) {
					$("#ID_liveStatus").val("未开始");
				} else {
					$("#ID_liveStatus").val("直播中");
				}
			});
		});
	});

	$("#ID_startLive").click(function() {
		set_live_status(1, function() {
			query_bar_info(function(data) {
				$("#ID_liveName").val(data.live_name);
				$("#ID_pushUrl").val(data.live_push_url);
				if(parseInt(data.live_status) == 0) {
					$("#ID_liveStatus").val("未开始");
				} else {
					$("#ID_liveStatus").val("直播中");
				}
			});
		});
	});

	$("#ID_stopLive").click(function() {
		set_live_status(0, function() {
			query_bar_info(function(data) {
				$("#ID_liveName").val(data.live_name);
				$("#ID_pushUrl").val(data.live_push_url);
				if(parseInt(data.live_status) == 0) {
					$("#ID_liveStatus").val("未开始");
				} else {
					$("#ID_liveStatus").val("直播中");
				}
			});
		});
	});

	query_bar_info(function(data) {
		$("#ID_liveName").val(data.live_name);
		$("#ID_pushUrl").val(data.live_push_url);
		if(parseInt(data.live_status) == 0) {
			$("#ID_liveStatus").val("未开始");
		} else {
			$("#ID_liveStatus").val("直播中");
		}
	});

});

function set_live_status(live_status, succeed_callback) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/set_live_status",
			{
				bar_id:g_bar.bar_id,
				live_status:live_status
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				succeed_callback();
			},
			"json"
	);
}

function TimeToDateStr(timestamp) {
	var dateObj = new Date(timestamp*1000);
    var timeStr = dateObj.getFullYear() + '/' + pad((dateObj.getMonth() +1 ),2) + '/' + 
    					pad(dateObj.getDate(),2)+ ' ' + pad(dateObj.getHours(),2) + ':' + 
    					pad(dateObj.getMinutes(),2);
    return timeStr;
}

function create_live(live_name, succeed_callback) {
	if(live_name == "") {
		alert("请填写正确的直播名称");
		return;
	}

	$.post(
			"http://dream.waimaipu.cn/index.php/admin/create_live",
			{
				bar_id:g_bar.bar_id, 
				live_name:live_name
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				succeed_callback();
			},
			"json"
		);
}

function query_bar_info(succeed_callback) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/query_bar_info",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				succeed_callback(json.data);
			},
			"json"
		);
}

function query_activity() {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/query_activity",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				var actInfo = json.activity_info;
				$("#ID_activityName").val(actInfo.activity_name);
				var timeStart = parseInt(actInfo.activity_start);
				var timeEnd = parseInt(actInfo.activity_end);
				$("#ID_acTimeStart").val(TimeToDateStr(timeStart));
				$("#ID_acTimeEnd").val(TimeToDateStr(timeEnd));
				$("#ID_phoneurl").val(actInfo.activity_phoneurl);
				$("#ID_pcurl").val(actInfo.activity_pcurl);
			},
			"json"
		);
}

function showContent() {
	var nav_id = $(".nav-sel").attr("nav-id");
	var subnav_id = $("#ID_leftNav_"+nav_id+" .subnav-sel").attr("subnav-id");
	show(nav_id, subnav_id);
}

function show(nav_id, subnav_id) {
	if(nav_id == "member") {
		if(subnav_id == "singer") {
			showSinger();
		} else if(subnav_id == "server") {
			showServer();
		} else if(subnav_id == "guest") {
			showGuest();
		} else if(subnav_id == "desk") {
			showDesk();
		}
	} else if(nav_id == "service") {
		if(subnav_id == "singer-switch") {
			querySingerList();
		}
	}
}

function showGuest() {
	$("#onlineGuestTbody").html("");
	g_socket.emit("getOnlineUsers", {
		bar_id:g_bar.bar_id
	});
	queryBarUsers();
}

function showDesk() {
	g_socket.emit("getOnlineUsers", {
		bar_id:g_bar.bar_id
	});
	query_desks();
}

function showServer() {
	$("#verifiedServerTbody").html("");
	$("#unverifiedServerTbody").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/queryBarServer",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				var servers = json.servers;
				for(var i = 0; i < servers.length; i++) {
					if(servers[i].verified == 1) {//审核通过
						$("#verifiedServerTbody").append(
							'<tr class="odd"> \
								<td>'+servers[i].id+'</td> \
								<td class="avatar"><img src="'+servers[i].headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
								<td>'+servers[i].nickname+'</td> \
								<td>'+servers[i].sex+'</td> \
								<td>'+servers[i].realname+'</td> \
								<td>'+servers[i].phone+'</td> \
								<td>在线</td> \
								<td>'+Time2Str(servers[i].reg_time)+'</td> \
								<td> \ \
									<button type="submit" class="red">删除</button> \
								</td> \
							</tr>'
						);
					} else {
						$("#unverifiedServerTbody").append(
							'<tr class="odd"> \
								<td>'+servers[i].id+'</td> \
								<td class="avatar"><img src="'+servers[i].headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
								<td>'+servers[i].nickname+'</td> \
								<td>'+servers[i].sex+'</td> \
								<td>'+servers[i].realname+'</td> \
								<td>'+servers[i].phone+'</td> \
								<td>在线</td> \
								<td>'+Time2Str(servers[i].reg_time)+'</td> \
								<td> \
									<button type="submit" onclick="verifyServer('+servers[i].id+')" class="green">通过</button> \
									<button type="submit" class="red">删除</button> \
								</td> \
							</tr>'
						);
					}
				}

				if($("#verifiedServer").hasClass("nav-subbar-sel")) {
					$("#verifiedServerTable").removeClass("hide");
					$("#unverifiedServerTable").addClass("hide");
				} else {
					$("#unverifiedServerTable").removeClass("hide");
					$("#verifiedServerTable").addClass("hide");
				}
			},
			"json"
		);
}

function verifySinger(id) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/verifySinger",
			{
				bar_id:g_bar.bar_id,
				id:id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				showSinger();
				showServer();
			},
			"json"
		);
}

function verifyServer(id) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/verifyServer",
			{
				bar_id:g_bar.bar_id,
				id:id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				showServer();
			},
			"json"
		);
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

function showSinger() {
	$("#verifiedSingerTbody").html("");
	$("#unverifiedSingerTbody").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/queryBarSinger",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				var singers = json.singers;
				for(var i = 0; i < singers.length; i++) {
					if(singers[i].verified == 1) {//审核通过
						$("#verifiedSingerTbody").append(
							'<tr class="odd"> \
								<td>'+singers[i].id+'</td> \
								<td class="avatar"><img src="'+singers[i].headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
								<td>'+singers[i].nickname+'</td> \
								<td>'+singers[i].sex+'</td> \
								<td>'+singers[i].realname+'</td> \
								<td>'+singers[i].phone+'</td> \
								<td>在线</td> \
								<td>'+Time2Str(singers[i].reg_time)+'</td> \
								<td> \
									<button type="submit" class="red" onclick="delSinger('+singers[i].id+')">删除</button> \
								</td> \
							</tr>'
						);
					} else {
						$("#unverifiedSingerTbody").append(
							'<tr class="odd"> \
								<td>'+singers[i].id+'</td> \
								<td class="avatar"><img src="'+singers[i].headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
								<td>'+singers[i].nickname+'</td> \
								<td>'+singers[i].sex+'</td> \
								<td>'+singers[i].realname+'</td> \
								<td>'+singers[i].phone+'</td> \
								<td>在线</td> \
								<td>'+Time2Str(singers[i].reg_time)+'</td> \
								<td> \
									<button type="submit" onclick="verifySinger('+singers[i].id+')" class="green">通过</button> \
									<button type="submit" class="red">删除</button> \
								</td> \
							</tr>'
						);
					}
				}

				if($("#verifiedSinger").hasClass("nav-subbar-sel")) {
					$("#verifiedSingerTable").removeClass("hide");
					$("#unverifiedSingerTable").addClass("hide");
				} else {
					$("#unverifiedSingerTable").removeClass("hide");
					$("#verifiedSingerTable").addClass("hide");
				}
			},
			"json"
		);
}

function queryData(nav_id) {
	if(nav_id == "member") {
		querySinger();//查询歌手列表
	}
}

function initWebSocket() {
	g_socket = io.connect('ws://imoke.live:3000');
	//发送登录消息给服务器
	g_socket.emit('bar_login', {
			bar_id:g_bar.bar_id,
			bar_img:g_bar.barimg,
			bar_name:g_bar.barname
		});

	g_socket.on('bar_login', function(obj) {
		$("#ID_onlineCount").html(obj.guest.length);
	});

	g_socket.on('querySinger', function(obj) {
		if(obj) {
			$("#ID_curSingerImg").attr('src', obj.headimg);
			$("#ID_curSingerName").html(obj.nickname);
			$("#ID_curSingerLogintime").html(Time2Str(obj.logintime));
		}
	});

	g_socket.on('retOnlineUsers', function(obj) {
		var desk_users = {};
		for(var i = 0;i < obj.guest.length; i++) {
			var guest = obj.guest[i];
			var sex = guest.sex==1?"男":"女";
			$("#onlineGuestTbody").append(
							'<tr class="odd"> \
								<td ><img src="'+guest.headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
								<td>'+guest.nickname+'</td> \
								<td>'+sex+'</td> \
								<td>'+guest.desk_id+'</td> \
								<td>'+Time2Str(guest.logintime)+'</td> \
								<td> \
									<button type="submit" class="green">通过</button> \
									<button type="submit" class="red">删除</button> \
								</td> \
							</tr>'
						);
			if(!desk_users.hasOwnProperty(guest.desk_id)) {
				desk_users[guest.desk_id] = [];
			}

			desk_users[guest.desk_id].push(guest);
		}

		$("#deskUserTbody").html("");
		for(var p in desk_users) {
			var imgs = "";
			for(var i = 0; i < desk_users[p].length; i++) {
				var user = desk_users[p][i];
				imgs += '<img src="'+user.headimg+'" style="width:40px;height:40px;border-radius:100%;"></img>';
			}
		
			$("#deskUserTbody").append(' \
				<tr class="odd"> \
					<td>'+p+'</td> \
					<td>'+imgs+'</td> \
					<td> \
						<button type="submit" class="green">查看</button> \
						<button type="submit" class="none">打印二维码</button> \
						<button type="submit" class="red">删除</button> \
					</td> \
				</tr> \
			');	
		}
	});
}

function query_desks() {
	$("#deskTbody").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/query_desks",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}

				if(json.desks && json.desks.length >= 0) {
					for(var i = 0; i < json.desks.length; i++) {
						var desk = json.desks[i];
						$("#deskTbody").append(' \
							<tr class="odd" id="ID_desk'+desk.desk_id+'"> \
									<td><input type="text" value="'+desk.desk_id+'" onkeyup="this.value=this.value.replace(/\D/g,"")"></input></td> \
									<td><input type="text" value="'+desk.desk_name+'"></input></td> \
									<td><a href="'+desk.qrcode_img+'" target="_blank"><img src="'+desk.qrcode_img+'" style="width:40px;height:40px;"></img></a></td> \
									<td> \
										<button type="submit" class="green" onclick="editDesk('+desk.desk_id+')">修改</button> \
										<button type="submit" class="red" onclick="delDesk('+desk.desk_id+')">删除</button> \
									</td> \
							</tr> \
						');
					}

					$("#deskTbody").append(' \
						<tr> \
							<td><input type="text" id="ID_deskid" value=""></input></td> \
							<td><input type="text" id="ID_deskname" value=""></input></td> \
							<td></td> \
							<td> \
								<button type="submit" class="green" onclick="addDesk();">添加餐桌</button> \
							</td> \
						</tr> \
					');
				} else {
					$("#deskTbody").append(' \
						<tr> \
							<td><input type="text" id="ID_deskid" value=""></input></td> \
							<td><input type="text" id="ID_deskname" value=""></input></td> \
							<td></td> \
							<td> \
								<button type="submit" class="green" onclick="addDesk();">添加餐桌</button> \
							</td> \
						</tr> \
					');
				}
			},
			"json"
		);
}

function querySingerList() {
	g_socket.emit('querySinger', {bar_id:g_bar.bar_id});


	$("#ID_singerTBody").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/queryBarSinger",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				for(var i = 0; i < json.singers.length; i++) {
					$("#ID_singerTBody").append('<tr class="odd"> \
													<td class="avatar">'+json.singers[i].nickname+'</td> \
													<td class="avatar"><img src="'+json.singers[i].headimg+'" style="width:40px;height:40px;border-radius:100%;"></img></td> \
													<td><button type="submit" class="green switch-singer" style="width:80%" user-id="'+json.singers[i].user_id+'" nickname="'+json.singers[i].nickname+'" headimg="'+json.singers[i].headimg+'">切换</button></td> \
												</tr>');
				}

				$(".switch-singer").click(function() {
					g_socket.emit('singerSwitch', {
								user_id:$(this).attr('user-id'),
								bar_id:g_bar.bar_id,
								nickname:$(this).attr('nickname'),
								headimg:$(this).attr('headimg')
							});
					g_socket.emit('querySinger', {bar_id:g_bar.bar_id});
				});
			},
			"json"
		);


}

function editDesk(desk_id) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/update_desk",
			{
				bar_id:g_bar.bar_id,
				desk_id:desk_id,
				new_desk_id:($("#ID_desk"+desk_id+" input")[0]).value,
				desk_name:($("#ID_desk"+desk_id+" input")[1]).value
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				query_desks();
			},
			"json"
		);
}


function delSinger(id) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/delSinger",
			{
				bar_id:g_bar.bar_id,
				id:id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				showSinger();
			},
			"json"
		);
}
function delDesk(desk_id) {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/del_desk",
			{
				bar_id:g_bar.bar_id,
				desk_id:desk_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				query_desks();
			},
			"json"
		);
}

function addDesk() {
	var desk_id = $("#ID_deskid").val();
	var desk_name = $("#ID_deskname").val();
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/add_desk",
			{
				bar_id:g_bar.bar_id,
				desk_id:desk_id,
				desk_name:desk_name
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				query_desks();
			},
			"json"
		);
}

function queryBarUsers() {
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/queryBarUsers",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				$("#offlineGuestTbody").html("");
				for(var i = 0; i < json.users.length; i++) {
					var user = json.users[i];
					var sex = user.sex == 1?"男":"女";
					$("#offlineGuestTbody").append(
								'<tr class="odd"> \
									<td ><img src="'+user.headimg+'" style="width:40px;height:40px;border-radius:100%"></td> \
									<td>'+user.nickname+'</td> \
									<td>'+sex+'</td> \
									<td> \
										<button type="submit" class="green">查看</button> \
									</td> \
								</tr>'
							);
				}

			},
			"json"
		);

}

function queryBarSinger() {
	$("#ID_singerList").html("");
	$("#verifiedSingerTbody").html("");
	$("#unverifiedSingerTbody").html("");
	$.post(
			"http://dream.waimaipu.cn/index.php/admin/queryBarSinger",
			{
				bar_id:g_bar.bar_id
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					return;
				}
				for(var i = 0; i < json.singers.length; i++) {
					$("#ID_singerList").append('<tr class="odd"> \
													<td class="avatar"><img width="40" height="40" style="border-radius:100%" src="'+json.singers[i].headimg+'"></img>'+json.singers[i].nickname+'</td> \
													<td><button type="submit" class="green switch-singer" style="width:80%" user-id="'+json.singers[i].user_id+'" nickname="'+json.singers[i].nickname+'" headimg="'+json.singers[i].headimg+'">切换</button></td> \
												</tr>');
				}

				$(".switch-singer").click(function() {
					g_socket.emit('singerSwitch', {
								user_id:$(this).attr('user-id'),
								bar_id:g_bar.bar_id,
								nickname:$(this).attr('nickname'),
								headimg:$(this).attr('headimg')
							});
				});
			},
			"json"
		);

}
