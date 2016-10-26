<?php
	class Tjscfollow_model extends CI_Model {
		private $tableName = 'user_follow';
		private $db = null;

		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_user_follows($user_id) {
			$query = $this->db->get_where($this->tableName, array('user_id' => $user_id));
			if($this->db->affected_rows() > 0) {
				$follow_infos = $query->result_array();
				return $follow_infos;
			}
			return null;
		}

		public function insert_user_follow($user_id, $seller_id) {
			$follow = array(
					'user_id' => $user_id,	
					'seller_id' => $seller_id,
					'timestamp' => time()
				);

			return $this->db->insert($this->tableName, $follow);
		}

		public function del_user_follow($user_id, $seller_id) {
			$this->db->where('user_id', $user_id);
			$this->db->where('seller_id', $seller_id);
	        $this->db->delete($this->tableName);
		}
	}
?>