<?php
//error_reporting(E_ALL);
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	public function __construct()
	{        
	    parent::__construct();		
		$this->load->library('WriteLog');		
		ini_set('memory_limit', "4096M");       		
    }	
	
	//==== Function for insert all campaign in archive table
	public function index()
	{			
		$days = (int) $_REQUEST['days'] ;
		$con =  $_REQUEST['confirm'] ;
		
		if(empty($days) || $days<1) {
			echo "----Invalid Parameters:: Not Less than 15 days. ----";
			$this->writelog->lwrite(__LINE__ . "----Invalid Parameter Supplied----",1);
			return false;
		}		
		
		$prev_date = date('Y-m-d', strtotime($date .' -'.$days.' day'));
		$this->writelog->lwrite(__LINE__ . "----Shifting ".$days."  Days from Today.----",1);
		$this->writelog->lwrite(__LINE__ . "----Moving Data Till: ".$prev_date." ----",1);
		echo "----Shifting ".$days."  Days from Today.----<br/>". "----Moving Data Till: ".$prev_date." ----";
		 
		if($con == 0) { 
			$this->writelog->lwrite(__LINE__ . "----****Confirmation Failed****----",1);
			echo "Process ends<br/>"; exit;
		}
		$this->writelog->lwrite(__LINE__ . "----****Confirmed****----",1);
		$this->writelog->lwrite(__LINE__ . "----Migration PROCESS STARTED----",1);
		$this->archive_details($prev_date);
			
	}
	
	//==== Function for insert all campaign in archive table
	private function archive_details($prev_date)
	{		
		$pagelimit = 10000;
		//==== Archive data for table campaign_widget_details_archive 
		$this->writelog->lwrite(__LINE__ . "----Starting Transaction for Campaign Detail Table----",1);
		$this->db->reconnect();
		$this->db->where('DATE(created_by)<=', $prev_date);	
		$this->db->order_by("id", "asc");
		$query = $this->db->get('campaign_widget_details');
		$this->writelog->lwrite(__LINE__ . "----Records to migrate : ".count($query->result())."----",1);
		
		$totalcount = count($query->result());
		$count = ceil($totalcount/$pagelimit);
		for ($x = 1; $x <= $count; $x++)
		{	
			$i=0;			
			$this->db->reconnect();
			$this->db->trans_start(); //== Starting Transaction
			$this->db->trans_strict(FALSE); //== See Note 01. If you wish can remove as well
			$sql = "SELECT * from `campaign_widget_details` WHERE DATE(created_by)<='".$prev_date."' order by id asc limit ".$pagelimit;	
			$this->writelog->lwrite(__LINE__ . $sql ,1);			
			$query1 = $this->db->query($sql);
			$this->writelog->lwrite(__LINE__ . "----Records to migrate in iteration : ".$x." - ".count($query1->result())."----",1);
			foreach($query1->result() as $row)
			{
				if($i=="0") $this->writelog->lwrite(__LINE__ . "----Start Id: ".$row->id."----",1);
				$i++;
				$data = array(
					'id' => $row->id,
					'widget_id' => $row->widget_id,
					'product_id' => $row->product_id,
					'target_url' => $row->target_url,
					'widget_source_view_id' => $row->widget_source_view_id,
					'ip' => $row->ip,
					'click' => $row->click,
					'time_loaded' => $row->time_loaded,
					'created_by' => $row->created_by,
					'retailer_id' => $row->retailer_id,
					'retailer_product_images_id' => $row->retailer_product_images_id,
					'device_version' => $row->device_version,
					'status' => $row->status,
					'report_status' => $row->report_status
				 );	
				$this->db->insert('campaign_details_archive', $data); # Inserting data
				$this->db->where('id=', $row->id);	
				$this->db->where('DATE(created_by)<=', $prev_date);			
				$this->db->delete('campaign_widget_details');
			}
			$this->writelog->lwrite(__LINE__ . "----Records To commit ".$i."----",1);		
			$this->writelog->lwrite(__LINE__ . "----Insert/Delete Completed----",1);		
			
			//== Deleting data
			
			$this->db->trans_complete(); //== Completing transaction
			$this->writelog->lwrite(__LINE__ . "----Completing transaction for widget_details table----Iteration:".$x,1);
			
			//== Optional	
			if ($this->db->trans_status() === FALSE) {
				$this->writelog->lwrite(__LINE__ . "----Something went wrong, Rollback occured----",1);	
				$this->db->trans_rollback();			
			} 
			else {
				$this->writelog->lwrite(__LINE__ . "----Perfect:: Committing data to the database----",1);	
				$this->db->trans_commit();
			}
		}//ending for loop	
		//$this->db->close();
		echo $mesg= "Completing Migration Process for Campaign_detail table";
		$this->writelog->lwrite(__LINE__ . "----".$mesg.".----",1);
		$this->view_archive_details($prev_date);
	}
	
	//==== Function for insert all campaign in archive table
	private function view_archive_details($prev_date)
	{		
		$pagelimit = 10000;
		//==== Archive data for table campaign_widget_details_archive 
		$this->writelog->lwrite(__LINE__ . "----Starting Transaction for Campaign Source View Table----",1);
		
		$this->db->where('DATE(created_date)<=', $prev_date);	
		$this->db->order_by("id", "asc");
		$query = $this->db->get('campaign_widget_source_view');
		$this->writelog->lwrite(__LINE__ . "----Records to migrate : ".count($query->result())."----",1);
		
		$totalcount = count($query->result());
		$count = ceil($totalcount/$pagelimit);
		for ($x = 1; $x <= $count; $x++)
		{	
			$i=0;			
			$this->db->reconnect();
			$this->db->trans_start(); //== Starting Transaction
			$this->db->trans_strict(FALSE); //== See Note 01. If you wish can remove as well
			$sql = "SELECT * from `campaign_widget_source_view` WHERE DATE(created_date)<='".$prev_date."' order by id asc limit ".$pagelimit;	
			$this->writelog->lwrite(__LINE__ . $sql ,1);			
			$query1 = $this->db->query($sql);
			$this->writelog->lwrite(__LINE__ . "----Records to migrate in iteration : ".$x." - ".count($query1->result())."----",1);
			foreach($query1->result() as $row)
			{
				if($i=="0") $this->writelog->lwrite(__LINE__ . "----Start Id: ".$row->id."----",1);
				$i++;
				$data = array(
					'id' => $row->id,
					'widget_id' => $row->widget_id,
					'source_url' => $row->source_url,				
					'ip' => $row->ip,
					'view' => $row->view,
					'country_name' => $row->country_name,
					'country_code' => $row->country_code,
					'region' => $row->region,
					'city' => $row->city,
					'latitude' => $row->latitude,
					'longitude' => $row->longitude,
					'device_version' => $row->device_version,
					'bin_type' => $row->bin_type,
					'created_date' => $row->created_date,
					'modified_date' => $row->modified_date,
					'status' => $row->status,
					'report_status' => $row->report_status
				 );	
				$this->db->insert('campaign_source_view_archive', $data); # Inserting data
				$this->db->where('id=', $row->id);	
				$this->db->where('DATE(created_date)<=', $prev_date);			
				$this->db->delete('campaign_widget_source_view');
			}
			$this->writelog->lwrite(__LINE__ . "----Records To commit ".$i."----",1);		
			$this->writelog->lwrite(__LINE__ . "----Insert/Delete Completed----",1);		
			
			//== Deleting data 
			
			$this->db->trans_complete(); //== Completing transaction
			$this->writelog->lwrite(__LINE__ . "----Completing transaction for widget_details_view table----Iteration:".$x,1);
			
			//== Optional	
			if ($this->db->trans_status() === FALSE) {
				$this->writelog->lwrite(__LINE__ . "----Something went wrong, Rollback occured----",1);	
				$this->db->trans_rollback();			
			} 
			else {
				$this->writelog->lwrite(__LINE__ . "----Perfect:: Committing data to the database----",1);	
				$this->db->trans_commit();
			}
		}//ending for loop	
		$this->db->close();
		echo $mesg= "Completing Migration Process for Campaign_details_view table";
		$this->writelog->lwrite(__LINE__ . "----".$mesg.".----",1);
	}	
	
}
