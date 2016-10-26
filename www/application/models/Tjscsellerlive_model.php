<?php
	class Tjscsellerlive_model extends CI_Model {
		private $tableName = 'seller_live';
		private $db = null;
		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function create_live($liveinfo) {
			$liveinfo = array(
					'seller_id' => $liveinfo['seller_id'],
					'live_cover' => $liveinfo['live_cover'],
					'goods_id' => $liveinfo['goods_id'],
					'push_url' => $liveinfo['push_url'],
					'live_status' => 0,
					'description' => $liveinfo['description'],
					'title' => $liveinfo['title'],
					'start_time' => time(),
					'end_time' => time(),
					'viewed_count' => 0,
 					'duration' => 0,
 					'create_time' => time(),
 					'live_id' => $liveinfo['live_id'],
 					'hls_url' => $liveinfo['hls_url'],
 					'flv_url' => $liveinfo['flv_url'],
 					'rtmp_url' => $liveinfo['rtmp_url']
				);

			$this->db->insert($this->tableName, $liveinfo);
			return $this->db->insert_id();;
		}

		public function del_live($id) {
			$this->db->where('id', $id);
	        $this->db->delete($this->tableName);
		}

		public function set_live_info($id, $liveinfo) {
			$where = array (
					'id' => $id
				);

			$this->db->update($this->tableName, $liveinfo, $where);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function get_sellerlives_by_sellerid($seller_id) {
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0)
			{
				$seller_lives = $query->result_array();
				return $seller_lives;
			}
			return [];
		}

		public function get_live_by_id($id) {
			$query = $this->db->get_where($this->tableName, array('id' => $id));
			if($this->db->affected_rows() > 0)
			{
				$live = $query->row_array();
				return $live;
			}
			return null;
		}

		public function query_sellerlives_by_ids($ids) {
			if(count($seller_ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('id', $ids);
			$query = $this->db->get($this->tableName);
			$seller_lives = [];
			foreach($query->result_array() as $row)
			{
				$seller_lives[] = $row;
			}
			return $seller_lives;
		}		

		public function inc_viewed_count($id) {
			$sql = "UPDATE ".$this->tableName." SET viewed_count=viewed_count+1"." WHERE id=".$id;
			$query = $this->db->query($sql);
		}

		public function dec_viewed_count($id) {
			$sql = "UPDATE ".$this->tableName." SET viewed_count=viewed_count-1"." WHERE id=".$id;
			$query = $this->db->query($sql);
		}
	}
?>