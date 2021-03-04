<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo "sdsd";die;
class Profile extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->load->model('permission_model');
        $this->load->model('user_model');       
		$this->load->model('report_model'); 
		$this->load->helper('custom_helper');
		$this->data['menu'] = $this->user_model->get_menus();
    }
	//==== Function for user dashboard
	public function dashboard()
	{
		$data['title'] = 'Dashboard';
		//get menu data
		$data['menu']  = $this->user_model->get_menus();
		$data['totalhits']  = $this->user_model->gettotalhits();
		$data['result'] = $this->user_model->gettopfive_campaign();
		$data['graphresult'] = $this->user_model->gettopfivegraph_campaign();
		$getalluser    = $this->user_model->getallusers();		
		//a($data['menu']); exit;
		$this->load->view('dashboard',$data);
	}
	/*
	  View User profile  page 
	*/
	public function user_profile()
	{
		$this->data['title'] 	 = 'Profile';
		$this->data['sub_title'] = 'Account Settings';
		if($this->session->userdata('user_id') != '')
		{
			$this->data['pagetitle']='setting';
			$country_list = $this->user_model->get_country_list(); 
			$this->data['country_list'] = $country_list;
			$get_state_list = $this->user_model->get_state_list(); 
			$this->data['state_list'] = $get_state_list; 
			$user_data = $this->user_model->get_all_user_data($this->session->userdata('user_id'));
			$this->data['user_data'] = $user_data;
			//echo "<pre>aa";print_r($this->data['user_data']);die;
			$this->load->view('profile/user_profile',$this->data);		
			$this->session->set_userdata('msgProfile', '');
		} else if($this->session->userdata('agency_id') != '')
		{
			$this->data['pagetitle']='setting';
			$country_list = $this->common_model->get_country_list(); 
			$this->data['country_list'] = $country_list;
			$get_state_list = $this->common_model->get_state_list(); 
			$this->data['state_list'] = $get_state_list; 
			$user_data = $this->user_model->get_agency_data($this->session->userdata('agency_id'));
			$this->data['user_data'] = $user_data;
			$this->load->view('profile/user_profile',$this->data);		
			$this->session->set_userdata('msgProfile', '');
		}
		else
		{
			redirect(base_url());
		}
	}
	
    /*
	   User Change Password
	*/
	function change_password()
	{
		$this->data['title'] = 'Change Password';
		$this->data['sub_title'] = 'Change Password';
		if($this->session->userdata('user_id')!= '')
		{
			$this->data['pagetitle']='setting';
			$this->load->view('profile/change_password',$this->data);		
			$this->session->set_userdata('msgchange', '');
		}
		else
		{
			redirect(base_url());
		}
	}
	
	/*
	   User Update Password
	*/
	function update_password()
	{
		//echo "<pre>sss";print_r($_POST);die;
		$this->data['title'] = 'Change Password';
		if($this->session->userdata('user_id') != '')
		{
			if(isset($_POST) && $_POST['old_password']!='')
			{
				$result = $this->user_model->getOldPassword($_POST);
				//echo "<pre>kkkkkk";print_r($result);die;
				if($result)
				{
					if($_POST['new_password']==$_POST['confirm_password'])
					{
						//6echo "<pre>kkkkkk";print_r($_POST);die;
						$suc = $this->user_model->updateUserPassword($_POST);//For updating the password
						if($suc)
						{
							echo json_encode(array("result"=>"success","mesg"=>"Password Updated Successfuly"));
							exit;
							$this->session->set_userdata('msgchange', "Password Updated Successfully.");
							redirect(base_url('profile/change_password'));
						}
					}else{
							echo json_encode(array("result"=>"failed","mesg"=>"Password not matched"));
							exit;
							$this->session->set_userdata('msgchange', "Password new does not match with confirm.");
							redirect(base_url('profile/change_password'));
					}
				}
				else
				{
					echo json_encode(array("result"=>"failed","mesg"=>"Old Password is not Correct, please try again"));
					exit;
					$this->session->set_userdata('msgchange', "Old Password is not Correct, please try again");
					redirect(base_url('profile/change_password'));
				}
			}
		} 
		else
		{
			redirect(base_url());
		}
	}
	
}

