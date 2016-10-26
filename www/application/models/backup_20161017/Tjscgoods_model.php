<?php
	require_once(APPPATH."controllers/errcode.php");
	class Tjscgoods_model extends CI_Model {
		private $tableName = 'goods';
		private $db = null;

		public function __construct()
		{
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_goodsinfo($goods_id)
		{
			$query = $this->db->get_where($this->tableName, array('goods_id' => $goods_id));
			if($this->db->affected_rows() > 0)
			{
				$goods_info = $query->row_array();
				return $goods_info;
			}
			return null;
		}

		public function query_seller_goods($seller_id)
		{
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0)
			{
				$goods_info = $query->result_array();
				return $goods_info;
			}
			return null;
		}
	}
?>