<!DOCTYPE html>
<html lang="">
<head>
	<?php

		// session_start();
		// if(isset($_SESSION['logined']) && $_SESSION['logined'])
		// {
		// 	header("location: http://dream.waimaipu.cn/index.php/admin/dashboard");
		// 	return;
		// }
	?>
	<base href="<?php  echo base_url();?>"/>
	<meta charset="utf-8">
	<title>管理系统</title>
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" href="application/views/admin/css/style.css" media="all" />
	<!--[if IE]><link rel="stylesheet" href="css/ie.css" media="all" /><![endif]-->
</head>
<body class="login">
	<section>
		<h1><strong>后台管理</strong> 系统</h1>
		<div method="link" action="dashboard.html">
			<input type="text" placeholder="user" id="username"/>
			<input type="password" id="password"/>
			<button class="blue">登陆</button>
		</div>
		<p style="display:none"><a href="#">忘记密码?</a></p>
	</section>
<script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
<script type="text/javascript">
// Page load delay by Curtis Henson - http://curtishenson.com/articles/quick-tip-delay-page-loading-with-jquery/
$(function(){
	$('.login button').click(function(e){ 
		alert('login');
		$.post(
			"http://dream.waimaipu.cn/index.php/admin/login", 
			{
				username:$("#username").val(),
				password:$("#password").val()
			},
			function(json) {
				if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
					alert(json.msg);
					return;
				}
				window.location.href = "http://dream.waimaipu.cn/index.php/admin/dashboard?uid="+json.uid;
			},
			"json"
		);
		// window.location.href = "http://dream.waimaipu.cn/index.php/admin/login?username="+$("#username").val()+"&password="+$("#password").val();
	});
	
	$('input').each(function() {

       var default_value = this.value;

       $(this).focus(function(){
               if(this.value == default_value) {
                       this.value = '';
               }
       });

       $(this).blur(function(){
               if(this.value == '') {
                       this.value = default_value;
               }
       });

});
});
</script>
</body>
</html>