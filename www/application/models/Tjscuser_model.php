<?php
	class Tjscuser_model extends CI_Model {
		private $tableName = 'user';
		private $tjsc = null;
		public function __construct() {
			parent::__construct();
			$this->tjsc = $this->load->database('tjsc', TRUE);  
		}

		public function if_user_exist($openid) {
			$query = $this->tjsc->get_where($this->tableName, array('open_id' => $openid));
			if($this->tjsc->affected_rows() > 0) {
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_openid($openid) {
			$query = $this->tjsc->get_where($this->tableName, array('open_id' => $openid));
			if($this->tjsc->affected_rows() > 0) {
				$user_info = $query->row_array();
				return $user_info;
			}
			return false;
		}

		public function get_user_by_userid($userid) {
			$query = $this->tjsc->get_where($this->tableName, array('user_id' => $userid));
			if($this->tjsc->affected_rows() > 0) {
				$user_info = $query->row_array();
				return $user_info;
			}
			return null;
		}

		public function get_user_by_userids($userids) {
			if(count($userids) <= 0) {
				return null;
			}

			$this->tjsc->or_where_in('user_id', $userids);
			$query = $this->tjsc->get($this->tableName);
			$usersinfo = [];
			foreach($query->result_array() as $row) {
				$usersinfo[] = $row;
			}
			return $usersinfo;
		}

		public function update_user($old_user_info, $new_user_info) {
			$where = array (
					'user_id' => $old_user_info['user_id']
				);

			$data = array (
					'nickname' => $new_user_info['nickname'],
					'sex' => $new_user_info['sex'],
					'headimg' => $new_user_info['headimg'],
					'role' => $new_user_info['role'],
					'status' => $new_user_info['status'],
					'realname' => $new_user_info['realname'],
					'phone' => $new_user_info['phone'],
 					'last_login_time' => $new_user_info['last_login_time']
				);

			$this->tjsc->update($this->tableName, $data, $where);
		}

		public function add_user($openid, $nickname, 
								 $sex, $headimg) {
			$user = array(
					'open_id' => $openid,	
					'nickname' => $nickname,
					'sex' => $sex,
					'headimg' => $headimg,
					'last_login_time' => time()
				);

			return $this->tjsc->insert($this->tableName, $user);
		}
	}
?>