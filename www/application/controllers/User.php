<?php
	//require_once("errcode.php");
	require_once(APPPATH."controllers/errcode.php");
	require_once(APPPATH."libraries/WxPay.Data.php");
	require_once(APPPATH."libraries/WxPay.Api.php");
	require_once(APPPATH."libraries/WxPay.JsApiPay.php");
	require_once(APPPATH."libraries/jssdk.php");
	require_once(APPPATH."libraries/WxApi.php");

	class User extends CI_Controller {

		private $privateKey = "1234567812345678";  
		private $iv     = "1234567812345678"; 
		public function __construct()
		{
			parent::__construct();
			$this->load->model('bar_model');
			$this->load->model('user_model');
			$this->load->model('order_model');
			$this->load->model('money_model');
			$this->load->model('item_model');
			$this->load->model('admin_model');
			$this->load->model('barserver_model');
			$this->load->model('baruser_model');
			$this->load->model('chatrecord_model');
			$this->load->model('barlives_model');
			$this->load->model('useritem_model');
			$this->load->model('userpresentinfo_model');

			$this->load->helper('url_helper');
			$this->load->library('https');
			$this->load->library('aes');

			//$this->load->library('wxapi');
		}

		public function login_web()
		{
			$openid = $_REQUEST['open_id'];
			$user_info = $this->user_model->get_user_by_openid($openid);
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$role = $_REQUEST['role'];

			$data['headimgurl'] = $user_info['headimg'];
			$data['nickname'] = $user_info['nick'];
			$data['sex'] = $user_info['sex'];
			$data['barinfo'] = $this->bar_model->get_barinfo($bar_id);

			$data['user_id'] = $user_info['user_id'];
			$data['desk_id'] = $desk_id;
			$data['role'] = $role;
			$data['barrage_alert'] = $user_info['barrage_alert'];
			setcookie("user_id", $data['user_id'], time()+3600*24);
			setcookie("bar_id", $bar_id, time() + 3600*24);
			$this->load->view('index', $data);
		}

		public function login_web3()
		{
			$db_name = 'loginkey';
			$bNeedCheckLogin = false;
			$redis_connected = false;
			$redis = new Redis();
			// if(isset($_COOKIE['user_id']))
			// {
			// 	$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			// 	$redis_connected = true;
			// 	if(!$redis->hExists($db_name, $_COOKIE['user_id']))
			// 	{
			// 		$bNeedCheckLogin = true;
			// 	}
			// 	else
			// 	{
			// 		if(!isset($_COOKIE['loginkey']))
			// 		{
			// 			$bNeedCheckLogin = true;
			// 		}
			// 		else
			// 		{
			// 			$loginkey = $redis->hGet($db_name, $_COOKIE['user_id']);
			// 			if($loginkey != $_COOKIE['loginkey'])
			// 			{
			// 				$bNeedCheckLogin = true;
			// 			}
			// 			else
			// 			{
			// 				$bNeedCheckLogin = false;
			// 			}
			// 		}
			// 	}
			// }
			// else
			// {
			// 	$bNeedCheckLogin = true;
			// }

			$user_id = $_REQUEST['user_id'];
			//$openid = $_REQUEST['open_id'];
			$user_info = $this->user_model->get_user_by_userid($user_id);
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$role = $_REQUEST['role'];

			$data['headimgurl'] = $user_info['headimg'];
			$data['nickname'] = $user_info['nickname'];
			$data['sex'] = $user_info['sex'];
			$data['barinfo'] = $this->bar_model->get_barinfo($bar_id);

			$data['user_id'] = $user_info['user_id'];
			$data['desk_id'] = $desk_id;
			$data['role'] = $role;
			$data['barrage_alert'] = $user_info['barrage_alert'];


			setcookie("user_id", $data['user_id'], time()+3600*24);
			setcookie("bar_id", $bar_id, time() + 3600*24);
			setcookie("desk_id", $desk_id, time() + 3600*24);
			$loginkey = $this->getRandomStr(8);
			setcookie('loginkey', $loginkey);
			setcookie('headimg', $user_info['headimg']);
			setcookie('nickname', $user_info['nickname']);
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}
			// //记录登陆key

			if($retCode)
			{
				$redis->hSet($db_name, $user_info['user_id'], $loginkey);
			}

			if(isset($_GET['privmsg_user']))
			{
				setcookie('privmsg_user', $_REQUEST['privmsg_user']);
			}
			else
			{
				if(isset($_COOKIE['privmsg_user']))
				{
					setcookie('privmsg_user', "");
				}
			}
			$this->load->view('index_new', $data);
		}

		public function login_web_rong()
		{
			$db_name = 'loginkey';
			$bNeedCheckLogin = false;
			$redis_connected = false;
			$redis = new Redis();
			
			$user_id = $_REQUEST['user_id'];
			$user_info = $this->user_model->get_user_by_userid($user_id);
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$role = $_REQUEST['role'];

			$data['headimgurl'] = $user_info['headimg'];
			$data['nickname'] = $user_info['nickname'];
			$data['sex'] = $user_info['sex'];
			$data['barinfo'] = $this->bar_model->get_barinfo($bar_id);
			$singer_info = null;

			if($data['barinfo']['singer_id'] != "" && $data['barinfo']['singer_id'] != "null" && $data['barinfo']['singer_id'] != -1)
			{
				$singer_info = $this->user_model->get_user_by_userid($data['barinfo']['singer_id']);
			}

			
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}

			if($singer_info)
			{
				$singerlove = $redis->hget('love_db_name', 'user'.$singer_info['user_id']);
				if($singerlove == false)
				{
					$singer_info['love'] = 0;
				}
				else
				{
					$singer_info['love'] = $singerlove;
				}
			}
			$singer_info['liveness'] = 4;
			$data['singer_info'] = $singer_info;

			$data['user_id'] = $user_info['user_id'];
			$data['desk_id'] = $desk_id;
			$data['role'] = $role;
			$data['barrage_alert'] = $user_info['barrage_alert'];


			setcookie("user_id", $data['user_id'], time()+3600*24);
			setcookie("bar_id", $bar_id, time() + 3600*24);
			setcookie("desk_id", $desk_id, time() + 3600*24);
			$loginkey = $this->getRandomStr(8);
			setcookie('loginkey', $loginkey);
			setcookie('headimg', $user_info['headimg']);
			setcookie('nickname', $user_info['nickname']);
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}
			// //记录登陆key

			if($retCode)
			{
				$redis->hSet($db_name, $user_info['user_id'], $loginkey);
				$redis_connected = true;
			}

			if(isset($_GET['privmsg_user']))
			{
				setcookie('privmsg_user', $_REQUEST['privmsg_user']);
			}
			else
			{
				if(isset($_COOKIE['privmsg_user']))
				{
					setcookie('privmsg_user', "");
				}
			}

			//获取融云token
			$user_id = $user_info['user_id'];
			$nickname = $user_info['nickname'];
			$headimg = $user_info['headimg'];

			$data_rc = 'userId='.$user_id.'&name='.$nickname.'&portraitUri='.$headimg;

			$timestamp = time();
			$appkey = 'x18ywvqf8xdcc';
			$appsecret = 'IlKQIFBSPS';
			$nonce = rand();
			$singnature = sha1($appsecret.$nonce.$timestamp);
			$headers = array(
					'App-Key:'.$appkey,
					'Nonce:'.$nonce,
					'Timestamp:'.$timestamp,
					'Signature:'.$singnature,
					'Content-Type: application/x-www-form-urlencoded',
				);

			$url = 'https://api.cn.ronghub.com/user/getToken.json';
			$ret_json = $this->https->https_request($url, $data_rc, $headers);
			$data_json = json_decode($ret_json, true);
			if($data_json['code'] != "200") 
			{
				show_404();
				return;
			}
			$data['rc_token'] = $data_json['token'];
			$this->load->view('index_rong', $data);
		}

		public function get_bar_rctoken()
		{
			//获取融云token
			$bar_id = $_REQUEST['bar_id'];
			$barinfo = $this->bar_model->get_barinfo($bar_id);
			$bar_name = $barinfo['name'];
			$bar_img = $barinfo['barimg'];

			$data_rc = 'userId=bar'.$bar_id.'&name='.$bar_name.'&portraitUri='.$bar_img;

			$timestamp = time();
			$appkey = 'x18ywvqf8xdcc';
			$appsecret = 'IlKQIFBSPS';
			$nonce = rand();
			$singnature = sha1($appsecret.$nonce.$timestamp);
			$headers = array(
					'App-Key:'.$appkey,
					'Nonce:'.$nonce,
					'Timestamp:'.$timestamp,
					'Signature:'.$singnature,
					'Content-Type: application/x-www-form-urlencoded',
				);

			$url = 'https://api.cn.ronghub.com/user/getToken.json';
			$ret_json = $this->https->https_request($url, $data_rc, $headers);
			$data_json = json_decode($ret_json, true);
			$ret['code'] = 0;
			$ret['rc_token'] = $data_json['token'];
			echo json_encode($ret);
		}


		public function query_user_privmsg()
		{
			$from_user_id = $_REQUEST['from_user_id'];
			$to_user_id = $_REQUEST['to_user_id'];
			$from_userinfo = $this->user_model->get_user_by_userid($to_user_id);
			$records = $this->chatrecord_model->query_user_chatrecord($from_user_id, $to_user_id);
			$ret['user_info'] = $from_userinfo;
			$ret['records'] = $records;
			$ret['code'] = 0;
			echo json_encode($ret);
		}

		public function set_chatrecord_viewed()
		{
			$from_user_id = $_REQUEST['from_user_id'];
			$to_user_id = $_REQUEST['to_user_id'];
			$retCode = $this->chatrecord_model->set_chatrecord_viewed($from_user_id, $to_user_id);
			if($retCode)
			{
				$ret['code'] = 0;
			}
			else
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
			}
			echo json_encode($ret);
		}

		public function my()
		{
			$this->load->view('my', null);
		}

		public function barmsg()
		{
			$this->load->view('barmsg', null);
		}

		public function paypage()
		{
			$this->load->view('paypage', null);
		}

		public function payreordpage()
		{
			$this->load->view('payrecordpage', null);
		}

		public function sysmsg()
		{
			$this->load->view('sysmsg', null);
		}

		public function alluser()
		{
			$this->load->view('alluser', null);
		}

		public function rank() 
		{
			//校验登陆态
			if(!isset($_COOKIE['user_id']) || !isset($_COOKIE['loginkey']))
			{
				//echo $_COOKIE['user_id']."&".$_COOKIE['loginkey'];
				show_404();
				return;
			}
			$user_id = $_COOKIE['user_id'];
			$loginkey = $_COOKIE['loginkey'];
			$bar_id = $_COOKIE['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				show_404();
				return;
			}
			$db_name = 'loginkey';
			if(!$redis->hExists($db_name, $user_id))
			{
				//show_404();
				echo 'aaa';
				return;
			}

			$loginkey_db = $redis->hGet($db_name, $user_id);
			if($loginkey_db != $loginkey)
			{
				echo $user_id." ".$loginkey_db."  ".$loginkey;

				//show_404();
				return;
			}
			$data['bar_id'] = $bar_id;
			$data['user_id'] = $user_id;
			$this->load->view('rank', $data);
		}

		public function login3()
		{			
			if(!isset($_GET['code']))
			{
				show_404();
				return;
			}

			$bNeedCheckLogin = false;
			$redis_connected = false;
			$loginkey_db = 'loginkey';
			$redis = new Redis();
			$user_info = null;
			//如果已经登陆过了，则查看loginkey和redis存储的是否一致，不一致则需要重新请求微信登陆
			if(isset($_COOKIE['user_id']))
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
				$redis_connected = true;
				if(!$redis->hExists($loginkey_db, $_COOKIE['user_id']))
				{
					$bNeedCheckLogin = true;
				}
				else
				{
					if(!isset($_COOKIE['loginkey']))
					{
						$bNeedCheckLogin = true;
					}
					else
					{
						$loginkey = $redis->hGet($loginkey_db, $_COOKIE['user_id']);
						if($loginkey != $_COOKIE['loginkey'])
						{
							$bNeedCheckLogin = true;
						}
						else
						{
							$bNeedCheckLogin = false;
						}
					}
				}
			}
			else
			{
				$bNeedCheckLogin = true;
			}

			$CODE = $_GET['code'];
			if(!isset($_GET['state']))
			{
				show_404();
				return;
			}

			$state = $_GET['state'];
			list($barid_param, $deskid_param, $role_param) = explode("&", $state);
			list($barid_name, $barid) = explode("=", $barid_param);
			list($deskid_name, $deskid) = explode("=", $deskid_param);
			list($role_name, $role) = explode("=", $role_param);

			if($bNeedCheckLogin)//需要交验登陆，则请求微信
			{
				$url_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config->item('WX_APPID')."&secret=".$this->config->item('WX_SECRET')."&code=".$CODE."&grant_type=authorization_code";
		
				$ret_json = $this->https->https_request($url_access_token);
				$json_data = json_decode($ret_json);
				if(isset($json_data->errcode))//微信错误，重新请求一次
				{
					header("location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0523023df5aa4bf1&redirect_uri=http://dream.waimaipu.cn/index.php/user/login3&response_type=code&scope=snsapi_userinfo&state=barid%3D".$barid."%26deskid%3D".$deskid."%26role%3D".$role."#wechat_redirect");
					return;
				}
				$access_token = $json_data->access_token;
				$openid = $json_data->openid;
				$url_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid;
			
				$ret_json = $this->https->https_request($url_userinfo);
				$json_data = json_decode($ret_json);
				$openid = $json_data->openid;
				$data['headimgurl'] = $json_data->headimgurl;
				$data['nickname'] = $json_data->nickname;
				$data['sex'] = $json_data->sex;
			}
			else
			{//校验通过，直接取数据信息
				$user_info = $this->user_model->get_user_by_userid($_COOKIE['user_id']);
				if(!$user_info)
				{
					show_404();
					return;
				}
				$data['headimgurl'] = $user_info['headimg'];
				$data['nickname'] = $user_info['nickname'];
				$data['sex'] = $user_info['sex'];
			}
			
			$data['barinfo'] = $this->bar_model->get_barinfo($barid);
			if(!$data['barinfo'])
			{
				show_404();
				return;
			}

			if(!$user_info)
			{
				$user_info = $this->user_model->if_user_exist($openid);
			}

			if(!$user_info && $bNeedCheckLogin)
			{
				$this->user_model->add_user($openid, 
											$data['nickname'],
											$data['sex'], 
											$data['headimgurl'],
											$_SERVER['REMOTE_ADDR'],
											$barid,
											'g');
				$user_info = $this->user_model->get_user_by_openid($openid);
			}
			else
			{
				$new_user = $user_info;
				$new_user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$new_user['last_login_time'] = time();
				$new_user['status'] = 1;
				$new_user['nickname'] = $data['nickname'];//$json_data->nickname;
				$new_user['sex'] = $data['sex'];//$json_data->sex;
				$new_user['headimg'] = $data['headimgurl'];//$json_data->headimgurl;
				if(!strstr($user_info['role'], "g"))
				{
					$new_user['role'] = $new_user['role'].'|g';
				}
				$this->user_model->update_user($user_info, $new_user);
			}
			$data['user_id'] = $user_info['user_id'];
			$data['desk_id'] = $deskid;
			$data['role'] = $role;
			//$data['role'] = $user_info['role'];
			$data['barrage_alert'] = $user_info['barrage_alert'];
			setcookie("user_id", $data['user_id'], time()+3600*24);
			setcookie("bar_id", $barid, time() + 3600*24);
			setcookie('desk_id', $deskid, time()+3600*24);
			setcookie('headimg', $user_info['headimg']);
			setcookie('nickname', $user_info['nickname']);
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if($retCode)
			{
				$redis_connected = true;
				$db_name = 'bar'.$barid.'_logininfo';
				if(!$redis->hExists($db_name, $user_info['user_id']))
				{
					$login_info = array();
					$login_info['nickname'] = $json_data->nickname;
					$login_info['count'] = 1;
					$login_info['time'] = time();
					$login_info['sex'] = $data['sex'];
					$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
				}
				else
				{
					$strTmp = $redis->hGet($db_name, $user_info['user_id']);
					$login_info = json_decode($strTmp, true);
					$login_info['count'] = $login_info['count'] + 1;
					$login_info['time'] = time();
					$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
				}
			}

			$this->baruser_model->insert_user_if_not_exist($barid,
														   $data['user_id'],
														   $data['nickname'],
														   $data['headimgurl'],
														   $data['sex']
														   );
			//增加登陆key
			$loginkey = $this->getRandomStr(8);
			setcookie('loginkey', $loginkey, time() + 3600*24);
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}
			// //记录登陆key
			if($retCode)
			{
				$redis->hSet($loginkey_db, $user_info['user_id'], $loginkey);
			}
			//是否显示密语用户
			if(isset($_GET['privmsg_user']))
			{
				setcookie('privmsg_user', $_REQUEST['privmsg_user']);
			}
			else
			{
				if(isset($_COOKIE['privmsg_user']))
				{
					setcookie('privmsg_user', "");
				}
			}

			$this->load->view('index_new', $data);
		}

		public function login_rong()
		{			
			if(!isset($_GET['code'])) //没有CODE参数,必定是非微信登陆
			{
				show_404();
				return;
			}

			$bNeedCheckLogin = false;
			$redis_connected = false;
			$loginkey_db = 'loginkey';
			$redis = new Redis();
			$user_info = null;
			//校验登陆态
			//如果已经登陆过了，则查看loginkey和redis存储的是否一致，不一致则需要重新请求微信登陆
			if(isset($_COOKIE['user_id']))
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
				$redis_connected = true;
				if(!$redis->hExists($loginkey_db, $_COOKIE['user_id']))
				{
					$bNeedCheckLogin = true;
				}
				else
				{
					if(!isset($_COOKIE['loginkey']))
					{
						$bNeedCheckLogin = true;
					}
					else
					{
						$loginkey = $redis->hGet($loginkey_db, $_COOKIE['user_id']);
						if($loginkey != $_COOKIE['loginkey'])
						{
							$bNeedCheckLogin = true;
						}
						else
						{
							$bNeedCheckLogin = false;
						}
					}
				}
			}
			else
			{
				$bNeedCheckLogin = true;
			}

			$CODE = $_GET['code'];
			if(!isset($_GET['state']))
			{
				show_404();
				return;
			}

			$state = $_GET['state'];
			list($barid_param, $deskid_param, $role_param) = explode("&", $state);
			list($barid_name, $barid) = explode("=", $barid_param);
			list($deskid_name, $deskid) = explode("=", $deskid_param);
			list($role_name, $role) = explode("=", $role_param);

			if($bNeedCheckLogin)//需要交验登陆，则请求微信
			{
				$url_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config->item('WX_APPID')."&secret=".$this->config->item('WX_SECRET')."&code=".$CODE."&grant_type=authorization_code";
		
				$ret_json = $this->https->https_request($url_access_token);
				$json_data = json_decode($ret_json);
				if(isset($json_data->errcode))//微信错误，重新请求一次
				{
					header("location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0523023df5aa4bf1&redirect_uri=http://dream.waimaipu.cn/index.php/user/login3&response_type=code&scope=snsapi_userinfo&state=barid%3D".$barid."%26deskid%3D".$deskid."%26role%3D".$role."#wechat_redirect");
					return;
				}
				$access_token = $json_data->access_token;
				$openid = $json_data->openid;
				$url_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid;
			
				$ret_json = $this->https->https_request($url_userinfo);
				$json_data = json_decode($ret_json);
				$openid = $json_data->openid;
				$data['headimgurl'] = $json_data->headimgurl;
				$data['nickname'] = $json_data->nickname;
				$data['sex'] = $json_data->sex;
			}
			else
			{//校验通过，直接取数据信息
				$user_info = $this->user_model->get_user_by_userid($_COOKIE['user_id']);
				if(!$user_info)
				{
					show_404();
					return;
				}
				$data['headimgurl'] = $user_info['headimg'];
				$data['nickname'] = $user_info['nickname'];
				$data['sex'] = $user_info['sex'];
			}
			
			$data['barinfo'] = $this->bar_model->get_barinfo($barid);
			if(!$data['barinfo'])
			{
				show_404();
				return;
			}

			if(!$user_info)
			{
				$user_info = $this->user_model->if_user_exist($openid);
			}

			if(!$user_info && $bNeedCheckLogin)
			{
				$this->user_model->add_user($openid, 
											$data['nickname'],
											$data['sex'], 
											$data['headimgurl'],
											$_SERVER['REMOTE_ADDR'],
											$barid,
											'g');
				$user_info = $this->user_model->get_user_by_openid($openid);
			}
			else
			{
				$new_user = $user_info;
				$new_user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$new_user['last_login_time'] = time();
				$new_user['status'] = 1;
				$new_user['nick'] = $data['nickname'];//$json_data->nickname;
				$new_user['sex'] = $data['sex'];//$json_data->sex;
				$new_user['headimg'] = $data['headimgurl'];//$json_data->headimgurl;
				if(!strstr($user_info['role'], "g"))
				{
					$new_user['role'] = $new_user['role'].'|g';
				}
				$this->user_model->update_user($user_info, $new_user);
			}
			//获取歌手信息
			$singer_info = null;

			if($data['barinfo']['singer_id'] != "" && $data['barinfo']['singer_id'] != "null" && $data['barinfo']['singer_id'] != -1)
			{
				$singer_info = $this->user_model->get_user_by_userid($data['barinfo']['singer_id']);
			}

			
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}

			if($singer_info)
			{
				$singerlove = $redis->hget('love_db_name', 'user'.$singer_info['user_id']);
				if($singerlove == false)
				{
					$singer_info['love'] = 0;
				}
				else
				{
					$singer_info['love'] = $singerlove;
				}
			}
			$singer_info['liveness'] = 4;
			$data['singer_info'] = $singer_info;


			$data['user_id'] = $user_info['user_id'];
			$data['desk_id'] = $deskid;
			$data['role'] = $role;
			//$data['role'] = $user_info['role'];
			$data['barrage_alert'] = $user_info['barrage_alert'];
			setcookie("user_id", $data['user_id'], time()+3600*24);
			setcookie("bar_id", $barid, time() + 3600*24);
			setcookie('desk_id', $deskid, time()+3600*24);
			setcookie('headimg', $user_info['headimg']);
			setcookie('nickname', $user_info['nickname']);
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}

			if($retCode)
			{
				$redis_connected = true;
				$db_name = 'bar'.$barid.'_logininfo';
				if(!$redis->hExists($db_name, $user_info['user_id']))
				{
					$login_info = array();
					$login_info['nick'] = $user_info['nickname'];
					$login_info['count'] = 1;
					$login_info['time'] = time();
					$login_info['sex'] = $data['sex'];
					$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
				}
				else
				{
					$strTmp = $redis->hGet($db_name, $user_info['user_id']);
					$login_info = json_decode($strTmp, true);
					$login_info['count'] = $login_info['count'] + 1;
					$login_info['time'] = time();
					$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
				}
			}

			$this->baruser_model->insert_user_if_not_exist($barid,
														   $data['user_id'],
														   $data['nickname'],
														   $data['headimgurl'],
														   $data['sex']
														   );
			//增加登陆key
			$loginkey = $this->getRandomStr(8);
			setcookie('loginkey', $loginkey, time() + 3600*24);
			if(!$redis_connected)
			{
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			}
			//记录登陆key
			if($retCode)
			{
				$redis->hSet($loginkey_db, $user_info['user_id'], $loginkey);
			}
			//是否显示密语用户
			if(isset($_GET['privmsg_user']))
			{
				setcookie('privmsg_user', $_REQUEST['privmsg_user']);
			}
			else
			{
				if(isset($_COOKIE['privmsg_user']))
				{
					setcookie('privmsg_user', "");
				}
			}

			//获取融云token
			$user_id = $user_info['user_id'];
			$nickname = $user_info['nickname'];
			$headimg = $user_info['headimg'];
			$rc_token = $this->get_rctoken($user_id, $nickname, $headimg);
			if($rc_token)
			{
				$data['rc_token'] = $rc_token;
			}
			else
			{
				$data['rc_token'] = "";
			}
			
			$this->load->view('index_rong', $data);
		}

		/*
		*	获取融云token
		*/
		private function get_rctoken($user_id, $nickname, $headimg)
		{
			$data_rc = 'userId='.$user_id.'&name='.$nickname.'&portraitUri='.$headimg;
			$timestamp = time();
			$appkey = 'x18ywvqf8xdcc';
			$appsecret = 'IlKQIFBSPS';
			$nonce = rand();
			$singnature = sha1($appsecret.$nonce.$timestamp);
			$headers = array(
					'App-Key:'.$appkey,
					'Nonce:'.$nonce,
					'Timestamp:'.$timestamp,
					'Signature:'.$singnature,
					'Content-Type: application/x-www-form-urlencoded',
				);

			$url = 'https://api.cn.ronghub.com/user/getToken.json';
			$sJson = $this->https->https_request($url, $data_rc, $headers);
			$data_json = json_decode($sJson, true);
			if($data_json['code'] != "200") 
			{
				return  null;
			}
			return $data_json['token'];
		}

		public function login2()
		{			
			if(!isset($_GET['code']))
			{
				show_404();
				return;
			}

			$CODE = $_GET['code'];
			$url_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config->item('WX_APPID')."&secret=".$this->config->item('WX_SECRET')."&code=".$CODE."&grant_type=authorization_code";
		
			$ret_json = $this->https->https_request($url_access_token);

			$json_data = json_decode($ret_json);

			if(isset($json_data->errcode))
			{
				header("location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0523023df5aa4bf1&redirect_uri=http://dream.waimaipu.cn/index.php/user/login&response_type=code&scope=snsapi_userinfo&state=barid%3D1%26deskid%3D2%26role%3Dg#wechat_redirect");
				return;
			}
		
			$access_token = $json_data->access_token;
			//log_message('debug', 'access_token='.$access_token);

			$openid = $json_data->openid;

			$url_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid;
			
			$ret_json = $this->https->https_request($url_userinfo);
			$json_data = json_decode($ret_json);

			// $openid = 'oCwFFwj4eh94o8lbMeO44NfuOPpQ';//$json_data->openid;
			$openid = $json_data->openid;
			$data['headimgurl'] = $json_data->headimgurl;
			$data['nickname'] = $json_data->nickname;
			$data['sex'] = $json_data->sex;
			if(isset($_GET['state']))
			{
				$state = $_GET['state'];
				list($barid_param, $deskid_param, $role_param) = explode("&", $state);
				list($barid_name, $barid) = explode("=", $barid_param);
				list($deskid_name, $deskid) = explode("=", $deskid_param);
				list($role_name, $role) = explode("=", $role_param);
				$data['barinfo'] = $this->bar_model->get_barinfo($barid);
				if(!$data['barinfo'])
				{
					show_404();
					return;
				}

				$user_info = $this->user_model->if_user_exist($openid);
				if(!$user_info)
				{
					$this->user_model->add_user($openid, 
												$data['nickname'],
												$data['sex'], 
												$data['headimgurl'],
												$_SERVER['REMOTE_ADDR'],
												$barid,
												'g');
					$user_info = $this->user_model->get_user_by_openid($openid);
				}
				else
				{
					$new_user = $user_info;
					$new_user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
					$new_user['last_login_time'] = time();
					$new_user['status'] = 1;
					$new_user['nick'] = $data['nickname'];//$json_data->nickname;
					$new_user['sex'] = $data['sex'];//$json_data->sex;
					$new_user['headimg'] = $data['headimgurl'];//$json_data->headimgurl;
					if(!strstr($user_info['role'], "g"))
					{
						$new_user['role'] = $new_user['role'].'|g';
					}
					$this->user_model->update_user($user_info, $new_user);
				}
				$data['user_id'] = $user_info['user_id'];
				$data['desk_id'] = $deskid;
				$data['role'] = $role;
				//$data['role'] = $user_info['role'];
				$data['barrage_alert'] = $user_info['barrage_alert'];
				setcookie("user_id", $data['user_id'], time()+3600*24);
				setcookie("bar_id", $barid, time() + 3600*24);
				//增加登陆次数
				$redis = new Redis();
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
				if($retCode)
				{
					$db_name = 'bar'.$barid.'_logininfo';
					if(!$redis->hExists($db_name, $user_info['user_id']))
					{
						$login_info = array();
						$login_info['nick'] = $json_data->nickname;
						$login_info['count'] = 1;
						$login_info['time'] = time();
						$login_info['sex'] = $data['sex'];
						$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
					}
					else
					{
						$strTmp = $redis->hGet($db_name, $user_info['user_id']);
						$login_info = json_decode($strTmp, true);
						$login_info['count'] = $login_info['count'] + 1;
						$login_info['time'] = time();
						$redis->hSet($db_name, $user_info['user_id'], json_encode($login_info));
					}
				}

				$this->baruser_model->insert_user_if_not_exist($barid,
															   $data['user_id'],
															   $data['nickname'],
															   $data['headimgurl'],
															   $data['sex']
															   );
				$this->load->view('index', $data);
			}
			else
			{
				show_404();
			}
		}

		public function get_users_info()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');

			//$this->user_model->get_user_by_userids()
			if(!isset($_REQUEST['userids']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = "";
				echo json_encode($ret);
				return;
			}

			$userids = explode('|', $_REQUEST['userids']);
			if(count($userids) <= 0)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = "";
				echo json_encode($ret);
				return;
			}

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				echo json_encode($ret);
				return;
			}

			$ret['code'] = 0;
			$user_infos = $this->user_model->get_user_by_userids($userids);
			$out_user_infos = array();
			for($index = 0; $index < count($user_infos); $index++)
			{
				$user_info = array();
				$user_info['user_id'] = $user_infos[$index]['user_id'];
				$user_info['headimg'] = $user_infos[$index]['headimg'];
				$user_info['nick'] = $user_infos[$index]['nick'];
				$user_info['sex'] = $user_infos[$index]['sex'];
				$user_info['role'] = $user_infos[$index]['role'];
				$love = $redis->hget('love_db_name', 'user'.$user_info['user_id']);
				if($love == false)
				{
					$user_info['love'] = 0;
				}
				else
				{
					$user_info['love'] = $love;
				}
				$user_info['liveness'] = 4;
				array_push($out_user_infos, $user_info);
			}

			$ret['data'] = $out_user_infos;
			$ret['msg'] = "succeed";
			echo json_encode($ret);
			return;
		}

		public function get_users_love_liveness()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');

			//$this->user_model->get_user_by_userids()
			if(!isset($_REQUEST['userids']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = "";
				echo json_encode($ret);
				return;
			}

			$userids = explode('|', $_REQUEST['userids']);
			if(count($userids) <= 0)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = "";
				echo json_encode($ret);
				return;
			}

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				echo json_encode($ret);
				return;
			}

			$ids = array();
			for($index = 0; $index < count($userids); $index++)
			{
				array_push($ids, 'user'.$userids[$index]);
			}

			// $love_arr = $redis->hmget('love_db_name', $ids);
			// $love_arr = $redis->hmget('live_db_name', $ids);
			// var_dump($love_arr);
			// $ret['code'] = 0;
			// $ret['love'] = $love_arr;
			// $ret['code'] = 0;
			// $ret['data'] = $this->user_model->get_user_by_userids($userids);
			// $ret['msg'] = "succeed";
			// echo json_encode($ret);
			return;
		}

		public function query_activity()
		{
			if(!isset($_REQUEST['bar_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$bar_id = $_REQUEST['bar_id'];
			$bar_info = $this->bar_model->get_barinfo($bar_id);
			if(!$bar_info)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			$bar_info['cur_time'] = time();
			$ret['code'] = 0;
			$ret['activity_info'] = $bar_info;
			echo json_encode($ret);
		}

		public function query_bill()
		{
			$user_id = $_REQUEST['user_id'];
			$orders = $this->order_model->query_pay_order($user_id);
			if(!$orders)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}
			$ret['code'] = 0;
			$ret['data'] = $orders;
			echo json_encode($ret);
			return;
		}

		public function query_user_msg()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  
			
			$bar_id = $_REQUEST['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				show_404();
				return;
			}
			$all = $redis->hGetAll("msg_bar".$bar_id);
			$ret['code'] = 0;
			$ret['data'] = $all;
			echo json_encode($ret);
			return;
		}

		public function query_bar_msg()
		{
			$bar_id = $_REQUEST['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				show_404();
				return;
			}
			$all = $redis->hGetAll("barMessage".$bar_id);
			$ret['code'] = 0;
			$ret['data'] = $all;
			echo json_encode($ret);
			return;
		}

		public function query_sys_msg()
		{
			$bar_id = $_REQUEST['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				show_404();
				return;
			}
			$all = $redis->hGetAll("sysMessage".$bar_id);
			$ret['code'] = 0;
			$ret['data'] = $all;
			echo json_encode($ret);
			return;
		}


		public function del_bar_msg()
		{
			$barid = $_REQUEST['barid'];
			$msg_ids = $_REQUEST['msgids'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				show_404();
				return;
			}

			$msgids = explode('|', $msg_ids);
			for($index=0;$index<count($msgids);$index++)
			{
				$redis->hDel('msg_bar'.$barid, $msgids[$index]);
			}
			//$all = $redis->hGetAll('msg_bar'.$barid);
			$ret['code'] = 0;
			//$ret['data'] = $all;
			echo json_encode($ret);
			return;
		}

		public function query_all_bar()
		{
			$res = $this->bar_model->query_all_bars();
			$ret['code'] = 0;
			$ret['data'] = $res;
			echo json_encode($ret);
		}

		public function close_barrage_alert()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$user_id = $_REQUEST['user_id'];
			$this->user_model->close_barrage_alert($user_id);
			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function add_love()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			if(!isset($_REQUEST['user_id']) || !isset($_REQUEST['count']) || !isset($_REQUEST['bar_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			//barid_love_date;
			$user_id = $_REQUEST['user_id'];
			if(!isset($user_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$bar_id = $_REQUEST['bar_id'];
			$target_user_id = $_REQUEST['target_userid'];
			$count = $_REQUEST['count'];
			$love_db_name = 'love_db_name';
			if(!$redis->hExists($love_db_name, 'user'.$target_user_id))
			{
				$redis->hSet($love_db_name, 'user'.$target_user_id, $count);
			}
			else
			{
				$redis->hIncrBy($love_db_name, 'user'.$target_user_id, $count);	
			}

			//add to love rank
			$redis_set_givelove_name = 'bar'.$bar_id.'_givelove'.date('Ymd');
			$redis->zIncrBy($redis_set_givelove_name, $count, $user_id);
			
			$redis_set_gotlove_name = 'bar'.$bar_id.'_gotlove'.date('Ymd');
			$redis->zIncrBy($redis_set_gotlove_name, $count, $target_user_id);
			//echo var_dump($redis->zRevRange($redis_set_gotlove_name, 0, -1, true));
			//return;
			// if($this->user_model->add_love($target_user_id, $count))
			// {
			// 	$ret['code'] = 0;
			// }
			// else
			// {
			// 	$ret['code'] = ERRCODE_OPERATE_MYSQL;
			// }
			$ret['code'] = 0;
			echo json_encode($ret);
		}

		/*
		* 发送普通消息及弹幕消息
		*/
		public function send_msg()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['bar_id']) || !isset($_REQUEST['user_id']) || !isset($_REQUEST['content']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			$bar_id = $_REQUEST['bar_id'];
			$user_id = $_REQUEST['user_id'];
			$content = $_REQUEST['content'];
			$json_content = json_decode($content, true);
			if($json_content['user_id'] != $user_id)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}
			
			$message_id = $redis->incr('bar'.$bar_id.'_msgcount');
			if($json_content['type'] == 'barrageMsg')
			{
				if(ERRCODE_SUCCESS != $this->money_model->consume_money($user_id, 1))
				{
					$ret['code'] = ERRCODE_OPERATE_MYSQL;
					$ret['msg'] = 'error.';
					echo json_encode($ret);
					return;
				}
			}

			if(!$redis->hset('bar'.$bar_id.'msg', $message_id, $content))
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
 			}

 			$ret['code'] = ERRCODE_SUCCESS;
 			$ret['message_id'] = $message_id;
 			$ret['msg'] = 'succeed.';
 			echo json_encode($ret);
			return;
		}

		public function get_newest_message_id()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type'); 

			if(!isset($_REQUEST['bar_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			$bar_id = $_REQUEST['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}
			$message_id = $redis->get('bar'.$bar_id.'_msgcount');
			$ret['code'] = 0;
			$ret['message_id'] = $message_id;
			echo json_encode($ret);
			return;
		}

		public function get_msg()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type'); 

			if(!isset($_REQUEST['bar_id']) || !isset($_REQUEST['message_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			$message_id = $_REQUEST['message_id'];
			$bar_id = $_REQUEST['bar_id'];
			if($message_id == 0)
			{
				$redis = new Redis();
				$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
				if(!$retCode)
				{
					$ret['code'] = ERRCODE_REDIS;
					$ret['msg'] = 'unknow error.';
					echo json_encode($ret);
					return;
				}
				
				$message_id = $redis->get('bar'.$bar_id.'_msgcount');
				$ret['code'] = 0;
				$ret['message_id'] = $message_id;
				echo json_encode($ret);
				return;
			}

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			$new_message_id = $redis->get('bar'.$bar_id.'_msgcount');
			if($message_id >= $new_message_id)
			{
				$ret['code'] = -1;
				$ret['msg'] = 'no message';
				echo json_encode($ret);
				return;
			}

			$messages = array();
			for($message_id = $message_id+1; $message_id <= $new_message_id; $message_id++)
			{
				$message = $redis->hGet('bar'.$bar_id.'msg', $message_id);
				array_push($messages, $message);
			}
			$singer = $redis->get('bar'.$bar_id.'singer');

			$ret['data'] = $messages;
			$ret['singer'] = $singer;
			$ret['newmessage_id'] = $new_message_id;
			$ret['code'] = 0;
			echo json_encode($ret);
		}

		public function switch_singer()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			if(!isset($_REQUEST['bar_id']) || !isset($_content))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$bar_id = $_REQUEST['bar_id'];
			$content = $_REQUEST['content'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			if(!$redis->set('bar'.$bar_id.'singer', $content))
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
			}

			$message_id = $redis->incr('bar'.$bar_id.'_msgcount');
			if(!$redis->hset('bar'.$bar_id.'msg', $message_id, $content))
			{
				$ret['code'] = ERRCODE_REDIS;
				$ret['msg'] = 'unknow error.';
				echo json_encode($ret);
				return;
 			}
 			$ret['code'] = 0;
 			echo json_encode($ret);
		}

		public function query_user_info()
		{
			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			//check user valid
			$target_user_id = $_REQUEST['target_user_id'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$user_info = $this->user_model->get_user_by_userid($target_user_id);
			$data = array();
			$data['user_id'] = $user_info['user_id'];
			$data['nickname'] = $user_info['nickname'];
			$data['headimg'] = $user_info['headimg']; 
			$data['sex'] = $user_info['sex'];
			$data['role'] = $user_info['role'];
			$data['love'] = $redis->hGet('love_db_name', 'user'.$target_user_id);
			if($data['love'] == false)
			{
				$data['love'] = 0;
			}
			$data['liveness'] = 4;
			$money_info = $this->money_model->get_moneyinfo($target_user_id);
			$data['money'] = $money_info['money'];
			$ret['data'] = $data;
			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function get_robot_rctoken() {
			$user_id = $_REQUEST['user_id'];
			$nickname = $_REQUEST['nickname'];
			$headimg = $_REQUEST['headimg'];
			$rc_token = $this->get_rctoken($user_id, $nickname, $headimg);
			$ret['code'] = 0;
			$ret['token'] = $rc_token;
			echo json_encode($ret);
		}

		public function query_users_info()
		{
			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			//check user valid
			$target_user_ids = explode("|", $_REQUEST['target_user_ids']);

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$users_info = $this->user_model->get_user_by_userids($target_user_ids);

			$infos = [];
			for($index = 0; $index < count($users_info); $index++)
			{
				$info = $users_info[$index];
				$info['love'] = $redis->hGet('love_db_name', 'user'.$users_info[$index]["user_id"]);
				if($info['love'] == false)
				{
					$info['love'] = 0;
				}
				$info['liveness'] = 4;
				$money_info = $this->money_model->get_moneyinfo($users_info[$index]["user_id"]);
				$info['money'] = $money_info['money'];
				array_push($infos, $info);
			}
			
			$ret['data'] = $infos;
			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function server_reg()
		{
			//获取openid
			if(!isset($_GET['code']))
			{
				show_404();
				return;
			}

			$CODE = $_GET['code'];
			$url_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config->item('WX_APPID')."&secret=".$this->config->item('WX_SECRET')."&code=".$CODE."&grant_type=authorization_code";
		
			$ret_json = $this->https->https_request($url_access_token);

			$json_data = json_decode($ret_json);
			
			$openid = $json_data->openid;
			$access_token = $json_data->access_token;

			$url_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid;
			
			$ret_json = $this->https->https_request($url_userinfo);
			$json_data = json_decode($ret_json);
			
			$data['headimgurl'] = $json_data->headimgurl;
			$data['nickname'] = $json_data->nickname;
			$data['sex'] = $json_data->sex;
			$data['openid'] = $openid;

			if(isset($_GET['state']))
			{
				$state = $_GET['state'];
				list($barid_param) = explode("&", $state);
				list($barid_name, $bar_id) = explode("=", $barid_param);
				$data['bar_id'] = $bar_id;
			}
			else
			{
				show_404();
				return;
			}

			//判断歌手是否已经注册
			$user_info = $this->user_model->if_user_exist($openid);
			if(!$user_info)
			{//没有注册过，添加一条记录
				$this->user_model->add_user($openid, 
												$data['nickname'],
												$data['sex'], 
												$data['headimgurl'],
												$_SERVER['REMOTE_ADDR'],
												$bar_id,
												'g');

				$user_info = $this->user_model->if_user_exist($openid);
				$data['user_id'] = $user_info['user_id'];
			} 
			else 
			{
				$new_user = $user_info;
				$new_user['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
				$new_user['last_login_time'] = time();
				$new_user['status'] = 1;
				if(!strstr($user_info['role'], 'a'))
				{
					$new_user['role'] = $new_user['role'].'|'.'a';
					$this->user_model->update_user($user_info, $new_user);
				}
				$data['user_id'] = $user_info['user_id'];
			}

			$this->load->view('server_reg', $data);
		}

		public function admin_reg() 
		{
			if(!isset($_REQUEST['real_name']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['phone']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['user_name']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['password']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['role']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['bar_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['open_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$open_id = $_REQUEST['open_id'];
			$role = $_REQUEST['role'];
			$user_id = $_REQUEST['user_id'];
			$user_name = $_REQUEST['user_name'];
			$password = md5($_REQUEST['password']);
			$bar_id = $_REQUEST['bar_id'];
			$realname = $_REQUEST['real_name'];
			$phone = $_REQUEST['phone'];

			if($this->admin_model->if_username_exist($user_name))
			{
				$ret['code'] = ERRCODE_USER_EXIST;
				$ret['msg'] = '用户名已存在!';
				echo json_encode($ret);
				return;
			}

			$user_info = $this->admin_model->if_user_id_exists($user_id);
			if(!$user_info)
			{//不存在，则插入一个用户
				if($role == 'a')
				{
					$this->admin_model->add_user($user_name, $password, Admin_model::USER_TYPE_SINGER, "", $user_id);
				}
				else if($role == 's')
				{
					$this->admin_model->add_user($user_name, $password, Admin_model::USER_TYPE_SERVER, "", $user_id);
				}
			}
			else
			{//存在，检测用户的类型是否匹配，不匹配则更新
				if($role == 'a')
				{
					if($user_info['type'] != Admin_model::USER_TYPE_SINGER)
					{
						$new_user = $user_info;
						$new_user['type'] = Admin_model::USER_TYPE_SINGER;
						$this->admin_model->update_user($user_info, $new_user);
						// log_message("debug", 'admin_update_user');
					}
				}
				else if($role == 's')
				{
					if($user_info['type'] != Admin_model::USER_TYPE_SERVER)
					{
						$new_user = $user_info;
						$new_user['type'] = Admin_model::USER_TYPE_SERVER;
						$this->admin_model->update_user($user_info, $new_user);
					}
				}
				
			}

			$bar_user = $this->barserver_model->if_user_exists($user_id, $bar_id);
			if(!$bar_user)
			{//不存在，则插入新用户，等待审批
				if($role == 'a')
				{
					$this->barserver_model->add_user(Barserver_model::USER_TYPE_SINGER, $user_id, time(), 
												Barserver_model::USER_VERIFIED_WAIT, $bar_id);
				}
				else if($role == 's')
				{
					$this->barserver_model->add_user(Barserver_model::USER_TYPE_SERVER, $user_id, time(), 
												Barserver_model::USER_VERIFIED_WAIT, $bar_id);
				}
			}
			else
			{
				$need_update = false;
				$new_bar_server = $bar_user;
				if($role == 'a')
				{
					if($bar_user['type'] != Barserver_model::USER_TYPE_SINGER)
					{
						$need_update = true;
						$new_bar_server['type'] = Barserver_model::USER_TYPE_SINGER;
					}
				}
				else if($role == 's')
				{
					if($bar_user['type'] != Barserver_model::USER_TYPE_SERVER)
					{
						$need_update = true;
						$new_bar_server['type'] = Barserver_model::USER_TYPE_SERVER;
					}
				}

				if($need_update)
				{
					$this->barserver_model->update_user($bar_user, $new_bar_server);
				}
			}
			//更新用户名和手机
			$user_core_info = $this->user_model->get_user_by_userid($user_id);
			$new_user_core = $user_core_info;
			$new_user_core['realname'] = $realname;
			$new_user_core['phone'] = $phone;
			$this->user_model->update_user($user_core_info, $new_user_core);

			$ret['code'] = 0;
			echo json_encode($ret);
		}

		public function query_user_bar_msg()
		{
			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$barMsgTime = $redis->get('bar'.$bar_id.'_user'.$user_id.'_time');
			if(!$barMsgTime)
			{
				$redis->set('bar'.$bar_id.'_user'.$user_id.'_time', time(), 0);
			}

			$lastViewMsgTime = $redis->get('bar'.$bar_id.'_user'.$user_id.'_time');
			$allMsg = $redis->hGetAll('msg_bar'.$barid);



		}

		// public function test_add_item()
		// {
		// 	$user_id = $_REQUEST['user_id'];
		// 	$item_id = $_REQUEST['item_id'];
		// 	$item_count = $_REQUEST['item_count'];

		// 	$this->useritem_model->add_item($user_id, $item_id, $item_count);

		// }
		public function send_gift()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$target_user_id = $_REQUEST['target_userid'];
			$item_id = $_REQUEST['item_id'];
			$item_count = $_REQUEST['item_count'];
			$leave_word = $_REQUEST['leave_word'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			log_message('debug', '************************item_id='.$item_id);

			$item_info = $this->item_model->query_iteminfo($item_id);
			if(!$item_info)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM-3;
				echo json_encode($ret);
				return $ret;
			}

			$total_money = $item_info['price']*$item_count;
			$item_name = $item_info['name'];
			$item_img = $item_info['img'];
			$item_unit = $item_info['unit'];
			
			log_message("debug", "price=".$item_info['price']."&count=".$item_count);
			$retCode  = $this->money_model->consume_money($user_id, $total_money);
			if($retCode != ERRCODE_SUCCESS)
			{
				$ret['code'] = $retCode;
				echo json_encode($ret);
				return;
			}

			$target_userinfo = $this->user_model->get_user_by_userid($target_user_id);
			$sender_info = $this->user_model->get_user_by_userid($user_id);
			//add gift to target user
			$retCode = $this->useritem_model->add_item($target_user_id, $item_id, $item_count);
			if($retCode != true)
			{
				$errMsg = 'err=add_useritem&type=gift&barid='.$bar_id.'&user_id='.$user_id.'&desk_id='.$desk_id.'&donee_id='.$target_user_id.'&item_id='.$item_id.'&item_count='.$item_count;
				log_message('error', $errMsg);
			}

			//add to user_present_info
			$order_id = $user_id.time().($this->getRandomStr(8));
			$present_info = 'type=gift&item_id='.$item_id.'&item_count='.$item_count.
							'&item_name='.$item_name.'&item_img='.$item_img.'&item_unit='.$item_unit.
							'&target_user_id='.$target_user_id.'&target_userimg='.$target_userinfo['headimg'].
							'&target_nick='.$target_userinfo['nickname'].'&sender_headimg='.$sender_info['nickname'].
							'&leave_word='.$leave_word;
			if(!$this->userpresentinfo_model->insert_present_info($bar_id, $desk_id, $user_id, $target_user_id, $present_info))
			{
				$errMsg = 'err=log_present_info&type=gift&barid='.$bar_id.'&user_id='.$user_id.'&desk_id='.$desk_id.'&donee_id='.$target_user_id.'&item_id='.$item_id.'&item_count='.$item_count;
				log_message('error', $errMsg);
			}
			//增加贡献榜和收获榜
			$redis_set_givegift_name = 'bar'.$bar_id.'_givegift'.date('Ymd');
			$redis->zIncrBy($redis_set_givegift_name, $total_money, $user_id);
			
			$redis_set_gotgift_name = 'bar'.$bar_id.'_gotgift'.date('Ymd');
			$redis->zIncrBy($redis_set_gotgift_name, $total_money, $target_user_id);
			//写订单记录

			if(!$this->order_model->insert_order($order_id, $bar_id, $desk_id, $user_id, $present_info, Order_model::ORDER_STATUS_DONE,
												$target_user_id, $item_count, $total_money,Order_model::ORDER_TYPE_DUMYITEM, $item_id))
			{
				$errMsg = 'err=log_order_info&type=gift&barid='.$bar_id.'&user_id='.$user_id.'&desk_id='.$desk_id.'&donee_id='.$target_user_id.'&item_id='.$item_id.'&item_count='.$item_count;
				log_message('error', $errMsg);
				return;
			}

			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function tv_index()
		{
			if(!isset($_REQUEST['bar_id']))
			{
				show_404();
				return;
			}

			$bar_id = $_REQUEST['bar_id'];
			$data['bar_id'] = $bar_id;
			$bar_info = $this->bar_model->get_barinfo($bar_id);
			if(!$bar_info)
			{
				show_404();
				return;
			}
			$data['bar_img'] = $bar_info['barimg'];
			$data['bar_name'] = $bar_info['name'];
			$this->load->view('index_tv', $data);
		}

		public function send_redpacket() 
		{
			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$target_user_id = $_REQUEST['target_user_id'];
			$bakebi_count = $_REQUEST['bakebi_count'];
			$leave_word = $_REQUEST['leave_word'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$target_userinfo = $this->user_model->get_user_by_userid($target_user_id);
			$sender_info = $this->user_model->get_user_by_userid($user_id);

			$retCode  = $this->money_model->consume_money($user_id, $bakebi_count);
			if($retCode != ERRCODE_SUCCESS)
			{
				$ret['code'] = $retCode;
				echo json_encode($ret);
				return;
			}
			//红包需要后面经过审核来处理
			// $retCode = $this->money_model->add_money($target_user_id, $bakebi_count);
			// if($retCode != ERRCODE_SUCCESS)
			// {
			// 	$ret['code'] = $retCode;
			// 	$retCode = $this->money_model->add_money($user_id, $bakebi_count);
			// 	if($retCode != ERRCODE_SUCCESS)
			// 	{
			// 		log_message('error', 'err=log_present_info&type=sendredpacket&barid='.$bar_id.'&user_id='.$user_id.'&desk_id='.$desk_id.'&donee_id='.$target_user_id.'&bakebi_count='.$bakebi_count);
			// 	}
			// 	$ret['code'] = $retCode;
			// 	echo json_encode($ret);
			// 	return;
			// }

			$present_info = 'type=redpacket&target_user_id='.$target_user_id.'&target_userimg='.$target_userinfo['headimg'].
							'&target_nick='.$target_userinfo['nick'].'&sender_headimg='.$sender_info['nick'].'&bakebi_count='.$bakebi_count.
							'&leave_word='.$leave_word;
			if(!$this->userpresentinfo_model->insert_present_info($bar_id, $desk_id, $user_id, $target_user_id, $present_info))
			{
				//$errMsg = 'err=log_present_info&type=gift&barid='.$bar_id.'&user_id='.$user_id.'&desk_id='.$desk_id.'&donee_id='.$target_user_id.'&item_id='.$item_id.'&item_count='.$item_count;
				log_message('error', $present_info);
			}			

			//增加贡献榜和收获榜
			$redis_set_givegift_name = 'bar'.$bar_id.'_givegift'.date('Ymd');
			$redis->zIncrBy($redis_set_givegift_name, $bakebi_count, $user_id);
			
			$redis_set_gotgift_name = 'bar'.$bar_id.'_gotgift'.date('Ymd');
			$redis->zIncrBy($redis_set_gotgift_name, $bakebi_count, $target_user_id);

			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function enc_test()
		{
			$bar_id = $_REQUEST['bar_id'];
			$password = $_REQUEST['pwd'];
			$time = $_REQUEST['time'];
			$rand = $_REQUEST['rand'];
			$str = $bar_id.','.$time.','.$password.','.$rand;
			$encStr = $this->aes->encrypt($str);
			echo $encStr;
		}

		public function dec_test() 
		{
			if(!isset($_REQUEST['p']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			echo $this->aes->decrypt($_REQUEST['p']);
			return;
		}
		public function bar_login() 
		{
			session_start();
			if(!isset($_REQUEST['p']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$p = $_REQUEST['p'];

			// $decStr = $this->aes->decrypt($p);
			$a = explode(",", $p);
			// echo $decStr;
			// return;
			if(count($a) != 4)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM-1;
				echo json_encode($ret);
				return;
			}

			if(strlen($a[1]) != 11)//时间戳必须是11位的
			{
				$ret['code'] = ERRCODE_INVALID_PARAM-2;
				echo json_encode($ret);
				return;
			}

			if(preg_match("/[^\d, ]/",$a[1]))
			{//不是数字
				$ret['code'] = ERRCODE_INVALID_PARAM-3;
				echo json_encode($ret);
				return;
			}

			$bar_id = $a[0];
			$bar_info = $this->bar_model->get_barinfo($bar_id);
			if(!$bar_info)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM-5;
				echo json_encode($ret);
				return;
			}

			$_SESSION['bar'.$bar_id.'loginkey'] = $this->getRandomStr(8);
			$ret['code'] = 0;
			$ret['param'] = $_SESSION['bar'.$bar_id.'loginkey'];
			echo json_encode($ret);
			return;
		}

		public function query_gift_send()
		{
			$user_id = $_REQUEST['user_id'];
			$time_start = $_REQUEST['time_start'];
			$time_end = $_REQUEST['time_end'];
			$user_present_infos = $this->userpresentinfo_model->query_donate_present_info1($user_id, $time_start, $time_end);
			if($user_present_infos == null)
			{
				$ret['code'] = 0;
				$ret['data'] = [];
				echo json_encode($ret);
				return;
			}
			else
			{
				$ret['code'] = 0;
				$ret['data'] = $user_present_infos;
				echo json_encode($ret);
				return;
			}
		}

		public function query_gift_recv()
		{
			$user_id = $_REQUEST['userid'];
			$time_start = $_REQUEST['time_start'];
			$time_end = $_REQUEST['time_end'];

			$user_present_infos = $this->userpresentinfo_model->query_donee_present_info($user_id, $time_start, $time_end);
			if($user_present_infos == null)
			{
				$ret['code'] = 0;
				$ret['data'] = [];
				echo json_encode($ret);
				return;
			}
			else
			{
				$ret['code'] = 0;
				$ret['data'] = $user_present_infos;
				echo json_encode($ret);
				return;
			}
		}

		public function privmsg_page()
		{
			$this->load->view('privmsg', null);
		}

		public function send_priv_msg() 
		{
			$from_user_id = $_REQUEST['from_user_id'];
			$to_user_id = $_REQUEST['to_user_id'];
			$content = $_REQUEST['content'];
			$bar_id = $_REQUEST['bar_id'];
			$desk_id = $_REQUEST['desk_id'];
			$from_nickname = $_REQUEST['from_user_nickname'];
			$from_headimg = $_REQUEST['from_user_headimg'];
			$to_nickname = $_REQUEST['to_user_nickname'];
			$to_headimg = $_REQUEST['to_user_headimg'];
			// add_chat($bar_id, $desk_id, $from_user_id, $to_user_id, $from_nickname, $from_headimg, $to_nickname, $to_headimg, $content)
			$retCode = $this->chatrecord_model->add_chat($bar_id, $desk_id, $from_user_id, $to_user_id,$from_nickname, $from_headimg, $to_nickname, $to_headimg, $content);
			if($retCode)
			{
				$ret['code'] = 0;
			}
			else
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
			}
			echo json_encode($ret);
		}

		public function query_unviewed_privmsg()
		{
			$to_user_id = $_REQUEST['to_user_id'];
			$records = $this->chatrecord_model->query_unviewed_chatrecord($to_user_id);
			$ret['code'] = 0;
			if($records != null)
			{
				$ret['data'] = $records;
			}
			else
			{
				$ret['data'] = [];
			}
			echo json_encode($ret);
 		}

		public function data_config()
		{
			$url = $_REQUEST['url'];
			$jssdk = new JSSDK($this->config->item('WX_APPID'), $this->config->item('WX_SECRET'));
			$signPackage = $jssdk->GetSignPackage($url);
			$ret['code'] = 0;
			$ret['data'] = $signPackage;
			echo json_encode($ret);
			return;
		}

		public function consume_money()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  

			$user_id = $_REQUEST['user_id'];
			if(!isset($user_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$consume_count = $_POST['moneycount'];
			if(!isset($consume_count))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM-1;
				echo json_encode($ret);
				return;
			}

			$retCode = $this->money_model->consume_money($user_id, $consume_count);
			$ret['code'] = $retCode;
			echo json_encode($ret);
			return;
		}

		public function query_gotlove_rank()
		{
			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			if(!isset($user_id) || !isset($bar_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$redis_set_gotlove_name = 'bar'.$bar_id.'_gotlove'.date('Ymd');
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$rank_infos = $redis->zRevRange($redis_set_gotlove_name, 0, -1, true);
			$user_ids = $redis->zRevRange($redis_set_gotlove_name, 0, -1);
			$users_info = $this->user_model->get_user_by_userids($user_ids);
			$out_rank_infos = array();
			foreach($rank_infos as $key => $value)
			{
				$info = array();
				$info["gotlove"] = $value;
				for($index = 0; $index < count($users_info); $index++)
				{
					if($key == $users_info[$index]["user_id"])
					{
						$info['user_id'] = $users_info[$index]["user_id"];
						$info["nickname"] = $users_info[$index]["nickname"];
						$info["headimg"] = $users_info[$index]["headimg"];
						$info["role"] = $users_info[$index]["role"];
						$info['sex'] = $users_info[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos, $info);
			}

			$ret['code'] = 0;
			$ret['data'] = $out_rank_infos;
			echo json_encode($ret);
			return;
		}

		public function query_givegift_rank()
		{
			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			if(!isset($user_id) || !isset($bar_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$redis_set_givegift_name = 'bar'.$bar_id.'_givegift'.date('Ymd');
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$rank_infos = $redis->zRevRange($redis_set_givegift_name, 0, -1, true);

			$user_ids = $redis->zRevRange($redis_set_givegift_name, 0, -1);
			$users_info = $this->user_model->get_user_by_userids($user_ids);
			$out_rank_infos = array();
			foreach($rank_infos as $key => $value)
			{
				$info = array();
				$info["givemoney"] = $value;
				for($index = 0; $index < count($users_info); $index++)
				{
					if($key == $users_info[$index]["user_id"])
					{
						$info['user_id'] = $users_info[$index]["user_id"];
						$info["nickname"] = $users_info[$index]["nickname"];
						$info["headimg"] = $users_info[$index]["headimg"];
						$info["role"] = $users_info[$index]["role"];
						break;
					}
				}
				array_push($out_rank_infos, $info);
			}

			$ret['code'] = 0;
			$ret['data'] = $out_rank_infos;
			echo json_encode($ret);
			return;
		}

		public function add_user_online_time()
		{
			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$user_id = $_REQUEST['user_id'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				echo json_encode($ret);
				return;
			}

			$onlinetime_db = 'online_time';
			$redis->zIncrBy($onlinetime_db, 10, $user_id);
			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function query_user_online_rank()
		{
			if(!isset($_REQUEST['user_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$user_id = $_REQUEST['user_id'];

			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_REDIS;
				echo json_encode($ret);
				return;
			}

			$onlinetime_db = 'online_time';
			$rank_infos = $redis->zRevRange($onlinetime_db, 0, -1, true);
			$user_ids = $redis->zRevRange($onlinetime_db, 0, -1);
			$out_rank_infos = array();
			$users_info = $this->user_model->get_user_by_userids($user_ids);
			foreach($rank_infos as $key => $value)
			{
				$info = array();
				$info["time"] = $value;
				for($index = 0; $index < count($users_info); $index++)
				{
					if($key == $users_info[$index]["user_id"])
					{
						$info['user_id'] = $users_info[$index]["user_id"];
						$info["nickname"] = $users_info[$index]["nickname"];
						$info["headimg"] = $users_info[$index]["headimg"];
						$info["role"] = $users_info[$index]["role"];
						$info['sex'] = $users_info[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos, $info);
			}

			$ret['data'] = $out_rank_infos;
			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		public function query_gotgift_rank()
		{
			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			if(!isset($user_id) || !isset($bar_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$redis_set_gotgift_name = 'bar'.$bar_id.'_gotgift'.date('Ymd');
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$rank_infos = $redis->zRevRange($redis_set_gotgift_name, 0, -1, true);
			$user_ids = $redis->zRevRange($redis_set_gotgift_name, 0, -1);
			$users_info = $this->user_model->get_user_by_userids($user_ids);

			$out_rank_infos = array();
			foreach($rank_infos as $key => $value)
			{
				$info = array();
				$info["gotmoney"] = $value;
				for($index = 0; $index < count($users_info); $index++)
				{
					if($key == $users_info[$index]["user_id"])
					{
						$info['user_id'] = $users_info[$index]["user_id"];
						$info["nickname"] = $users_info[$index]["nickname"];
						$info["headimg"] = $users_info[$index]["headimg"];
						$info["role"] = $users_info[$index]["role"];
						$info['sex'] = $users_info[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos, $info);
			}

			$ret['code'] = 0;
			$ret['data'] = $out_rank_infos;
			echo json_encode($ret);
			return;
		}

		public function query_givelove_rank()
		{
			$user_id = $_REQUEST['user_id'];
			$bar_id = $_REQUEST['bar_id'];
			if(!isset($user_id) || !isset($bar_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$redis_set_givelove_name = 'bar'.$bar_id.'_givelove'.date('Ymd');
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$rank_infos = $redis->zRevRange($redis_set_givelove_name, 0, -1, true);
			$user_ids = $redis->zRevRange($redis_set_givelove_name, 0, -1);
			$users_info = $this->user_model->get_user_by_userids($user_ids);

			$out_rank_infos = array();
			foreach($rank_infos as $key => $value)
			{
				$info = array();
				$info["givelove"] = $value;
				for($index = 0; $index < count($users_info); $index++)
				{
					if($key == $users_info[$index]["user_id"])
					{
						$info['user_id'] = $users_info[$index]["user_id"];
						$info["nick"] = $users_info[$index]["nick"];
						$info["headimg"] = $users_info[$index]["headimg"];
						$info["role"] = $users_info[$index]["role"];
						$info['sex'] = $users_info[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos, $info);
			}

			$ret['code'] = 0;
			$ret['data'] = $out_rank_infos;
			echo json_encode($ret);
			return;
		}

		public function query_all_rank()
		{
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');
			
			$bar_id = $_REQUEST['bar_id'];
			$redis_set_givelove_name = 'bar'.$bar_id.'_givelove'.date('Ymd');
			$redis = new Redis();
			$retCode = $redis->connect($this->config->item('REDIS_IP'), 6379);
			if(!$retCode)
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}
			$rank_infos_givelove = $redis->zRevRange($redis_set_givelove_name, 0, -1, true);
			$user_ids_givelove = $redis->zRevRange($redis_set_givelove_name, 0, -1);
			$users_info_givelove = $this->user_model->get_user_by_userids($user_ids_givelove);

			$out_rank_infos_givelove = array();
			foreach($rank_infos_givelove as $key => $value)
			{
				$info = array();
				$info["givelove"] = $value;
				for($index = 0; $index < count($users_info_givelove); $index++)
				{
					if($key == $users_info_givelove[$index]["user_id"])
					{
						$info['user_id'] = $users_info_givelove[$index]["user_id"];
						$info["nick"] = $users_info_givelove[$index]["nick"];
						$info["headimg"] = $users_info_givelove[$index]["headimg"];
						$info["role"] = $users_info_givelove[$index]["role"];
						$info['sex'] = $users_info_givelove[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos_givelove, $info);
			}

			$ret['code'] = 0;
			$ret['data_givelove'] = $out_rank_infos_givelove;






			$redis_set_gotgift_name = 'bar'.$bar_id.'_gotgift'.date('Ymd');
			$rank_infos_gotgift = $redis->zRevRange($redis_set_gotgift_name, 0, -1, true);
			$user_ids_gotgift = $redis->zRevRange($redis_set_gotgift_name, 0, -1);
			$users_info_gotgift = $this->user_model->get_user_by_userids($user_ids_gotgift);

			$out_rank_infos_gotgift = array();
			foreach($rank_infos_gotgift as $key => $value)
			{
				$info = array();
				$info["gotmoney"] = $value;
				for($index = 0; $index < count($users_info_gotgift); $index++)
				{
					if($key == $users_info_gotgift[$index]["user_id"])
					{
						$info['user_id'] = $users_info_gotgift[$index]["user_id"];
						$info["nick"] = $users_info_gotgift[$index]["nick"];
						$info["headimg"] = $users_info_gotgift[$index]["headimg"];
						$info["role"] = $users_info_gotgift[$index]["role"];
						$info['sex'] = $users_info_gotgift[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos_gotgift, $info);
			}

			$ret['data_gotgift'] = $out_rank_infos_gotgift;




			$onlinetime_db = 'online_time';
			$rank_infos_online = $redis->zRevRange($onlinetime_db, 0, -1, true);
			$user_ids_online = $redis->zRevRange($onlinetime_db, 0, -1);
			$out_rank_infos_online = array();
			$users_info_online = $this->user_model->get_user_by_userids($user_ids_online);
			foreach($rank_infos_online as $key => $value)
			{
				$info = array();
				$info["time"] = $value;
				for($index = 0; $index < count($users_info_online); $index++)
				{
					if($key == $users_info_online[$index]["user_id"])
					{
						$info['user_id'] = $users_info_online[$index]["user_id"];
						$info["nick"] = $users_info_online[$index]["nick"];
						$info["headimg"] = $users_info_online[$index]["headimg"];
						$info["role"] = $users_info_online[$index]["role"];
						$info['sex'] = $users_info_online[$index]['sex'];
						break;
					}
				}
				array_push($out_rank_infos_online, $info);
			}

			$ret['data_online'] = $out_rank_infos_online;


			$redis_set_givegift_name = 'bar'.$bar_id.'_givegift'.date('Ymd');
			$rank_infos_givegift = $redis->zRevRange($redis_set_givegift_name, 0, -1, true);

			$user_ids_givegift = $redis->zRevRange($redis_set_givegift_name, 0, -1);
			$users_info_givegift = $this->user_model->get_user_by_userids($user_ids_givegift);
			$out_rank_infos_givegift = array();
			foreach($rank_infos_givegift as $key => $value)
			{
				$info = array();
				$info["givemoney"] = $value;
				for($index = 0; $index < count($users_info_givegift); $index++)
				{
					if($key == $users_info_givegift[$index]["user_id"])
					{
						$info['user_id'] = $users_info_givegift[$index]["user_id"];
						$info["nick"] = $users_info_givegift[$index]["nick"];
						$info["headimg"] = $users_info_givegift[$index]["headimg"];
						$info["role"] = $users_info_givegift[$index]["role"];
						break;
					}
				}
				array_push($out_rank_infos_givegift, $info);
			}

			$ret['code'] = 0;
			$ret['data_givegift'] = $out_rank_infos_givegift;

			echo json_encode($ret);

		}

		public function pay()
		{
			$user_id = $_REQUEST['user_id'];//we must encrypt user_id at last
			$userinfo = $this->user_model->get_user_by_userid($user_id);
			if(!$userinfo)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			$openid = $userinfo['openid'];
			$bar_id = $_POST['bar_id'];
			$desk_id = $_POST['desk_id'];
			$coin_count = $_POST['coin_count'];
			$order_info = 'op=buy_coin&coin_count='.$coin_count;
			$money = $coin_count;
			//generate orderid
			$order_id = $user_id.time().($this->getRandomStr(8));
			if(!$this->order_model->insert_order($order_id, $bar_id, $desk_id, $user_id, $order_info,Order_model::ORDER_STATUS_WAIT,
												$user_id, $coin_count, $money,Order_model::ORDER_TYPE_RECHARGE, 'bakebi'))
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			$unifiedOrder = new WxPayUnifiedOrder();
			$unifiedOrder->SetBody('购买'.$coin_count.'个八客币');//商品或支付单简要描述4
			$unique_id = md5($order_id);
			$unifiedOrder->SetAttach($unique_id);//附加数据，在查询API和支付通知中原样返回，该字段主要用于商户携带订单的自定义数据
			$unifiedOrder->SetOut_trade_no($order_id);
			$unifiedOrder->SetTotal_fee(1);//单位：分（到时候根据购买虚拟币数量计算）
			$unifiedOrder->SetTime_start(date("YmdHis"));
			$unifiedOrder->SetTime_expire(date("YmdHis", time() + 600));
			$unifiedOrder->SetGoods_tag('优惠券0-折扣0');//商品标记，代金券或立减优惠功能的参数

			$unifiedOrder->SetNotify_url("http://".$_SERVER["HTTP_HOST"]."/index.php/user/wxpay_notify");//异步通知地址
			$unifiedOrder->SetTrade_type("JSAPI");
			$unifiedOrder->SetOpenid($openid);
			$order_result = WxPayApi::unifiedOrder($unifiedOrder);
			if(!$order_result)
			{
				$ret['code'] = -1003;
				echo json_encode($ret);
				return;
			}

			if($order_result['return_code'] == "SUCCESS")
			{
				$prepay_id = $order_result['prepay_id'];
				if(!$this->order_model->update_prepay_id($order_id, $prepay_id))
				{
					$ret['code'] = ERRCODE_OPERATE_MYSQL;
					echo json_encode($ret);
					return;
				}
				
				$tools = new JsApiPay();
				$jsApiParameters = $tools->GetJsApiParameters($order_result);

				$ret['code'] = 0;
				$ret['jsApiParameters'] = $jsApiParameters;
				$ret['order_id'] = $order_id;//we must encrypt the message at last.
				echo json_encode($ret);
				return;
			}
			else
			{
				$ret['code'] = -1004;
				$ret['msg'] = $order_result['return_code'];
				echo json_encode($ret);
				return;
			}
			return;
		}

		public function query_money()
		{
			$user_id = $_REQUEST['user_id'];
			if(!isset($user_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$money_info = $this->money_model->get_moneyinfo($user_id);
			if(!$money_info)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			$ret['code'] = 0;
			$ret['money'] = $money_info['money'];
			echo json_encode($ret);
			return;
		}
		public function deliver_good()
		{
			$user_id = $_COOKIE['userid'];//we must encrypt user_id at last
			if(!isset($user_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$order_id = $_REQUEST['order_id'];//we must decrypt the order id
			if(!isset($order_id))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$order = $this->order_model->getorder_by_orderid($order_id);
			if(!$order)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			$order_info = $order['order_info'];
			// $ret['code'] = 102;
			// $ret['msg'] = $order_info;
			// echo json_encode($ret);
			// return;
			$infos = $this->get_param($order_info);
			$item_id = $infos['item_id'];
			$item_count = $infos['item_count'];
			if(!$item_id || !$item_count)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}

			// if(!$this->process_good_request($user_id, $item_count))
			// {
			// 	$ret['code'] = ERRCODE_OPERATE_MYSQL;
			// 	echo json_encode($ret);
			// 	return;
			// }

			$ret['code'] = 0;
			echo json_encode($ret);
			return;
		}

		//uniform process deliver good logic
		private function process_good_request($user_id, $coin_count)
		{
			return $this->money_model->add_money($user_id, $coin_count);
			// if($item_id == 1)//if it's bake money, we process it here.
			// {
			// 	return $this->money_model->add_money($user_id, $coin_count);
			// }
			// else
			// {//other goods

			// }
		}

		//$info = a=b&c=d&e=f...
		public function get_param($info)
		{
			$data = array();
			$infos = explode('&', $info); 
			for($index = 0; $index < count($infos); $index++)
			{
				$tmp = explode('=', $infos[$index]);
				$key = $tmp[0];
				$value = $tmp[1];
				//echo 'key='.$key.'&value='.$value;
				$data[$key] = $value;
			}
			return $data;
		}

		public function wxpay_notify()
		{
			//log_message('debug', 'wxpay_notify');
			$WX_RET_DATA = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
			$order_id = $WX_RET_DATA['out_trade_no'];
			$order = $this->order_model->getorder_by_orderid($order_id);
			log_message('debug', 'wxnotify1 order_id='.$order_id);
			if(!$order)
			{
				return;
			}

			if($order['order_status'] == 1)
			{
				return;
			}
			$s2=implode(',',$order);
			log_message('debug', 'wxnotify2='.$order['order_status']);
			if(!$this->order_model->update_order_status($order_id, 1))
			{
				//log_message('debug', 'wxpay_notify');
			}
			//发货
			log_message('debug', 'wxnotify3');
			$order_info = $order['order_info'];
			$infos = $this->get_param($order_info);
			$coin_count = $infos['coin_count'];
			if(!$coin_count)
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}
			$user_id = $order['user_id'];
			if(!$this->process_good_request($user_id, $coin_count))
			{
				$ret['code'] = ERRCODE_OPERATE_MYSQL;
				echo json_encode($ret);
				return;
			}
			log_message('debug', 'wxnotify4');
			$wxapi = new Wxapi($this->config->item("WX_APPID"), $this->config->item("WX_SECRET"));
			$wxapi->deliver_notify();
		}

		private static function getRandomStr($length = 8) 
		{
			$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
			$str ="";
			for ( $i = 0; $i < $length; $i++ )  {  
				$str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			} 
			return $str;
		}

		public function video_page() {
			$bar_id = $_REQUEST['bar_id'];
			$barinfo = $this->bar_model->get_barinfo($bar_id);
			$data['barinfo'] = $barinfo;
			$this->load->view('videopage', $data);
		}

		public function query_robot() {
			// 指定允许其他域名访问  
			header('Access-Control-Allow-Origin:*');  
			// 响应类型  
			header('Access-Control-Allow-Methods:POST');  
			// 响应头设置  
			header('Access-Control-Allow-Headers:x-requested-with,content-type');  
			
			$robots = $this->user_model->query_robots();
			$ret['code'] = 0;
			$ret['robots'] = $robots;
			echo json_encode($ret);
			return;
		}

		// public function get_rctoken()
		// {
		// 	$user_id = $_REQUEST['user_id'];
		// 	$nickname = $_REQUEST['nickname'];
		// 	$headimg = $_REQUEST['headimg'];

		// 	$data = 'userId='.$user_id.'&name='.$nickname.'&portraitUri='.$headimg;

		// 	$timestamp = time();
		// 	$appkey = 'x18ywvqf8xdcc';
		// 	$appsecret = 'IlKQIFBSPS';
		// 	$nonce = rand();
		// 	$singnature = sha1($appsecret.$nonce.$timestamp);
		// 	$headers = array(
		// 			'App-Key:'.$appkey,
		// 			'Nonce:'.$nonce,
		// 			'Timestamp:'.$timestamp,
		// 			'Signature:'.$singnature,
		// 			'Content-Type: application/x-www-form-urlencoded',
		// 		);

		// 	$url = 'https://api.cn.ronghub.com/user/getToken.json';
		// 	$ret_json = $this->https->https_request($url, $data, $headers);
		// 	echo $ret_json;
		// }


		public function create_rcchatroom()
		{
			$bar_id = $_REQUEST['bar_id'];
			$url = "https://api.cn.ronghub.com/chatroom/create.json";
			$timestamp = time();
			$appkey = 'x18ywvqf8xdcc';
			$appsecret = 'IlKQIFBSPS';
			$nonce = rand();
			$singnature = sha1($appsecret.$nonce.$timestamp);
			$headers = array(
					'App-Key:'.$appkey,
					'Nonce:'.$nonce,
					'Timestamp:'.$timestamp,
					'Signature:'.$singnature,
					'Content-Type: application/x-www-form-urlencoded',
				);

			$data = 'chatroom['.$bar_id.']='.'mokebar'.$bar_id;
			$ret_json = $this->https->https_request($url, $data, $headers);
			echo $ret_json;
		}

		public function get_qiniu_pushurl()
		{
			if(!isset($_REQUEST['bar_id']))
			{
				$ret['code'] = ERRCODE_INVALID_PARAM;
				echo json_encode($ret);
				return;
			}

			$bar_id = $_REQUEST['bar_id'];
			$RTMPPublishDomain = "pili-publish.toqive.com.cn";
			$Hub = "moke";
			$streamkey = $this->getRandomStr(16);
			$exptime = time()+3600*24;
			$path = "/".$Hub."/".$streamkey;
			$skey = "tKGq-NeRWt_c8rCt_o3WsIHOtkTsHAK7hF3VGYVS";
			$akey = "H-XXK1YKv9S1Btd2fcXC2NaQb-KvrTUHCxhhJTRJ";
			$sign = hash_hmac("sha1", $path, $skey, TRUE);
			$encodedSign = base64_encode($sign);
			$token = $akey.":".$encodedSign;

			$push_url = "rtmp://".$RTMPPublishDomain."/".$Hub."/".$streamkey."?e=".$exptime."&token=".$token;
			
			$hls_url = "http://pili-live-hls.toqive.com.cn/".$Hub."/".$streamkey.".m3u8";
			$rtmp_url = "rtmp://pili-live-rtmp.toqive.com.cn/".$Hub."/".$streamkey;
			$this->barlives_model->add_live($bar_id, $push_url, $hls_url, $rtmp_url, 1);
			$ret['code'] = 0;
			$ret['data'] = array('push_url' => $push_url, 'hls_url' => $hls_url, 'rtmp_url' => $rtmp_url);
			echo json_encode($ret);
		}

		public function insert_robot()
		{
			// $robots_json = file_get_contents("C:\wwwroot\zysoft\application\controllers/robot.json");
			// $robots = json_decode($robots_json, true);
			// for($i = 0; $i < count($robots); $i++)
			// {
			// 	$open_id = $this->getRandomStr(32);
			// 	$nick =$robots[$i]['nickname'];
			// 	$sex = $robots[$i]['sex'];
			// 	$headimg = $robots[$i]['headimg'];
			// 	$last_login_ip = "117.136.75.216";

			// 	$this->user_model->add_robot($open_id, 
			// 								$nick,
			// 								$sex, 
			// 								$headimg,
			// 								$_SERVER['REMOTE_ADDR'],
			// 								1,
			// 								'g');
			// }
		}
	}
?>