/* *
 * 礼物js事件
 */
$(function(){
	//显示礼物模块
	$(".diy-img-gift").on("click", function() {
		if(g_singer) {
			show_gift(g_singer);
		}
	});

	//礼物：选择事件
	$(".diy-gift li").on("click",choose_gift);

	//礼物：发送事件
	$(".diy-gift .gift-send").on("click",send_gift);

	//礼物：知道了事件
	$(".diy-gift .prompt-box a").on("click",close_prompt);

	$(".user-gift").click(function() {
		$(".diy-cryptolalia").addClass("hide");
		show_gift(g_chatter_user);
	});
});
//显示礼物模块
function show_gift(target_user){
	$(".diy-gift ul").html("");
	$(".diy-gift .gift-send").removeClass("gift-selected");
	g_sendgift_user = target_user;
	// alert(target_user.role);
	for(var i = 0; i < items.length; i++) {
		if(target_user.role.indexOf(items[i].type) >= 0) {
			if(items[i].price > 0) {
				$(".diy-gift ul").append('<li data-item="'+items[i].item_id+'"> \
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
			// alert(target_user.role);
		}
	}
	commonJS.cover();
	//礼物：选择事件
	$(".diy-gift li").on("click",choose_gift);

	$(".diy-gift").removeClass("hide");
}

//选择礼物
function choose_gift(){
	$(this).addClass('choose').siblings().removeClass('choose');
	$(".diy-gift .gift-send").addClass("gift-selected");
}
//发送礼物
function send_gift(){
	if($(".diy-gift .choose").length==0){
		commonJS.alert('请选择赠送的礼物！');
		return;
	}
	var item_id = $(".diy-gift .choose").attr("data-item");
	var item_name = $(".diy-gift .choose .item-name").html();
	var item_img = $(".diy-gift .choose img").attr("src");

	if(!g_sendgift_user) {
		commonJS.alert('当前没有歌手表演!');
		return;
	}

	var item;
	for(var i = 0; i < items.length; i++) {
		if(items[i].item_id == item_id) {
			item = items[i];
			break;
		}
	}

	if(item.price > 0) {//普通物品
		$.post(
			"http://dream.waimaipu.cn/index.php/user/send_gift",
			{
				user_id:g_guest.user_id,
				bar_id:g_bar.bar_id,
				desk_id:g_bar.desk_id,
				target_userid:g_sendgift_user.user_id,
				item_id:item_id,
				item_count:1,
				leave_word:'send you a gift'
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					if(json.code == -1003) {
						commonJS.alert("您八客币余额不足，请充值!");
					}
					return;
				}
				
				g_socket.emit('giftMessage', {
					type:'giftMessage',
					bar_id:g_bar.bar_id,//<?php echo $barinfo['bar_id'];?>,
					user_id:g_guest.user_id,//<?php echo $userid;?>,
					nickname:g_guest.nickname,
					headimg:g_guest.headimg,
					desk_id:1,
					
					target_userid:g_sendgift_user.user_id,
					target_nickname:g_sendgift_user.nickname,
					
					item_name:item_name,
					item_count:1,
					item_img:item_img
				});
			},
			"json"
		);
	} else {
		if(item.name == "红包") {//跳转到红包页面
			switchToRedPacket();
		} else if (item.name == "礼券") {//跳转到礼券页面
			
		}
	}
	
}


//关闭提示
function close_prompt(){
	$(this).parent().remove();
}
