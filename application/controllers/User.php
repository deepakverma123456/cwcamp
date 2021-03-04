<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	
	public function __construct()
	{
        parent::__construct();
		$this->load->model('permission_model');
        $this->load->model('user_model');       
		$this->load->model('report_model'); 
		$this->load->helper('custom_helper');
		$this->load->helper('mail_helper');
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
	
	//==== Function for user dashboard2 for new development when done it should migrate to dashboard
	public function dashboard2()
	{
		$data['title'] = 'Dashboard';
		//get menu data
		$data['menu']  = $this->user_model->get_menus();
		$data['totalhits']  = $this->user_model->gettotalhits();
		$data['result'] = $this->user_model->gettopfive_campaign();
		$data['graphresult'] = $this->user_model->gettopfivegraph_campaign();
		$getalluser    = $this->user_model->getallusers();		
		//a($data['menu']); exit;
		$this->load->view('dashboard2',$data);
	}
	
	//==== Function for list all users
	public function list_all()
	{
		$data['title'] = 'All Users';		
		//$this->login_model->checkmoduleAuth();
		$userId 	= 1;	
        if($userId=='')
		{ 
        	redirect(base_url().'login/'); 
        }               
		
		$getalluser = $this->user_model->getallusers();	
		if(count($getalluser) > 0)
		{                		
			$data['alluserlists'] = $getalluser;
		}
		$data['menu']  = $this->user_model->get_menus();
		$this->load->view('user/list_all',$data);           
		$this->session->set_userdata('msg', "");        	
	}
	
	//==== Function for create new user
	public function create()
	{
		$data['title'] = 'Create User Account';
		if(!empty($_POST) ){
			$resultData = $this->user_model->insertuser();
			echo (!empty($resultData)) ? "User created successfully"
									   : "Some error occurred please try again";
			exit;
		}
		$this->load->view('user/create',$data);
	}
	
	//==== Function for edit user
	public function edit_user($user_id=0)
	{		
		$data['title'] = 'Update User Account';
		if(!empty($_POST['id']))
		{	
			$user_id    = decrypt($_POST['id']);
			echo $this->user_model->updateuser($user_id);
			exit;
		}
		$user_id    = decrypt($_REQUEST['id']);
		$data['result'] = $this->user_model->getedituser(array('id'=>$user_id));						
		$this->load->view('user/edit_user',$data);
	}
	
	//==== Code for checking with ajax Email already exist.
	function checkemail()
	{	
		$this->user_model->checkuseremail();
	}
	
	//==== Function for update user status
	public function update_user_status()
	{
		$user_id 	= trim($_POST['id']);
		$status 	= trim($_POST['status']); 
		if(!empty($user_id) && !empty($status))
			echo $this->user_model->updateuserstatus($user_id, $status);
		else 
			echo "0";
		exit;		
	}
	
	//==== Function for delete user
	public function delete_user()
	{
		$user_id 	= trim($_POST['id']);		
		if(!empty($user_id))
			echo $this->user_model->deleteuser($user_id);
		else 
			echo "0";
		exit;		
	}
	
	//==== Function for logout user
	function logout()
 	{
		session_destroy();		
		//redirect(base_url().'user/login');
		redirect(base_url());
	}	
	
	//==== Function for get all campaign list
	public function gettotalhits()
	{			
		
		$getalluser 	= $this->user_model->getallusers();
		$numUser 		= (count($getalluser)>0)? count($getalluser) : "0";
		
		$getallcampaign = $this->user_model->gettotal_campaign();
		$allCampaign 	= (count($getallcampaign)>0)? count($getallcampaign) : "0";
		
		$getallproduct  = $this->user_model->getallbrandproduct();
		$allProduct 	= (count($getallproduct)>0)? count($getallproduct) : "0";
		
		$allclick 		= $this->user_model->gettotalhits();
		$finaclick 		= (($allclick[0]->finaclick)>0)? $allclick[0]->finaclick : "0";	
		$data = array(
					   "user" => $numUser,
					   "activecamp" => $allCampaign,
					   "brandproduct" => $allProduct,
					   "totalclick" => $finaclick
					  );
		
		echo json_encode($data);
		exit;
	}
	
	public function campaignGraph()
    {
        $widget_id     = trim($_REQUEST['id']);  
		if(empty($widget_id)) echo "Some error occurred. Please try again.";
        else{
			$data['graphresult'] = $this->user_model->gettopfivegraph_campaign($widget_id);
			$data['monthlyresult'] = $this->user_model->getcampaign_monthly($widget_id);
			echo json_encode($data);
		}
        exit;       
    }

	public function user_brand_data_permission()
	{
		$brand_per = $this->user_model->get_user_brands_ids();
		if($brand_per)
		{
			$this->brand_permisison = '  AND dm.id IN ('.$brand_per.') ';
		}
		$data_per = $this->user_model->get_user_data();
		if($data_per)
		{
			$this->data_permisison = '  AND wm.id IN ('.$data_per.') ';
		}
	}
	
	//==== Function for get campaign graph
	public function campaign_graph()
	{
		$widget_id 	= trim($_REQUEST['id']);
		//echo $widget_id;die; 
		if(!empty($widget_id))
		{
			$data['graphresult'] = $this->user_model->gettopfivegraph_campaign($widget_id);
			//echo "<pre>";print_r($data);die;
			//$this->load->view('campaign_graph',$data);
			echo '<div>
			<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
<script type="text/javascript">
window.onload = function () {
    var chart = new CanvasJS.Chart("chartContainer",
    {
      title:{
	fontColor: "Grey",
        text: "Campaign Report",			
	fontSize: 15
      },
      axisX:{
        interval: 3,
        intervalType: "month",
	labelFontSize: 12
      },
      axisY: {
	gridColor: "Silver",
	tickColor: "silver",
	interval: 100,
	labelFontSize: 12
	},
      data: [	
      {
        type: "line",
        dataPoints: [//array        
	<?php				
	if(count($graphresult)>0)
	{
		foreach($graphresult as $row)
		{						
	?>
	{
         x: new Date(<?php echo date("Y, m-1, d", strtotime($row->LastclickTime));?>),
         y: <?php  echo $row->TotalClick;?>,
	 }, 
	 <?php }} ?>             
       ]
     },       
     ]
   });

    chart.render();
}
</script>
</div>';
			//$this->load->view('user/campaign_graph',$data);
		}
		else
		{
			echo "0";
		}
		//exit;		
	}
/**
| -------------------------------------------------------------------
|  getQuarterlyHits
| -------------------------------------------------------------------
* @Date:		March 29, 2017
* @Method : 	getQuarterlyHits
* @Created By: 	Nisha Mishra
* @Purpose: 	This function is used to fetch hits of current/previous quarter depending on time slots.
* @Param: 		none
* @Return: 		$array
**/
	public function getQuarterlyHits()
	{   
		$currQuarter 				= get_quarter();   
       	$data['currQuarterStartDt'] = date('d M Y',strtotime($currQuarter['start']));
       	$preQuarter 				= get_quarter(1);   
       	$data['preQuarterStartDt'] 	= date('d M Y',strtotime($preQuarter['start']));
       	$data['preQuarterEndDt'] 	= date('d M Y',strtotime($preQuarter['end']));
		$data['currQuarter'] 		= $this->user_model->getCurrentQuarterHits_slotWise();
		$data['preQuarter'] 		= $this->user_model->getPreQuarterHits_slotWise();
		echo json_encode($data);
		exit;
	}
/**
| -------------------------------------------------------------------
|  userRecentActivity
| -------------------------------------------------------------------
* @Date:		March 31, 2017
* @Method : 	fetchUserTenDaysActivity
* @Created By: 	Nisha Mishra
* @Purpose: 	This function is used to fetch user 10 days records.
* @Param: 		none
* @Return: 		$array
**/
	public function fetchUserTenDaysActivity()
	{
		$data['title'] 				= 'Activity Information';
		$data['recentRecords'] 		= $this->user_model->fetchUserTenDaysActivity();
		$this->load->view('user/user_recent_activity',$data);
	}
}

