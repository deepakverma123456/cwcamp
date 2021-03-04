<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deploy extends CI_Controller {

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
		$this->load->model('deploy_model');
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
	function campaign(){
		//setting data for view form
		
		$session_id	= $this->session->userdata['session_id'];
		//echo "<pre>";print_r($_REQUEST);die;
		//echo "<pre>"; print_r($_SERVER);die;
		if(!empty($_REQUEST)){
			
			$userId = '26';
			$created_on = date('Y-m-d H:i:s');
			if($_REQUEST['hkey']){
				
				$getWidget = $this->deploy_model->getwidget($_REQUEST['hkey']);
				//echo "<pre>";print_r($getWidget);die;
				if(!empty($getWidget)){
				$ip=$_SERVER['REMOTE_ADDR'];
				$retailer_logo_url="http://cwqa.srmtechsol.com/china/app/images/".$getWidget[0]['retailer_logo'];
				$loading_logo=base_url()."assets/images/redirect_loading.gif";
				$time       = date('Y-m-d H:i:s');
				$getDevice = $this->get_device_version();
				//echo $getDevice;die;
				$url=$getWidget[0]['buy_now_url'];
				$retailerId=$getWidget[0]['retailer_id'];
				$productId=$getWidget[0]['product_id'];
				$widget_id=$getWidget[0]['widget_id'];
				$ipArray = array('180.233.120.245','180.233.120.246','180.233.121.210','80.233.121.211','182.90.129.192','180.233.120.244','127.0.0.1','192.200.12.169');
				if(!in_array($ip,$ipArray))
				{
					$data_col='';
				}else{
					$data_col='cw_test';
					//$data_col='';
				}
				$data =   array('widget_id'  		=>	$widget_id,
		                'source_url'        =>	$url,
		                'view'              =>	1,
		                'ip'                =>	$ip,
		                'device_version'    => 	$getDevice,
						'bin_type'    => 	'1',
		               /* 'city'              => 	addslashes($getR['city']),
		                'region'            => 	($getR['region_name']!=''?$getR['region_name']:''),
		                'country_code'      => 	$getR['country_code'],
		                'country_name'      => 	$getR['country_name'],
		                'longitude'         => 	$getR['longitude'],
		                'latitude'          => 	$getR['latitude'], */
		                'created_date' 	    => 	$time,
		                'modified_date'     => 	$time,
						'report_status'     =>  $data_col
		            );
					//echo "<pre>";print_r($data);die;
				$sourceData=$this->deploy_model->saveAnalyticViewCampaignWidgetProduct($data);
				if($sourceData){
					$dataClick = array(
								  'widget_id'                  => $widget_id,
								  'ip'                         => $ip,
								  'device_version'             => $getDevice,     
								  'target_url'                 => $url,
								  'click'                      => 1,
								  'widget_source_view_id'      => $sourceData,
								  'retailer_id'                => $retailerId,
								  'retailer_product_images_id' => '0',
								  'product_id'                 => $productId,
								  'time_loaded'                => $time,
								  'created_by'                 => $time,
								  'report_status'              => $data_col
								);
				
					$this->deploy_model->saveAnalyticCampaignWidgetProduct($dataClick);
				}
				
				
				$this->session->set_userdata("msg",$numWidget . " Campaign Created/updated Successfully.<br/>" );
				//$this->log->lwrite(__LINE__ . "Campaign Created Successfully.",1);
//die;
//echo $url;die;

				echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
				echo "<div style='margin:0 auto; padding-top:10%; width:100%; text-align:center;font-size:16px;'><img src='".$retailer_logo_url."' width='100px' ><br /><img src='".$loading_logo."'><br />卫宝更强除菌保护正在赶来，请耐心等待~</div>";
				header( "refresh:1; url=".$url );
				exit;
				}else{
					echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />'; 
					echo "<div style='margin:0 auto; padding-top:10%; width:100%; text-align:center;font-size:16px;'>找不到数据</div>";
				exit;
					
				}
				//echo "</body></head></html>";
			}

			
			//header('Location:'.$url);
			#redirect(base_url().'campaign_widget/create');
		}
		$this->session->userdata("msg","");		
		$this->load->view('campaign_widget_create.php',$this->data);
	}
	
	function get_device_version()
	{
		$this->load->library('Mobile_Detect');
        $detect = new Mobile_Detect();
        if ($detect->isMobile()) {
             //echo "opened in mobile";
			 return '1';
        }
        else
        {
            //echo "opened in desktop";
			 return '0';
        }
		
	}
	
	
}