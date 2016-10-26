<?php
	class TjscRedisClient {
		private $redis;
		private $redis_connect_ret;
		private $redis_ip = "119.29.10.176";
		private $redis_port = 6379;
		private $db_logintoken_pc = "tjsc_logintoken_pc";

		public function __construct() {
			$this->redis = new Redis();
			$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
		}

		public function getSellerLoginToken($seller_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			if(!$this->redis->hExists($this->db_logintoken_pc, $seller_id))
			{
				return null;
			}

			$loginToken = $this->redis->hGet($this->db_logintoken_pc, $seller_id);
			return $loginToken;
		}

		public function setSellerLoginToken($seller_id, $loginToken) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			$this->redis->hSet($this->db_logintoken_pc, $seller_id, $loginToken);
		}

		public function delSellerLoginToken($seller_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			$this->redis->hDel($this->db_logintoken_pc, $seller_id);
		}
	}
?>