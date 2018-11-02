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
		$data=array(
		'first_name'=>$this->input->post('firstname'),
		'last_name'=>$this->input->post('lastname'),
		'email'=>$this->input->post('email'),
		'password'=>$this->input->post('password')
		);
		echo "<pre>";print_r($data);
		//'user_id', 'first_name', 'last_name', 'email', 'active', 'password'
		$this->signup_model->addUser();

	}
}
