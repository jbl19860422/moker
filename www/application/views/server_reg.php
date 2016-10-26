<!DOCTYPE html>
<html> 
<head>
<!-- 	<base href="<?php  echo base_url();?>"/> -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title>八刻互动注册</title>
    <meta name="description" content="八刻互动">
    <meta name="keywords" content="八刻互动">
	
	<link href="http://cdn.staticfile.org/twitter-bootstrap/3.0.1/css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="http://cdn.staticfile.org/jquery/2.0.0/jquery.min.js"></script>
	<script type="text/javascript" src="http://cdn.staticfile.org/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="http://cdn.staticfile.org/jqueryui-touch-punch/0.2.2/jquery.ui.touch-punch.min.js"></script>
	<script type="text/javascript" src="http://cdn.staticfile.org/twitter-bootstrap/3.0.1/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<div class="row clearfix">
			<div class="col-md-12 column">
				<div role="form">
					<div class="form-group">
						 <img src="<?php echo $headimgurl; ?>" style="width:50px;height:50px;border-radius:100%"></img>
						 <label><?php echo $nickname ?>,您好！</label>
					</div>
					<div class="form-group">
						 <label>真实姓名</label><input type="text" class="form-control" id="real_name" />
					</div>
					
					<div class="form-group">
						 <label>电话</label><input type="text" class="form-control" id="phone" onkeyup="this.value=this.value.replace(/\D/g,'')"/>
					</div>
					
					
					<label>类型：</label>
					<div class="btn-group">
					 <button class="btn btn-default role" data-item="a">歌手</button> 
					 <button data-toggle="dropdown" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>
						<ul class="dropdown-menu">
							<li>
								 <a href="javascript:void(0);" class="role-sel" data-item="a">歌手</a>
							</li>
							<li class="divider"></li>
							<li>
								 <a href="javascript:void(0);" class="role-sel" data-item="s">服务员</a>
							</li>
						</ul>
					</div>
					<p></p>
					
					<div class="form-group">
						 <label>用户名(用户登录后台管理系统)</label><input type="text" class="form-control" id="user_name" />
					</div>
					<div class="form-group">
						 <label for="exampleInputPassword1">密码</label><input type="password" class="form-control" id="pwd1" />
					</div>
					<div class="form-group">
						 <label for="exampleInputPassword1">确认密码</label><input type="password" class="form-control" id="pwd2" />
					</div>
					
					<button type="submit" class="btn btn-default submit" style="width:60%;margin:auto 0;">提交注册</button>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		var bar_id = <?php echo $bar_id; ?>;
		var open_id = "<?php echo $openid; ?>";
		var user_id = <?php echo $user_id;?>;

        $(document).ready(function () {
            $('.dropdown-toggle').dropdown();
        });


        $(".role-sel").click(function() {
        	$(".role").html($(this).html());
        	$(".role").attr("data-item", $(this).attr("data-item"));
        });

        $(".submit").click(function() {
        	var real_name = $("#real_name").val();
        	var phone = $("#phone").val();
        	var user_name = $("#user_name").val();
        	var pwd1 = $("#pwd1").val();
        	var pwd2 = $("#pwd2").val();
        	var role = $(".role").attr("data-item");

        	if(!real_name) {
        		alert("请填写真实姓名！");
        		return;
        	}

        	if(!phone) {
        		alert("请填写手机号码！");
        		return;
        	}

        	if(!user_name) {
        		alert("请填写用户名！");
        		return;
        	}

        	if(!pwd1) {
        		alert("密码为空！");
        		return;
        	}

        	if(pwd1.length < 4) {
        		alert("密码过短！");
        		return;
        	}
        	if(pwd1 != pwd2) {
        		alert("密码不一致！");
        		return;
        	}

        	$.post(
					"http://dream.waimaipu.cn/index.php/user/admin_reg",
					{
						real_name:real_name,
						phone:phone,
						user_name:user_name,
						password:pwd1,
						role:role,
						bar_id:bar_id,
						open_id:open_id,
						user_id:user_id
					},
					function(json) {
						// alert(JSON.stringify(json));
						if(json.code != 0) {//查询失败，后面考虑如何提示(thinklater)
							alert(json.msg);
							return;
						}
						
						
					},
					"json"
			);

        });
   </script>
</body>