<?php
	require_once(APPPATH."controllers/errcode.php");
	class Item_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function query_iteminfo($item_id)
		{
			$query = $this->db->get_where('item', array('item_id' => $item_id));
			log_message('debug', 'affect_rows='.$item_id);
			if($this->db->affected_rows() > 0)
			{
				$item_info = $query->row_array();
				return $item_info;
			}
			return null;
		}
	}
?>