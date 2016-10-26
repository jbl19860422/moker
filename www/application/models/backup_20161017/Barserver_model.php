<?php
	class Barserver_model extends CI_Model {

		const USER_TYPE_SERVER 	= 0;
		const USER_TYPE_SINGER	= 1;

		const USER_VERIFIED_WAIT = 0;
		const USER_VERIFIED_DONE = 1;
		
		public function __construct()
		{
			$this->load->database();
		}

		public function delSinger($id)
		{
			$this->db->where('id',$id);
	        $this->db->delete('bar_servers');
		}

		public function verifySinger($id)
		{
			$where = array (
					'id' => $id
				);

			$data = array (
					'verified' => 1,
					'verified_time' => time()
				);

			$this->db->update('bar_servers', $data, $where);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function if_user_id_exists($user_id)
		{
			$query = $this->db->get_where('bar_servers', array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function if_user_exists($user_id, $bar_id)
		{
			$query = $this->db->get_where('bar_servers', array('user_id' => $user_id, 'bar_id' => $bar_id));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_user_id($user_id)
		{
			$query = $this->db->get_where('bar_servers', array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_singers($bar_id)
		{
			$query = $this->db->get_where('bar_servers', array('bar_id' => $bar_id, 'type' => 0));
			if($this->db->affected_rows() > 0)
			{
				$singers_info = $query->result_array();
				return $singers_info;
			}
			return null;
		}

		public function get_servers($bar_id)
		{
			$query = $this->db->get_where('bar_servers', array('bar_id' => $bar_id, 'type' => 1));
			if($this->db->affected_rows() > 0)
			{
				$servers_info = $query->result_array();
				return $servers_info;
			}
			return null;
		}

		public function add_user($type, $user_id, $regtime, $verified, $bar_ids)
		{
			$user = array(
					'type' => $type,	
					'user_id' => $user_id,
					'regtime' => $regtime,
					'verified' => $verified,
					'bar_id' => $bar_ids
				);

			return $this->db->insert('bar_servers', $user);
		}

		public function update_user($old_user, $new_user)
		{
			$where = array (
					'id' => $old_user['id']
				);

			$data = array (
					'type' => $new_user['type'],
					'regtime' => $new_user['regtime'],
					'verified' => $new_user['verified'],
					'verifiedtime' => $new_user['verifiedtime'],
					'remarks' => $new_user['remarks'],
					'bar_id' => $new_user['bar_id'],
				);

			$this->db->update('bar_servers', $data, $where);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}
	}
?>