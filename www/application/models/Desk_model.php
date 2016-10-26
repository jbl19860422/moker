<?php
	require_once(APPPATH."controllers/errcode.php");
	class Desk_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function query_desks($bar_id)
		{
			$query = $this->db->get_where('desk', array('bar_id' => $bar_id));
			if($this->db->affected_rows() > 0)
			{
				$desk_infos = $query->result_array();
				return $desk_infos;
			}
			return null;
		}

		public function update_desk($bar_id, $desk_id, $new_desk_id, $desk_name, $qrcode_img)
		{
			$where = array (
					'bar_id' => $bar_id,
					'desk_id' => $desk_id
				);

			$new = array(
					'desk_id' => $new_desk_id,
					'desk_name' => $desk_name,
					'qrcode_img' => $qrcode_img
				);
			return $this->db->update('desk', $new, $where);
		}

		public function add_desk($bar_id, $desk_id, $desk_name, $qrcode_img)
		{
			$desk = array(
					'bar_id' => $bar_id,
					'desk_id' => $desk_id,
					'desk_name' => $desk_name,
					'qrcode_img' => $qrcode_img
				);

			return $this->db->insert('desk', $desk);
		}

		public function del_desk($bar_id, $desk_id)
		{
			$this->db->where('bar_id',$bar_id);
			$this->db->where('desk_id',$desk_id);
	        $this->db->delete('desk');
		}
	}
?>