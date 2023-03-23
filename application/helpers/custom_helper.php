<?php 

function show_date($date=''){
	$CI =& get_instance();
    if ($CI->session->userdata('view_date')=='dd/mm/yyyy') {
      return date('d/m/Y',strtotime(str_replace('/', '-', $date)));
    }
    elseif($CI->session->userdata('view_date')=='mm/dd/yyyy'){
      return date("m/d/Y",strtotime($date));
    }
    else{
      return date("d-m-Y",strtotime($date));
    }
  }


function UserLoggedIn(){
  $CI =& get_instance();
  if(empty($CI->session->userdata('user'))){
    $CI->session->set_flashdata('failed',"You are not logged in.");
		redirect(base_url('/login'));
  }
}

function UserNotLoggedIn(){
  $CI =& get_instance();
  if(!empty($CI->session->userdata('user'))){
    $CI->session->set_flashdata('success',"You are already loggedin.");
		redirect(base_url('/admin'));
  }
}




?>