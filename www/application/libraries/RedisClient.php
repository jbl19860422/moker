<?php
	class Redisclient {
		private $redis;
		private $redis_connect_ret;
		private $redis_ip = "119.29.10.176";
		private $redis_port = 6379;
		private $db_logintoken = "logintoken";
		private $db_tjsc_logintoken = "tjsc_logintoken";
		private $db_tjsc_logintoken_app = "tjsc_logintoken_app";
		private $DB_BAR_MESSAGE = 'barMessage';

		public function __construct() {
			$this->redis = new Redis();
			$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
		}

		public function getLoginToken($user_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			if(!$this->redis->hExists($this->db_logintoken, $user_id))
			{
				return null;
			}

			$loginToken = $this->redis->hGet($this->db_logintoken, $user_id);
			return $loginToken;
		}

		public function setLoginToken($user_id, $loginToken) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			$this->redis->hSet($this->db_logintoken, $user_id, $loginToken);
		}

		public function getBarMessage($bar_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			$all = $this->redis->hGetAll("barMessage".$bar_id);
			return $all;
		}

		public function getSysMessage($bar_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}
			$all = $this->redis->hGetAll("sysMessage".$bar_id);
			return $all;
		}







		public function getTjscLoginToken($user_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			if(!$this->redis->hExists($this->db_tjsc_logintoken, $user_id))
			{
				return null;
			}

			$loginToken = $this->redis->hGet($this->db_tjsc_logintoken, $user_id);
			return $loginToken;
		}

		public function setTjscLoginToken($user_id, $loginToken) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			$this->redis->hSet($this->db_tjsc_logintoken, $user_id, $loginToken);
		}

		public function getAppLoginToken($user_id) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			if(!$this->redis->hExists($this->db_tjsc_logintoken_app, $user_id))
			{
				return null;
			}

			$loginToken = $this->redis->hGet($this->db_tjsc_logintoken_app, $user_id);
			return $loginToken;
		}

		public function setAppLoginToken($user_id, $loginToken) {
			if(!$this->redis_connect_ret) {
				$this->redis_connect_ret = $this->redis->connect($this->redis_ip, $this->redis_port);
			}

			if(!$this->redis_connect_ret) {
				return null;
			}

			$this->redis->hSet($this->db_tjsc_logintoken_app, $user_id, $loginToken);
		}
	}
?>