<?php
	require_once(APPPATH."controllers/errcode.php");
	class Tjsccart_model extends CI_Model {
		private $tableName = 'user_cart';
		private $db = null;

		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function add_cart($user_id, $seller_id, $goods_id) {
			$cart_item = array(
					'user_id' => $user_id,	
					'goods_id' => $goods_id,
					'seller_id' => $seller_id,
					'goods_count' => 1,
 					'timestamp' => time()
				);

			return $this->db->insert($this->tableName, $cart_item);
		}

		public function set_cartitem_count($cart_id, $count) {
			$where = array (
					'id' => $cart_id
				);

			$new = array(
					'goods_count' => $count
				);
			return $this->db->update($this->tableName, $new, $where);
		}

		public function query_user_cart($user_id) {
			$query = $this->db->get_where($this->tableName, array('user_id' => $user_id));
			if($this->db->affected_rows() > 0) {
				$cart_items = $query->result_array();
				if(!$cart_items) {
					$cart_items = [];
				}
				return $cart_items;
			}
			return null;
		}

		public function del_cart($cart_id) {
			$this->db->where('id', $cart_id);
	        $this->db->delete($this->tableName);
		}


		public function del_cart_items($cart_ids) {
			$this->db->or_where_in('id', $cart_ids);
	        $this->db->delete($this->tableName);
		}
	}
?>