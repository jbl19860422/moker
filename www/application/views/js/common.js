$(function(){

	init();
})

//初始函数
function init(){
	
	scrollBottom();	//滚动条默认底部
	user_detail(); 	//显示用户详情
}

//显示用户详情
function user_detail(){
	$(".show-user-detail").on("click",function(){
		commonJS.cover();
		$(".diy-user-detail").removeClass("hide");
	});
}

//滚动条默认底部
function scrollBottom(){
	$(".scrollBottom").each(function(){
		this.scrollTop = this.scrollHeight;
	});
}

var commonJS={
	//遮盖层
	cover:function(opa){
		var html = "<div class='cover' style='opacity:"+opa+";'></div>";
		$(".cover").remove();
		$("body").append(html);
		$(".cover").click(function(){
			$(".diy-gift , .diy-chat , .diy-user-detail, .diy-cryptolalia").addClass('hide');
			$(this).remove();
			g_chatter_user = null;
		})
	},
	//底部模态弹出框
	modeWindow:function(opt,type){
		opt=$.extend({
			message:'',						//弹出框内容
			type:"alert",					//alert：只有确定按钮|confirm：包括确定和取消按钮
			confirmCallback:function(check){},	//确认按钮的回调函数
			cancelCallback:function(){},		//取消按钮的回调函数
			check:true
		},opt || {});
		
		show();
		
		//显示弹窗
		function show(){
			var html = '';
			html += '<div class="alertBox">';
			html += '<div class="f-mask" style=+"display:block"></div>';
			if(type=='confirm'){
				html += '<div class="f-box1" style="display:block">';
			}else {
				html += '<div class="f-box" style="display:block">';
			}
			html += '<p class="message">' + opt.message + '</p>';

			if(type=='confirm'){
				html +=		"<div class='status check'><em><img src='http://o95rd8icu.bkt.clouddn.com/confirm.png' /></em>不再提示</div>";
			}
			if(opt.type.length > 0){
				if(opt.type == 'confirm'){
					html += '<a class="do-submit no">取消</a>';
					html += '<a class="do-submit yes">确定</a>';
				}else{
					html += '<a class="do-submit yes" style="width:100%;">确定</a>';
				}
			}
			html += '</div></div>';
			$("body").append(html);
			
			$(".alertBox .yes").click(function(){
				destroy();
				opt.confirmCallback(opt.check);
			});

			$(".alertBox .no").click(function(){
				destroy();
				opt.cancelCallback();
			});

			$(".alertBox .status").click(function(){
				if($(".alertBox .status img").length == 1){
					$(".alertBox .status").removeClass("check");
					$(".alertBox .status img").remove();
					opt.check = false;
				}else {
					$(".alertBox .status").addClass("check");
					$(".alertBox .status em").append("<img src='http://o95rd8icu.bkt.clouddn.com/confirm.png' />");
					opt.check = true;
				}
			});
		}
		
		//销毁弹窗
		function destroy(){
			$(".alertBox").remove();
		}
	},
	//普通模态弹出框
	alert:function(msg,callback,time){
		var opt = {
				message : msg,
				type: "alert",
				confirmCallback: callback
		};
		
		commonJS.modeWindow(opt,'alert');
		var time = time>0 ? time : 0;
		if(time!=0){
			setTimeout(function(){
				$(".alertBox").remove();
			},time);
		}
	},
	//确认模态弹出框
	confirm:function(msg, confirmCallback, cancelCallback){
		var opt = {
				message : msg,
				type: "confirm",
				confirmCallback: confirmCallback,	
				cancelCallback: cancelCallback
		};
		
		commonJS.modeWindow(opt,'confirm');
	},
	//模板切换
	diychoose:function(optarg){
		var opt = {
			'chooseobj' : $(".diymenu > a"),
			'diyobj'	: $(".diymodel > div ")
		};
		opt = $.extend(opt,optarg);
		opt.chooseobj.click(function(){
			var eq = $(this).index();
			$(this).addClass("current").siblings(".current").removeClass("current");
			opt.diyobj.eq(eq).removeClass("hide").siblings().addClass("hide");
		});
	}
	
};
