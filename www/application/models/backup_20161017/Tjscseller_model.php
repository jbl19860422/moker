<?php
	class Tjscseller_model extends CI_Model {
		private $tableName = 'seller';
		private $tjsc = null;
		public function __construct()
		{
			parent::__construct();
			$this->tjsc = $this->load->database('tjsc', TRUE);  
		}

		public function get_user($username, $password)
		{
			$query = $this->tjsc->get_where($this->tableName, array('username' => $username, 'password' => $password));
			if($this->tjsc->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function set_live_status($seller_id, $live_status)
		{
			$where = array (
					'seller_id' => $seller_id
				);

			$data = array (
					'live_status' => $live_status
				);

			$this->tjsc->update($this->tableName, $data, $where);
		}
		public function update_seller_live($seller_id, $live_name, $live_desc, $live_cover, $rtmp_push_url, $rtmp_play_url, $hls_play_url, $live_status)
		{
			$where = array (
					'seller_id' => $seller_id
				);

			$data = array (
					'live_name' => $live_name,
					'live_desc' => $live_desc,
					'live_cover' => $live_cover,
					'rtmp_push_url' => $rtmp_push_url,
					'rtmp_play_url' => $rtmp_play_url,
					'hls_play_url' => $hls_play_url,
					'live_status' => $live_status
				);

			$this->tjsc->update($this->tableName, $data, $where);
		}

		public function get_seller_by_id($seller_id)
		{
			$query = $this->tjsc->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->tjsc->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		// public function add_user($openid, $nickname, 
		// 						 $sex, $headimg,
		// 						 $last_ip, $bar_id,
		// 						 $role)
		// {
		// 	$user = array(
		// 			'open_id' => $openid,	
		// 			'nickname' => $nickname,
		// 			'sex' => $sex,
		// 			'headimg' => $headimg,
		// 			'last_login_time' => time(),
		// 			'status' => 1,
		// 		);

		// 	return $this->tjsc->insert($this->tableName, $user);
		// }
	}
?>