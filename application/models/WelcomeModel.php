<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WelcomeModel extends CI_Model {
  
  public function getAllData()
	{
    $this->load->database();
		// $query = $this->db->query("SELECT * From `user_tbl`");
    // $query = $this->db->get('mytable', 10, 20);
    $query = $this->db->select('*')->limit(5)->get('user_tbl');//->where('id', $id)
    $result = $query->result_array();
    return $result; 
	}
  
  
  
  
  
  
  
  
  
}
?>