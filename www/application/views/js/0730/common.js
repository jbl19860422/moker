$(function(){
	init();
})

//初始函数
function init(){
	
	scrollBottom();//滚动条默认底部

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
			$(".diy-gift").addClass('hide');
			$(this).remove();
		})
	},
	//底部模态弹出框
	modeWindow:function(opt,type){
		opt=$.extend({
			message:'',						//弹出框内容
			type:"alert",					//alert：只有确定按钮|confirm：包括确定和取消按钮
			confirmCallback:function(){},	//确认按钮的回调函数
			cancelCallback:function(){}		//取消按钮的回调函数
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
				html +=		"<div class='status check'><em></em>不再提示</div>";
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
				opt.confirmCallback();
			});
			$(".alertBox .no").click(function(){
				destroy();
				opt.cancelCallback();
			});
			$(".status").click(function(){
				if($(".status img").length == 1){
					$(".status").removeClass("check");
					$(".status img").remove();
				}else {
					$(".status").addClass("check");
					$(".status em").append("<img src='/statics/images/ico-18.png' />");
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
	}
	
};
