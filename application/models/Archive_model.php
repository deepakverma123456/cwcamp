<?php
class Archive_model extends CI_Model 
{
	public $data_permisison;
	public $brand_permission;
	public	$globCondition =  " 1 AND wm.widget_type ='1' AND  wd.ip != '180.233.120.244' AND wd.ip !='180.233.120.245' AND wd.ip !='180.233.120.246' AND wd.ip !='180.233.121.210' AND wd.ip !='180.233.121.211'  AND  wd.report_status!='cw_test' ";
	
	
	
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
	/**
	* Function getTopFiveProductWiseReport
	*
	* Top 5 Product wise excel report
	*
	* @Created Date: 16 Mar, 2017
	* @Modified Date: 16 Mar, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return OBJECT ARRAY
	*/
	function getTopFiveProductWiseReport($daterange='') 
	{		
		$where = $this->globCondition;		
				
		//----------permission code-----------
		$where.= $this->data_permisison;
		//------------------------------------		
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}
		if($daterange)
		{
			$date = explode('-',$daterange);
			$start_date = _dateformat($date[0]);
			$end_date = _dateformat($date[1]);
			$where.= '  AND DATE_FORMAT(wd.created_by,"%Y-%m-%d") BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
		}
		$ql = "
			SELECT * FROM(
			
					(SELECT  DATE_FORMAT(MIN(wd.created_by),'%d %b %y') AS start_date , DATE_FORMAT(MAX(wd.created_by),'%d %b %y') AS end_date, p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick,dm.id AS id_domain, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
					when dm.status='1' then dm.domain_name end as domain_name
					FROM `campaign_widget_master` AS wm
					INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
					INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
					INNER JOIN products AS p ON p.id = wm.product_id
					INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
					where " . $where . "
					GROUP BY wd.widget_id
					ORDER BY TotalClick DESC
					LIMIT 5)
					
					UNION
					
					(SELECT  DATE_FORMAT(MIN(wd.created_by),'%d %b %y') AS start_date , DATE_FORMAT(MAX(wd.created_by),'%d %b %y') AS end_date, p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick,dm.id AS id_domain, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
					when dm.status='1' then dm.domain_name end as domain_name
					FROM `campaign_widget_master` AS wm
					INNER JOIN campaign_source_view_archive ws ON ws.widget_id = wm.id
					INNER JOIN campaign_details_archive wd ON wd.widget_source_view_id = ws.id	
					INNER JOIN products AS p ON p.id = wm.product_id
					INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
					where " . $where . "
					GROUP BY wd.widget_id
					ORDER BY TotalClick DESC
					LIMIT 5 )
					
					) AS kk
					GROUP BY widget_id
					ORDER BY TotalClick DESC
					LIMIT 5";
				
				
		$query = $this->db->query($ql);        
		return $query->result();	
    }
	/**
	* Function brand_campaign_report
	*
	* Brand campaign report and top five brand campaign report
	*
	* @Created Date: 17 Mar, 2017
	* @Modified Date: 17 Mar, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  STRING
	* @return OBJECT ARRAY
	*/
	function brand_campaign_report($brandIds,$daterange='',$topfive='') 
	{		
		$where = $this->globCondition;		
		if(!empty($brandIds))
		{
			$where .= " AND p.product_website_id IN(".$brandIds.") ";
		}		
		//----------permission code-----------
		$where.= $this->data_permisison;
//------------------------------------	
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}
		
		if($daterange)
		{
			$date = explode('-',$daterange);
			$start_date = _dateformat($date[0]);
			$end_date = _dateformat($date[1]);
			$where.= '  AND DATE_FORMAT(wd.created_by,"%Y-%m-%d") BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
		}
		
		if($topfive)
		{
			$orderby = ' ORDER BY TotalClick DESC
						LIMIT 5 ';
		}
		else{
			
				$orderby = ' ORDER BY wm.id DESC ';		
		}
		if($topfive)
			$sql1 = "SELECT * FROM( ";
		
		$ql = "(SELECT p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS Number_of_clicks_desktop, SUM( IF( wd.device_version =1, 1, 0 ) ) AS Number_of_clicks_mobile, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
			INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			where " . $where . "
			GROUP BY wm.promo_code
			".$orderby."
			)
			UNION
			(SELECT p.title,wm.promo_code,wd.widget_id, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick, SUM( IF( wd.device_version =0, 1, 0 ) ) AS Number_of_clicks_desktop, SUM( IF( wd.device_version =1, 1, 0 ) ) AS Number_of_clicks_mobile, wd.target_url,wm.modified_on,created_by,case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name
			FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_source_view_archive ws ON ws.widget_id = wm.id
			INNER JOIN campaign_details_archive wd ON wd.widget_source_view_id = ws.id	
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			where " . $where . "
			GROUP BY wm.promo_code
			".$orderby." )
			";
		if($topfive)
			$sql2 = " ) AS kk
					GROUP BY promo_code
					ORDER BY TotalClick DESC
					LIMIT 5";
		$query = $this->db->query($sql1.$ql.$sql2);        
		return $query->result();	
    }
	
	//==== Function to find Most Active IP Hits Report
	function getMostipHitsReport($daterange='') 
	{		
		$where = $this->globCondition;		
		if($daterange)
		{
			$date = explode('-',$daterange);
			$start_date = _dateformat($date[0]);
			$end_date = _dateformat($date[1]);
			$where.= '  AND DATE_FORMAT(wd.created_by,"%Y-%m-%d") BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
		}
		else
		{
			$days = "";			
			$quarterdate = get_quarter($days);		
			$start_date	 = $quarterdate['start'];
			$end_date 	 = $quarterdate['end'];
			$where.= '  AND DATE_FORMAT(wd.created_by,"%Y-%m-%d") BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
		}
		$ql = "
			SELECT * FROM(			
					(SELECT DATE_FORMAT(MAX(wd.created_by),'%d %b %y') AS LastclickTime, p.title,wm.promo_code,wd.widget_id, wd.ip, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick,dm.id AS id_domain, wd.target_url,wm.modified_on,created_by,case wm.widget_type when '1' then 'Camapaign' when '2' then 'Widget' end as type
					FROM `campaign_widget_master` AS wm
					INNER JOIN campaign_widget_source_view ws ON ws.widget_id = wm.id
					INNER JOIN campaign_widget_details wd ON wd.widget_source_view_id = ws.id	
					INNER JOIN products AS p ON p.id = wm.product_id
					INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
					where  " . $where . " 
					GROUP BY wd.ip having TotalClick>5
					ORDER BY TotalClick DESC
					LIMIT 50)
					
					UNION
					
					(SELECT DATE_FORMAT(MAX(wd.created_by),'%d %b %y') AS LastclickTime, p.title,wm.promo_code,wd.widget_id,wd.ip, wm.hash_key, wm.widget_name, count( wd.click ) AS TotalClick,dm.id AS id_domain, wd.target_url,wm.modified_on,created_by,case wm.widget_type when '1' then 'Camapaign' when '2' then 'Widget' end as type
					FROM `campaign_widget_master` AS wm
					INNER JOIN campaign_source_view_archive ws ON ws.widget_id = wm.id
					INNER JOIN campaign_details_archive wd ON wd.widget_source_view_id = ws.id	
					INNER JOIN products AS p ON p.id = wm.product_id
					INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
					where " . $where . "
					GROUP BY wd.ip having TotalClick>5
					ORDER BY TotalClick DESC
					LIMIT 50 )
					
					) AS kk
					GROUP BY ip
					ORDER BY TotalClick DESC
					LIMIT 50";				
				
		$query = $this->db->query($ql);        
		return $query->result();	
    }
}