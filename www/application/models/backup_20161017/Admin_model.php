<?php
	class Admin_model extends CI_Model {

		const USER_TYPE_SUPER 	= 0;
		const USER_TYPE_BOSS	= 1;
		const USER_TYPE_SERVER	= 2;
		const USER_TYPE_SINGER	= 3;

		public function __construct()
		{
			$this->load->database();
		}

		public function get_user($username, $password)
		{
			$query = $this->db->get_where('admin', array('username' => $username,
														'password' => $password));
			if($this->db->affected_rows() <= 0)
			{
				return null;
			}
			return $query->row_array();
		}

		public function if_user_id_exists($user_id)
		{
			$query = $this->db->get_where('admin', array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function if_username_exist($username)
		{
			$query = $this->db->get_where('admin', array('username' => $username));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_uid($uid)
		{
			$query = $this->db->get_where('admin', array('uid' => $uid));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function update_user($old_user, $new_user)
		{
			$where = array (
					'uid' => $old_user['uid']
				);

			$data = array (
					'username' => $new_user['username'],
					'type' => $new_user['type'],
					'password' => $new_user['password'],
					'privileges' => $new_user['privileges'],
				);

			$this->db->update('admin', $data, $where);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function add_user($user_name, $pwd, $type, $privileges, $user_id)
		{
			$user = array(
					'username' => $user_name,	
					'type' => $type,
					'password' => $pwd,
					'privileges' => $privileges,
					'user_id' => $user_id
				);

			return $this->db->insert('admin', $user);
		}
	}
?>