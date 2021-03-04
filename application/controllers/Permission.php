<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission extends CI_Controller {
	
	public $data = array();
	
	/**
	* Function __construct
	*
	* constructor for Permission class
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return NULL
	*/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('permission_model');
		$this->load->model('user_model');
		//fetch menu data
		$this->data['menu'] = $this->user_model->get_menus();
	}
	/**
	* Function index
	*
	* Module permission lisitng 
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return array
	*/
	public function index()
	{
		$this->data['title'] = 'Module Permissions';
		//get modules &sub modules data
		$this->data['modules'] = $this->permission_model->get_all_modules();
		if($_POST)
		{
			$this->form_validation->set_rules('module[]', 'Module', 'trim|callback_valid_module');
			$this->form_validation->set_rules('id_user', 'User', 'trim|required');
			if($this->form_validation->run() == TRUE){
				
				$this->permission_model->add_module_permission($_POST);
				$this->session->set_flashdata('message_name', 'Permission has been saved.');
				
				redirect(base_url() . 'permission');		
			}
			else
			{ 
				$this->load->view('permission/list_module_permission',$this->data);
			}
		}
		else
		{
			$this->load->view('permission/list_module_permission',$this->data);
		}
	}
	/**
	* Function delete_module_permission
	*
	* Delete user module permission
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return NULL
	*/
	public function delete_module_permission()
	{
		$id_user = trim($_REQUEST['id_user']);
		if($id_user)
		{
			$this->permission_model->delete_module_permission($id_user);
			$this->session->set_flashdata('message_name', 'Permission has been deleted.');
		}
		redirect(base_url() . 'permission');	
	}
	/**
	* Function data_permission
	*
	* List page for data permission
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return array
	*/
	public function data_permission()
	{
		$this->data['title'] = 'Data Permissions';
		$this->data['brands'] = $this->user_model->get_brands_active();
		
		$this->data['campaigns'] = $this->permission_model->get_all_campaigns();
		
		if($_POST)
		{
			$this->form_validation->set_rules('id_user', 'User', 'trim|required');
			if($_POST['permission_type']=='1') //brand permission
			{
				$this->form_validation->set_rules('brand_multi[]', 'Brand', 'trim|callback_valid_brand');
			}
			elseif($_POST['permission_type']=='2')
			{
				$this->form_validation->set_rules('brand_id', 'Brand', 'trim|required');
				$this->form_validation->set_rules('widget_id[]', 'Campaigns', 'trim|callback_valid_campaign');
			}
			
			if($this->form_validation->run() == TRUE){
				
				$this->permission_model->add_data_permission($_POST);
				$this->session->set_flashdata('message_name', 'Permission has been saved.');
				
				redirect(base_url() . 'permission/data_permission');		
			}
			else
				$this->load->view('permission/list_data_permission',$this->data);
		}
		else
			$this->load->view('permission/list_data_permission',$this->data);
	}
	/**
	* Function delete_data_permission
	*
	* Delete user brand & data permission
	*
	* @Created Date: 16 Feb, 2017
	* @Modified Date: 16 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return NULL
	*/
	public function delete_data_permission()
	{
		$id_user = trim($_REQUEST['id_user']);
		if($id_user)
		{
			$this->permission_model->delete_data_permission($id_user);
			$this->session->set_flashdata('message_name', 'Permission has been deleted.');
		}
		redirect(base_url() . 'permission/data_permission');	
	}
	
	/*------------------------------------------------------CALL BACK & AJAX FUNCTION ------------------------------------------------------------------*/
	/**
	* Function valid_module
	*
	* callback function to validate modules
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return boolean
	*/
	function valid_module($array)
	{
		if(count($array)>0)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('valid_module','Please select at least one sub module');
			return false;
		}
	}
	/**
	* Function list_module_permission
	*
	* ajax function to get list of permissed/non permissied users
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return array
	*/
	public function list_module_permission()
	{
		$data = $this->permission_model->list_module_permission_data($_REQUEST);
		echo $data;
	}
	/**
	* Function get_user_permission_module
	*
	* ajax function to get list of modules for user access area
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return JSON
	*/
	public function get_user_permission_module()
	{
		$id_user = trim($_REQUEST['id_user']);
		$data  = $this->permission_model->get_user_permission_module($id_user);
		echo json_encode($data);
	}
	/**
	* Function list_data_permission
	*
	* ajax function to get list of permissed/non permissied users
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return array
	*/
	public function list_data_permission()
	{
		$data = $this->permission_model->list_data_permission_users($_REQUEST);
		echo $data;
	}
	/**
	* Function get_campaigns_by_brand
	*
	* ajax function to get lisT OF campaigns
	*
	* @Created Date: 13 Feb, 2017
	* @Modified Date: 13 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return JSON
	*/
	function get_campaigns_by_brand(){
		if($_REQUEST['brand'])
		{
			$data = $this->permission_model->get_all_campaigns($_REQUEST);
		}
		echo $data;
	}
	/**
	* Function get_user_permission_data
	*
	* ajax function to get list of modules for user access area
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return JSON
	*/
	public function get_user_permission_data()
	{
		$id_user = trim($_REQUEST['id_user']);
		$data  = $this->permission_model->get_user_permission_data($id_user);
		echo json_encode($data);
	}
	/**
	* Function valid_brand
	*
	* callback function to validate modules
	*
	* @Created Date: 16 Feb, 2017
	* @Modified Date: 16 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return boolean
	*/
	function valid_brand($array)
	{
		if(count($array)>0)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('valid_brand','Please select at least one Brand');
			return false;
		}
	}
	/**
	* Function valid_campaign
	*
	* callback function to validate modules
	*
	* @Created Date: 16 Feb, 2017
	* @Modified Date: 16 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return boolean
	*/
	function valid_campaign($array)
	{
		if(count($array)>0)
		{
			return true;
		}
		else
		{
			$this->form_validation->set_message('valid_campaign','Please select at least one Campaign');
			return false;
		}
	}
	/**
	* Function get_user_permissed_brands
	*
	* ajax function to get lisT OF campaigns
	*
	* @Created Date: 13 Feb, 2017
	* @Modified Date: 13 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return JSON
	*/
	function get_user_permissed_brands(){
		
		if($_REQUEST['id_user'])
		{
			$data = $this->permission_model->get_user_permissed_brands($_REQUEST['id_user']);
			
			if($data)
			{
				$res = '<option value="">-- Select Brand --</option>';
				foreach((array)$data as $row)
				{
					 $res .= '<option value='. $row->id_domain.' >'. $row->domain_name.'</option>';    
				}
			}
			else
			{
				$res.= '<option>No Brand Permission</option>';
			}
			echo $res;
		
		}
	}
	
	/**
	* Function get_all_brands
	*
	* ajax function to gte list of brands
	*
	* @Created Date: 16 Feb, 2017
	* @Modified Date: 16 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return JSON
	*/
	function get_all_brands(){
		
		if($_REQUEST['id_user'])
		{
			$ids = array();
			$data = $this->permission_model->get_user_permissed_brands($_REQUEST['id_user']);
			foreach((array) $data as $val)
			{
				$ids[] = $val->id_domain;
			}
			echo json_encode($ids);
			exit;
		}
	}
	
	//=== function for how work to permission
    public function how_permission_work()
    {
        $this->data['title'] = 'How Permission Works?';       
        $this->load->view('permission/how_permission_work',$this->data);
    }
	
}	
