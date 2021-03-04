<?php
class Common_model extends CI_Model 
{  
    function __construct()
    {
        // Call the Model constructor
		parent::__construct();		
    }
	
	//==== function to get contact email
	public function get_admin_email()
	{
		$email		= null;
		$query 		= $this->db->query("SELECT email FROM campaign_users WHERE id=1000 LIMIT 1");
		if($query->num_rows())
		{
			$row 	= $query->result();
			$email	= $row[0]->email;
		}
		return $email;
	}
	
	//==== function to get created user name
	public function get_user_name($user_id)
	{		
		$created_name	= null;		
		$query 		= $this->db->query("SELECT name, email FROM campaign_users WHERE id=".$user_id);
		if($query->num_rows())
		{
			$row 	= $query->result();
			$created_name = $row[0]->name;
		}
		return $created_name;
	}
}