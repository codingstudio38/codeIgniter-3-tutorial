<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserModel extends CI_Model {

  public $seconddb;
  public function __construct(){
     $this->seconddb=$this->load->database('seconddb', TRUE); 
	}
  
  public function getAllData()
	{ 
    $query = $this->db->select('*')->get('users_tbl');
    $result = $query->result_array();
    return $result; 
	}

  
  public function getAllUserFromSecondDb()
	{ 
    $query = $this->seconddb->select('*')->get('users');
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
      if($this->db->update('users_tbl', $data, array('id' => $id))){
        return array("status"=>true,"message"=>"Successfully updated.");
      } else {
        return array("status"=>false,"message"=>"Failed to updated data.");
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
  
  
public function CheckTotalRecords(string $query)
{
  try { 
    if(empty($query)){
      return 0;
    }
    $queryis = $this->db->query($query);
    //$result = $query->row();
    return $queryis->num_rows(); 
   } catch(Exception $e) {
    return 0;
  }
} 



public function UserLoginDetails($id, $limit, $start)
{
  try { 
    if(empty($id)){ return array("status"=>false,"message"=>"Id not found."); }
    $this->db->select('*');
    $this->db->from('users_tbl user'); 
    $this->db->join('user_login_details dtl', 'dtl.login_id=user.id', 'inner');
    $this->db->where('user.id',$id);
    $this->db->order_by('dtl.created_at','DESC');
    $this->db->limit($limit, $start);         
    $query = $this->db->get(); 
    if($query->num_rows() > 0)
    {
      return array("status"=>true,"message"=>"Record found.","total"=>$query->num_rows(),"data"=>$query->result_array());
    } else {
      return array("status"=>false,"message"=>"Record not found.");
    }
  } catch(Exception $e) {
    return array("status"=>false,"message"=>$e->getMessage());
  }
	
}  
  
  
public function savelogindetails($data)
{  
  try {
    if($this->db->insert('user_login_details', $data)){
      return array("status"=>true,"lastid"=>$this->db->insert_id(),"message"=>"Login information successfully saved.");
    } else {
      return array("status"=>false,"message"=>"Failed to save data.");
    } 
  } catch(Exception $e) {
    return array("status"=>false,"message"=>$e->getMessage());
  }
} 

public function savelogoutdetails($data,$id)
{ 
  try {
    if($this->db->update('user_login_details', $data, array('id' => $id))){
      return array("status"=>true,"lastid"=>$this->db->insert_id(),"message"=>"Successfully update.");
    } else {
      return array("status"=>false,"message"=>"Failed to update.");
    } 
  } catch(Exception $e) {
    return array("status"=>false,"message"=>$e->getMessage());
  }
} 

  



public function paginatelink(int $limit,int $total_records,int $page,string $url,string $params){
  if(empty($params)){
    $params_ = "";
  } else {
    $params_ = "&".$params;
  }
	$previous = $page-1;
	$next =$page+1;
	$currentPage = $page;		
	$total_page = ceil($total_records/$limit);		
	$lastPage = $total_page;
	$fiestPage = 1;
	if($next <= $total_page){
		$next_ = $next;
	} else {
		$next_ = 0;
	}

	if($total_records > $limit){

	
		$output_n = "<nav aria-label='Page navigation example'>";
		$output_n .= "<ul class='pagination nav justify-content-center'>";
		if($previous <= 0){
			$output_n .="<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Previous</a></li>";
		} else {
			$output_n .="<li class='page-item'><a href='$url?page=$previous$params_' class='page-link' rel='prev'>Previous</a></li>";
		}
		if ($currentPage > 3) {
				$output_n .="<li class='page-item'><a class='page-link' href='$url?page=1$params_'>1</a></li>";
			}
			if ($currentPage > 4) {
				$output_n .="<li class='page-item'><a class='page-link' href='javascript:void(0)'>...</a></li>";
			}
		foreach (range(1, $lastPage) as $i){
			if ($i >= $currentPage - 2 && $i <= $currentPage + 2) {
				if ($i == $currentPage) {
				$output_n .="<li class='page-item active'><a class='page-link'>$i</a></li>";
			} else {
				$output_n .="<li class='page-item'><a class='page-link' href='$url?page=$i$params_'>$i</a></li>";
			}
			}
		}
		if ($currentPage < $lastPage - 3) {
			$output_n .="<li class='page-item'><a class='page-link' href='javascript:void(0)'>...</a></li>";
			}

		if ($currentPage < $lastPage - 2) {
		$output_n .="<li class='page-item'><a class='page-link' href='$url?page=$lastPage$params_'>$lastPage</a></li>";
		}

		if($next <= $total_page){
			$output_n .="<li class='page-item'><a class='page-link' href='$url?page=$next$params_' rel='next'>Next</a></li>";
		} else {
			$output_n .="<li class='page-item disabled'><a class='page-link' href='javascript:void(0)'>Next</a></li>";
		}
		
		$output_n .="</ul>";

		$output_n .="</nav>";  



	} else {
	
	$output_n ="";
	
	}
 return array(
    "html_view" => $output_n,
    "active_page" => $page,
    "total_records" => $total_records,
    "total_page" => $total_page,
    "previous" => $previous,
    "next" => $next_,
    "first_page"=>$fiestPage,
    "last_page"=>$lastPage
  );
}
















}
?>