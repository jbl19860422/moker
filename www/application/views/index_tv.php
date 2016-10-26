<!DOCTYPE html>
<html>
<base href="<?php  echo base_url();?>"/>
	<head>
		<meta charset="utf-8">
		<title></title>
		<link rel="stylesheet" href="application/views/css/index_tv.css" />
		<script type="text/javascript" src="application/views/js/jquery-2.1.1.min.js?v=2016080301"></script>
		<script type="text/javascript" src="application/views/js/bootstrap.js"></script>
		<script type="text/javascript" src="application/views/js/index_tv.js?v=5"></script>
		<script src="http://119.29.10.176/plhwin/node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script>
	</head>
	<style>
		* {
			margin: 0;
			padding: 0;
			list-style: none;
		}
		
		img {
			border-radius: 25px;
			width: 35px;
			margin-left: 30px;
			margin-top: -62px;
		}
		
		#tou {
			width: 150px;
			height: 40px;
			margin-left: 25px;
			margin-top: 20px;
			border-top-left-radius: 25px;
			border-bottom-left-radius: 25px;
			background-color: white;
			opacity: 0.4;
		}
		/*style="opacity:0.8;filter:alpha(opacity=80);background-color:#808080"*/
		
		.div1 {
			padding-left: 30px;
		}
		
		#xing {
			background-color: limegreen;
			width: 40px;
			height: 20px;
			border-radius: 5px;
			margin-left: 130px;
			margin-top: -45px;
		}
		
		#xxt {
			width: 15px;
			margin-left: 6px;
			margin-top: -3px;
		}
		
		#kan {
			color: darkorange;
			font-weight: bold;
			margin-left: 60px;
			margin-top: -80px;
		}
		
		#dadada {
			background-color: black;
		}
		.list-group-item > .badge {
			background-color: #4e9038;
			float: left;
			margin-right: 8px;
		}
	</style>

	<body>
		<div class="container" class="col-lg-12" id="dadada">
			<div class="col-lg-2" class="div1">

				<div id="datou" style="padding-bottom: 100px;">
					<div id="tou"></div>
					<img src="img/headimg.jpg" id="ID_singerHeadImg">
					<p style="color: white;font-weight: bold;font-size: 12px;margin-top: -55px;margin-left: 75px;" id="ID_singerName">悠悠乐队</p>
					<p style="margin-left: 45px;margin-top: -10px;"><span><img src="img/icon-11.png" style="width: 15px;margin-top: -5px;"></span><span style="margin-left:5px" id="ID_love">805</span></p>
					<div id="xing">
						<img src="img/t0129b6a9d44c2723b2.png" id="xxt">
						<span style="color: white;" id="ID_liveness">3</span>
					</div>
				</div>
				<p id="kan">观看数 1025</p>

				<ul style="margin-top: 50px;" id="ID_giftMsg"> 
				<!--
					<li>
						<img src="img/headimg.jpg" style="width: 30px;">
						<p style="color: darkorange;font-size: 12px;margin-left: 70px;margin-top: -55px;">大板-第1桌</p>
						<p style="color: #27A4B0;font-size: 12px;margin-left: 70px;margin-top: -12px;">玫瑰花</p>
						<p style="margin-left: 100px;margin-top: -5px;"><img src="img/icon-6.png" style="width: 20px;"><span style="position:absolute;margin-top: -30px;">X10</span></p>
					</li>
					<li style="margin-top: 20px;">
						<img src="img/headimg.jpg" style="width: 30px;">
						<p style="color: darkorange;font-size: 12px;margin-left: 70px;margin-top: -55px;">大板-第1桌</p>
						<p style="color: #27A4B0;font-size: 12px;margin-left: 70px;margin-top: -12px;">玫瑰花</p>
						<p style="margin-left: 100px;margin-top: -5px;"><img src="img/icon-6.png" style="width: 20px;"><span style="position:absolute;margin-top: -30px;">X10</span></p>
					</li>
					<li style="margin-top: 20px;">
						<img src="img/headimg.jpg" style="width: 30px;">
						<p style="color: darkorange;font-size: 12px;margin-left: 70px;margin-top: -55px;">大板-第1桌</p>
						<p style="color: #27A4B0;font-size: 12px;margin-left: 70px;margin-top: -12px;">玫瑰花</p>
						<p style="margin-left: 100px;margin-top: -5px;"><img src="img/icon-6.png" style="width: 20px;"><span style="position:absolute;margin-top: -30px;">X10</span></p>
					</li>
				-->
				</ul>
			</div>

			<div class="col-lg-7" class="div2">
				<img src="img/201281415193696010.jpg" style="border-radius: 0px;width: 100%; height:820px;border: none;">
				<div class="media">
					<div class="media-body" style="bottom: 100px;color: #f7dd3e;left: 90px;position: absolute;">
						<div style="background-color: #bebec0;border-radius: 26px;width: 180px;height: 34px;margin-left: 5px;">
							<img src="img/headimg.jpg" style="bottom: 98px;left:-30px;position: absolute;"/>
							<div style="margin-left: 32px;padding-top: 7px;">大板-第1集：<span style="color: white;">再来一首</span></div>
						</div>
						<div class="media">
							<div class="media-body">
								<div style="background-color: #bebec0;border-radius: 26px;width: 180px;height: 34px;margin-left: 45px;">
									<img src="img/headimg.jpg" style="bottom: 49px;left:10px;position: absolute;"/>
									<div style="margin-left: 32px;padding-top: 7px;">大板-第1集：<span style="color: white;">再来一首</span></div>
								</div>
								
								<div class="media">
									
									<div class="media-body">
										<div style="background-color: #bebec0;border-radius: 26px;width: 180px;height: 34px;margin-left: 5px;">
											<img src="img/headimg.jpg" style="bottom: 0px;left:-30px;position: absolute;"/>
											<div style="margin-left: 32px;padding-top: 7px;">大板-第1集：<span style="color: white;">再来一首</span></div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="col-lg-3">
				<div class="panel panel-default" style="border:none;background-color: #1b0b3c;">
					<div class="panel-heading" style="border:none;background-color: #1b0b3c;"></div>
					<div class="panel-body" style="padding: 15px 0px;border:none;background-color: #1b0b3c;">
						
						<ul class="list-group" style="float: left;border:none;background-color: #1b0b3c;">
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<button style="padding: 0px 25px;border: none;background-color: #52347e;border-radius: 5px;color: white;">送礼</button>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
						</ul>
						<ul class="list-group" style="float: left;">
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<button style="padding: 0px 25px;border: none;background-color: #52347e;border-radius: 5px;color: white;">送礼</button>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
						</ul>
						<ul class="list-group" style="float: left;">
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<button style="padding: 0px 25px;border: none;background-color: #52347e;border-radius: 5px;color: white;">送礼</button>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
						</ul>
						<ul class="list-group" style="float: left;">
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<button style="padding: 0px 25px;border: none;background-color: #52347e;border-radius: 5px;color: white;">送礼</button>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
							<li class="list-group-item" style="width: 126px;border:none;background-color: #1b0b3c;">
								<span style="margin-right: 30px;color: #f7dd3e;">1</span><span style="color: #f7dd3e;">娜娜-第2桌10000币</span></li>
								<img src="img/icon-11.png" style="margin-left: 28px;margin-top: -50px;position: absolute;width: 25px;z-index: 10;"/>
							</li>
						</ul>
					</div>
						<div class="list-group" id="ID_userMsg">
						    <!--
						    <a href="##" class="list-group-item" style="border:none;background-color: #1b0b3c;"><span class="badge">10</span><span style="color: #f7dd3e;">Anly-第3集：</span><span style="color: white;">很好！</span></a>
						    <a href="##" class="list-group-item" style="border:none;background-color: #1b0b3c;"><span class="badge">3</span><span style="color: #f7dd3e;">Anly-第3集：</span><span style="color: white;">很好！</span></li>
						    <a href="##" class="list-group-item" style="border:none;background-color: #1b0b3c;"><span class="badge">0</span><span style="color: #f7dd3e;">Anly-第3集：</span><span style="color: white;">很好！</span></a>
						    <a href="##" class="list-group-item" style="border:none;background-color: #1b0b3c;"><span class="badge">22</span><span style="color: #f7dd3e;">Anly-第3集：</span><span style="color: white;">很好！</span></a>
							-->
						</div>
					<div class="panel-footer" style="background-color: #1b0b3c;"></div>
				</div>
				
			</div>

		</div>
		<!--<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>-->
		<script type="text/javascript">
			var g_bar = {};
			g_bar.bar_id = <?php echo $bar_id; ?>;
			g_bar.bar_name = "<?php echo $bar_name; ?>";
			g_bar.bar_img = "<?php echo $bar_img; ?>";
		</script>
	</body>

</html>