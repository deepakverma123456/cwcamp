<?php

class App_auth
{
	private $CI;
	
	function App_auth()
	{
		$this->CI = &get_instance();
		if(!isset($this->CI->session)){  //Check if session lib is loaded or not
              $this->CI->load->library('session');  //If not loaded, then load it here
        }
	}
	
	function index()
	{
		// If no loggedin
		
	}
}

?>
