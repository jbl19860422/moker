<?php
	require_once(APPPATH."controllers/errcode.php");
	class Chatrecord_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function query_unviewed_chatrecord($to_user_id)
		{
			$query = $this->db->get_where('chat_record', array(
																'to_user_id' => $to_user_id,
																'viewed' => 0));
			if($this->db->affected_rows() > 0)
			{
				$records = $query->result_array();
				return $records;
			}
			return null;
		}

		public function query_user_chatrecord($from_user_id, $to_user_id)
		{
			$currTime = time();
			$sql = 'select * from chat_record where (from_user_id='.$from_user_id.' and to_user_id='.$to_user_id.') or (from_user_id='.$to_user_id.' and to_user_id='.$from_user_id.')';//.' and timestamp>'.($currTime-3600);

			$query = $this->db->query($sql);
			if($this->db->affected_rows() > 0)
			{
				$records = $query->result_array();
				return $records;
			}
			return null;
		}
		//设定某个人的消息都看过了
		public function set_chatrecord_viewed($from_user_id, $to_user_id)
		{
			$where = array (
					'from_user_id' => $from_user_id,
					'to_user_id' => $to_user_id
				);

			$new = array(
					'viewed' => 1
				);
			return $this->db->update('chat_record', $new, $where);
		}

		public function add_chat($bar_id, $desk_id, $from_user_id, $to_user_id, $from_nickname, $from_headimg, $to_nickname, $to_headimg, $content)
		{
			$record = array(
					'bar_id' => $bar_id,
					'desk_id' => $desk_id,
					'from_user_nickname' => $from_nickname,
					'to_user_nickname' => $to_nickname,
					'from_user_headimg' => $from_headimg,
					'to_user_headimg' => $to_headimg,
					'from_user_id' => $from_user_id,
					'to_user_id' => $to_user_id,
					'content' => $content,
					'timestamp' => time(),
					'viewed' => 0
				);

			return $this->db->insert('chat_record', $record);
		}

		// public function del_chat($bar_id, $desk_id)
		// {
		// 	$this->db->where('bar_id',$bar_id);
		// 	$this->db->where('desk_id',$desk_id);
	 //        $this->db->delete('desk');
		// }
	}
?>