<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {
	
	public function __construct() { 
         parent::__construct(); 
      } 

	public function authenticate($data)
	{
		$query = $this->db->get_where('campaign_users', array('email' => $data['email'],'password'=>hash('sha256',$data['password'])))->row_array();
		return $query;	
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
	
	
}