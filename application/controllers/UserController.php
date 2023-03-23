 <?php
defined('BASEPATH') OR exit('No direct script access allowed');
date_default_timezone_set("Asia/Kolkata");
class UserController extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		$this->load->model('UserModel');
		$this->load->library('form_validation');
		$this->load->library('upload');
	}

    public function index()
	{ 
		UserNotLoggedIn();
		$this->load->view('login');
	}
 

    public function register()
	{ 
		UserNotLoggedIn();
		$this->load->view('register');
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


	public function saveuser()
	{ 
		UserNotLoggedIn();
		if($this->input->post()){
		
		$this->form_validation->set_error_delimiters('<label class="invalid">','</label>');
		$this->form_validation->set_rules('name', 'name', 'trim|required');
		$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email|is_unique[users_tbl.email]');
		$this->form_validation->set_rules('phone', 'phone', 'trim|required|is_unique[users_tbl.phone]');
		$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[12]');
		$this->form_validation->set_rules('picture', '','callback_file_check');
		$this->form_validation->set_message('is_unique', 'This %s already exists. Try different.');
		$this->form_validation->set_message('min_length', '{field} minimum 6 digit');
		$this->form_validation->set_message('max_length', '{field} maximum 12 digit');
		
			if ($this->form_validation->run() == FALSE) {

				$this->load->view('register');

			} else {

				$file_name = $_FILES[ "picture" ][ "name" ];
				$file_extension = pathinfo( $file_name, PATHINFO_EXTENSION );
				$config['upload_path']   = './uploads/'; 
				$config['allowed_types'] = 'gif|jpg|png|jpeg|PNG'; 
				$config['max_size']      = 2048;
				$config['file_name'] = date("YmdHis")."-".rand(10,100).".$file_extension";
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('picture')) {
					$this->session->set_flashdata('failed',$this->upload->display_errors());
					redirect(base_url('/register'));
				} else {

					$data = array(
						'name' => $this->input->post('name'),
						'email' => $this->input->post('email'),
						'phone' => $this->input->post('phone'),
						'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
						"picture" => $config['file_name']
					);
					
					$result = $this->UserModel->save($data);
					// echo "<pre>";
					// print_r($result);
					// echo "</pre>";
					if($result['status']){
						$this->session->set_flashdata('success',$result['message']);
					} else {
						$this->session->set_flashdata('failed',$result['message']);
					}
					redirect(base_url('/register'));
				}
			}
		} else {
			redirect(base_url('/register'));
		}
	}




	
	public function userlogin()
	{ 
		UserNotLoggedIn();
		if($this->input->post()){
			
			$this->form_validation->set_error_delimiters('<label class="invalid">','</label>');
			$this->form_validation->set_rules('email', 'email', 'trim|required|valid_email');
			$this->form_validation->set_rules('password', 'password', 'required');
			
				if ($this->form_validation->run() == FALSE) {
	
					$this->load->view('login');
	
				} else {

					$email = $this->input->post('email');
					$password = $this->input->post('password');

					$result = $this->UserModel->findByEmail($email);
					if($result['status']){

						if(password_verify($password,$result['data']->password)){

							$this->session->set_userdata('user',$result['data']);
							redirect(base_url('/admin'));

						} else {
							$this->session->set_flashdata('failed',"Login failed.");
							redirect(base_url('/login'));
						}

					} else {

						$this->session->set_flashdata('failed',$result['message']);
						redirect(base_url('/login'));
					}

				}

			} else {
			redirect(base_url('/login'));
		}
	}




    
}



?>