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
		$user = $this->UserModel->findById($id);
		
		if($user['status']){
			$userdata = array("users"=>$user);
		} else {
			$this->session->set_flashdata('failed',$user['message']);
			redirect(base_url('/admin'));
		}
		if($this->input->post()){
		$result_email = $this->UserModel->findByIdEmail($id,$this->input->post('email'));
		$email_check = "";
        if (!$result_email['status'])
        {
           $email_check = "|is_unique[users_tbl.email]";
        }
		$result_phoen = $this->UserModel->findByIdPhone($id,$this->input->post('phone'));
		$phoen_check = "";
        if (!$result_phoen['status'])
        {
           $phoen_check = "|is_unique[users_tbl.phone]";
        }
		$this->form_validation->set_error_delimiters('<label class="invalid">','</label>');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('email', 'email', "trim|required|valid_email$email_check");
		$this->form_validation->set_rules('phone', 'phone', "trim|required$phoen_check");
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

				$this->load->view('edit',$userdata);

			} else {
				$newfile_name="";
				$file="";
				if(isset($_FILES[ "picture" ]) && $_FILES[ "picture" ]['name']!==""){
					$file_name = $_FILES[ "picture" ][ "name" ];
					$file_type = $_FILES[ "picture" ][ "type" ];
					$file_tmpname = $_FILES[ 'picture' ][ 'tmp_name' ];
					$file_size = $_FILES[ "picture" ][ "size" ];
					$file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
					$newfile_name=date("YmdHis")."-".rand(10,100).".$file_extension";
					if (!move_uploaded_file($file_tmpname, FCPATH.'uploads/'.$newfile_name)) {
						$newfile_name="";
						$file="Failed to movie file.";
					}
				}
				$data['name']=$this->input->post('name');
				$data['email']=$this->input->post('email');
				$data['phone']=$this->input->post('phone');
				if(isset($_POST['password']) && $_POST['password']!==""){
					$data['password']=password_hash($this->input->post('password'), PASSWORD_DEFAULT);
				} 
				if(!empty($newfile_name)){
					$data['picture']=$newfile_name;
				}
				$update = $this->UserModel->UpdateUser($data,$id);
				if($update['status']){
					if(!empty($newfile_name)){
						if(!empty($user['data']->picture)){
							if(file_exists( FCPATH.'uploads/'.$user['data']->picture))
							{ 
								unlink(FCPATH.'uploads/'.$user['data']->picture);
								$file  = "New file successfully uploaded. User old file has been deleted.";
							} else {
								$file  = "New file successfully uploaded. User old file directory not found.";
							}
						}
					}
					$this->session->set_flashdata('success',$update['message']." ".$file);
					redirect(base_url('/admin'));
				} else {
					$this->session->set_flashdata('failed',$update['message']." ".$file);
					redirect(base_url('/admin'));
				}
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