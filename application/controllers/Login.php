<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct()
	{
		parent::__construct();
		$this->load->model('login_model');
		$this->load->model('common_model');
		$this->load->model('user_model');
		$this->load->helper('custom_helper');
		$this->load->helper('mail');
		$this->load->helper('mail_helper');
	}
	/**
| -------------------------------------------------------------------
|  index
| -------------------------------------------------------------------
* @Created Date:  	?
* @Modified Date:  	March 31, 2017
* @Method : 		index
* @Created By: 		?
* @Modified By: 	Nisha Mishra
* @Purpose: 		This function is used to check session
* @Param: 			none
* @Return: 			none
**/
	public function index()
	{
		if($this->session->userdata('user_id'))
		{
			redirect(base_url().'user/dashboard'); 
		}
		else
		{
			$this->load->view('login');
		}
		
	}

/**
| -------------------------------------------------------------------
|  authenticate
| -------------------------------------------------------------------
* @Created Date:  	?
* @Modified Date:  	March 31, 2017
* @Method : 		authenticate
* Created By: 		?
* Modified By: 		Nisha Mishra
* @Purpose: 		This function is used for user authentication and for user last login activity.
* @Param: 			none
* @Return: 			none
**/
	public function authenticate()
	{
		if($_REQUEST['email'])
		{
			$data['email'] = trim($_REQUEST['email']);
			$data['password'] = trim($_REQUEST['password']);
			$result = $this->login_model->authenticate($data);
			//a($result);
			if($result)
			{
				if($result['status']=='1'){
					
					$this->session->set_userdata('user_id',$result['id']) ;
					$this->session->set_userdata('role_id',$result['role_id']) ;
					$this->session->set_userdata('email',$result['email']) ;
					$succ	= $this->user_model->insertUserLastLoginActivity();
					$this->session->set_userdata('userHistoryId',$succ['last_id']);
					echo json_encode(array("result"=>"success","mesg"=>"Logging Successfully"));
				}else{
					echo json_encode(array("result"=>"failed","mesg"=>"Your account has been blocked. Please contact site admin. "));
				}
				exit;
				//echo '200';
			}else{
					echo json_encode(array("result"=>"failed","mesg"=>"Invalid login details. Please try again"));

			}
			
			exit;
		
			
		}
	}
	
	//==== Code for checking with ajax forgot Email exist.
	function forgotpassword()
	{
		if(!empty($_POST['email']))
		{									
			$row = $this->login_model->checkforgotemail();
			if(count($row)>0)
			{
				$data = array('user_id' => $row[0]->id, 'name' => $row[0]->name, 'email' => $_POST['email'], 'password' => decrypt($row[0]->password));
      			$suc = forgetMailService($data);
      			if($suc)
      			{
      				echo "true"; exit;
      			}
                
			}
			else echo "false";
		}
		else {
			echo "false";
			exit;
		}			
	}
	
}
