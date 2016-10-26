<?php
	class Barlives_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function update_push_url($id, $push_url)
		{
			$where = array (
					'id' => $id
				);

			$new = array(
					'push_url' => $push_url
				);
			return $this->db->update('bar_lives', $new, $where);
		}

		public function update_play_url($id, $hls_url, $rtmp_url)
		{
			$where = array('id' => $id);
			$new = array('hls_play_url' => $hls_url, 'rtmp_play_url' => $rtmp_url);
			return $this->db->update('bar_lives', $new, $where);
		}

		public function add_live($bar_id, $push_url, $hls_url, $rtmp_url, $status)
		{
			$live = array(
					'bar_id' => $bar_id,
					'push_url' => $push_url,
					'hls_play_url' => $hls_url,
					'rtmp_play_url' => $rtmp_url,
					'timestamp' => time(),
					'status' => $status
				);

			return $this->db->insert('bar_lives', $live);
		}

		public function update_status($id, $status)
		{
			$where = array('id' => $id);
			$new = array('status' => $status);
			return $this->db->update('bar_lives', $new, $where);
		}

	}
?>