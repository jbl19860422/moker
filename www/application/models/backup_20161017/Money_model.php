<?php
	require_once(APPPATH."controllers/errcode.php");
	class Money_model extends CI_Model {
		public function __construct()
		{
			$this->load->database();
		}

		public function insert_mondyinfo($user_id)
		{
			$query = $this->db->get_where('user_wallet', array('user_id' => $user_id));
			if($this->db->affected_rows() <= 0)
			{
				$money_info = array(
					'user_id' => $user_id,
					'money' => 0,
					'coupons' => ""
				);

				$this->db->insert('user_wallet', $money_info);

				if($this->db->affected_rows() > 0)
				{
					return true;
				}
				return false;
			}
			return true;
		}

		public function get_moneyinfo($user_id)
		{
			$query = $this->db->get_where('user_wallet', array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$money_info = $query->row_array();
				return $money_info;
			}
			return null;
		}

		public function update_wallet($old_moneyinfo, $new_moneyinfo)
		{
			$where = array (
					'user_id' => $old_moneyinfo['user_id']
				);

			$data = array (
					'money' => $new_moneyinfo['money'],
					'coupons' => $new_moneyinfo['coupons']
				);

			$this->db->update('user_wallet', $data, $where);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function add_money($user_id, $count)
		{
			$money_info = $this->get_moneyinfo($user_id);
			if(!$money_info)
			{
				$this->insert_mondyinfo($user_id);
				$money_info = $this->get_moneyinfo($user_id);
			}
			$new_money_info = $money_info;
			$new_money_info['money'] = $new_money_info['money'] + $count;
			return $this->update_wallet($money_info, $new_money_info);
		}

		
		public function consume_money($user_id, $count)
		{
			$money_info = $this->get_moneyinfo($user_id);
			if(!$money_info)
			{
				$this->insert_mondyinfo($user_id);
				$money_info = $this->get_moneyinfo($user_id);
			}
			$new_money_info = $money_info;
			log_message("debug", "money=".$new_money_info['money']."&count=".$count);
			if($new_money_info['money'] < $count)
			{
				return ERRCODE_NOT_ENOUGH_MONEY;
			}
			$new_money_info['money'] = $new_money_info['money'] - $count;
			if(!$this->update_wallet($money_info, $new_money_info))
			{
				return ERRCODE_OPERATE_MYSQL;
			}
			return ERRCODE_SUCCESS;
		}
	}
?>