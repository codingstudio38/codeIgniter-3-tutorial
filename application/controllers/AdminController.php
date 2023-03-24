 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Kolkata");
class AdminController extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}

    public function index()
	{ 
		UserLoggedIn();
		$result = $this->UserModel->getAllData();
		$data = array("users"=>$result);
		$this->load->view('mytable',$data);
	}

 
	
	public function logout()
	{ 
		UserLoggedIn();
		$this->session->set_userdata('user',"");
		unset($_SESSION['user']);
		$this->session->set_flashdata('success',"Successfully logged out");
		redirect(base_url('/login'));
	}
 

	public function edit()
	{ 
		UserLoggedIn();
		$id=$this->uri->segment(2);
		$result = $this->UserModel->findById($id);
		if($result['status']){
			$this->session->set_flashdata('success',$result['message']);
		} else {
			$this->session->set_flashdata('failed',$result['message']);
		}
		redirect(base_url('/admin'));
	}


 
	public function delete()
	{ 
		UserLoggedIn();
		$loggedinuser = $this->session->userdata('user');
		$id=$this->uri->segment(2); 
		$result = $this->UserModel->DeleteById($id);
		if($result['status']){
			if($loggedinuser->id==$id){
				$this->logout();
				// $this->session->set_userdata('user',"");
				// unset($_SESSION['user']);
				// $this->session->set_flashdata('success',"Your account has been deleted. Please create an account. ");
			    // redirect(base_url('/register'));
			} else {
				$this->session->set_flashdata('success',$result['message']);
			    redirect(base_url('/admin'));
			}
		} else {
			$this->session->set_flashdata('failed',$result['message']);
			redirect(base_url('/admin'));
		}
		
	}

	
    
}



?>