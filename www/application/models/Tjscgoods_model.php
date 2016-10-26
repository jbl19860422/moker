<?php
	require_once(APPPATH."controllers/errcode.php");
	class Tjscgoods_model extends CI_Model {
		private $tableName = 'goods';
		private $db = null;

		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_goodsinfo($goods_id) {
			$query = $this->db->get_where($this->tableName, array('goods_id' => $goods_id));
			if($this->db->affected_rows() > 0) {
				$goods_info = $query->row_array();
				return $goods_info;
			}
			return null;
		}

		public function query_seller_goods($seller_id) {
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0) {
				$goods_info = $query->result_array();
				return $goods_info;
			}
			return [];
		}

		public function add_goods($goods_info) {
			return $this->db->insert($this->tableName, $goods_info);
		}

		public function del_goods($goods_id) {
			$this->db->where('goods_id',$goods_id);
	        $this->db->delete($this->tableName);
		}

		public function set_goods($goods_id, $goods_info)
		{
			$where = array (
					'goods_id' => $goods_id
				);
			return $this->db->update($this->tableName, $goods_info, $where);
		}


		public function query_goods_by_ids($goods_ids) {
			if(count($goods_ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('goods_id', $goods_ids);
			$query = $this->db->get($this->tableName);
			$goods_infos = [];
			foreach($query->result_array() as $row)
			{
				$goods_infos[] = $row;
			}
			return $goods_infos;
		}		
	}
?>