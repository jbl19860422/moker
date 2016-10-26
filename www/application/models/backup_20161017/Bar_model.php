<?php
	class Bar_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function get_barinfo($bar_id)
		{
			$query = $this->db->get_where('bar', array('bar_id' => $bar_id));
			if($this->db->affected_rows() > 0)
			{
				return $query->row_array();
			}
			return null;
		}

		public function update_activity($bar_id, $activity_start, $activity_end, $activity_name,
									 $activity_phoneurl, $activity_pcurl)
		{
			$where = array (
					'bar_id' => $bar_id
				);

			$new = array(
					'activity_start' => $activity_start,
					'activity_end' => $activity_end,
					'activity_name' => $activity_name,
					'activity_phoneurl' => $activity_phoneurl,
					'activity_pcurl' => $activity_pcurl
				);
			return $this->db->update('bar', $new, $where);
		}

		public function update_liveinfo($bar_id, $live_name, $live_push_url, $live_play_rtmp_url, $live_play_hls_url,  $live_play_flv_url, $live_status) {
			$where = array (
					'bar_id' => $bar_id
				);

			$new = array(
					'live_name' => $live_name,
					'live_push_url' => $live_push_url,
					'live_play_rtmp_url' => $live_play_rtmp_url,
					'live_play_hls_url' => $live_play_hls_url,
					'live_play_flv_url' => $live_play_flv_url,
					'live_status' => $live_status
				);
			return $this->db->update('bar', $new, $where);
		}

		public function set_live_status($bar_id, $live_status) {
			$where = array (
					'bar_id' => $bar_id
				);

			$new = array(
					'live_status' => $live_status
				);
			return $this->db->update('bar', $new, $where);
		}

		public function query_all_bars()
		{
			$query = $this->db->query('select * from `bar`');
			return $query->row_array();
		}
	}
?>