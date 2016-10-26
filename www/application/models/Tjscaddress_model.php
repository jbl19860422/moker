<?php
	class Tjscaddress_model extends CI_Model {
		private $tableName = 'user_address';
		private $db = null;

		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_address_info($id) {
			$query = $this->db->get_where($this->tableName, array('id' => $id));
			if($this->db->affected_rows() > 0) {
				$order_info = $query->row_array();
				return $order_info;
			}
			return null;
		}

		public function query_address_by_ids($ids) {
			if(count($ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('id', $ids);
			$query = $this->db->get($this->tableName);
			$addresses = [];
			foreach($query->result_array() as $row)
			{
				$addresses[] = $row;
			}
			return $addresses;
		}

		public function query_user_address($user_id) {
			$query = $this->db->get_where($this->tableName, array('user_id' => $user_id));
			if($this->db->affected_rows() > 0) {
				$address_info = $query->row_array();
				return $address_info;
			}
			return null;
		}

		public function set_address_info($id, $address_info) {
			$where = array (
					'id' => $id
				);
			return $this->db->update($this->tableName, $address_info, $where);
		}

		public function add_address($address_info) {
			return $this->db->insert($this->tableName, $address_info);
		}
	}
?>