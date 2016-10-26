/* *
 * 聊天js事件
 */
$(function(){
	//显示聊天模块
	$(".diy-img-chat").on("click",show_chat);

	//聊天：发送事件
	$(".diy-gift .chat-send").on("click",send_chat);

});
//显示聊天模块
function show_chat(){
	commonJS.cover();
	$(".diy-chat").removeClass("hide");
}
