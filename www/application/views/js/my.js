var GUI = {
	//获取活动信息
	updateActivity : function(actInfo) {
		var time_start = parseInt(actInfo.activity_start);
		var time_end = parseInt(actInfo.activity_end);
		var cur_time = parseInt(actInfo.cur_time);
		if(time_start < cur_time && cur_time < time_end) {
			$("#ID_activity div").html("HI现场("+actInfo.activity_name+"正在进行)");
			$("#ID_activity").attr('href', actInfo.activity_phoneurl);
		} else {
			$("#ID_activity div").html("无活动");
			$("#ID_activity").attr('href', "javascript:void(0)");
		}
	},
	//更新用户个人信息
	updateUserInfo : function(userInfo) {
		$(".diy-userinfo .zan").html(userInfo.love);
		$(".diy-userinfo .collect").html(userInfo.liveness);
		if(userInfo.sex == 1) {
			$(".diy-userinfo .nickname img").attr('src', "http://o95rd8icu.bkt.clouddn.com/男性.png");
		} else {
			$(".diy-userinfo .nickname img").attr('src', "http://o95rd8icu.bkt.clouddn.com/女性.png");
		}
		$(".page-center .diy-usermsg .money-num").html(userInfo.money);
	}
};