 <?php
	defined('BASEPATH') or exit('No direct script access allowed');

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	use Dompdf\Dompdf;

	date_default_timezone_set("Asia/Kolkata");
	class AdminController extends CI_Controller
	{

		public function __construct()
		{
			parent::__construct();
			$this->load->model('UserModel');
			$this->load->library('form_validation');
			$this->load->library('upload');
		}

		public function index()
		{
			UserLoggedIn();
			$result = $this->UserModel->getAllData();
			$data = array("users" => $result);
			$this->load->view('mytable', $data);
		}






		public function joinfunction()
		{
			$id = isset($_GET['id']) ? $_GET['id'] : "";

			//for pagination start
			$page = isset($_GET['page']) ? $_GET['page'] : 1;

			$limit = 5;
			$query = "SELECT * FROM `users_tbl` `user` JOIN `user_login_details` `dtl` ON `user`.`id`= `dtl`.`login_id` WHERE `dtl`.`login_id`='$id'";
			$total = $this->UserModel->CheckTotalRecords($query);
			$start = ($page - 1) * $limit;

			$url = base_url() . "admin/joinfunction";
			$params = "id=$id";
			//for pagination end

			$result = $this->UserModel->UserLoginDetails($id, $limit, $start);
			if ($result['status']) {
				$links = $this->UserModel->paginatelink($limit, $total, $page, $url, $params);
				$data = array('links' => $links, 'data' => $result);
				$this->load->view('login_dtl', $data);
			} else {
				$this->session->set_flashdata('failed', "No records founds");
				redirect(base_url('/admin'));
			}
		}





		public function excelimport()
		{
			if ($this->input->post()) {
				UserLoggedIn();
				$file_name = $_FILES["excelfile"]["name"];
				$file_type = $_FILES["excelfile"]["type"];
				$file_tmpname = $_FILES['excelfile']['tmp_name'];
				$file_size = round($_FILES["excelfile"]["size"] / (1024 * 1024), 2);
				$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
				$allowed_extension = array("xls", "xlsx", "csv");
				if (empty($_FILES['excelfile']['name'])) {
					$this->session->set_flashdata('failed', 'Please select file');
					redirect(base_url('/admin'));
				} else if (!in_array($file_extension, $allowed_extension)) {
					$this->session->set_flashdata('failed', 'Please select xls, xlsx, csv file');
					redirect(base_url('/admin'));
				} else if ($file_size >= 2) {
					$this->session->set_flashdata('failed', 'Please select size less than 2 MB');
					redirect(base_url('/admin'));
				}

				$array = array();
				$success = array();
				$failed = array();
				$duplicate = array();
				if ($file_extension == 'xls') {
					$render = new \PhpOffice\PhpSpreadsheet\Reader\Xls;
				} else if ($file_extension == 'xlsx') {
					$render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
				} else {
					$render = new \PhpOffice\PhpSpreadsheet\Reader\Csv;
				}
				$spreadsheet = $render->load($file_tmpname);
				$data = $spreadsheet->getActiveSheet()->toArray();
				foreach ($data as $key => $row) {
					if ($key == 0) {
						continue;
					}
					$name = $row[0];
					$email = $row[1];
					$phone = $row[2];
					$password = $row[3];
					$dataImp = [
						'name' => $name,
						'email' => $email,
						'phone' => $phone,
						'password' => password_hash($password, PASSWORD_DEFAULT),
					];
					array_push($array, $dataImp);
					$result_email = $this->UserModel->findByEmail($email);
					if ($result_email['status']) {
						array_push($duplicate, $email);
					} else {
						$result = $this->UserModel->save($dataImp);
						if ($result['status']) {
							array_push($success, $email);
						} else {
							array_push($failed, $email);
						}
					}
				}

				$excel_result = array("success" => $success, "failed" => $failed, "duplicate" => $duplicate);
				echo "<pre>";
				print_r($excel_result);
				echo "</pre>";
			} else {
				redirect(base_url('/admin'));
			}
		}


		public function excelexport()
		{
			UserLoggedIn();
			$result = $this->UserModel->getAllData();
			$fileName = date("YmdHis") . "-" . rand(10, 100) . '.xlsx';
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Id');
			$sheet->setCellValue('B1', 'Name');
			$sheet->setCellValue('C1', 'Email');
			$sheet->setCellValue('D1', 'Phone');
			$sheet->setCellValue('E1', 'Picture');
			$sheet->setCellValue('F1', 'Created Date');
			$rows = 2;
			foreach ($result as $val) {
				$sheet->setCellValue('A' . $rows, $val['id']);
				$sheet->setCellValue('B' . $rows, $val['name']);
				$sheet->setCellValue('C' . $rows, $val['email']);
				$sheet->setCellValue('D' . $rows, $val['phone']);
				$sheet->setCellValue('E' . $rows, $val['picture']);
				$sheet->setCellValue('F' . $rows, $val['created_at']);
				$rows++;
			}
			$writer = new Xlsx($spreadsheet);
			$writer->save("xl-export/" . $fileName);
			header("Content-Type: application/vnd.ms-excel");
			redirect(base_url() . "xl-export/" . $fileName);
		}


		public function  pdfexport()
		{
			UserLoggedIn();
			$result = $this->UserModel->getAllData();
			$data = array("users" => $result);
			$this->load->view('mypdf', $data, true);

			// composer require dompdf/dompdf -----------> useing command line dom pdf pakege 
			$dompdf = new \Dompdf\Dompdf();
			$dompdf->set_option('isRemoteEnabled', true);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'portrait'); //setPaper('A4', 'landscape');
			$dompdf->render();
			$canvas = $dompdf->get_canvas();
			$canvas->page_text(10, 20, "Page - {PAGE_NUM} of {PAGE_COUNT}", null, 11, array(0, 0, 0));
			$file_name = rand(10, 10000) . '.pdf';
			$dompdf->stream($file_name);

			// error_reporting(0); -----------> useing manually downloaded dom pdf pakege  
			// $this->load->library('pdf');
			// mb_internal_encoding('UTF-8');
			// $this->dompdf->loadHtml($html);
			// $this->dompdf->render();
			// $this->dompdf->setPaper('A4', 'landscape');
			// $canvas = $this->dompdf->get_canvas();
			// $canvas->page_text(10, 20, "Page - {PAGE_NUM} of  {PAGE_COUNT}", null, 11, array(0,0,0));
			// $output = $this->dompdf->output();
			// $file_name = rand(10,10000).'.pdf';
			// file_put_contents(FCPATH.'pdf-export/'.$file_name, $output);
			// redirect(base_url('pdf-export/'.$file_name)); 
		}


		public function  multipledatabase()
		{
			UserLoggedIn();
			$result = $this->UserModel->getAllUserFromSecondDb();
			$data = array("users" => $result);
			$this->load->view('multiple-db-view', $data);
		}


		public function  sendmail()
		{
			UserLoggedIn();
			$this->load->library('email');
			$config = array();
			$config['protocol'] = 'smtp'; //'smtp'//sendmail
			$config['mailpath'] = '/usr/sbin/sendmail';
			$config['charset'] = 'iso-8859-1';
			$config['wordwrap'] = TRUE;
			$config['smtp_host'] = 'smtp.googlemail.com'; //'ssl://smtp.googlemail.com'
			$config['smtp_user'] = 'onlinemessages0001@gmail.com';
			$config['smtp_pass'] = 'wiuocjngxjitzntr';
			$config['smtp_port'] = 25; //587//25
			$this->email->initialize($config);
			$this->email->from("onlinemessages0001@gmail.com", 'Identification');
			$this->email->to("mondalbidyut38@gmail.com");
			$this->email->cc('another@another-example.com');
			$this->email->bcc('them@their-example.com');
			$this->email->subject('Send Email Codeigniter');
			$this->email->message('The email send using codeigniter library');
			if ($this->email->send()) {
				echo 1;
			} else {
				echo 0;
			}
		}



		public function edit()
		{
			UserLoggedIn();
			$id = $this->uri->segment(2);
			$result = $this->UserModel->findById($id);
			if ($result['status']) {

				$data = array("users" => $result);
				$this->load->view('edit', $data);
			} else {
				$this->session->set_flashdata('failed', $result['message']);
				redirect(base_url('/admin'));
			}
		}


		public function file_check()
		{
			$file_name = $_FILES["picture"]["name"];
			$file_type = $_FILES["picture"]["type"];
			$file_tmpname = $_FILES['picture']['tmp_name'];
			$file_size = round($_FILES["picture"]["size"] / (1024 * 1024), 2);
			$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
			$allowed_extension = array("png", "PNG", "jpg", "jpeg", "JPEG", "gif");
			if (empty($_FILES['picture']['name'])) {
				$this->form_validation->set_message('file_check', 'Please select profile picture');
				return false;
			} else if (!in_array($file_extension, $allowed_extension)) {
				$this->form_validation->set_message('file_check', 'Please select gif, jpg, png file');
				return false;
			} else if ($file_size >= 2) {
				$this->form_validation->set_message('file_check', 'Please select size less than 2 MB');
				return false;
			} else {
				return true;
			}
		}

		public function updateuser()
		{
			UserLoggedIn();
			$loggedinuser = $this->session->userdata('user');
			$id = $this->uri->segment(2);
			$user = $this->UserModel->findById($id);

			if ($user['status']) {
				$userdata = array("users" => $user);
			} else {
				$this->session->set_flashdata('failed', $user['message']);
				redirect(base_url('/admin'));
			}
			if ($this->input->post()) {
				$result_email = $this->UserModel->findByIdEmail($id, $this->input->post('email'));
				$email_check = "";
				if (!$result_email['status']) {
					$email_check = "|is_unique[users_tbl.email]";
				}
				$result_phoen = $this->UserModel->findByIdPhone($id, $this->input->post('phone'));
				$phoen_check = "";
				if (!$result_phoen['status']) {
					$phoen_check = "|is_unique[users_tbl.phone]";
				}
				$this->form_validation->set_error_delimiters('<label class="invalid">', '</label>');
				$this->form_validation->set_rules('name', 'name', 'trim|required');
				$this->form_validation->set_rules('email', 'email', "trim|required|valid_email$email_check");
				$this->form_validation->set_rules('phone', 'phone', "trim|required$phoen_check");
				if (isset($_POST['password']) && $_POST['password'] !== "") {
					$this->form_validation->set_rules('password', 'password', 'required|min_length[6]|max_length[12]');
				}

				if (isset($_FILES["picture"]) && $_FILES["picture"]['name'] !== "") {
					$this->form_validation->set_rules('picture', '', 'callback_file_check');
				}

				$this->form_validation->set_message('is_unique', 'This %s already exists. Try different.');
				$this->form_validation->set_message('min_length', '{field} minimum 6 digit');
				$this->form_validation->set_message('max_length', '{field} maximum 12 digit');

				if ($this->form_validation->run() == FALSE) {

					$this->load->view('edit', $userdata);
				} else {
					$newfile_name = "";
					$file = "";
					if (isset($_FILES["picture"]) && $_FILES["picture"]['name'] !== "") {
						$file_name = $_FILES["picture"]["name"];
						$file_type = $_FILES["picture"]["type"];
						$file_tmpname = $_FILES['picture']['tmp_name'];
						$file_size = $_FILES["picture"]["size"];
						$file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
						$newfile_name = date("YmdHis") . "-" . rand(10, 100) . ".$file_extension";
						if (!move_uploaded_file($file_tmpname, FCPATH . 'uploads/' . $newfile_name)) {
							$newfile_name = "";
							$file = "Failed to movie file.";
						}
					}
					$data['name'] = $this->input->post('name');
					$data['email'] = $this->input->post('email');
					$data['phone'] = $this->input->post('phone');
					if (isset($_POST['password']) && $_POST['password'] !== "") {
						$data['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
					}
					if (!empty($newfile_name)) {
						$data['picture'] = $newfile_name;
					}
					$update = $this->UserModel->UpdateUser($data, $id);
					if ($update['status']) {
						if (!empty($newfile_name)) {
							if (!empty($user['data']->picture)) {
								if (file_exists(FCPATH . 'uploads/' . $user['data']->picture)) {
									unlink(FCPATH . 'uploads/' . $user['data']->picture);
									$file  = "New file successfully uploaded. User old file has been deleted.";
								} else {
									$file  = "New file successfully uploaded. User old file directory not found.";
								}
							}
						}
						if ($loggedinuser['details']->id == $id) {
							$this->logout();
						}
						$this->session->set_flashdata('success', $update['message'] . " " . $file);
						redirect(base_url('/admin'));
					} else {
						$this->session->set_flashdata('failed', $update['message'] . " " . $file);
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
			$id = $this->uri->segment(2);
			$result = $this->UserModel->DeleteById($id);
			if ($result['status']) {
				if ($loggedinuser['details']->id == $id) {
					$this->logout();
					// $this->session->set_userdata('user',"");
					// unset($_SESSION['user']);
					// $this->session->set_flashdata('success',"Your account has been deleted. Please create an account. ");
					// redirect(base_url('/register'));
				} else {
					$this->session->set_flashdata('success', $result['message']);
					redirect(base_url('/admin'));
				}
			} else {
				$this->session->set_flashdata('failed', $result['message']);
				redirect(base_url('/admin'));
			}
		}


		public function logout()
		{
			UserLoggedIn();
			$loggedinuser = $this->session->userdata('user');
			$datalog = [
				'logouttime' => date('Y-m-d H:i:s'),
			];
			$savedtl = $this->UserModel->savelogoutdetails($datalog, $loggedinuser['login_id']);
			if ($savedtl['status']) {
				$this->session->set_userdata('user', "");
				unset($_SESSION['user']);
				$this->session->set_flashdata('success', "Successfully logged out");
				redirect(base_url('/login'));
			} else {
				$this->session->set_flashdata('failed', $savedtl['message']);
				redirect(base_url('/admin'));
			}
		}
	}



	?>