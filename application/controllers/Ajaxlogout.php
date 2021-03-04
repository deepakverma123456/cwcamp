<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajaxlogout extends CI_Controller {
	private $CI;
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->library('session');
	}
	
	function index()
	{
		// If no loggedin
		$withoutLoginPageArray = array('login','ajaxlogout','reset-password','deploy','');
		
		$urls = array();
		$url = $_SERVER['REQUEST_URI'];
		$urls = explode('/',$url);
		//echo "<pre>";print_r($urls);
				
		if ( $this->session->userdata('user_id') == ''  && !in_array($urls[2],$withoutLoginPageArray)  )  // If no session found redirect to login page.
		{
			echo json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
		}
		elseif( $this->session->userdata('user_id') != '' && in_array($urls[2],$withoutLoginPageArray ))
		{
			echo json_encode(array("result"=>"success","mesg"=>"ok"));
		}else{
			echo json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
		}
		
		exit;
	}
	
}
