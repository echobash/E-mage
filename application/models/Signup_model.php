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
class Signup_model extends MY_Model {
	protected $_table_name = AVANSE_INCENTIVE_TABLE;
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




	function upload_user_csv($organization_id,$branch_id){
		//echo $organization_id; echo $branch_id;die;
		$this -> load ->model("gallery_model");
//		echo "<pre>";print_r($_FILES["user_csv"]);die;
		if(isset($_FILES["user_csv"])){
			if($_FILES["user_csv"]["error"] == 0) 
			{
				$path		=	realpath($this->gallery_path."/csv");
				//echo "<pre>"; print_r($path);
				$csv_file	= 	$this->gallery_model -> upload_file($path,"user_csv","csv");
				//echo "<pre>jh"; print_r($csv_file);
				$file = fopen($csv_file['full_path'], "r");
				//echo "<pre>";print_r($file);die;
				$csvData = array();
				while (!feof($file)) {
					$csvRowElement = fgetcsv($file);
					$csvData[] = $csvRowElement;
				}
				fclose($file);
				$csvData = array_filter($csvData);
				//echo "<pre>";print_r($csvData);die;
				//echo count($csvData);die;
				if(count($csvData)<=1) { 
					$this->session->set_flashdata('warning', 'CSV Parsing error, please check your CSV file');
					return false;
				}
				//echo "<pre>"; print_r($this->clean($csvData[1][2]));
				//echo count($csvData[0]);

				if(count($csvData[0])<13) { 
					$this->session->set_flashdata('warning', 'CSV Parsing error, please check your CSV file');
					return false;
				}

			}
			else if($_FILES["quiz_csv"]["error"] != 4) { 
				$this->session->set_flashdata('warning', 'File uploading error, please try again.');
				return false;

			} 
		}
		$employee = $this->array_from_post(self::$db_fields);

		for($index=1;$index<count($csvData);$index++)
		{

			if(isset($csvData[$index][0])){
				$employee['employee_id']=$this->clean($csvData[$index][0]);
			}else{
				$employee['employee_id']="";
			}

			if(isset($csvData[$index][0])){
				$employee['employee_name']=$this->clean($csvData[$index][1]);
			}else{
				$employee['employee_name']="";
			}

			if(isset($csvData[$index][0])){
				$employee['financial_year']=$this->clean($csvData[$index][2]);
			}else{
				$employee['financial_year']="";
			}

			if(isset($csvData[$index][0])){
				$employee['incentive_type']=$this->clean($csvData[$index][3]);
			}else{
				$employee['incentive_type']="";
			}

			if(isset($csvData[$index][0])){
				$employee['incentive_name']=$this->clean($csvData[$index][4]);
			}else{
				$employee['incentive_name']="";
			}

			if(isset($csvData[$index][0])){
				$employee['incentive_period']=$this->clean($csvData[$index][5]);
			}else{
				$employee['incentive_period']="";
			}

			if(isset($csvData[$index][0])){
				$employee['incentive_achieved']=$this->clean($csvData[$index][6]);
			}else{
				$employee['incentive_achieved']="";
			}

			if(isset($csvData[$index][0])){
				$employee['paid_amount']=$this->clean($csvData[$index][7]);
			}else{
				$employee['paid_amount']="";
			}
			if(isset($csvData[$index][0])){
				$employee['paid_percentage']=$this->clean($csvData[$index][8]);
			}else{
				$employee['paid_percentage']="";
			}

			if(isset($csvData[$index][0])){
				$employee['retained_amount']=$this->clean($csvData[$index][9]);
			}else{
				$employee['retained_amount']="";
			}

			if(isset($csvData[$index][0])){
				$employee['retained_percentage']=$this->clean($csvData[$index][10]);
			}else{
				$employee['retained_percentage']="";
			}

			if(isset($csvData[$index][0])){
				$employee['paid_month']=$this->clean($csvData[$index][11]);
			}else{
				$employee['paid_month']="";
			}

			if(isset($csvData[$index][0])){
				$employee['incentive_rank']=$this->clean($csvData[$index][12]);
			}else{
				$employee['incentive_rank']="";
			}

			$employee['organization_id']	=	$organization_id;
			$employee['branch_id']	=	$branch_id;
			$employee['month']	=	$this->input->post("month");
			$employee['year']	=	$this->input->post("year");
			$quarter	=	$this->input->post("quarter");

			//echo $organization_id;die;
			$employee['user_id']	=	$this->user_model->get_user_ids($employee['employee_id'],$organization_id,$branch_id);
			//echo "<pre>"; print_r($employee['user_id']);die;
			//echo "<pre>";print_r($this->input->post());
			if($this->input->post("quarterRadio")=='YES'){
			
			 $employee['month'] = $quarter;
		}/*else{
			echo $employee['month'];echo "<br>";
		}*/
			//echo $employee['month'];die;

			$this->db->select('');
            $this->db->where('organization_id',$organization_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->where('employee_id',$employee['employee_id']);
            $this->db->where('month',$employee['month']);
            $this->db->where('incentive_type',$employee['incentive_type']);
            $this->db->where('year',$employee['year']);
        	$result = $this->get();
        	//echo "<pre>";print_r($result);
        	//echo $this->db->last_query();die;
        	$results=$result[0];
        	//echo "<pre>";print_r($results);die;
        	if($results){
        		//echo "duplicate";die;
        		$this->save($employee,$result[0]->avanse_incentive_id);
        		// echo "<pre>";print_r($result);
        		// echo $this->db->last_query();die;
        	}
        	else
			{
				//echo "not duplicate";die;
				$avanse_incentive_id = $this->save($employee,NULL);
				//echo $this->db->last_query();die;
				//echo "<pre>";print_r($avanse_incentive_id);
        		//echo $this->db->last_query();die;
			}
			  //selectquewery before save && save in else && id instead of null
			//echo"<pre>"; print_r($avanse_incentive_id);
			//echo $this->db->last_query();die;
		}
		return true;
	}

	function getIncentiveList($organization_id,$branch_id,$month,$year){

		$this->db->select('');
		$this->db->where('organization_id',$organization_id);
		$this->db->where('branch_id',$branch_id);
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->where('is_deleted','0');
		$this->db->where('is_inactive','0');
		$incentive_data = $this->get();
		//echo $this->db->last_query();die;
		//echo "<pre>";print_r($incentive_data);die;
		return $incentive_data;
	}




function getIncentiveResponse($organization_id,$branch_id,$user_id,$incentive_type){
 //echo $organization_id;echo $branch_id;echo $user_id;echo $incentive_type;die;
		$this->db->select('');
		$this->db->where('organization_id',$organization_id);
		$this->db->where('branch_id',$branch_id);
		$this->db->where('user_id',$user_id);
		$this->db->where('incentive_type',$incentive_type);
		$this->db->where('is_deleted','0');
		$this->db->where('is_inactive','0');
		//echo $this->get_query();die;
		//echo $this->db->last_query();die;
		$incentive_response = $this->get();
		
		$incentive_response_row=$incentive_response[0];
		//echo "<pre>";print_r($incentive_response_row);die;
		/*echo $this->db->last_query();die;*/
		//echo $this->db->last_query();die;
		//echo "<pre>";print_r($incentive_data);die;
		return $incentive_response_row;
	}

	function delete_record($avanse_incentive_id){
		//echo "lai";die;
		//echo $this->_primary_key;
		//echo $avanse_incentive_id;die;
		$this->load->sub_admin_model;
		$data['logged_user']=$this->sub_admin_model->logged_user();
		$organization_id=$data['logged_user']['organization_id'];
		$branch_id=$data['logged_user']['branch_id'];
		//echo $organization_id;echo $branch_id;die;
		/*$this->db->where($this->_primary_key,$avanse_incentive_id);
		$this->db->where('organization_id',$organization_id);
		$this->db->where('branch_id',$branch_id);
		$employee['is_deleted'] = 1;*/


		    $result = $this->db->query("UPDATE bs_avanse_incentive SET is_deleted='1'WHERE avanse_incentive_id=$avanse_incentive_id AND organization_id=$organization_id AND branch_id=$branch_id ");
		//$this->db->last_query();die;


		/*if(!$this->save($employee,$avanse_incentive_id))
				return false;*/
			//echo"ali";echo $this->db->last_query();die;
		return true;
	}
	public function deleteCsvByMonth($month,$year){
		//var_dump($avanse_incentive_id);die;
		/*$this->db->where_in('month',$month);
		$employee['is_deleted'] = 1;*/
		$this->load->sub_admin_model;
		$data['logged_user']=$this->sub_admin_model->logged_user();
		$organization_id=$data['logged_user']['organization_id'];
		$branch_id=$data['logged_user']['branch_id'];
		$result = $this->db->query("UPDATE bs_avanse_incentive SET is_deleted='1'WHERE month= '".$month."' AND year=$year AND organization_id=$organization_id AND branch_id=$branch_id");
		//echo $this->db->last_query();die;
		return true;
	}

	function getCsvLog($organization_id,$branch_id){
		try {
			$this->_select=false;
			$this->db->select('month');
			$this->db->select('year');
			$this->db->select('avanse_incentive_id');
			$this->db->select('added_on');
			$this->db->select('count("month") AS month_count');
			$this->db->where('organization_id',$organization_id);
			$this->db->where('branch_id',$branch_id);
			$this->db->where('is_deleted','0');
			$this->db->where('is_inactive','0');
			$this->db->group_by('month');
			$this->db->order_by('added_on','desc');
			$log = $this->get();
			//echo "<pre>";print_r($log);die;
			return $log;

		} catch (Exception $e) {
			
		}
	}
}
