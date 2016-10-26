<?php
	class Order_model extends CI_Model {
		const ORDER_TYPE_DUMYITEM = 0;
		const ORDER_TYPE_ENTITY = 1;
		const ORDER_TYPE_COUPON = 2;
		const ORDER_TYPE_RECHARGE = 3;

		const ORDER_STATUS_WAIT = 0;
		const ORDER_STATUS_DONE = 1;

		public function __construct()
		{
			$this->load->database();
		}

		public function insert_order($order_id, $bar_id, $desk_id, $user_id, $order_info, $order_status,
									$donee_id, $count, $amount, $order_type, $comm_info)
		{
			$order = array(
					'order_id' => $order_id,
					'bar_id' => $bar_id,
					'desk_id' => $desk_id,
 					'user_id' => $user_id,
					'order_info' => $order_info,
					'order_status' => $order_status,
					'timestamp' => time(),
					'donee_id' => $donee_id,
					'count' => $count,
					'amount' => $amount,
					'comm_info' => $comm_info,
					'order_type' => $order_type
				);

			$this->db->insert('order', $order);

			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			return false;
		}

		public function query_by_userid($user_id)
		{
			$query = $this->db->get_where('order', array('user_id' => $user_id));
			if($this->db->affected_rows() > 0)
			{
				$order = $query->result_array();
				return $order;
			}
			return null;
		}

		public function query_pay_order($user_id)
		{
			$query = $this->db->get_where('order', array('user_id' => $user_id, 'order_type' => self::ORDER_TYPE_RECHARGE));
			if($this->db->affected_rows() > 0)
			{
				$order = $query->result_array();
				return $order;
			}
			return null;
		}

		public function getorder_by_orderid($order_id)
		{
			$query = $this->db->get_where('order', array('order_id' => $order_id));
			if($this->db->affected_rows() > 0)
			{
				$order_info = $query->row_array();
				return $order_info;
			}
			return null;
		}

		public function update_prepay_id($order_id, $prepay_id)
		{
			$where = array (
					'order_id' => $order_id
				);

			$data = array (
					'prepay_id' => $prepay_id
				);

			$this->db->update('order', $data, $where);
			if($this->db->affected_rows() <= 0)
			{
				return false;
			}
			return true;
		}

		public function update_order_status($order_id, $status)
		{
			$where = array (
					'order_id' => $order_id
				);

			$data = array (
					'order_status' => $status
				);

			$this->db->update('order', $data, $where);
			if($this->db->affected_rows() <= 0)
			{
				return false;
			}
			return true;
		}

	}
?>