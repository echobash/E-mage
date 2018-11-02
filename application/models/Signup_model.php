<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
    @Author: Ali Anwar
    @email:  @email: anwarali377@gmail.com
    @copyright : Chapter App INC
    @web: chapterapps.net


	@date Created: 22nd Oct 2018
	Class Module_model
	emp259729	uid70804
	emp2398	uid 69027
	 
*/
class Signup_model extends CI_Model {
	protected $_table_name = SIGNUP_TABLE;
	protected $_order_by = 'user_id';
	protected $_primary_key = 'user_id';
	protected $_timestamps = TRUE;

	protected static $db_fields = array('user_id', 'first_name', 'last_name', 'email', 'active', 'password');

	public $user_id;
	public $first_name;
	public $last_name;
	public $email;
	public $active;
	public $password;

	public function __construct()
	{
		parent::__construct();		
		
	}

	public function readUser()
	{
		$query=$this->db->query("SELECT * FROM bs_signup");
		echo $this->db->last_query();
		foreach ($query->result() as $row)
		{
        echo $row->user_id; echo "<br>";
        echo $row->first_name; echo "<br>";
        echo $row->email; echo "<br>";
        echo $row->active; echo "<br>";
        echo $row->password;
		}

	}

	public function addUser($data)
	{	
		$email=$data['email'];die;
		$query=$this->db->query("select * from bs_signup where email=$email);
		echo $this->db->last_query();die;
		if($query->num_rows())
		{
			echo "duplicacy";die;
		}else{
		$result=$this->db->insert(SIGNUP_TABLE, $data);
			  }
	}




	
}
