<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->model('signup_model');
		
	}

	
	public function index()
	{
		$this->load->view('signup');
	}

	public function registerUser()
	{
		$this->signup_model->addUser();
	}
}
