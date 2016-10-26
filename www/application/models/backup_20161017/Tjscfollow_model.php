<?php
	class Tjscfollow_model extends CI_Model {
		private $tableName = 'user_follow';
		private $db = null;

		public function __construct()
		{
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function query_user_follows($user_id)
		{
			$query = $this->db->get_where($this->tableName, array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$follow_infos = $query->result_array();
				return $follow_infos;
			}
			return null;
		}
	}
?>