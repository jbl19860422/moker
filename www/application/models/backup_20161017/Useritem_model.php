<?php
	require_once(APPPATH."controllers/errcode.php");
	class Useritem_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function query_useritem($user_id, $item_id)
		{
			$query = $this->db->get_where('user_item', array('user_id' => $user_id, 'item_id' => $item_id));
			if($this->db->affected_rows() > 0)
			{
				$useritems = $query->row_array();
				return $useritems;
			}
			return null;
		}

		public function insert_item($user_id, $item_id, $item_count)
		{
			$user_items = array(
					'user_id' => $user_id,
					'item_id' => $item_id,
					'count' => $item_count
				);

			return $this->db->insert('user_item', $user_items);
		}

		public function update_item($id, $new)
		{
			$where = array (
					'id' => $id
				);
			return $this->db->update('user_item', $new, $where);
		}

		public function add_item($user_id, $item_id, $item_count)
		{
			$user_item = $this->query_useritem($user_id, $item_id);
			if(!$user_item)
			{//不存在，则插入
				return $this->insert_item($user_id, $item_id, $item_count);
			}

			$user_item['count'] = $user_item['count'] + $item_count;
			return $this->update_item($user_item['id'], $user_item);
		}
	}
?>