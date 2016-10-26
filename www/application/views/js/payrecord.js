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

function splitKeyValue(val, sp1, sp2) {
	var arr = val.split(sp1);
	var kv = {};
	for(var i = 0; i < arr.length; i++) {
		var arr_tmp = arr[i].split(sp2);
		kv[arr_tmp[0]] = arr_tmp[1];
	}
	return kv;
}

var GUI = {
	updateGiftRecv : function(data) {
		$("#ID_giftrecv").html("");
		for(var i = 0;i < data.length; i++) {
			//item_id=2&item_count=1&item_name=红包$item_img=http://dream.waimaipu.cn/img/1.jpg
			var present_info = data[i]['present_info'];
			var infos = splitKeyValue(present_info, '&', '=');
			var timeStr = Time2Str(data[i].timestamp);
			if(infos['type'] == "gift") {
				$("#ID_giftrecv").append('<li> \
										<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+$.cookie('headimg')+'" /></div> \
										<div class="title hd-h3">收到<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
										<div class="title hd-h3">'+infos['item_count']+infos['item_unit']+infos['item_name']+'</em></div> \
										<div class="introl text-gray">'+timeStr+'</div> \
										<div class="status hd-h3 margin-top"><img src="'+infos["item_img"]+'" /></div> \
									</li>');
			} else if(infos['type'] == 'redpacket') {
				$("#ID_giftrecv").append('<li> \
										<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+$.cookie('headimg')+'" /></div> \
										<div class="title hd-h3">收到<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
										<div class="title hd-h3">一个'+infos['bakebi_count']+'币的红包</em></div> \
										<div class="introl text-gray">'+timeStr+'</div> \
										<div class="status hd-h3 margin-top"><img src="'+infos["item_img"]+'" /></div> \
									</li>');
			}
		}
	},

	updateGiftSend : function(data) {
		$("#ID_giftsend").html("");
		for(var i = 0;i < data.length; i++) {
			var present_info = data[i]['present_info'];
			var infos = splitKeyValue(present_info, '&', '=');
			var timeStr = Time2Str(data[i].timestamp);
			if(infos['type'] == "gift") {
				$("#ID_giftsend").append('<li> \
									<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+$.cookie('headimg')+'" /></div> \
									<div class="title hd-h3">送给<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
									<div class="title hd-h3">'+infos['item_count']+infos['item_unit']+infos['item_name']+'</em></div> \
									<div class="introl text-gray">'+timeStr+'</div> \
									<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["target_userimg"]+'" /></div> \
								</li>');
			} else if(infos['type'] == "redpacket") {
				$("#ID_giftsend").append('<li> \
									<div class="headimg fl-l margin-right"><img style="width:0.5rem;height:0.5rem" class="radius-100" src="'+$.cookie('headimg')+'" /></div> \
									<div class="title hd-h3">送给<em class="padding-left hd-h3">'+infos['target_nick']+'</em></div> \
									<div class="title hd-h3">'+'一个'+infos['bakebi_count']+'币的红包'+'</em></div> \
									<div class="introl text-gray">'+timeStr+'</div> \
									<div class="status hd-h3 margin-top"><img class="radius-100" src="'+infos["target_userimg"]+'" /></div> \
								</li>');
			}
		}
	},
	//更新支付记录 
	updatePayBill : function(data) {
		$("#ID_bill").html("");
		for(var i = 0;i < data.length; i++) {
			var order_info = data[i]['order_info'];
			var coin_info = order_info.split("&")[1];
			var coin_count = coin_info.split("=")[1];
			var timeStr = Time2Str(data[i].timestamp);
			if(data[i]['order_status'] == "1") {
				$("#ID_bill").append('<li>'+
										'<div class="title hd-h3">充值'+coin_count+'个八刻币</div>'+
										'<div class="introl text-gray">'+timeStr+'</div>'+
										'<div class="status hd-h3">成功</div>'+
									'</li>');
			}
		}
	}
};