<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {
	
	Public function __construct() { 
         parent::__construct(); 
      } 

	Public function authenticate($data)
	{
		$query = $this->db->get_where('campaign_users', array('email' => $data['email'],'password'=>encrypt($data['password'])))->row_array();
		//$query = $this->db->get_where('campaign_users', array('email' => $data['email']))->row_array();
		return $query;	
	}
	
	//==== Function for check forgot email exist
	public function checkforgotemail()
	{		
		$userEmail 	= $this->db->query("SELECT * FROM campaign_users WHERE email='".trim($_POST['email'])."'");											
		return $row = $userEmail->result();		
	}	
}