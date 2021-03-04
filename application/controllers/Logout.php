<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
	}
	public function index()
	{
		$this->session->unset_userdata('user_id') ;
		$this->session->unset_userdata('role_id') ;
		$this->session->sess_destroy();
		
		redirect(base_url() . 'login');	
	}
	
}
