<?php
	class Mobile extends CI_Controller {
		public function __construct() {
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
			$this->load->helper('http');
			$this->load->helper('string');
			$this->load->library('aes');
			$this->load->library('redisclient');
			$this->load->library('rongcloudapi');
		}

		/*
		* 功能：手机端入口页面
		*/
		public function index() {
			echo 'aaa';return;
			if(!isset($_GET['code']) || !isset($_GET['state'])) {
				show_404();
				return;
			}

			$CODE = $_GET['code'];
			$STATE = $_GET['state'];
			list($bar_id, $desk_id) = explode("|", $STATE);
			$user_id = $this->checkLogin();
			if(!$user_id) {//启动微信登陆
				$userinfo_wechat = $this->loginWeChat($bar_id, $desk_id, $CODE);
				$user_info = $this->user_model->get_user_by_openid($userinfo_wechat['openid']);
				if(!$user_info) {
					$this->user_model->add_user($userinfo_wechat['openid'], $userinfo_wechat['nickname'], $userinfo_wechat['sex'], $userinfo_wechat['headimgurl']);

					$user_info = $this->user_model->get_user_by_openid($userinfo_wechat['openid']);
				}
			} else {//否则，直接查询用户信息
				$user_info = $this->user_model->get_user_by_userid($user_id);
			}

			if(!$user_info) {//查询不到，可能是被恶意攻击
				show_404();
				return;
			}
			$user_id = $user_info['user_id'];
			//查询酒吧，歌手信息
			$bar_info = $this->bar_model->get_barinfo($bar_id);
			if(!$bar_info) {
				show_404();
				return;
			}

			//查询歌手信息
			if($bar_info['singer_id'] != "" && $bar_info['singer_id'] != "null" && $bar_info['singer_id'] != -1) {
				$singer_info = $this->user_model->get_user_by_userid($bar_info['singer_id']);
			}
			//聊天室 token 
			$nickname = $user_info['nickname'];
			$headimg = $user_info['headimg'];
			$rc_token = $this->rongcloudapi->get_rctoken($user_id, $nickname, $headimg);
			if($rc_token) {
				$data['rc_token'] = $rc_token;
			} else {
				$data['rc_token'] = "";
			}

			$data['barinfo'] = $bar_info;
			$data['singer_info'] = $singer_info;
			$data['user_info'] = $user_info;
			$data['bar_id'] = $bar_id;
			$data['desk_id'] = $desk_id;
			//写入cookie登陆信息
			$login_token = genRandomStr(16);
			$logindata = $this->aes->aes128cbcEncrypt($user_id."|".$login_token);
			$this->redisclient->setLoginToken($user_id, $login_token);
			$this->input->set_cookie('logindata', $logindata, time()+3600*24);
			$this->input->set_cookie('headimg', $user_info['headimg'], time()+3600*24);
			$this->input->set_cookie('nickname', $user_info['nickname'], time()+3600*24);
			$this->input->set_cookie('bar_id', $bar_id, time()+3600*24);
			
			$this->load->view('index_page', $data);
		}

		/*
		* 个人页面
		*/
		public function my() {
			$user_id = $this->checkLogin();
			if(!$user_id) {
				show_404();
				return;
			}
			$user_info = $this->user_model->get_user_by_userid($user_id);
			if(!$user_info) {
				show_404();
				return;
			}
			$bar_id = $_COOKIE['bar_id'];
			$bar_info = $this->bar_model->get_barinfo($bar_id);
			if(!$bar_info) {
				show_404();
				return;
			}

			$chatRecords = $this->chatrecord_model->query_unviewed_chatrecord($user_id);
			if($chatRecords != null) {
				$data['chatRecords'] = $chatRecords;
			} else {
				$data['chatRecords'] = [];
			}

			$bar_info['cur_time'] = time();
			$money_info = $this->money_model->get_moneyinfo($user_id);
			$user_info['money'] = $money_info['money'];
			$data['user_info'] = $user_info;
			//查询酒吧最近消息
			$data['bar_message'] = $this->redisclient->getBarMessage($bar_id);
			$data['sys_message'] = $this->redisclient->getSysMessage($bar_id);
			$data['bar_info'] = $bar_info;
			$this->load->view('my_page', $data);
		}

		/*
		* 校验用户是否登陆过了
		* @return 登陆过返回user_id，否则返回null
		*/
		private function checkLogin() {
			if(!isset($_COOKIE['logindata'])) {
				return null;
			}
			$logindata = $this->aes->aes128cbcDecrypt($_COOKIE['logindata']);
			list($user_id, $login_token) = explode("|", $logindata);
			$store_logintoken = $this->redisclient->getLoginToken($user_id);
			if(!$store_logintoken) {
				return null;
			}

			if($login_token != $store_logintoken) {
				return null;
			}

			return $user_id;
		}

		/*
		* 功能：微信登陆
		*/
		private function loginWeChat($bar_id, $desk_id, $CODE) {
			$url_access_token = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->config->item('WX_APPID')."&secret=".$this->config->item('WX_SECRET')."&code=".$CODE."&grant_type=authorization_code";
			$ret_json = http_request($url_access_token);
			$json_data = json_decode($ret_json, true);
			if(isset($json_data['errcode'])) {//微信错误，重新请求一次
				header("location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0523023df5aa4bf1&redirect_uri=http://dream.waimaipu.cn/index.php/mobile/index&response_type=code&scope=snsapi_userinfo&state=".$bar_id."%7C".$desk_id."#wechat_redirect");
				return null;
			}
			$access_token = $json_data['access_token'];
			$openid = $json_data['openid'];
			$url_userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid;
		
			$ret_json = http_request($url_userinfo);
			$json_data = json_decode($ret_json, true);
			return $json_data;
		}
	}
?>
