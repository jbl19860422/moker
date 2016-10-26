<?php
	class Tjscpic_model extends CI_Model {
		private $tableName = 'pics';
		private $db = null;
		public function __construct() {
			parent::__construct();
			$this->db = $this->load->database('tjsc', TRUE);  
		}

		public function if_pic_exist($id) {
			$query = $this->db->get_where($this->tableName, array('id' => $id));
			if($this->db->affected_rows() > 0) {
				$pic_info = $query->row_array();
				return $pic_info;
			}
			return null;
		}

		public function get_pic_by_id($id) {
			$query = $this->db->get_where($this->tableName, array('id' => $id));
			if($this->db->affected_rows() > 0) {
				$pic_info = $this->db->row_array();
				return $pic_info;
			}
			return false;
		}

		public function get_pic_by_sellerid($seller_id) {
			$query = $this->db->get_where($this->tableName, array('seller_id' => $seller_id));
			if($this->db->affected_rows() > 0) {
				$pic_infos = $query->result_array();
				return $pic_infos;
			}
			return [];
		}

		public function get_pic_by_ids($ids) {
			if(count($ids) <= 0) {
				return null;
			}

			$this->db->or_where_in('id', $ids);
			$query = $this->db->get($this->tableName);
			if($this->db->affected_rows() > 0) {
				$pics = $query->result_array();
				return $pics;
			}
			return [];
		}

		public function set_pic($id, $pic_info) {
			$where = array (
					'id' => $id
				);

			$this->db->update($this->tableName, $data, $pic_info);
		}

		public function add_pic($seller_id, $pic_url) {
			$pic = array(
					'seller_id' => $seller_id,
					'url' => $pic_url,
					'timestamp' => time()
				);

			return $this->db->insert($this->tableName, $pic);
		}

		public function del_pic($id) {
			$this->db->where('id',$id);
	        $this->db->delete($this->tableName);
		}
	}
?>