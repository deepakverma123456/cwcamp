<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaining extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/user
	 *	- or -
	 * 		http://example.com/index.php/user/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/user/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');	
		$this->load->library('pagination');
		$this->load->model('campaign_model');
			//$this->load->library('log');		
	}
	
	/*
     * @Created Date: Jan 31, 2017
     * @Modified Date: Jan 31, 2017
     * @Method : create start url
     * Created By: STPL
     * Modified By: STPL
     * @Purpose: This function is used to display modules
     * @Param: none
     * @Return: none 
     */
	public function deploy()
	{
		$this->load->model('campaign_model');
		$this->load->model('user_model');
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'All Campaign';
		echo "GGGGGG<pre>";print_r("test");die;
		
		//echo "<pre>";print_r($this->session->userdata());die;
		if($this->session->userdata('user_id')!='')
		{
			$query = $this->campaign_model->getall_campaign();
			$data['result'] = $query->result();
			//echo "<pre>";print_r($data);die;
			$this->load->view('campaign/all',$data);
		}
		else
		{
			$this->load->view('/');
		}
		
	}
	
	
}