<?php
class User_model extends CI_Model 
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
		$role_id = $this->session->userdata('role_id');
		if($role_id == '1')
		{
			$this->user_brand_data_permission();
		}
    }
	
	//==== Function for get all users
	public function getallusers()
	{										
		$query = $this->db->query("SELECT *, case role_id when '1' then 'User' when '2' then 'Admin' end as role,
		case status when '1' then 'Active' when '2' then 'Blocked' when '3' then 'Deleted' end as status, (SELECT cu.name from campaign_users cu where id= campaign_users.created_by) as CreatedBy FROM campaign_users");								
		return $query->result();		
	}
	
	//==== Function for insert users
	//modified by : Nisha Mishra
	public function insertuser()
	{
		if(!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']))
		{
			$filds = array(
			'role_id' => $_POST['role_id'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],
			'password' => encrypt($_POST['password']),
			'created_by' => $this->session->userdata('user_id'),
			'created_date' => date("Y-m-d H:i:s"),
			'updated_date' => date("Y-m-d H:i:s")
			);	

			if($_POST['sendmail'] == 'on')
			{
            	$data = array('name' => $_POST['name'], 'email' => $_POST['email'], 'password' => encrypt($_POST['password']), 'created_by' => $this->session->userdata('user_id'), 'created_date' => date("Y-m-d H:i:s"));
            	$succ = send_registration_confirmation($data);

        	}
            return $record = $this->db->insert('campaign_users', $filds);    		
			
		}
	}
	
	//==== Function for check email exist
	public function checkuseremail()
	{
		$user_id = decrypt($_REQUEST['id']);
		$id = (isset($user_id)) ? " AND id!='".$user_id."'" : "" ;
		if(empty($_POST['email'])){ echo "false"; exit; }
		$userEmail = $this->db->query("SELECT * FROM campaign_users WHERE email='".trim($_REQUEST['email'])."' ".$id );											
		echo  ($userEmail->num_rows()>0) ? "false":  'true';
	    exit;	
	}
	/**
	* Function get_menus
	*
	* fetch menu data on the basis of user role
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return array
	*/
	public function get_menus()
	{
		$menu = array();
		//check if user is logged in
		$role_id = $this->session->userdata('role_id');
		if($role_id=='1')
		{
			$id_user = $this->session->userdata('user_id');
			//check if user has module permission
			$menu_data = $this->permission_menu_data($id_user);
			//a($menu_data);
			if(!$menu_data)
				$menu_data = $this->menu_data($role_id);
			else
				$per = 1;
		}
		else
			$menu_data = $this->menu_data($role_id);
		
		$i=0;
		foreach((array)$menu_data as $val)
		{
			$menu[$i]['menu'] = $val;
			if($role_id=='1' && $per==1)
			{
				$submenu = $this->permission_submenu_data($val->id_menu,$id_user);
			}
			else
				$submenu = $this->submenu_data($val->id_menu,$role_id);
			if(count($submenu)>0)
			{
				foreach((array)$submenu as $res)
				{
					$menu[$i]['submenu'][] = $res;
				}
			}
			$i++;
		}
		return $menu;
	}
	/**
	* Function menu_data
	*
	* fetch main menu on the basis of user role
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	Public function menu_data($role_id)
	{
		$query = 'SELECT *
				FROM campaign_menu
				WHERE id_parent="0" AND status="1"
				AND FIND_IN_SET("'.$role_id.'",role_id)
				ORDER BY menu_order ASC,menu_label ASC';
				
		$res =	$this->db->query($query)->result();		
		return $res;	
	}
	/**
	* Function permission_menu_data
	*
	* fetch permissed main menu on the basis of user role
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	Public function permission_menu_data($id_user)
	{
		$query = 'SELECT *
				FROM campaign_module_permission AS cmp
				INNER JOIN campaign_menu AS cm ON cm.id_menu = cmp.id_menu
				WHERE cm.id_parent="0" AND cm.status="1" AND cmp.id_user="'.$id_user.'"
				ORDER BY menu_order ASC,menu_label ASC'; 
		$res = $this->db->query($query)->result();
		return $res;	
	}
	/**
	* Function submenu_data
	*
	* fetch sub menu on the basis of parent ID
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	Public function submenu_data($id_parent)
	{
		$query = $this->db->order_by('menu_order', 'ASC')
						->order_by('menu_label', 'ASC')
						->get_where('campaign_menu', array('id_parent' => $id_parent,'status'=>'1','inner_pages!='=>'1'))->result();
		return $query;	
	}
	/**
	* Function permission_submenu_data
	*
	* fetch  permissed sub menu on the basis of parent ID and user ID
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT,INT
	* @return ARRAY
	*/
	Public function permission_submenu_data($id_parent,$id_user)
	{
		$query = 'SELECT *
				FROM campaign_module_permission AS cmp
				INNER JOIN campaign_menu AS cm ON cm.id_menu = cmp.id_menu
				WHERE cm.id_parent="'.$id_parent.'" AND cm.status="1" AND cm.inner_pages!="1" AND cmp.id_user="'.$id_user.'"
				ORDER BY menu_order ASC,menu_label ASC';
		$res = $this->db->query($query)->result();
		return $res;	
	}
	
	//==== Function for get edit user	
	public function getedituser($fields = array())
	{
		// check if id is given 
		$user_id	= array_key_exists('id', $fields) ? $fields['id'] : '';
		$query = $this->db->query("SELECT *, case status when '1' then 'Active'
										when '2' then 'Blocked' end as status FROM campaign_users where id=".$user_id);
		return $query->result();
	}
	
	//==== Function for update users
	//modified by : Nisha Mishra
	public function updateuser($user_id)
	{
		if(!empty($user_id))
		{
			$fields = array(
			'role_id' => $_POST['role_id'],
			'name' => $_POST['name'],
			'email' => $_POST['email'],
			'password' => encrypt($_POST['password']),
			'created_by' => $this->session->userdata('user_id'),
			'updated_date' => date("Y-m-d H:i:s")
			);	
			if($_POST['sendmail'] == 'on')
			{
            	$data = array('name' => $_POST['name'], 'email' => $_POST['email'], 'password' => encrypt($_POST['password']), 'created_by' => $this->session->userdata('user_id'), 'updated_date' => date("Y-m-d H:i:s"));
            	
            	$succ = user_records_updation($data);

        	}		
			return $record = $this->db->where('id', $user_id)->update('campaign_users', $fields);			
		}
	}
	
	//==== Function for update user
	public function updateuserstatus($user_id, $curstatus)
	{		
		$newStatus = ($curstatus=="Active") ? "2" : "1";
		$fields = array(
		'status' => $newStatus			
		);			
		$record = $this->db->where('id', $user_id)->update('campaign_users', $fields);
		return ($newStatus == "2")?"Blocked":"Active";
	}
	
	//==== Function for update user status
	public function deleteuser($user_id)
	{
		if(!empty($user_id))
		{
			$fields = array(
			'status' => 3			
			);			
			return $res = $this->db->where('id', $user_id)->update('campaign_users', $fields);			
		}
	}
	/**
	* Function all_submenu_data
	*
	* fetch all sub menu on the basis of parent ID for assigning permission
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	public function all_submenu_data($id_parent)
	{
		$query = $this->db->order_by('menu_order', 'ASC')
					->order_by('menu_label', 'ASC')
					->get_where('campaign_menu', array('id_parent' => $id_parent,'status'=>'1'))->result();
		return $query;	
	}
	/**
	* Function get_brands
	*
	* fetch all brands 
	*
	* @Created Date: 13 Feb, 2017
	* @Modified Date: 13 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_brands()
	{
		$query = $this->db->select('id,case when status="0" then concat(domain_name,"","(i)")
					when status="1" then domain_name end as domain_name,language_id, status')
					->order_by('domain_name', 'ASC')
					->get_where('domain_master', array('language_id' => '33'))->result();
		return $query;	
	}
	/**
	* Function get_brands
	*
	* fetch all brands 
	*
	* @Created Date: 13 Feb, 2017
	* @Modified Date: 13 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_brands_active()
	{
		$query = $this->db->select('id,domain_name,language_id, status')
					->order_by('domain_name', 'ASC')
					->get_where('domain_master', array('language_id' => '33','status'=>'1'))->result();
		return $query;	
	}
	/**
	* Function check_data_permission
	*
	* check if user has permissions for data
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function check_brand_permission()
	{
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$query = $this->db->select('id')
					->get_where('campaign_data_permission', array('id_user' => $id_user))->result();
			if(count($query)>0)
				return true;	
		}
		return false;
	}
	/**
	* Function get_user_brands
	*
	* get permissed brands for user
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_user_brands()
	{
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$query = '	SELECT cdp.id_domain,case when dm.status="0" then concat(dm.domain_name,"","(i)")
						when dm.status="1" then dm.domain_name end as domain_name,dm.language_id, dm.status
						FROM campaign_data_permission AS cdp
						INNER JOIN domain_master AS dm ON dm.id = cdp.id_domain
						WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1"
						GROUP BY cdp.id_domain
						ORDER BY domain_name ASC ';
			$result = $this->db->query($query)->result();
			
			if(count($result)>0)
				return $result;	
		}
		$data = $this->get_brands();
		return $data;
	}
	/**
	* Function get_user_data
	*
	* get permissed data for user
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return STRING
	*/
	public function get_user_data()
	{
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$query = '	SELECT GROUP_CONCAT(cdp.id_data) AS id_data
						FROM campaign_data_permission AS cdp
						WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1" AND cdp.id_data !="0"
						ORDER BY cdp.id ASC '; 
			$result = $this->db->query($query)->result();
			
			if(count($result)>0)
			{
				foreach((array)$result as $val)
				{
					$data = $val->id_data;
				}
				return $data;	
			}
		}
	}
	
	function get_all_user_data($user_id=null)
	{
		//echo "select * from user as u INNER JOIN campaign_users cu ON cu.user_id = u.id where u.id='".$user_id."'";die;
		$query = $this->db->query("select * from campaign_users as cu INNER JOIN profile p ON p.user_id = cu.id where cu.id='".$user_id."'");
		return  $query->result();
	}
	/**
	* Function get_user_brands_ids
	*
	* get permissed brands ids for user
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_user_brands_ids()
	{
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$query = '	SELECT GROUP_CONCAT(cdp.id_domain) AS id_domain
						FROM campaign_data_permission AS cdp
						WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1" AND cdp.id_data= "0"
						 '; 
			$result = $this->db->query($query)->result();
			
			if(count($result)>0)
			{
				foreach((array)$result as $val)
				{
					$data = $val->id_domain;
				}
				return $data;	
			}
		}
	}
	//==== Function for get all brand product	
	public function getallbrandproduct($fields = array())
	{		
		$where = $this->brand_permisison;
		$query = $this->db->query("SELECT * from products AS p
								  INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
								  where 1 " . $where . " AND product_website_id!=''");		
		return $query->result();
	}

	//==== Function for get all campaign list
	public function gettotalhits()
	{
		$days      = 15;
		$prev_date = date('Y-m-d H:i:s', strtotime($date .' -'.$days.' day'));
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
			/*$query  =  $this->db->query("SELECT SUM(TotalClick) as finaclick FROM (SELECT 	 wm.id,sum( wd.click ) AS TotalClick FROM `campaign_widget_master` AS wm
				INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
				INNER JOIN products AS p ON p.id = wd.product_id
				INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
				WHERE " . $where . " AND exists(SELECT count(*) FROM `campaign_widget_master` AS wm
				INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
				INNER JOIN products AS p ON p.id = wd.product_id
				INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
				WHERE " . $where . " AND wd.created_by<='".$prev_date."') group by wd.widget_id) as TotalClick");		
		return $query->result();
		}else{	*/
		}
		$query  =  $this->db->query("SELECT SUM(TotalClick) as finaclick FROM (SELECT 	 wm.id,sum( wd.click ) AS TotalClick FROM `campaign_widget_master` AS wm
				INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
				INNER JOIN products AS p ON p.id = wd.product_id
				INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
				WHERE " . $where . " group by wd.widget_id) as TotalClick");		
		return $query->result();
		
	}
	
	//==== Function for get top five campaign list
	public function gettopfive_campaign()
	{
		$days      = 15;
		$prev_date = date('Y-m-d H:i:s', strtotime($date .' -'.$days.' day'));
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
			$query  =  $this->db->query("SELECT wm.id, wd.widget_id,wd.product_id, wm.promo_code,wm.widget_name,wd.report_status, max(wd.created_by) as LastclickTime,sum( wd.click ) AS TotalClick, case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
			INNER JOIN products AS p ON p.id = wd.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			WHERE " . $where . " AND exists(SELECT wd.id FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
			WHERE " . $where . " AND wd.created_by<='".$prev_date."') group by wd.widget_id order by TotalClick desc limit 0,5
			");		
		return $query->result();
		}else{	
		$query  =  $this->db->query("SELECT wm.id, wd.widget_id,wm.product_id, wm.promo_code,wm.widget_name,wd.report_status, max(wd.created_by) as LastclickTime,sum( wd.click ) AS TotalClick, case when dm.status='0' then concat(dm.domain_name,'','(i)')
			when dm.status='1' then dm.domain_name end as domain_name FROM `campaign_widget_master` AS wm
			INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
			INNER JOIN products AS p ON p.id = wm.product_id
			INNER JOIN domain_master AS dm ON dm.id = p.product_website_id
			WHERE " . $where . " group by wd.widget_id order by TotalClick desc limit 0,5
			");		
		return $query->result();
		}
	}
	
	//==== Function for get top five campaign list
	public function gettopfivegraph_campaign($wid='')
	{
		$days      = 15;
		$prev_date = date('Y-m-d H:i:s', strtotime($date .' -'.$days.' day'));
		$where = $this->globCondition;
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------
		$where.= " AND wd.widget_id='".$wid."'";
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}	
		$query  =  $this->db->query("SELECT wd.widget_id,  
											count( wd.click ) AS TotalClick, 
											SUM( IF( device_version =0, 1, 0 ) ) AS DektopClick, 
											SUM( IF( device_version =1, 1, 0 ) ) AS MobileClick,
											DAY(wd.created_by) as d,
											MONTH(wd.created_by) as m,
											YEAR(wd.created_by) as y,
											(SELECT DATE_FORMAT(min(wd.created_by), '%b %d,%Y') from `campaign_widget_master` AS wm
														INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
													INNER JOIN products AS p ON p.id = wm.product_id
													INNER JOIN domain_master AS dm ON dm.id = p.product_website_id	
													WHERE " . $where . " ORDER by wd.created_by ) as startdate,
											(SELECT DATE_FORMAT(max(wd.created_by), '%b %d,%Y') from `campaign_widget_master` AS wm
														INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
													INNER JOIN products AS p ON p.id = wm.product_id
													INNER JOIN domain_master AS dm ON dm.id = p.product_website_id	
													WHERE " . $where . " ORDER by wd.created_by ) as enddate
													FROM `campaign_widget_master` AS wm
														INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
													INNER JOIN products AS p ON p.id = wm.product_id
													INNER JOIN domain_master AS dm ON dm.id = p.product_website_id	
													WHERE " . $where . " 
													GROUP BY DATE(wd.created_by)");		
		return $query->result();		
	}
	
	//==== Function for get top five campaign list
	public function getcampaign_monthly($wid)
	{			
		$where = $this->globCondition;
		//----------permission code-----------
		$where.= $this->brand_permisison;
		$where.= $this->data_permisison;
		//------------------------------------
		$where.= " AND wd.widget_id='".$wid."'";
		$id_user = $this->session->userdata('user_id');
		$role_id = $this->session->userdata('role_id');
		if($id_user && $role_id=='1') //check permission for users only
		{
			$where.= " AND wm.user_id='".$id_user."'";
		}	
		$query  =  $this->db->query("SELECT wd.widget_id, count( wd.click ) AS TotalClick, SUM( IF( device_version =0, 1, 0 ) ) AS DektopClick, SUM( IF( device_version =1, 1, 0 ) ) AS MobileClick, DAY(wd.created_by) as d,
									case MONTH(wd.created_by) when '1' then 'JAN' when '2' then 'FEB' when '3' then 'MAR' when '4' then 'APR' when '5' then 'MAY' when '6' then 'JUN' when '7' then 'JLY' when '8' then 'AUG' when '9' then 'SEP' when '10' then 'OCT' when '11' then 'NOV' when '12' then 'DEC' end as months,
									YEAR(CURDATE()) as y FROM `campaign_widget_master` AS wm INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id
									INNER JOIN products AS p ON p.id = wm.product_id
									INNER JOIN domain_master AS dm ON dm.id = p.product_website_id	
													WHERE " . $where . " 
													GROUP BY MONTH(wd.created_by)");
		
		return $query->result();		
	}	
	
		//-- Function to authenticate the old password before updating the exiting password with new password --//
	function getOldPassword($post)
	{
		$password = encrypt($post['old_password']);
		if($this->session->userdata('user_id')!='') {
		$id = $this->session->userdata('user_id');
		}
		$table='campaign_users';
		$this->db->select('*');
		$this->db->where('password',$password); 
		$this->db->where('id',$id); 
		$query = $this->db->get($table);
		foreach($query->result() as $row)
		{
		  $pass=$row->password;
		}
		if($pass == $password)
		{
		  return true;
		}
		else
		{
			return false;
		}
		//-- End Here --//
	}
	
	//-- Function to update the password of the logged in user with new password --//
	function updateUserPassword($post)
	{
		if($this->session->userdata('user_id')!='') {
		$id = $this->session->userdata('user_id');
			$table	= 'campaign_users';
		}
		$data = array(
						'password' => encrypt($post['new_password']),
				     );
		$this->db->where('id',$id);
		$suc=$this->db->update($table, $data);
		 
		if($suc)
		{
			return true;
		}
		else
		{	
			return false;
		}
	}
	//-- End Here --//
	
	/*
	  Update User detail
	*/
    function update_user_details($params = array())
	{
		if(isset($params['id']))
		{
			$id = $params['id'];
			unset($params['id']);
			$this->db->where('id',$id);
			$suc=$this->db->update('user', $params); 
			return $suc;
		}
	}
	
	 /*
	  Get Country List order by id 
	*/
	function get_country_list()
	{
		$query = $this->db->query("select * from country order by country ASC");
		return  $query->result();
	}
	
	 /*
	  Get Active Country List order by id 
	*/
	function get_active_country_list()
	{
		$query = $this->db->query("select * from country where status = 1 order by country ASC");
		return  $query->result();
	}
	
	 /*
	  Get State List order by statename 
	*/
	function get_state_list()
	{
		$query = $this->db->query("select statename from state order by statename ASC");
		return  $query->result();
	}
	//==== Function for get top five campaign list
	public function getcampaign_monthly_value($wid)
	{			
		$where = $this->globCondition;
		$where.= " AND wd.widget_id='".$wid."'";		
		$query  =  $this->db->query("SELECT wd.widget_id, count( wd.click ) AS TotalClick, SUM( IF( device_version =0, 1, 0 ) ) AS DektopClick, SUM( IF( device_version =1, 1, 0 ) ) AS MobileClick, DAY(wd.created_by) as d,
									MONTH(wd.created_by)  as month,
									YEAR(wd.created_by) as y FROM `campaign_widget_master` AS wm INNER JOIN campaign_widget_details wd ON wd.widget_id = wm.id			
													WHERE " . $where . " 
													GROUP BY month
													ORDER BY month ASC,y ASC ");
		return $query->result();		
	}	
		
	public function user_brand_data_permission()
	{
		$brand_per = $this->get_user_brands_ids();
		if($brand_per)
		{
			$this->brand_permisison = '  AND dm.id IN ('.$brand_per.') ';
		}
		$data_per = $this->get_user_data();
		if($data_per)
		{
			$this->data_permisison = '  AND wm.id IN ('.$data_per.') ';
		}
	}
	
	//==== Function for get all campaign list
	public function gettotal_campaign()
	{		
		$userId=$this->session->userdata('user_id') ;
		$userRole=$this->session->userdata('role_id') ;

		$query_str  = 'where 1';
		if($userRole=='1')
		$query_str  .= ' AND cm.user_id='.$userId;
		
		$query_str  .= ' AND widget_type=1 order by cm.created_on desc';
		$query       =  $this->db->query("select p.title,prtp.brand,u.name,p.id as product_id,p.product_website_id,prtp.title as product_title,promo_code,cm.created_on,cm.widget_name,cm.hash_key,prtp.buy_now_url,cm.id as widget_id from campaign_widget_master cm
								   LEFT JOIN products p ON cm.product_id = p.id
								   LEFT JOIN campaign_users u ON u.id = cm.user_id
								   LEFT JOIN campaign_widget_products wp ON cm.id = wp.widget_id
								   AND wp.campaign_default=1 
								   LEFT JOIN product_retailer_to_products prtp ON wp.product_retailer_id = prtp.id
								   ".$query_str);		
		return $query->result();		
	}

	
/**
| -------------------------------------------------------------------
|  getCurrentQuarterHits_slotWise
| -------------------------------------------------------------------
* @Date:		March 28, 2017
* @Method : 	getCurrentQuarterHits_slotWise
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to fetch hits of current quarter depending on time slots.
* @Param: 		none
* @Return: 		$array
**/
	public function getCurrentQuarterHits_slotWise()
	{   
        $quarterdate 		= get_quarter();   
        $start_date    		= date('Y-m-d',strtotime($quarterdate['start']));
        $now 				= date('Y-m-d');

		$query  = $this->db->query("CALL QuaterlyClicksData('".$start_date."', '".$now."', @finalClick, @slot1, @slot2, @slot3, @slot4, @slot5, @slot6)" );
   
        $result = $query->result()[0];
        $this->db->close();
        return $result;

	}

/**
| -------------------------------------------------------------------
|  getPreQuarterHits_slotWise
| -------------------------------------------------------------------
* @Date:		March 29, 2017
* @Method : 	getPreQuarterHits_slotWise
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to fetch hits of previous quarter depending on time slots.
* @Param: 		none
* @Return: 		$array
**/
	public function getPreQuarterHits_slotWise()
	{   
        $quarterdate 		= get_quarter(1);
        $start_date    		= date('Y-m-d',strtotime($quarterdate['start']));
        $end_date    		= date('Y-m-d',strtotime($quarterdate['end']));
        
		$query  = $this->db->query("CALL QuaterlyClicksData('".$start_date."', '".$end_date."', @finalClick, @slot1, @slot2, @slot3, @slot4, @slot5, @slot6)" );
   
        $result = $query->result()[0];
   		$query->free_result(); 
        return $result;

	}
	
/**
| -------------------------------------------------------------------
|  get_client_ip
| -------------------------------------------------------------------
* @Date:		March 30, 2017
* @Method : 	get_client_ip
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used for getting ip.
* @Param: 		none
* @Return: 		$array
**/
	public function get_client_ip()
	{
	      $ipaddress = '';
	      if (getenv('HTTP_CLIENT_IP'))
	          $ipaddress = getenv('HTTP_CLIENT_IP');
	      else if(getenv('HTTP_X_FORWARDED_FOR'))
	          $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	      else if(getenv('HTTP_X_FORWARDED'))
	          $ipaddress = getenv('HTTP_X_FORWARDED');
	      else if(getenv('HTTP_FORWARDED_FOR'))
	          $ipaddress = getenv('HTTP_FORWARDED_FOR');
	      else if(getenv('HTTP_FORWARDED'))
	          $ipaddress = getenv('HTTP_FORWARDED');
	      else if(getenv('REMOTE_ADDR'))
	          $ipaddress = getenv('REMOTE_ADDR');
	      else
	          $ipaddress = 'UNKNOWN';

	      return $ipaddress;
	 }

/**
| -------------------------------------------------------------------
|  getBrowser
| -------------------------------------------------------------------
* @Date:		March 30, 2017
* @Method : 	getBrowser
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to get browser details.
* @Param: 		none
* @Return: 		$array
**/
	function getBrowser() 
	{ 
	    $u_agent    = $_SERVER['HTTP_USER_AGENT']; 
	    $bname 		= 'Unknown';
	    $platform   = 'Unknown';
	    $version    = "";

	    //First get the platform?
	    if (preg_match('/linux/i', $u_agent)) {
	        $platform = 'linux';
	    }
	    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
	        $platform = 'mac';
	    }
	    elseif (preg_match('/windows|win32/i', $u_agent)) {
	        $platform = 'windows';
	    }
	    
	    // Next get the name of the useragent yes seperately and for good reason
	    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) 
	    { 
	        $bname = 'Internet Explorer'; 
	        $ub = "MSIE"; 
	    } 
	    elseif(preg_match('/Firefox/i',$u_agent)) 
	    { 
	        $bname = 'Mozilla Firefox'; 
	        $ub = "Firefox"; 
	    } 
	    elseif(preg_match('/Chrome/i',$u_agent)) 
	    { 
	        $bname = 'Google Chrome'; 
	        $ub = "Chrome"; 
	    } 
	    elseif(preg_match('/Safari/i',$u_agent)) 
	    { 
	        $bname = 'Apple Safari'; 
	        $ub = "Safari"; 
	    } 
	    elseif(preg_match('/Opera/i',$u_agent)) 
	    { 
	        $bname = 'Opera'; 
	        $ub = "Opera"; 
	    } 
	    elseif(preg_match('/Netscape/i',$u_agent)) 
	    { 
	        $bname = 'Netscape'; 
	        $ub = "Netscape"; 
	    } 
	    
	    // finally get the correct version number
	    $known = array('Version', $ub, 'other');
	    $pattern = '#(?<browser>' . join('|', $known) .
	    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
	    if (!preg_match_all($pattern, $u_agent, $matches)) {
	        // we have no matching number just continue
	    }
	    
	    // see how many we have
	    $i = count($matches['browser']);
	    if ($i != 1) {
	        //we will have two since we are not using 'other' argument yet
	        //see if version is before or after the name
	        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
	            $version= $matches['version'][0];
	        }
	        else {
	            $version= $matches['version'][1];
	        }
	    }
	    else {
	        $version= $matches['version'][0];
	    }
	    
	    // check if we have a number
	    if ($version==null || $version=="") {$version="?";}
	    
	    return array(
	        'userAgent' => $u_agent,
	        'name'      => $bname,
	        'version'   => $version,
	        'platform'  => $platform,
	        'pattern'    => $pattern
	    );
	} 

/**
| -------------------------------------------------------------------
|  insertUserLastLoginActivity
| -------------------------------------------------------------------
* @Date:		March 30, 2017
* @Method : 	insertUserLastLoginActivity
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to insert login activities of user.
* @Param: 		none
* @Return: 		$array
**/
	public function insertUserLastLoginActivity()
	{       
			$lastHitUrl     = $_SERVER['HTTP_REFERER']; 
			$user_id 		= $this->session->userdata('user_id');	
			$ipaddress 		= $this->get_client_ip();
			$ua 			= $this->getBrowser();
			$deviceDetails	= $ua['name'] . '|' . $ua['version'] . "|" .$ua['platform'] . "|" . $ua['userAgent'];
		
			$rows    		= $this->db->query("SELECT DATE_FORMAT(last_account_activity,'%Y-%m-%d') AS dateCount FROM campaign_user_login_history WHERE campaign_user_id = ".$user_id." group by DATE_FORMAT(last_account_activity,'%Y-%m-%d')")->num_rows();

			if($rows >= 10)
			{
					$row   = $this->db->query("SELECT MIN(DATE_FORMAT(last_account_activity,'%Y-%m-%d')) AS firstRecord FROM campaign_user_login_history WHERE campaign_user_id = ".$user_id)->result()[0];
					$array 	= array('DATE_FORMAT(last_account_activity,"%Y-%m-%d")' => $row->firstRecord, 'campaign_user_id' => $user_id);
					$this->db->where($array); 
					
					$suc 	= $this->db->delete('campaign_user_login_history');
			}
			$data = array(
						'campaign_user_id' 	  		=> $this->session->userdata('user_id'),		
						'ip' 				  		=> $ipaddress,
						'login_date' 		  		=> date("Y-m-d H:i:s"),
						'device_details' 	  		=> $deviceDetails,
						'last_activity_url'   		=> $lastHitUrl
					);			
			$record['insert']   = $this->db->insert('campaign_user_login_history', $data);	
			$record['last_id'] 	= $this->db->insert_id();
			return $record;
		
	}

/**
| -------------------------------------------------------------------
|  fetchUserLastLoginActivity
| -------------------------------------------------------------------
* @Date:		March 30, 2017
* @Method : 	fetchUserLastLoginActivity
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to fetch login activities of user.
* @Param: 		none
* @Return: 		$array
**/
	public function fetchUserLastLoginActivity()
	{   
		$userHistoryId 	= $this->session->userdata('userHistoryId');
		
		$query   		=  $this->db->query("SELECT id,campaign_user_id,login_date,ip,last_account_activity,last_activity_url, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 2), '|',1) AS brwsr,SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 2), '|',-1) AS vr, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 3), '|',-1) AS ptfm, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 4), '|',-1) AS report  FROM campaign_user_login_history WHERE id = ".$userHistoryId." ORDER BY login_date DESC");
		//ECHO '<pre>';
		//print_r($query->result());die();		
		return $query->result()[0];	
	}

/**
| -------------------------------------------------------------------
|  updateUserLastActivity
| -------------------------------------------------------------------
* @Date:		March 31, 2017
* @Method : 	updateUserLastActivity
* Created By: 	Nisha Mishra
* @Purpose: 	This function is used to update some details of logged-in user history on page reload.
* @Param: 		none
* @Return: 		$array
**/ 
    public function updateUserLastActivity($lastHitUrl)
    {
		$data = array(
						'last_account_activity'		=> date("Y-m-d H:i:s"),				
						'last_activity_url' 		=> $lastHitUrl,	
					);	
		$this->db->where('id',$this->session->userdata('userHistoryId'));
		$suc = $this->db->update('campaign_user_login_history',$data);
		return $suc;
    }

/**
| -------------------------------------------------------------------
|  fetchUserTenDaysActivity
| -------------------------------------------------------------------
* @Created Date:		March 31, 2017
* @Modified Date: 		April 03, 2017
* @Method : 			fetchUserTenDaysActivity
* Created By: 			Nisha Mishra
* @Purpose: 			This function is used to fetch user activities for ten days.
* @Param: 				none
* @Return: 				$array
**/
	public function fetchUserTenDaysActivity()
	{   
		$user_id 	= $this->session->userdata('user_id');
		
		$query   	= $this->db->query("SELECT id,campaign_user_id,login_date,ip,last_account_activity,last_activity_url, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 2), '|',1) AS brwsr,SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 2), '|',-1) AS vr, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 3), '|',-1) AS ptfm, SUBSTRING_INDEX(SUBSTRING_INDEX(device_details, '|', 4), '|',-1) AS report  FROM campaign_user_login_history WHERE campaign_user_id = ".$user_id." ORDER BY login_date DESC");
		return $query->result();	
	}
}