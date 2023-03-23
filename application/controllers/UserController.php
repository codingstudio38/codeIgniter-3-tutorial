 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {
 
	public function __construct(){
		parent::__construct();
		$this->load->model('WelcomeModel','welcome');
	}

    public function index()
	{ 

		$this->load->view('login');
        
	}


    public function register()
	{ 

		$this->load->view('register');
        
	}














    
}



?>