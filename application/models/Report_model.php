<?php
class Report_model extends CI_Model 
{
	public $data_permisison;
	public $brand_permission;
	public	$globCondition =  " 1 AND wm.widget_type ='1' AND  wd.ip != '180.233.120.244' AND wd.ip !='180.233.120.245' AND wd.ip !='180.233.120.246' AND wd.ip !='180.233.121.210' AND wd.ip !='180.233.121.211'  AND  wd.report_status!='cw_test' ";
	#public $globCondition =  " 1 AND wm.widget_type ='1'  ";
	
	
	
    function __construct()
    {
        // Call the Model constructor
		parent::__construct();
		$this->load->library('session');
		$this->load->helper('custom_helper');
		$this->load->model('user_model');
		$role_id = $this->session->userdata('role_id');
		if($role_id == '1')
		{
			$this->user_brand_data_permission();
		}
		
    }
	
	//==== Function for get all campaign list
	public function getall_campaign()
	{		
		$where = $this->globCondition;
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------		
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}
		
		$query  =  $this->db->query("SELECT wm.id, wd.widget_id,wm.promo_code,wm.widget_name,max(wd.time_loaded) as LastclickTime,sum( wd.click ) AS TotalClick, case when dm.status='0' then concat(dm.domain_name,'','(i)')
		when dm.status='1' then dm.domain_name end as domain_name
					FROM `campaign_widget_master` AS wm
					INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
					INNER JOIN products AS p ON p.id = wm.product_id
					INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
					WHERE " . $where . " group by wd.widget_id order by LastclickTime desc
			");
		
		return $query->result();		
	}
	
	//==== Function for get all campaign report
	function getallcampaign($widgetId = NULL) 
	{	   	
		if(!empty($this->session->userdata('user_id')))
		{			
		    $user_Id = $userId;
		}
		$where = $this->globCondition;
		if ($widgetId != '')
		{
            $where.= " AND wm.id = '".$widgetId."'";
        }  				
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------
		
		$ql = "SELECT wd.id,p.title,p.buy_now_url,wd.created_by,wd.widget_id, wm.hash_key,wm.promo_code, wm.widget_name, count( wd.click ) AS TotalClick, count(*) AS alltotalclick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS DektopClick, SUM( IF( wd.device_version =1, 1, 0 ) ) AS MobileClick, wd.target_url
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id			
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id WHERE 
			" . $where . "
			GROUP BY CAST(wd.created_by AS DATE), wd.target_url
			";
		$query = $this->db->query($ql);        
		return $query->result();	
    }
	
	//==== Function for get user brand
	public function get_user_brands($search='')
	{
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id'); 
		if($id_user && $role_id=='1') //check permission for users only
		{			
			$query = '	SELECT cdp.id_domain,case when dm.status="0" then concat(dm.domain_name,"","(i)")
						when dm.status="1" then dm.domain_name end as domain_name,dm.language_id, dm.status,dm.id
						FROM campaign_data_permission AS cdp
						INNER JOIN domain_master AS dm ON dm.id = cdp.id_domain
						WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1"
						GROUP BY cdp.id_domain
						ORDER BY domain_name ASC ';
			$result = $this->db->query($query)->result();			
			if(count($result)>0)
				return $result;	
		}
		$data = $this->get_brands($search);
		return $data;
	}
	
	//==== Function for get brand
	public function get_brands($search='')
	{
		$query = $this->db->select('id,case when status="0" then concat(domain_name,"","(i)")
		when status="1" then domain_name end as domain_name,language_id, status')
		->like('domain_name', $search, 'after')
		->order_by('domain_name', 'ASC')
		->get_where('domain_master')->result();		
		return $query;
	}
	
	//==== Function for get all brand campaign report
	function getallbrandcampaign($brandIds) 
	{		
		$where = $this->globCondition;		
		if(!empty($brandIds))
		{
			$where .= " AND p.product_website_id IN(".$brandIds.") ";
		}
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------			
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}	
		$ql = "SELECT p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS Number_of_clicks_desktop, SUM( IF( wd.device_version =1, 1, 0 ) ) AS Number_of_clicks_mobile, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			where " . $where . " GROUP BY dm.domain_name ORDER BY dm.domain_name ASC";
		$query = $this->db->query($ql);        
		return $query->result();	
    }
	
	//==== Function for get all brand campaign report
	function getbrandWiseExport($brandIds) 
	{		
		$where = $this->globCondition;		
		if(!empty($brandIds))
		{
			$where .= " AND p.product_website_id IN(".$brandIds.") ";
		}		
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------	
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}	
		$ql = "SELECT p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS Number_of_clicks_desktop, SUM( IF( wd.device_version =1, 1, 0 ) ) AS Number_of_clicks_mobile, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			where " . $where . " GROUP BY wd.widget_id ORDER BY dm.domain_name ASC"; 
		$query = $this->db->query($ql);        
		return $query->result();	
    }
	//==== Function for get all brand campaign report
	function TotalBrandWiseReport($brandIds) 
	{		
		$where = $this->globCondition;		
		if(!empty($brandIds))
		{
			$where .= " AND p.product_website_id IN(".$brandIds.") ";
		}
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------	
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}	
		$ql = "SELECT DISTINCT dm.id AS total_brands, COUNT(DISTINCT wd.widget_id) AS total_campaigns, wd.widget_id, count( wd.click ) AS total_click, SUM( IF( wd.device_version =0, 1, 0 ) ) AS total_desktop_click, SUM( IF( wd.device_version =1, 1, 0 ) ) AS total_mobile_click, case when dm.status='0' then concat(dm.domain_name,'','(i)') when dm.status='1' then dm.domain_name end as domain_name FROM `campaign_widget_master` AS wm
				INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
				INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
				INNER JOIN products AS p ON p.id = wm.product_id
				INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
				where " . $where . "
				GROUP BY dm.id
				ORDER BY dm.domain_name ASC";
		$query = $this->db->query($ql);        
		return $query->result();	
    }
	
    //==== Function for get all campaign list
	public function getall_campaign_list($widgetId = NULL)
	{		
		$where = $this->globCondition;
		if ($widgetId != '')
		{
			$where.= " AND wd.widget_id = ".$widgetId." ";
		}		
		$query  =  $this->db->query("SELECT *, case device_version when '1' then 'Mobile'
										when '0' then 'Desktop' end as device_version, wm.id, wd.widget_id,wd.ip,wd.time_loaded,wm.promo_code,wm.widget_name FROM `campaign_widget_master` AS wm INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id WHERE " . $where . " order by time_loaded desc limit 0,100");	
		return $query->result();		
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
	
	function getProductWiseReport($widgetIds) 
	{		
		$where = $this->globCondition;		
		if(!empty($widgetIds))
		{
			$where .= " AND wm.id IN(".$widgetIds.") ";
		}		
		//----------permission code-----------
		$where.= $this->data_permisison;
		//------------------------------------
		$ql = "SELECT  DATE_FORMAT(MIN(wd.created_by),'%d %b %y') AS start_date , DATE_FORMAT(MAX(wd.created_by),'%d %b %y') AS end_date, p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick,dm.id AS id_domain, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			where " . $where . "
			
			GROUP BY wd.widget_id ORDER BY dm.domain_name ASC"; 
		$query = $this->db->query($ql);        
		return $query->result();	
    }

	/*
	* @Created Date: ?
	* @Modified Date: Mar 27, 2017
	* @Method:  getCampaignUrlWise()
	* @Created By: ?
	* @Modified By: Nisha Mishra
	* @Purpose: This function is used for getting campaign report data url-wise. 
	* @Param: widgetId
	* @Return: array
	*/
	function getCampaignUrlWise($widgetId = NULL) 
	{	   	
		if(!empty($this->session->userdata('user_id')))
		{			
		    $user_Id = $userId;
		}
		$where = $this->globCondition;
		if ($widgetId != '')
		{
            $where.= " AND wm.id = '".$widgetId."'";
        }  				
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------
		
		$ql = "SELECT wd.id,p.title,p.buy_now_url,wd.widget_id, wm.hash_key,wm.promo_code, wm.widget_name,max(CAST(wd.created_by AS DATE)) as toDate, min(CAST(wd.created_by AS DATE)) as fromDate, count( wd.click ) AS TotalClick, count(*) AS alltotalclick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS DektopClick, SUM( IF( wd.device_version =1, 1, 0 ) ) AS MobileClick, wd.target_url
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id			
			INNER JOIN products AS p ON p.id = wd.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id WHERE 
			" . $where . "
			GROUP BY wd.target_url
			";
		$query = $this->db->query($ql);        
		return $query->result();	
    }
}
