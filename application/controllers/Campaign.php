<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends CI_Controller {

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
     * @Method : view_campaign
     * Created By: STPL
     * Modified By: STPL
     * @Purpose: This function is used to display modules
     * @Param: none
     * @Return: none 
     */
	public function all()
	{
		$this->load->model('campaign_model');
		$this->load->model('user_model');
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'All Campaign';
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
	public function products_list_all()
	{
		$this->load->model('campaign_model');
		$data['title'] = 'All Campaign';
		if($this->session->userdata('user_id')!='')
		{
			$query = $this->campaign_model->getall_campaign();
			$data['result'] = $query->result();
			$this->load->view('campaign/products_list_all',$data);
		}
		else
		{
			$this->load->view('/');
		}
		
	}
	
	public function create_promo()
	{
		$params=$_REQUEST['product_id'];
		$promo_code = $this->campaign_model->getPromocode($params);
		echo $promo_code;die;
		//echo "<pre>";print_r($promo_code);die;
	}
	
	public function check_campagn_url()
	{
		//echo "<pre>";print_r($_REQUEST);die;
		$data = $this->campaign_model->check_campagn_url($_REQUEST);
		echo (!empty($data)) ?
			json_encode(array("result"=>"success","id"=>$data[0]['id'],"mesg"=>"Data Found"))
			:
			json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
		
	}
	public function products_campaign_create()
	{
		$params=$_REQUEST['product_id'];
		$data['retailers'] = $this->campaign_model->getProductRetailer($params);
		$this->load->view('campaign/products_campaign_create',$data);
	}
	public function create()
	{
		
		$this->load->model('product_model');
		$this->load->model('user_model');
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'Create Campaign';
		$data['brands'] = $this->campaign_model->getAllBrands();
		$params=$_REQUEST['domain'];
		if($this->session->userdata('id')=='')
		{
			$query = $this->product_model->getall_products($params);
			$data['result'] = $query->result();
			if(!empty($_POST) ){
				$resultData = $this->campaign_model->insertcampaign();
				echo (!empty($resultData[0]->hash_key)) ?
					json_encode(array("result"=>"success","promo_code"=>$resultData[0]->promo_code,"hash_key"=>$resultData[0]->hash_key,"widget_name"=>$resultData[0]->widget_name,"mesg"=>"Campaign created Successfuly"))
					:
					json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
				exit;
			}
			//echo "<pre>";print_r($data);die;
			$this->load->view('campaign/create',$data);
		}
		else
		{
			$this->load->view('/');
		}
		
	}
	
	
	public function update()
	{
		$this->load->model('product_model');
		$this->load->model('user_model');
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'Create Campaign';
		$data['brands'] = $this->campaign_model->getAllBrands();
		$params=$_REQUEST['cid'];
		if($this->session->userdata('id')=='')
		{
			$query = $this->campaign_model->get_campaign($params);
			$data['result'] = $query->result();
			$params=$_REQUEST['product_id'];
			$data['retailers'] = $this->campaign_model->getProductRetailer($params);
			//echo "<pre>";print_r($data);die;
			if(!empty($_POST) ){
				$resultData = $this->campaign_model->updatecampaign();
				//echo "<pre>";print_r($resultData);die;
					//echo (!empty($resultData)) ? "Campaign created successfully"
					//					   : "Some error occurred please try again";
					echo (!empty($resultData[0]->hash_key)) ?
					json_encode(array("result"=>"success","promo_code"=>$resultData[0]->promo_code,"hash_key"=>$resultData[0]->hash_key,"widget_name"=>$resultData[0]->widget_name,"mesg"=>"Campaign Updated Successfuly"))
					:
					json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
				exit;
			}
			//echo "<pre>";print_r($data);die;
			$this->load->view('campaign/update',$data);
		}
		else
		{
			$this->load->view('/');
		}
		
	}
	
	public function list_all()
	{
		$data['title'] = 'All Campaign';
		$this->load->view('campaign/list_all',$data);
	}
	
	public function create_product()
	{
		$data['title'] = 'Create Product';
			if(!empty($_POST) ){
				$data = $this->campaign_model->insertproduct();
				///$data = $this->campaign_model->check_campagn_url($pid,$rid,$url);
				echo (!empty($data)) ?
				json_encode(array("result"=>"success","id"=>$data,"mesg"=>"Product added successfully. You can now continue to create product campaign."))
				:
				json_encode(array("result"=>"failed","mesg"=>"Some error occured. Please try again"));
				die;
			}
		$this->load->view('campaign/create_product',$data);
	}
	
	//=== function for how work to permission
    public function how_it_work()
    {
        $this->data['title'] = 'How it Works?';       
        $this->load->view('campaign/how_it_work',$this->data);
    }
	
}