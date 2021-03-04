<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deploy_model extends CI_Model {
	
	public function __construct() { 
         parent::__construct(); 
      } 

	public function authenticate($data)
	{
		$query = $this->db->get_where('campaign_users', array('email' => $data['email'],'password'=>hash('sha256',$data['password'])))->row_array();
		return $query;	
	}
	
	function GenerateKey()
	{
	   return md5(microtime().rand());
	}
	function getwidget($wid=''){
				$wids = trim($wid);
				$sql = "select * from campaign_widget_master
							WHERE hash_key  = '".$wids."'";
				$query 	= $this->db->query($sql);
				$result =$query->result_array();
				if($result[0]['id']){
					$query	= "SELECT x.buy_now_url,wp.widget_id,wp.product_id,wp.retailer_id,rm.retailer_logo,x.buy_now_url FROM  `campaign_widget_products` as wp
					INNER JOIN retailer_master rm ON rm.id=wp.retailer_id
					INNER JOIN product_retailer_to_products x ON x.id = wp.product_retailer_id
					WHERE wp.widget_id='".$result[0]['id']."' and wp.campaign_default='1' and wp.status = '1' and rm.status = '1'";
					$query   	= $this->db->query($query);
					return $query->result_array();
				}
		
		
	}
	
	/*
     * @Created Date: 16 oct 2016
     * @Modified Date: 16 oct 2016
     * @Method:  saveWidgetProduct()
     * Created By: Deepak verma
     * Modified By: Deepak Verma
     * @Purpose: This function to widget data in database
     * @Param: widget data
     * @Return: None
     */
	function saveAnalyticViewCampaignWidgetProduct($data=array()){
				
				  $suc = $this->db->insert('campaign_widget_source_view', $data); 
				  $insert_id = $this->db->insert_id();
				return $insert_id;
	}
	
	/*
     * @Created Date: 16 oct 2016
     * @Modified Date: 16 oct 2016
     * @Method:  saveWidgetProduct()
     * Created By: Deepak verma
     * Modified By: Deepak Verma
     * @Purpose: This function to widget data in database
     * @Param: widget data
     * @Return: None
     */
	function saveAnalyticCampaignWidgetProduct($data=array()){
				
				  $suc = $this->db->insert('campaign_widget_details', $data); 
				  $insert_id = $this->db->insert_id();
		
	}
	
	function saveCampaignWidgetProduct($wpids='',$wids='',$rids=''){
		
				$wpids = trim($wpids);
				$sql = "select * from campaign_widget_products as wp
				INNER JOIN products p ON p.id = wp.product_id
				INNER JOIN domain_master as dm ON dm.id=p.product_website_id
				WHERE wp.id  = '".$wpids."'";
				$query 	= $this->db->query($sql);
				$result =$query->result_array();
				if($result[0]['id']){
					$sql = "UPDATE campaign_twidget_products
								SET campaign_default 	= '0'
								WHERE widget_id  = '".$wids."'";
					$query 	= $this->db->query($sql);
					//echo "<pre>";print_r($query);die;
					if($query){	
						/*$sql = "UPDATE widget_products
								SET campaign_default 	= '1'
								WHERE widget_id  = '".$wids."' and retailer_id  = '".$rids."' ";
						*/
						$sql = "UPDATE campaign_widget_products
								SET campaign_default 	= '1'
								WHERE id  = '".$wpids."'";
								$query 	= $this->db->query($sql);
						//die;
						$sql = "UPDATE campaign_widget_master
								SET widget_type 	= '1',product_id  ='".$result[0]['product_id']."'
								WHERE id  = '".$wids."'";
						$query 	= $this->db->query($sql);
					
					}
				}
				return true;
		
	}
	
	
	
	
}