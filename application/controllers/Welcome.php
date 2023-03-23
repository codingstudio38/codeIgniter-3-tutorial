<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		$this->load->model('WelcomeModel','welcome');
	}
	
	public function index()
	{
		//$this->session->set_userdata('id',1);
		//$this->session->set_flashdata('success',"1");
		//echo $this->session->userdata('id');
		//echo $this->session->flashdata('success');
		//echo show_date('d/m/Y');
		$this->load->view('welcome_message');
	}
  
	public function about()
	{ 

		// if($this->session->userdata('id') == ""){

		// 	redirect(base_url());

			
		// }
		
		  
		$users = $this->welcome->getAllData();
		$data = array("users"=>$users);
		$this->load->view('mytable',$data);
		
	}



}