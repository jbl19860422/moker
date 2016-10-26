<?php
	class Tjscorder_model extends CI_Model {
		private $tableName = 'order';
		private $db = null;

		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_orderinfo($order_id) {
			$query = $this->db->get_where($this->tableName, array('order_id' => $order_id));
			if($this->db->affected_rows() > 0) {
				$order_info = $query->row_array();
				return $order_info;
			}
			return null;
		}

		public function query_user_orders($user_id) {
			$query = $this->db->get_where($this->tableName, array('user_id' => $user_id));
			if($this->db->affected_rows() > 0) {
				$order_infos = $query->result_array();
				return $order_infos;
			}
			return [];
		}

		public function query_seller_orders($seller_id) {
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0) {
				$order_infos = $query->result_array();
				return $order_infos;
			}
			return [];
		}

		public function add_order($order_info) {
			return $this->db->insert($this->tableName, $order_info);
		}

		public function del_order($order_id) {
			$this->db->where('order_id',$order_id);
	        $this->db->delete($this->tableName);
		}

		public function del_orders($order_ids) {
			$this->db->or_where_in('order_id', $order_ids);
			$this->db->delete($this->tableName);
		}

		public function set_order($order_id, $order_info)
		{
			$where = array (
					'order_id' => $order_id
				);
			return $this->db->update($this->tableName, $order_info, $where);
		}


		public function query_order_by_ids($order_ids) {
			if(count($order_ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('order_id', $order_ids);
			$query = $this->db->get($this->tableName);
			$order_infos = [];
			foreach($query->result_array() as $row)
			{
				$order_infos[] = $row;
			}
			return $order_infos;
		}		
	}
?>