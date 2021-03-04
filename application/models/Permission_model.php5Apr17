<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permission_model extends CI_Model {
	
	/**
	* Function __construct
	*
	* constructor for Permission Model
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return NULL
	*/
	Public function __construct() { 
         parent::__construct();
		 $this->load->model('User_model');
    }
	/**
	* Function list_module_permission_data
	*
	* get all permissed/non permissied user for module
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  array
	* @return JSON
	*/
	Public function list_module_permission_data($post='')
	{
		$columns = array( 
					0 => 'us.name'
				);
		if($post['type']=='1') //update case 
		{
			$query = '  SELECT us.id AS id_user,CONCAT(us.name," - ",us.email) AS name,us.email,cmp.id_menu,GROUP_CONCAT(cm.menu_label) AS 	module_name,MAX(cmp.created_date) AS created_date
						FROM campaign_users AS us
						INNER JOIN campaign_module_permission AS cmp ON cmp.id_user = us.id
						INNER JOIN campaign_menu AS cm ON cm.id_menu = cmp.id_menu
						WHERE 1 ';
			
			if( !empty($post['search']['value']) ) { 
				$query.=" AND ( cm.menu_label LIKE '%".$post['search']['value']."%' ";    
			}
		}
		elseif($post['type']=='2') //add case
		{
			//select those users which dont have permission
			$query = '  SELECT us.id AS id_user, CONCAT(us.name," - ",us.email) AS name
						FROM campaign_users AS us 
						WHERE us.role_id="1" AND us.id NOT IN(SELECT DISTINCT(id_user) FROM campaign_module_permission) AND us.status = "1"';
		}
		if( !empty($post['search']['value']) ) { 
			$query.=" OR us.name  LIKE '%".$post['search']['value']."%' ";
		}
        $query.=' GROUP BY us.id';
		
		$data = $this->db->query($query);
		$totalData =  $data->num_rows();
		$totalFiltered = $totalData;  
		if($columns[$post['order'][1]['column']])
			$query.=" ORDER BY ". $columns[$post['order'][1]['column']]."   ".$post['order'][1]['dir']." ";
		else
			$query.=' ORDER BY us.name ASC ';
			
		$main = $this->db->query($query);
		$GetData = $main->result();
		$result = array();
		//$i=1;
		foreach((array)$GetData as $val)
		{
			$nestedData=array();
			$checkbox = '<input type="radio" id="user_'.$val->id_user.'" onclick="getuservalue(this.value);" value="'.$val->id_user.'" name="user_id" class="user_id"  ';
			$checkbox.= ' >&nbsp;&nbsp;&nbsp;&nbsp;';
			//$nestedData[] = $checkbox;
			$user_data  = $checkbox.$val->name;
			
			if($post['type']=='1')
			{
				$user_data.= '<a class="btn btn-danger btn-xs fa fa-times pull-right" href="'.base_url().'permission/delete_module_permission?id_user='.$val->id_user.'"></a>';
			}
			$nestedData[] = $user_data;
			$result[] = $nestedData;
			
		}
		$json_data = array(
					"recordsTotal"    => intval( $totalData ),  
					"recordsFiltered" => intval( $totalFiltered ), 
					"data"            => $result  
					);
		return json_encode($json_data); 
	}
	/**
	* Function get_all_modules
	*
	* get all modules for user
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_all_modules()
	{
		$menu = array();
		//get user related modules
		$menu_data = $this->user_model->menu_data('1');
		$i=0;
		foreach((array)$menu_data as $val)
		{
			$menu[$i]['menu'] = $val;
			$submenu = $this->user_model->all_submenu_data($val->id_menu);
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
	* Function add_module_permission
	*
	* add/update module permisison
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return NULL
	*/
	function add_module_permission($post)
	{
		if($post)
		{
			$all_menu = array();
			if($post['module'] && $post['submodule'] )
			{
				$all_menu = array_merge(array_filter($post['module']),array_filter($post['submodule']));
			}
			elseif($post['module'] && !@$post['submodule'])
			{
				$all_menu = array_filter($post['module']);
			}
			elseif(!@$post['module'] && $post['submodule'])
			{
				$all_menu = $post['submodule'];
			}
			//check for add/edit case
			$check_case = $this->check_module_exist_foruser($post);
			if($check_case) //update case do delete previous records
			{
				$this->db->where('id_user', $post['id_user']);
				$this->db->delete('campaign_module_permission'); 
			}
			foreach((array)$all_menu as $val)
			{
				$data = array();
				$data = array(
					'id_user' => $post['id_user'],
					'id_menu' => $val,
					'status' => '1',
					'created_date'=>DATETIME(),
					'updated_date'=>DATETIME()
				);
				if(!$this->check_module_permission_dataexist($data))
				{
					$this->db->insert('campaign_module_permission', $data);
				}
			}
		}
	}
	/**
	* Function add_module_permission
	*
	* add/update module permisison
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return NULL
	*/
	function check_module_permission_dataexist($post)
	{
		$query = $this->db->get_where('campaign_module_permission', array('id_user' => $post['id_user'],'id_menu'=>$post['id_menu']))->row_array();
		if($query)
			return true;
		else
			return false;
		
	}
	/**
	* Function check_module_exist_foruser
	*
	* check if user has module permission
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return BOOLEAN
	*/
	function check_module_exist_foruser($post)
	{
		$query = $this->db->get_where('campaign_module_permission', array('id_user' => $post['id_user']))->row_array();
		if($query)
			return true;
		else
			return false;
	}
	/**
	* Function get_user_permission_module
	*
	* get permissed modules of user
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	function get_user_permission_module($id_user)
	{
		$query = 'SELECT cmp.*,menu.id_parent
				FROM campaign_module_permission AS cmp
				INNER JOIN campaign_menu AS menu ON cmp.id_menu = menu.id_menu
				WHERE cmp.id_user="'.$id_user.'"';
		
		$res = $this->db->query($query)->result();
		$data = array();
		if($res)
		{
			$i=0;
			foreach((array)$res as $val)
			{
				if($val->id_parent=='0')
				{
					$data[$i]['module'] = $val->id_menu;
				}
				foreach((array)$res as $value)
				{
					if($val->id_menu==$value->id_parent)
					{
						$data[$i]['submodule'][] = $value->id_menu;
					}
				}
			$i++;
			}
		}
		return $data;
		
	}
	/**
	* Function delete_module_permission
	*
	* delete module permission
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return BOOLEAN
	*/
	function delete_module_permission($id_user)
	{
		if($id_user)
		{
			$this->db->where('id_user', $id_user );
			$this->db->delete('campaign_module_permission');
			
		}
		return true;	
	}
	/**
	* Function list_data_permission_users
	*
	* get list of permissed / non permissied user for data permisison
	*
	* @param  ARRAY
	* @return JSON
	*/
	Public function list_data_permission_users($post='')
	{
		$columns = array( 
				1 =>'cmp.id', 
				2 => 'us.name',
				
			);
		if($post['type']=='1') //update case
		{
			$query = '  SELECT us.id AS id_user, CONCAT(us.name," - ",us.email) AS name,cdp.id AS id_permission
		
					FROM campaign_users AS us
					INNER JOIN campaign_data_permission AS cdp ON us.id = cdp.id_user
					WHERE 1
					';
		}
		elseif($post['type']=='2')
		{
			//select those users which dont have permission
			$query = '  SELECT us.id AS id_user, CONCAT(us.name," - ",us.email) AS name,us.email
						FROM campaign_users AS us 
						WHERE us.role_id="1" AND us.id NOT IN(SELECT DISTINCT(id_user) FROM campaign_data_permission) AND us.status = "1"';
			
		}
		if( !empty($post['search']['value']) ) { 
			$query.=" OR us.name  LIKE '%".$post['search']['value']."%' ";
			$query.=" OR us.email  LIKE '%".$post['search']['value']."%' ";
		}
		
        $query.=' GROUP BY us.id ';
		
		$data = $this->db->query($query);
		$totalData =  $data->num_rows();
		$totalFiltered = $totalData;
		if($columns[$post['order'][1]['column']])
			$query.=" ORDER BY ". $columns[$post['order'][1]['column']]."   ".$post['order'][1]['dir']." ";
		else
			$query.=' ORDER BY us.name ASC ';
			
			
		$main = $this->db->query($query);
		$GetData = $main->result();
		$result = array();
			
		foreach((array)$GetData as $val)
		{
			$nestedData=array();
			$checkbox = '<input type="radio" id="user_'.$val->id_user.'" onclick="getuservalue(this.value);" value="'.$val->id_user.'" name="user_id" class="user_id" ';
			$checkbox.= ' >&nbsp;&nbsp;&nbsp;&nbsp;';
			///$nestedData[] = $checkbox;
			$user_data = $checkbox.$val->name;
			if($post['type']=='1')
			{
				$user_data.= '<a class="btn btn-danger btn-xs fa fa-times pull-right" href="'.base_url().'permission/delete_data_permission?id_user='.$val->id_user.'"  onclick="return ConfirmDialog();"></a>';
			}
			$nestedData[] = $user_data;
			$result[] = $nestedData;
		}
		$json_data = array(
						"recordsTotal"    => intval( $totalData ),  
						"recordsFiltered" => intval( $totalFiltered ), 
						"data"            => $result  
						);
			
		return json_encode($json_data);  // send data as json format
	}
	/**
	* Function get_all_campaigns
	*
	* get list of all campaigns
	*
	* @Created Date: 13 Feb, 2017
	* @Modified Date: 13 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return JSON
	*/
	public function get_all_campaigns($post)
	{
		$columns = array( 
				0 =>'cwm.id',
				//1 =>'pro.image_url', 
				//2 => 'cwm.widget_name',
				
			);
		
		if ($post['brand']!= '') {
			
			$query = " 	SELECT cwm.widget_name,cwm.id,pro.title AS product_title,pro.image_url,cwm.promo_code
						FROM campaign_widget_master AS cwm
						INNER JOIN products AS pro ON pro.id = cwm.product_id
						INNER JOIN domain_master AS dm ON dm.id = pro.product_website_id
						WHERE dm.id = '".$post['brand']."'
	
						AND cwm.widget_category_id != '3'
						AND cwm.widget_type = '1' ";
				
			if( !empty($post['search']['value']) ) {  
				$query.=" AND ( cwm.widget_name LIKE '%".$post['search']['value']."%' ";    
				$query.=" OR pro.title  LIKE '%".$post['search']['value']."%' ";
				$query.=" OR pro.image_url LIKE '%".$post['search']['value']."%' )";
			}
			$query.=' GROUP BY cwm.id';
			//echo $query; exit;
			$main = $this->db->query($query);
			$totalData =  $main->num_rows();
			$totalFiltered = $totalData;
			if($columns[$post['order'][0]['column']])
				$query.=" ORDER BY ". $columns[$post['order'][0]['column']]."   ".$post['order'][0]['dir']." ";
				
			
			$main = $this->db->query($query);
			$GetBrand = $main->result();
			$data = array();
			if($post['id_user'] ) //for edit case
			{
				$WIDGET_IDS = array();
				$WIDGET_IDS = $this->get_user_data_bybrand($post['id_user'],$post['brand']);
			}
			
			foreach((array)$GetBrand as $val)
			{
				$nestedData=array();
				$checkbox = '<div class="pull-left"><input type="checkbox" id="widget_'.$val->id.'" value="'.$val->id.'" name="widget_id[]" class="widget_id" ';
				if(in_array($val->id,$WIDGET_IDS))
				{
				   $checkbox.=' checked="checked" ';
				}
				   
				$checkbox.= ' >&nbsp;&nbsp;&nbsp;</div><div class="pull-left"><img src="'.$val->image_url.'" class="pimg-thumb" title="'. $val->product_title.'" alt="'. $val->product_title.'" >&nbsp;&nbsp;&nbsp;</div><div class="pull-left" style="max-width:300px;">'.$val->widget_name.' &nbsp;</br />('.$val->promo_code.' ) </div>';
				$nestedData[] = $checkbox;
				//$nestedData[] = '<img src="'.$val->image_url.'" class="pimg-thumb" title="'. $val->product_title.'" alt="'. $val->product_title.'" >&nbsp;&nbsp;&nbsp;<div>'.$val->widget_name.' ('.$val->promo_code.' ) </div>';
				//$nestedData[] = $val->widget_name.' ('.$val->promo_code.' ) ';
				$data[] = $nestedData;
			}
			$json_data = array(
						"recordsTotal"    => intval( $totalData ),  
						"recordsFiltered" => intval( $totalFiltered ), 
						"data"            => $data  
						);
			
			return json_encode($json_data); 
		}
	}
	/**
	* Function add_data_permission
	*
	* add/update brand/data permisison
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return NULL
	*/
	function add_data_permission($post)
	{
		if($post)
		{
			
			//check the permission type
			if($post['permission_type']=='1') //brand permission
			{
				//get previous brands
				//check for add/edit case
				$pre_brands = $this->check_brand_exist_foruser($post);
				if($pre_brands)
				{
					foreach((array)$pre_brands as $res)
					{
						if(!in_array($res,$post['brand_multi']))
						{
							//delete that brand and data for the brand
							$this->db->where('id_user', $post['id_user']);
							$this->db->where('id_domain', $res);
							$this->db->delete('campaign_data_permission');
							
						}
					}
				}
				//remove those brand and data which are not in new brands
				
				foreach((array)$post['brand_multi'] as $val)
				{
					if(!in_array($val,$pre_brands))
					{
						$data = array();
						$data = array(
							'id_user' => $post['id_user'],
							'status' => '1',
							'id_domain'=> $val,
							'data_type'=> '1',
							'created_date'=>DATETIME(),
							'updated_date'=>DATETIME()
						);
						$this->db->insert('campaign_data_permission', $data);
						
					}
				}
			}
			elseif($post['permission_type']=='2') //data permission
			{
				//check for add/edit case
				$check_case = $this->check_data_exist_foruser($post);
				if($check_case) //update case do delete previous brands
				{
					$this->db->where('id_user', $post['id_user']);
					$this->db->where('id_data !=', '0');
					$this->db->where('id_domain', $post['brand_id']);
					$this->db->delete('campaign_data_permission'); 
				}
				foreach((array)$post['widget_id'] as $val)
				{
					$data = array();
					$data = array(
						'id_user' => $post['id_user'],
						'status' => '1',
						'id_domain'=> $post['brand_id'],
						'id_data'=> $val,
						'data_type'=> '1',
						'created_date'=>DATETIME(),
						'updated_date'=>DATETIME()
					);
					if(!$this->check_data_permission_dataexist($data))
					{
						$this->db->insert('campaign_data_permission', $data);
					}
				}
				
			}
			
		}
	}
	/**
	* Function check_brand_exist_foruser
	*
	* check if user has module permission
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return BOOLEAN
	*/
	function check_brand_exist_foruser($post)
	{
		$data = array();
		$query = $this->db->get_where('campaign_data_permission', array('id_user' => $post['id_user'],'id_data'=>'0'))->result();
		if($query)
		{
			foreach((array)$query as $val)
			{
				$data[] = $val->id_domain;
			}
			
		}
		return $data;
	}
	/**
	* Function check_data_exist_foruser
	*
	* check if user has data permission for particular brand
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return BOOLEAN
	*/
	function check_data_exist_foruser($post)
	{
		$query = $this->db->get_where('campaign_data_permission', array('id_user' => $post['id_user'],'id_domain'=>$post['id_domain'],'id_data !='=>'0'))->row_array();
		if($query)
			return true;
		else
			return false;
	}
	/**
	* Function check_data_permission_dataexist
	*
	* check if data permisison exist for particular cmpaign
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return BOOLEAN
	*/
	function check_data_permission_dataexist($post)
	{
		$query = $this->db->get_where('campaign_data_permission', array('id_user' => $post['id_user'],'id_domain'=>$post['id_domain'],'id_data'=>$post['id_data']))->row_array();
		if($query)
			return true;
		else
			return false;
	}
	/**
	* Function get_user_permission_data
	*
	* get permissed modules of user
	*
	* @Created Date: 5 Feb, 2017
	* @Modified Date: 5 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	function get_user_permission_data($id_user)
	{
		$query = 'SELECT cdp.*
				FROM campaign_data_permission AS cdp
				WHERE cdp.id_user="'.$id_user.'"';
		
		$res = $this->db->query($query)->result();
		$data = array();
		if($res)
		{
			$data['brand'] = $res[0]->id_domain;
			foreach((array)$res as $val)
			{
				$data['widget_id'][] = $val->id_data;
			}
		}
		return $data;
		
	}
	/**
	* Function delete_data_permission
	*
	* delete data permission
	*
	* @Created Date: 14 Feb, 2017
	* @Modified Date: 14 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return BOOLEAN
	*/
	function delete_data_permission($id_user)
	{
		if($id_user)
		{
			$this->db->where('id_user', $id_user );
			$this->db->delete('campaign_data_permission');
			
		}
		return true;	
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
	* @return ARRAY
	*/
	public function get_user_data_bybrand($id_user,$id_domain)
	{
		
		$query = '	SELECT (cdp.id_data)
					FROM campaign_data_permission AS cdp
					WHERE cdp.id_user = "'.$id_user.'" AND cdp.id_domain="'.$id_domain.'" AND cdp.data_type="1" AND cdp.id_data !="0"
					ORDER BY cdp.id ASC ';
		$result = $this->db->query($query)->result();
		
		if(count($result)>0)
		{
			foreach((array)$result as $val)
			{
				$data[] = $val->id_data;
			}
			return $data;	
		}
	}
	/**
	* Function get_user_permissed_brands
	*
	* get permissed brands for user
	*
	* @Created Date: 16 Feb, 2017
	* @Modified Date: 16 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	public function get_user_permissed_brands($id_user)
	{
		
		$query = '	SELECT cdp.id_domain,case when dm.status="0" then concat(dm.domain_name,"","(i)")
					when dm.status="1" then dm.domain_name end as domain_name,dm.language_id, dm.status
					FROM campaign_data_permission AS cdp
					INNER JOIN domain_master AS dm ON dm.id = cdp.id_domain
					WHERE cdp.id_user = "'.$id_user.'" AND cdp.data_type="1"
					GROUP BY cdp.id_domain
					ORDER BY domain_name ASC ';
		$result = $this->db->query($query)->result();
		
		return $result;	
		
	}
	/**
	* Function get_brands
	*
	* fetch all brands 
	*
	* @Created Date: 16 Feb, 2017
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
	* Function user_module_submodule
	*
	* get permissed modules & submodules of user
	*
	* @Created Date: 03 Mar, 2017
	* @Modified Date: 03 Mar, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	function user_module_submodule($id_user)
	{
		$query = 'SELECT cmp.*
				FROM campaign_module_permission AS cmp
				WHERE cmp.id_user="'.$id_user.'"';
		
		$res = $this->db->query($query)->result();
		$data = array();
		if($res)
		{
			foreach((array)$res as $val)
			{
				$data[] = $val->id_menu;
			}
		}
		return $data;
		
	}
	/**
	* Function user_module_submodule
	*
	* get permissed modules & submodules of user
	*
	* @Created Date: 03 Mar, 2017
	* @Modified Date: 03 Mar, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  INT
	* @return ARRAY
	*/
	function get_menu_from_name($filename)
	{
		$query = 'SELECT id_menu
				FROM campaign_menu AS cm
				WHERE cm.filename="'.$filename.'"
				LIMIT 1
				';
		
		$res = $this->db->query($query)->result();
		return $res[0]->id_menu;
		
	}
	
	/**
	* Function user_permissed_menu_links
	*
	* fetch all menu links
	*
	* @Created Date: 06 March, 2017
	* @Modified Date: 06 March, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  NULL
	* @return ARRAY
	*/
	Public function user_permissed_menu_links()
	{
		$role_id = '1';
		$query = 'SELECT *
				FROM campaign_menu
				WHERE status="1" AND filename!=""
				ORDER BY menu_order ASC,menu_label ASC';
				
		$res =	$this->db->query($query)->result();
		foreach((array)$res as $val)
		{
			$data[] = $val->filename;
		}
		return $data;	
	}
	
}