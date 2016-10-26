<?php
	class Bar extends CI_Controller {

		public function __construct()
		{
			parent::__construct();
			$this->load->model('bar_model');
			$this->load->model('user_model');
			$this->load->helper('url_helper');
			$this->load->library('https');
			//$this->load->library('aes');
		}
	}
?>