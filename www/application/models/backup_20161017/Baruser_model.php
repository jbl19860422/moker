<?php
	class Baruser_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}
		public function insert_user_if_not_exist($bar_id, $user_id, $nickname,
												$headimg, $sex)
		{
			$query = $this->db->get_where('bar_users', 
											array('bar_id' => $bar_id,
												  'user_id' => $user_id));

			if($this->db->affected_rows() > 0)
			{
				return true;
			}

			$user = array(
					'bar_id' => $bar_id,	
					'user_id' => $user_id,
					'nickname' => $nickname,
					'headimg' => $headimg,
					'sex' => $sex
				);

			return $this->db->insert('bar_users', $user);
		}

		public function query_bar_users($bar_id)
		{
			$query = $this->db->get_where('bar_users', array('bar_id' => $bar_id));
			if($this->db->affected_rows() > 0)
			{
				return $query->result_array();
			}
			return null;
		}
	}
?>