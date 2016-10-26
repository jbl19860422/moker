/* *
 * 礼物js事件
 */
$(function(){
	//显示礼物模块
	$(".diy-img-gift").on("click",show_gift);

	//礼物：选择事件
	$(".diy-gift li").on("click",choose_gift);

	//礼物：发送事件
	$(".diy-gift .gift-send").on("click",send_gift);

	//礼物：知道了事件
	$(".diy-gift .prompt-box a").on("click",close_prompt);
});
//显示礼物模块
function show_gift(){
	commonJS.cover();
	$(".diy-gift").removeClass("hide");
}

//选择礼物
function choose_gift(){
	$(this).addClass('choose').siblings().removeClass('choose');
}
//发送礼物
function send_gift(){
	if($(".diy-gift .choose").length==0){
		commonJS.alert('请选择赠送的礼物！');
		return;
	}
	var id = $(".diy-gift .choose").attr("data-item");

	//逻辑处理
	//##todo
}
//关闭提示
function close_prompt(){
	$(this).parent().remove();
}
