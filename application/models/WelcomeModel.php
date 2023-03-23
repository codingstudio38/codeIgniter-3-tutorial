<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WelcomeModel extends CI_Model {
  
  public function getAllData()
	{ 
    $this->load->database();
		// $query = $this->db->query("SELECT * From `users_tbl`");
    // $query = $this->db->get('mytable', 10, 20);
    $query = $this->db->select('*')->get('users_tbl');//->where('id', $id)->limit(5)
    $result = $query->result_array();
    return $result; 
	}
  
  
  
  
  
  
  
  
  
}
?>