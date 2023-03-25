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
			
			$data = array("users"=>$result);
			$this->load->view('edit',$data);

		} else {
			$this->session->set_flashdata('failed',$result['message']);
			redirect(base_url('/admin'));
		}
		
	}

	
	public function file_check()
	{ 
		$file_name = $_FILES[ "picture" ][ "name" ];
		$file_type = $_FILES[ "picture" ][ "type" ];
		$file_tmpname = $_FILES[ 'picture' ][ 'tmp_name' ];
		$file_size = round($_FILES[ "picture" ][ "size" ] / ( 1024 * 1024 ), 2 );
		$file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
		$allowed_extension = array( "png", "PNG", "jpg","jpeg", "JPEG","gif");
		if(empty($_FILES['picture']['name'])){
			$this->form_validation->set_message('file_check', 'Please select profile picture');
			return false;
		} else if(!in_array($file_extension, $allowed_extension )){
			$this->form_validation->set_message('file_check', 'Please select gif, jpg, png file');
			return false;
		}else if ( $file_size >= 2 ) {
			$this->form_validation->set_message('file_check', 'Please select size less than 2 MB');
			return false;
		} else {
			return true;
		}
	}
	
	public function updateuser()
	{ 
		UserLoggedIn();
		$id=$this->uri->segment(2);
		$result = $this->UserModel->findById($id);
		if($result['status']){
			$user = array("users"=>$result);
		} else {
			$this->session->set_flashdata('failed',$result['message']);
			redirect(base_url('/admin'));
		}
		if($this->input->post()){

		
		$this->form_validation->set_error_delimiters('<label class="invalid">','</label>');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[users_tbl.email]');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|is_unique[users_tbl.phone]');
		if(isset($_POST['password']) && $_POST['password']!==""){
			$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[12]');
		}
		
		if(isset($_FILES[ "picture" ]) && $_FILES[ "picture" ]['name']!==""){
			$this->form_validation->set_rules('picture', '','callback_file_check');
		} 
		
		$this->form_validation->set_message('is_unique', 'This %s already exists. Try different.');
		$this->form_validation->set_message('min_length', '{field} minimum 6 digit');
		$this->form_validation->set_message('max_length', '{field} maximum 12 digit');
		
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('edit',$user);

			} else {
				
				if(isset($_POST['password']) && $_POST['password']!==""){
					$data = array(
						'name' => $this->input->post('name'),
						'email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
						'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
					);
				} else{
					$data = array(
						'name' => $this->input->post('name'),
						'email' => $this->input->post('email'),
						'phone' => $this->input->post('phone')
					);
				}
				print_r($data);exit;
				$result = $this->UserModel->UpdateUser($data,$id);
			
			}
		} else {

		redirect(base_url('/admin'));
		}


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