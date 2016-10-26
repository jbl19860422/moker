var GUI = {
	updateGiveGiftRank : function(data) {
		$("#ID_givegift_rank").html("");
		for(var i = 0; i < data.length; i++) {
			var user = data[i];
			var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
			if(user["role"].indexOf("a") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
			} else if(user["role"].indexOf("s") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
			}
			var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
			if(i <= 2) {
				sort_img += (i+1)+".png";
			}

			var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
			if(user.sex == 2) {
				sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
			}

			if(i <= 2) {
				$("#ID_givegift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
											<span class="sort text-c"><img src="'+sort_img+'" /></span> \
											<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
											<span class="nickname">'+user["nickname"]+'</span> \
											<span style="float:right;margin-right:0.4rem">'+user["givemoney"]+'币</span> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
											</a></li>');
			} else {
				$("#ID_givegift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
											<span class="sort text-c"><img src="'+(i+1)+'" /></span> \
											<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
											<span class="nickname">'+user["nickname"]+'</span> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
											<span style="float:right;margin-right:0.4rem">'+user["givemoney"]+'币</span> \
											</a></li>');
			}
		}
	},

	updateOnlineRank : function(data) {
		$("#ID_online_rank").html("");
		for(var i = 0; i < data.length; i++) {
			var user = data[i];
			var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
			if(!user.role) {
				continue;
			}
			if(user["role"].indexOf("a") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
			} else if(user["role"].indexOf("s") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
			}

			var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
			if(i <= 2) {
				sort_img += (i+1)+".png";
			}

			var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
			if(user.sex == 2) {
				sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
			}

			var timeSec = parseInt(user["time"]);
			var timeHour = Math.floor(timeSec/3600);
			var timeMin = Math.floor((timeSec%3600)/60);
			var timeSec = timeSec%60;
			if(i <= 2) {
				$("#ID_online_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
											<span class="sort text-c"><img src="'+sort_img+'" /></span> \
											<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
											<span class="nickname">'+user["nickname"]+'</span> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
											<span style="float:right;margin-right:0.4rem">'+timeHour+'小时'+timeMin+'分钟'+timeSec+'秒'+'</span> \
											</a></li>');
			} else {
				$("#ID_online_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
											<span class="sort text-c">'+(i+1)+'</span> \
											<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'"/></span> \
											<span class="nickname">'+user["nickname"]+'</span> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
											<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
											<span style="float:right;margin-right:0.4rem">'+timeHour+'小时'+timeMin+'分钟'+timeSec+'秒'+'</span> \
											</a></li>');
			}
		}
	},

	updateGotGiftRank : function(data) {
		$("#ID_gotgift_rank").html("");
		for(var i = 0; i < data.length; i++) {
			var user = data[i];
			var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
			if(user["role"].indexOf("a") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
			} else if(user["role"].indexOf("s") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
			}

			var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
			if(i <= 2) {
				sort_img += (i+1)+".png";
			}

			var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
			if(user.sex == 2) {
				sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
			}

			if(i <= 2){
				$("#ID_gotgift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
					<span class="sort text-c"><img src="'+sort_img+'" /></span> \
					<span class="headimg"><img class="radius-100" src="'+user["headimg"]+'" style="width:0.5rem;height:0.5rem"/></span> \
					<span class="nickname">'+user["nickname"]+'</span> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
					<span style="float:right;margin-right:0.4rem">'+user["gotmoney"]+'币</span> \
				</a></li>');
			} else {
				$("#ID_gotgift_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);"> \
					<span class="sort text-c">'+(i+1)+'</span> \
					<span class="headimg"><img class="radius-100" src="'+user["headimg"]+'" style="width:0.5rem;height:0.5rem"/></span> \
					<span class="nickname">'+user["nickname"]+'</span> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
					<span style="float:right;margin-right:0.4rem">'+user["gotmoney"]+'币</span> \
				</a></li>');
			}
			
		}
	},

	updateGotLoveRank : function(data) {
		$("#ID_gotlove_rank").html("");
		for(var i = 0; i < data.length; i++) {
			var user = data[i];
			var roleImg = "http://o95rd8icu.bkt.clouddn.com/客人.png";
			if(user["role"].indexOf("a") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/歌手.png";
			} else if(user["role"].indexOf("s") > 0) {
				roleImg = "http://o95rd8icu.bkt.clouddn.com/大堂经理.png";
			}

			var sexImg = "http://o95rd8icu.bkt.clouddn.com/男性.png";
			if(user.sex == 2) {
				sexImg = "http://o95rd8icu.bkt.clouddn.com/女性.png";
			}

			var sort_img = 'http://o95rd8icu.bkt.clouddn.com/b';
			if(i <= 2) {
				sort_img += (i+1)+".png";
			}


			if(i <= 2) {
				$("#ID_gotlove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
					<span class="sort text-c"><img src="'+sort_img+'" /></span> \
					<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'" /></span> \
					<span class="nickname">'+user["nickname"]+'</span> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
					<span class="zan">'+user['gotlove']+'</span> \
				</a></li>');
			} else {
				$("#ID_gotlove_rank").append('<li class="padding-top padding-bottom"><a href="javascript:void(0);" data-item="'+user["user_id"]+'"> \
					<span class="sort text-c">'+(i+1)+'</span> \
					<span class="headimg"><img class="radius-100" style="width:0.5rem;height:0.5rem" src="'+user["headimg"]+'" /></span> \
					<span class="nickname">'+user["nickname"]+'</span> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+sexImg+'" /></div> \
					<div style="float:left;margin-left:0.1rem;"><img src="'+roleImg+'" /></div> \
					<span class="zan">'+user['gotlove']+'</span> \
				</a></li>');
			}
		
			//$("#ID_gotlove_rank a").click(addLove);
		}
	}
};