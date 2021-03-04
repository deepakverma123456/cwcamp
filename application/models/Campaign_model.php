<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign_model extends CI_Model {
	
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
	public function insertproduct()
	{
		if(!empty($_POST['product_name']))
		{
			$post=$_POST;
			$post['description'] = "this is a campaign product";
			$now              = date('Y-m-d H:i:s');	
			$product_url_id=0;
			$post['cat'] = ($post['cat']) ? $post['cat'] : $post['brandname'];
			$post['subcat'] = ($post['subcat']) ? $post['subcat'] : $post['brandname'];
			
			$productsdata    = array(
									'retailer_id'		   =>  $post['retailerid'],
									'product_id'		   =>  $post['productid'],
									'other_products_url_id'=>  $product_url_id,
									'brand'				   =>  $post['brandname'],
									'title'				   =>  str_replace("'","",$post['product_name']),
									'price'				   =>  $post['product_price'],
									'category'			   =>  str_replace("'","",$post['cat']),
									'sub_category'		   =>  str_replace("'","",$post['subcat']),
									'quantity'             => '',
									'buy_now_url'          =>  $_POST['product_url'],
									'description'          =>  $post['description'],
									'reviews'              => 0,
									'hits'                 => 0,
									'rating'               => 0,
									'image_url'            =>  $post['product_image_url'],
									'created_on'           =>  $now,
									'modified_on'           =>  $now,
									'status'           =>  '2',
						     );
			//echo "<pre>"; print_r($productsdata);exit;	 			 
			$sucproductsdata = $this->db->insert('product_retailer_to_products', $productsdata);
			return $product_retailer_id  = $this->db->insert_id();
		}
	}
	public function insertcampaign()
	{
		//echo "<pre>";print_r($_POST);die;
		$userId=$this->session->userdata('user_id') ;
		$userRole=$this->session->userdata('role_id') ;
		if(!empty($_POST['campaign_name']))
		{
			$post=$_POST;
			//echo "<pre>";print_r($post);die;
			$product_retailer_id=$_POST['product_retailer_id'];
			$MASTERKEY = $this->GenerateKey();
			//$userId = 26;
			//$getProductList = $this->getProductListByWeb($userId);
			$getProductList = $this->getProductRetailer($post['productid']);
			$created_on = date('Y-m-d H:i:s');
	
			$widget_theme_checkidcustom  = 0;
			$widget_theme_checkiddefault = 0;
			
			$widgetMaster = array(
								'user_id' => $userId,
								'modified_by' => $userId,
								'hash_key' => $MASTERKEY,
								'widget_name'=>$post['campaign_name'],
								'product_id'=>$post['productid'],
								'promo_code'=>$post['promo_code'],
								'widget_category_id'=>1,
								'widget_theme_id'=>$widget_theme_checkiddefault,
								'custom_theme_detail_id'=>$widget_theme_checkidcustom,
								'created_on'=>$created_on,
								'modified_on'=>$created_on
						);
			$this->db->insert('campaign_widget_master', $widgetMaster);
			$insertId = $this->db->insert_id();
			foreach($getProductList as $val)
			{
				
				if($post['retailer_id']==$val['retailer_id']){
					$widgetPrd = array(
									'user_id' => $userId,
									'widget_id'=>$insertId,
									'product_id' => $post['productid'],
									'retailer_id' => $val['retailer_id'],
									'product_retailer_id' => $product_retailer_id,
									'campaign_default' => 1,
									'created_on'=>$created_on,
									'modified_on'=>$created_on
							);
				}else{
					$widgetPrd = array(
									'user_id' => $userId,
									'widget_id'=>$insertId,
									'product_id' => $post['productid'],
									'retailer_id' => $val['retailer_id'],
									'created_on'=>$created_on,
									'modified_on'=>$created_on
							);
					
				}
				$result =$this->db->insert('campaign_widget_products', $widgetPrd);
				
			}
			if($result){
				return $final_result=$this->gethashCampaign($insertId);
			}
			
		}
	}
	
	public function updatecampaign()
	{
		$userId=$this->session->userdata('user_id') ;
		$userRole=$this->session->userdata('role_id') ;
		if(!empty($_POST['campaign_name']))
		{
			//echo "<pre>";print_r($_POST);die;
			$post=$_POST;
			$product_retailer_id=$_POST['product_retailer_id'];
			$MASTERKEY = $this->GenerateKey();
			//$userId = 26;
			$getProductList = $this->getProductRetailer($post['productid']);
			$created_on = date('Y-m-d H:i:s');
			$widget_theme_checkidcustom  = 0;
			$widget_theme_checkiddefault = 0;
			
			$widgetMaster = array(
								'modified_by'=>$userId,
								'widget_name'=>$post['campaign_name'],
								'promo_code'=>$post['promo_code'],
								'modified_on'=>$created_on
						);
			$result=$this->db->where('id', $_POST['campaign_id'])->update('campaign_widget_master', $widgetMaster);
			//echo "<pre>";print_r($getProductList);die;
			if($product_retailer_id){
				foreach($getProductList as $val)
				{
					
					if($post['retailer_id']==$val['retailer_id']){
						$widgetPrd = array(
										'product_retailer_id' => $product_retailer_id,
										'campaign_default' => '1',
										'modified_on'=>$created_on
								);
						
					}else{
						$widgetPrd = array(
										'product_retailer_id' => '',
										'campaign_default' => '0',
										'modified_on'=>$created_on
								);
					}
					$this->db->where('retailer_id', $val['retailer_id']);
					$this->db->where('widget_id', $_POST['campaign_id']);
					$this->db->where('product_id', $_POST['productid']);
					$result =$this->db->update('campaign_widget_products', $widgetPrd);
					
				}
			}
			if($result){
				//echo $_POST['campaign_id'];die;
				return $final_result=$this->gethashCampaign($_POST['campaign_id']);
			}
			
			
		}
	}
	
	
	function check_campagn_url($data=array()){
		//echo "<pre>";print_r($data);die;
				if($data['retailer_id'] && $data['campaign_url']){
						if($data['brand']){
							$query		= "SELECT prtp.id FROM product_retailer_to_products as prtp
													WHERE prtp.brand='".$data['brand']."' and prtp.retailer_id='".$data['retailer_id']."' and prtp.buy_now_url='".$data['campaign_url']."'";
							//die;
							$query   	= $this->db->query($query);
							return $result=$query->result_array();
						//echo "<pre>";print_r($result);die;
						}else{
							//echo "<pre>";print_r($result);die;
							$query		= "SELECT prtp.id FROM product_retailer_to_products as prtp
													WHERE prtp.retailer_id='".$data['retailer_id']."' and prtp.buy_now_url='".$data['campaign_url']."'";
							$query   	= $this->db->query($query);
							return $result=$query->result_array();
						}
				}
	}
	function getProductRetailer($pid=''){
				if($pid){
						#$this->db->query("SELECT id,domain_name,language_id FROM domain_master WHERE status='1' ORDER BY domain_name")->result();
						$query		= "SELECT btrm.id,rm.id as retailer_id,rm.retailer_name,x.title,dm.domain_name,dm.id as domain_id,x.id as product_id FROM  brand_to_retailer_master as btrm
						INNER JOIN retailer_master rm ON rm.id=btrm.retailer_master_id
						INNER JOIN domain_master dm ON dm.id = btrm.domain_id
						INNER JOIN products x ON x.product_website_id = dm.id
						WHERE x.id='".$pid."' and rm.status = '1'
									";
						$query   	= $this->db->query($query);
						return $query->result_array();
				}
	
	}
	
	function getPromocode($product_id=null)
	{
			
			$query_str  = 'where 1';
			if($product_id)
			$query_str  .= ' AND p.id='.$product_id;
			
			$sql = "select p.id,dm.brand_code from products as p
			INNER JOIN domain_master as dm ON dm.id=p.product_website_id
			".$query_str;
			$query 	= $this->db->query($sql);
			$resultA1 =$query->result_array();
			if($resultA1[0]['id']){
				
				foreach($resultA1 as $valA1)
				{
//					if($valA1['promo_code']==''){
						$promo_code=substr($valA1['brand_code'],0,2).substr($valA1['id'],-3).date("Mdys");
//						$sql = "UPDATE campaign_widget_master SET widget_type = '1',promo_code 	= '".strtoupper($promo_code)."',product_id  ='".$valA1['product_id']."'	WHERE id  = '".$valA1['id']."'";
//						$query 	= $this->db->query($sql);
					//}
				}		
			}
		return strtoupper($promo_code);
		echo "Promo Code generated successfully.";
	}
	
	public function gethashCampaign($cid='')
    {
		$query_str  = 'where 1';
		if($cid)
		$query_str  .= ' AND id='.$cid;
		
		$strQuery = "SELECT id,
					id, widget_name, promo_code,hash_key,status
					FROM campaign_widget_master
					".$query_str;
			   $getallcampaign = $this->db->query($strQuery);       
        return $getallcampaign->result();    
    }
	public function getSingleBrand($id='')
    {
		$query_str  = 'where 1';
		if($id)
		$query_str  .= ' AND id='.$id;
		
		$strQuery = "SELECT id,
					id, domain_name,language_id, status
					FROM domain_master
					".$query_str;
			   $getallbrand = $this->db->query($strQuery);       
        return $getallbrand->result();    
    }
	public function getAllBrands($params='')
    {
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$query = '	SELECT dm.id,dm.domain_name,dm.language_id, dm.status
						FROM campaign_data_permission AS cdp
						INNER JOIN domain_master AS dm ON dm.id = cdp.id_domain
						WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1"
						GROUP BY cdp.id_domain
						ORDER BY domain_name ASC ';
			$result = $this->db->query($query)->result();
			
			if(count($result)>0){
				return $result;	
				}else{
					
				$strQuery = "SELECT id,
							domain_name,language_id, status
							FROM domain_master
							WHERE '1' AND status='1' 
							ORDER BY domain_name";
							if($params)
							{
								$offset = mysql_real_escape_string(intval($params['offset']));
								$strQuery.= " LIMIT $offset," . mysql_real_escape_string($params['num']);
								
							}
				$getallbrand = $this->db->query($strQuery);       
				return $getallbrand->result();
				}
		}else{
			
		$strQuery = "SELECT id,
					domain_name,language_id, status
					FROM domain_master
					WHERE '1' AND status='1' 
					ORDER BY domain_name";
					if($params)
					{
						$offset = mysql_real_escape_string(intval($params['offset']));
						$strQuery.= " LIMIT $offset," . mysql_real_escape_string($params['num']);
						
					}
        $getallbrand = $this->db->query($strQuery);       
        return $getallbrand->result();
		}
    }
	public function getall_campaign()
	{
		$userId=$this->session->userdata('user_id') ;
		$userRole=$this->session->userdata('role_id') ;

		$query_str  = 'where 1';
		if($userRole=='1')
		$query_str  .= ' AND cm.user_id='.$userId;
		
		$query_str  .= ' AND widget_type=1 order by cm.created_on desc';
		$records    =  $this->db->query("select p.title,p.image_url,prtp.brand,u.name,p.id as product_id,p.product_website_id,prtp.title as product_title,promo_code,cm.created_on,cm.widget_name,cm.hash_key,prtp.buy_now_url,cm.id as widget_id from campaign_widget_master cm
								   LEFT JOIN products p ON cm.product_id = p.id
								   LEFT JOIN campaign_users u ON u.id = cm.user_id
								   LEFT JOIN campaign_widget_products wp ON cm.id = wp.widget_id
								   AND wp.campaign_default=1 
								   LEFT JOIN product_retailer_to_products prtp ON wp.product_retailer_id = prtp.id
								   ".$query_str);
		return $records;	
	}
	public function get_campaign($cid=null)
	{
		$query_str  = 'where 1';
		if($cid)
		$query_str  .= ' AND cm.id='.$cid;
		
		$records    =  $this->db->query("select p.title,p.image_url,p.prd_sku,p.category,p.sub_category,p.product_website_id,p.id,prtp.title as product_title,promo_code,cm.created_on,cm.widget_name,prtp.buy_now_url,prtp.retailer_id as retailrid,cm.id as widget_id from campaign_widget_master cm	LEFT JOIN products p ON cm.product_id = p.id LEFT JOIN campaign_widget_products wp ON cm.id = wp.widget_id AND wp.campaign_default=1 LEFT JOIN product_retailer_to_products prtp ON wp.product_retailer_id = prtp.id ".$query_str);                                 
		return $records;	
	}
	
	public function getall_products($domain_id=null)
	{
		$query_str  = 'where 1';
		if($domain_id)
		$query_str  .= ' AND dm.id='.$domain_id;
		
		$records    =  $this->db->query("SELECT p.*, domain_name, domain_url
				FROM  products p
				INNER JOIN domain_master dm ON dm.id=p.product_website_id
				".$query_str);
		return $records;	
	}
	/*
     * @Date: May 10, 2016
     * @Method :record_count
     * Created By: Nisha Mishra
     * @Purpose: This function is used to count all record of table "access_module_master" in database cartwire_co
     * @Param: $post
     * @Return:  $records
     */
    public function record_count($post) 
    {
        $module_name         = array_key_exists('title', $post) ? $post['title'] : '';
        $campaign_code        = array_key_exists('promo_code', $post) ? $post['promo_code'] : '';

        $status             = array_key_exists('status', $post) ? $post['status'] : '';
        $query_str  = 'where 1';
		$query_str  .= ' AND widget_type=1';
        if($module_name != '')
        {
            $query_str.= " AND p.title Like '%".mysql_real_escape_string(trim($module_name))."%'";
        }
		
		if($campaign_code != '')
        {
            $query_str.= " AND promo_code Like '%".mysql_real_escape_string(trim($campaign_code))."%'";
		}

        if($status!='')
        {
            $query_str .= " AND wm.status ='".mysql_real_escape_string($status)."'";
        } 
        $records    =  $this->db->query("select p.title,prtp.title as product_title,promo_code,wp.created_on,prtp.buy_now_url from campaign_widget_master wm
								   LEFT JOIN products p ON wm.product_id = p.id
								   LEFT JOIN campaign_campaign_widget_products wp ON wm.id = wp.widget_id
								   AND wp.campaign_default=1 
								   LEFT JOIN product_retailer_to_products prtp ON wp.product_retailer_id = prtp.id
								   ".$query_str);
        
        return $records->num_rows();  
       

    }
	
	/*
     * @Date: Jan 30, 2016
     * @Method :view_campaignmaster
     * Created By: STPL
     * @Purpose: This function is used to display modules
     * @Param: $limit, $id
     * @Return: $query
     */
    public function view_campaignmaster($limit, $offset, $post)
    {
        
        $module_name         = array_key_exists('title', $post) ? $post['title'] : '';
        $campaign_code        = array_key_exists('promo_code', $post) ? $post['promo_code'] : '';
        $status              = array_key_exists('status', $post) ? $post['status'] : '';
        $order_by            = array_key_exists('order_by', $post) ? $post['order_by'] : '';
        $sortBy              = array_key_exists('sortBy', $post) ? $post['sortBy'] : '';
        $query_str           = 'where 1';
		$query_str  .= ' AND widget_type=1';
        
        if($module_name != '')
        {
            $query_str .= " AND p.title Like '%".mysql_real_escape_string(trim($module_name))."%'";
        }
		if($campaign_code != '')
        {
            $query_str.= " AND promo_code Like '%".mysql_real_escape_string(trim($campaign_code))."%'";
		}

 
        if($status!='')
        {
            $query_str .= " AND wm.status ='".mysql_real_escape_string($status)."'";
        } 

        if($order_by!='' && $sortBy!='')
        {
            $query_str .= " ORDER BY  ".$order_by." ".$sortBy."" ;
        }
        else
        {
             $query_str .= " ORDER BY wm.id DESC ";
        }
        $query  = $this->db->query("select p.title,prtp.title as product_title,promo_code,wp.created_on,prtp.buy_now_url from campaign_widget_master wm
								   LEFT JOIN products p ON wm.product_id = p.id
								   LEFT JOIN campaign_campaign_widget_products wp ON wm.id = wp.widget_id
								   AND wp.campaign_default=1 
								   LEFT JOIN product_retailer_to_products prtp ON wp.product_retailer_id = prtp.id
								   ".$query_str." limit " .$offset.",".$limit);
        
        return $query;
	}
	
	
}