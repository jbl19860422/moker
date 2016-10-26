<?php
	require_once(APPPATH."controllers/errcode.php");
	class Userpresentinfo_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function insert_present_info($bar_id, $desk_id, $donate_id, $donee_id, $present_info)
		{
			$user_present_info = array(
					'bar_id' => $bar_id,
					'desk_id' => $desk_id,
					'donate_id' => $donate_id,
					'donee_id' => $donee_id,
					'present_info' => $present_info,
					'timestamp' => time()
				);

			$this->db->insert('user_present_info', $user_present_info);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function query_donate_present_info1($donate_id, $time_start = -1, $time_end = -1)
		{
			if($time_start > 0 && $time_end > 0 && $time_end > $time_start)
			{
				$where = array(
								'donate_id' => $donate_id,
								'timestamp > ' => $time_start,
								'timestamp < ' => $time_end
							);
			}
			else
			{
				$where = array('donate_id' => $donate_id);
			}
			
			$query = $this->db->get_where('user_present_info', $where);
			if($this->db->affected_rows() > 0)
			{
				$infos = $query->result_array();
				return $infos;
			}
			return null;
		}


		public function query_donee_present_info($donee_id, $time_start = -1, $time_end = -1)
		{
			if($time_start > 0 && $time_end > 0 && $time_end > $time_start)
			{
				$where = array(
								'donee_id' => $donee_id,
								'timestamp > ' => $time_start,
								'timestamp < ' => $time_end
							);
			}
			else
			{
				$where = array('donee_id' => $donee_id);
			}
			
			$query = $this->db->get_where('user_present_info', $where);
			if($this->db->affected_rows() > 0)
			{
				$infos = $query->result_array();
				return $infos;
			}
			return null;
		}

		public function query_bar_present_info($bar_id, $time_start = -1, $time_end = -1)
		{
			if($time_start > 0 && $time_end > 0 && $time_end > $time_start)
			{
				$where = array(
								'bar_id' => $bar_id,
								'timestamp > ' => $time_start,
								'timestamp < ' => $time_end
							);
			}
			else
			{
				$where = array('bar_id' => $bar_id);
			}
			
			$query = $this->db->get_where('user_present_info', $where);
			if($this->db->affected_rows() > 0)
			{
				$infos = $query->result_array();
				return $infos;
			}
			return null;
		}
	}
?>