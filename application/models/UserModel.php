<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {


  public function getAllData()
	{ 
    $query = $this->db->select('*')->get('users_tbl');
    $result = $query->result_array();
    return $result; 
	}


  
  public function save($data)
	{ 
    try {
      if($this->db->insert('users_tbl', $data)){
        return array("status"=>true,"lastid"=>$this->db->insert_id(),"message"=>"Successfully saved.");
      } else {
        return array("status"=>false,"message"=>"Failed to save data.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}


  public function findById($id)
	{ 
    try {
      if(empty($id)){
        return array("status"=>true,"email"=>"id required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `id`='$id'");
      $result = $query->row();
      if($result->totalis >= 1){
        $getquery = $this->db->select('*')->where('id', $id)->get('users_tbl');
        $data = $getquery->row();
        return array("status"=>true,"data"=>$data,"message"=>"Record found");
      } else {
        return array("status"=>false,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}
 

  public function findByEmail($email)
	{ 
    try {
      if(empty($email)){
        return array("status"=>true,"email"=>"email required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `email`='$email'");
      $result = $query->row();
      if($result->totalis >= 1){
        $getquery = $this->db->select('*')->where('email', $email)->get('users_tbl');
        $data = $getquery->row();
        return array("status"=>true,"data"=>$data,"message"=>"Record found");
      } else {
        return array("status"=>false,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}
 

  public function DeleteById($id)
	{ 
    try {
      if(empty($id)){
        return array("status"=>true,"email"=>"id required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `id`='$id'");
      $result = $query->row();
      if($result->totalis >= 1){
        $getquery = $this->db->select('*')->where('id', $id)->get('users_tbl');
        $data = $getquery->row();
        return array("status"=>true,"data"=>$data,"message"=>"Successfully deleted.");
      } else {
        return array("status"=>false,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}
  
  
  
  
  
  
  
  
  
}
?>