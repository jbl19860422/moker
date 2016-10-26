<?php
	class Tjscseller_model extends CI_Model {
		private $tableName = 'seller';
		private $db = null;
		public function __construct()
		{
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function get_user($username, $password)
		{
			$query = $this->db->get_where($this->tableName, array('username' => $username, 'password' => $password));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		// public function set_live_status($seller_id, $live_status) {
		// 	$where = array (
		// 			'seller_id' => $seller_id
		// 		);

		// 	$data = array (
		// 			'live_status' => $live_status
		// 		);

		// 	$this->db->update($this->tableName, $data, $where);
		// }

		// public function update_seller_live($seller_id, $live_name, $live_desc, $live_cover, $rtmp_push_url, $rtmp_play_url, $hls_play_url, $live_status) {
		// 	$where = array (
		// 			'seller_id' => $seller_id
		// 		);

		// 	$data = array (
		// 			'live_name' => $live_name,
		// 			'live_desc' => $live_desc,
		// 			'live_cover' => $live_cover,
		// 			'rtmp_push_url' => $rtmp_push_url,
		// 			'rtmp_play_url' => $rtmp_play_url,
		// 			'hls_play_url' => $hls_play_url,
		// 			'live_status' => $live_status
		// 		);

		// 	$this->db->update($this->tableName, $data, $where);
		// }

		public function get_seller_by_id($seller_id) {
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function query_seller_by_ids($seller_ids) {
			if(count($seller_ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('seller_id', $seller_ids);
			$query = $this->db->get($this->tableName);
			$seller_infos = [];
			foreach($query->result_array() as $row)
			{
				$seller_infos[] = $row;
			}
			return $seller_infos;
		}		

		public function inc_followed_count($seller_id) {
			$sql = "UPDATE ".$this->tableName." SET follow_count=follow_count+1"." WHERE seller_id=".$seller_id;
			$query = $this->db->query($sql);
		}

		public function dec_followed_count($seller_id) {
			$sql = "UPDATE ".$this->tableName." SET follow_count=follow_count-1"." WHERE seller_id=".$seller_id;
			$query = $this->db->query($sql);
		}
	}
?>