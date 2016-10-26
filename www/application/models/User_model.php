<?php
	class User_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function if_user_exist($openid)
		{
			$query = $this->db->get_where('user_core', array('openid' => $openid));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_openid($openid)
		{
			$query = $this->db->get_where('user_core', array('openid' => $openid));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return false;
		}

		public function get_user_by_userid($userid)
		{
			$query = $this->db->get_where('user_core', array('user_id' => $userid));
			if($this->db->affected_rows() > 0)
			{
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_userids($userids)
		{
			if(count($userids) <= 0)
			{
				return null;
			}

			$this->db->or_where_in('user_id', $userids);
			$query = $this->db->get('user_core');
			$usersinfo = [];
			foreach($query->result_array() as $row)
			{
				$usersinfo[] = $row;
			}
			return $usersinfo;
		}

		public function query_robots()
		{
			$query = $this->db->get_where('user_core', array('type' => 1));
			if($this->db->affected_rows() > 0)
			{
				$user_infos = $query->result_array();
				return $user_infos;
			}
			return null;
		}

		public function update_user($old_user_info, $new_user_info)
		{
			$where = array (
					'user_id' => $old_user_info['user_id']
				);

			$data = array (
					'nickname' => $new_user_info['nickname'],
					'sex' => $new_user_info['sex'],
					'headimg' => $new_user_info['headimg'],
					'last_login_ip' => $new_user_info['last_login_ip'],
					'recent_bar_id' => $new_user_info['recent_bar_id'],
					'role' => $new_user_info['role'],
					'status' => $new_user_info['status'],
					'realname' => $new_user_info['realname'],
					'phone' => $new_user_info['phone'],
 					'last_login_time' => $new_user_info['last_login_time']
				);

			$this->db->update('user_core', $data, $where);
		}

		public function close_barrage_alert($user_id)
		{
			$where = array(
					'user_id' => $user_id
				);

			$data = array (
					'barrage_alert' => 0
				);

			$this->db->update('user_core', $data, $where);
		}

		public function add_user($openid, $nickname, $sex, $headimg)
		{
			$user = array(
					'openid' => $openid,	
					'nickname' => $nickname,
					'sex' => $sex,
					'headimg' => $headimg,
					'last_login_time' => time()
				);

			return $this->db->insert('user_core', $user);
		}

		public function add_robot($openid, $nick, 
								 $sex, $headimg,
								 $last_ip, $bar_id,
								 $role)
		{
			$user = array(
					'openid' => $openid,	
					'nickname' => $nick,
					'sex' => $sex,
					'headimg' => $headimg,
					'last_login_ip' => $last_ip,
					'recent_bar_id' => $bar_id,
					'role' => $role,
					'last_login_time' => time(),
					'status' => 1,
					'type' => 1
				);

			return $this->db->insert('user_core', $user);
		}

		public function add_love($user_id, $count)
		{
			$userinfo = $this->get_user_by_userid($user_id);
			if(!$userinfo)
			{
				return false;
			}

			$lovecount = $count + $userinfo['love_count'];

			$where = array (
					'user_id' => $user_id
				);

			$data = array (
					'love_count' => $lovecount
				);

			$this->db->update('user_core', $data, $where);
			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function insert_order()
		{
			
		}
	}
?>