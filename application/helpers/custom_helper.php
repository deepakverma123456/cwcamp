<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	function _dateform($date)
	{
		return date('d-m-Y',strtotime($date)); 
	}
	function _general_status($status)
	{
		switch ($status)
		{
			case 1:
				$val = 'Active';
				break;
			case 2:
				$val = 'Inactive';
				break;
			case 3:
				$val = 'Blocked';
				break;
		}
		return $val;
	}
	function a($var)
	{
		if( empty($var) )
			return false;
			
		print "<div style=\"border: 1px solid black; font:12px 'Courier new',monospace; padding:5px;\">";
			print "<b>PRINT ARRAY RESULT:</b><br/>";
			print "<pre>";
				print_r($var);
			print "</pre>";
		print "</div>";
	}
	
	function _dateformat($date='',$type='date')
	{
		if($type=='date')
			return date('Y-m-d',strtotime($date));
		else
			return date('d-m-Y H:i:s',strtotime($date));
	}
	
	function _dateformat_form($date='',$type='date')
	{
		if($type=='date')
			return date('d-m-Y',strtotime($date));
	}
	
	function trim_all($data=array())
	{
		$res = array();
		foreach((array)$data as $key=>$val)
		{
			$res[$key] = trim($val);
			
		}
		return $res;
	}
	function hash_password($password){
		return password_hash($password, PASSWORD_BCRYPT);
	}
	
	function encrypt($text)
    {
        return base64_encode(base64_encode(base64_encode($text)));
    }

    function decrypt($text)
    {
        return base64_decode(base64_decode(base64_decode($text)));
    }
    function DATETIME()
	{
		return date('Y-m-d H:i:s');
	}
	function USERNAME()
	{
		$CI =& get_instance();
		$id_user = $CI->session->userdata('user_id');
		$res = $CI->db->get_where('campaign_users',array('id'=>$id_user))->row_array();
		return ucfirst($res['name']);
		
	}
	//==== Function for get previous querter
	function get_quarter($i)
	{
		$y = date('Y');
		$m = date('m');
		if($i > 0) {
			for($x = 0; $x < $i; $x++) {
				if($m <= 3) { $y--; }
				$diff = $m % 3;
				$m = ($diff > 0) ? $m - $diff:$m-3;
				if($m == 0) { $m = 12; }
			}
		}
		switch($m) {
			case $m >= 1 && $m <= 3:
				$start = $y.'-01-01 00:00:01';
				$end = $y.'-03-31 00:00:00';
				break;
			case $m >= 4 && $m <= 6:
				$start = $y.'-04-01 00:00:01';
				$end = $y.'-06-30 00:00:00';
				break;
			case $m >= 7 && $m <= 9:
				$start = $y.'-07-01 00:00:01';
				$end = $y.'-09-30 00:00:00';
				break;
			case $m >= 10 && $m <= 12:
				$start = $y.'-10-01 00:00:01';
				$end = $y.'-12-31 00:00:00';
					break;
		}
		return array(
			'start' => $start,
			'end' => $end									
		);
	}
?>