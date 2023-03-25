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

  
  public function UpdateUser($data,$id)
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
        return array("status"=>false,"message"=>"id required.");
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
        return array("status"=>false,"message"=>"email required.");
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
  
  public function findByIdEmail($id,$email)
	{ 
    try {
      if(empty($id)){
        return array("status"=>false,"message"=>"id required.");
      }
      if(empty($email)){
        return array("status"=>false,"message"=>"email required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `id`!='$id' AND `email`='$email'");
      $result = $query->row();
      if($result->totalis >= 1){
        $getquery = $this->db->select('*')->where('email', $email)->get('users_tbl');
        $data = $getquery->row();
        return array("status"=>false,"data"=>$data,"message"=>"Record found");
      } else {
        return array("status"=>true,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}

  

  public function findByIdPhone($id,$phone)
	{ 
    try {
      if(empty($id)){
        return array("status"=>false,"message"=>"id required.");
      }
      if(empty($phone)){
        return array("status"=>false,"message"=>"phone no required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `id`!='$id' AND `phone`='$phone'");
      $result = $query->row();
      if($result->totalis >= 1){
        $getquery = $this->db->select('*')->where('phone', $phone)->get('users_tbl');
        $data = $getquery->row();
        return array("status"=>false,"data"=>$data,"message"=>"Record found");
      } else {
        return array("status"=>true,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}

  
  
  public function DeleteById($id)
	{ 
    try {
      if(empty($id)){
        return array("status"=>true,"message"=>"id required.");
      }
      $query = $this->db->query("SELECT COUNT(id) AS totalis FROM `users_tbl` WHERE `id`='$id'");
      $result = $query->row();
      if($result->totalis >= 1){
        $result = $this->findById($id);
        $file = "";
        if(!empty($result['data']->picture)){
          if(file_exists( FCPATH.'uploads/'.$result['data']->picture))
          { 
             unlink(FCPATH.'uploads/'.$result['data']->picture);
             //unlink(__DIR__.'./../../uploads/'.$result['data']->picture)
             $file  = "User file has been deleted.";
          } else {
             $file  = "User file directory not found";
          }
        }
       $delete=$this->db->where('id', $id)->delete('users_tbl');
       return array("status"=>true,"data"=>$delete,"message"=>"Successfully deleted. $file");
      } else {
        return array("status"=>false,"message"=>"Record not found.");
      } 
    } catch(Exception $e) {
      return array("status"=>false,"message"=>$e->getMessage());
    }
	}
  
  
  
  
  
  
  
  
  
}
?>