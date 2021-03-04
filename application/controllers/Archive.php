<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Archive extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');	
		$this->load->library('pagination');
		$this->load->model('user_model');
		$this->load->model('report_model');
		$this->load->helper('custom_helper');
		$this->load->model('permission_model');
		$this->load->model('archive_model');
			//$this->load->library('log');		
	}
	
	/*
     * @Created Date: Jan 31, 2017
     * @Modified Date: Jan 31, 2017
     * @Method : view_campaign
     * Created By: Deepak
     * Modified By: Deepak
     * @Purpose: This function is used to display modules
     * @Param: none
     * @Return: none 
     */
	public function reports()
    {
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'Reports';       
        $this->load->view('archive/reports',$data);
    }
	
	//==== Function for list user brand
	public function select_brand()
	{	
		$data['title'] = 'Brand List';		
		$data['brands'] = $this->report_model->get_user_brands();
		$this->load->view('archive/select_brand',$data);
	}
	/**
	* Function topfive_product_campaign_report
	*
	* Product wise excel report
	*
	* @Created Date: 28 Feb, 2017
	* @Modified Date: 28 Feb, 2017
	* @Created By: Diksha Srivastava
	* @Modified By: Diksha Srivastava
	* @param  ARRAY
	* @return EXCEL
	*/
	public function topfive_product_campaign_report()
	{
        $daterange =($_COOKIE['daterange']=="") ?   date("F j, Y", strtotime("-29 days"))." - ".date("F j, Y") 
												:   $_COOKIE['daterange']; 
		
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		$objPHPExcel 	= new PHPExcel();
		
		$styleArray = array(
							'borders' => array(
								'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							)
						);
		$max_col = 'F';
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight('20');
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight('17');
		$counter = 1;
		$objWorkSheet->mergeCells("A".$counter.":".$max_col.$counter);
		$objWorkSheet->getStyle("A".$counter.":".$max_col.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objWorkSheet->setCellValue('A'.$counter, 'Top Five Product Campaigns Report');
		$counter ++;
		$objWorkSheet->getStyle('A'.$counter.':'.$max_col.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
		$objWorkSheet->setCellValue('A'.$counter, '  ');
		$objWorkSheet->setCellValue('B'.$counter, '  Brands ');
		$objWorkSheet->setCellValue('C'.$counter, '  Campaign Name  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Campaign Code  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Total Hits  ');
		$objWorkSheet->setCellValue('F'.$counter, '  Duration('.$daterange.')  ');
		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);
		$getallwidget	= $this->archive_model->getTopFiveProductWiseReport($daterange);
		
		if(!empty($getallwidget))
		{
			$brand = array();
			foreach((array)$getallwidget as $val)
			{
				$brand[] = $val->id_domain;
			}
			$brand_unique = array_unique($brand);
			$occurences = array_count_values($brand);
			
			foreach((array)$getallwidget as $value)
			{
				$counter ++;
				//$brand_count = $occurences[$value->id_domain];
				//$count = $brand_count + $counter-1; 
				//if($brand_count>1 && in_array($value->id_domain,$brand_unique))
				//{
				//	//set Col B and merege
				//	$objWorkSheet->mergeCells("B".$counter.":B".$count);
				//	$objWorkSheet->getStyle("B".$counter.":B".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				//	$objWorkSheet->setCellValue("B".$counter,$value->domain_name);
				//	if(($key = array_search($value->id_domain, $brand_unique)) !== false) {
				//		$brand_unique[$key] = '';
				//		
				//			unset($brand_unique[$key]);
				//		}
				//	
				//}
				//elseif(in_array($value->id_domain,$brand_unique) && $brand_count=='1')  {
				//	$objWorkSheet->setCellValue("B".$counter,$value->domain_name);
				//	
				//}
				$objWorkSheet->setCellValue("B".$counter,$value->domain_name);
				$objWorkSheet->setCellValue('C'.$counter, $value->title.'('.$value->hash_key.')');
				$objWorkSheet->setCellValue('D'.$counter, $value->promo_code);
				if(!$value->total_click)
					$value->total_click = '0';
				$objWorkSheet->setCellValue('E'.$counter, $value->TotalClick);
				$objWorkSheet->setCellValue('F'.$counter, $value->start_date.' - '.$value->end_date);
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);
				
			}
			
			//set Col A and merege
			$objWorkSheet->mergeCells("A3:A".$counter);
			$objWorkSheet->getStyle("A3:A".$counter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorkSheet->setCellValue("A3", 'Campaigns');
			
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Top Five Campaigns Report');
		
		//$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Top-5-Product-Campaign-Report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	function topfive_brand_campaign_report()
	{
		$daterange =($_COOKIE['daterange']=="") ?   date("F j, Y", strtotime("-29 days"))." - ".date("F j, Y") 
												:   $_COOKIE['daterange']; 
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		$brands = $_POST['brandId'];
		$brandIds = implode(',',$brands);
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		
		//==== Create new PHPExcel object
		$objPHPExcel 	= new PHPExcel();
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		
		//looop the sheet brand wise
		$i=0;
		foreach((array)$brands as $val)
		{
			$getallwidget 	= $this->archive_model->brand_campaign_report($val,$daterange,1);
			
			if(count($getallwidget)>0)
			{
				if($i>0)
					$objWorkSheet 		= $objPHPExcel->createSheet($i);
				//else
				//	$objWorkSheet = $objPHPExcel->getActiveSheet($i);
				$objPHPExcel->setActiveSheetIndex($i)->getRowDimension('1')->setRowHeight('20');
				$objPHPExcel->setActiveSheetIndex($i)->getRowDimension('2')->setRowHeight('17');
				
				$counter 			= 1;
				
				$objWorkSheet->mergeCells("A".$counter.":E".$counter);
				$objWorkSheet->getStyle("A".$counter.":E".$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objWorkSheet->setCellValue('A'.$counter, $getallwidget[0]->domain_name);
				$counter ++;
				$objWorkSheet->getStyle('A'.$counter.':E'.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
				//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
				$objWorkSheet->setCellValue('A'.$counter, '  Campaign Code  ');
				$objWorkSheet->setCellValue('B'.$counter, '  Campaign Name ');
				$objWorkSheet->setCellValue('C'.$counter, '  Desktop Hits  ');
				$objWorkSheet->setCellValue('D'.$counter, '  Mobile Hits  ');
				$objWorkSheet->setCellValue('E'.$counter, '  Total Hits  ');
				
				$j = '3';
				foreach($getallwidget as $bd)
				{
					$j++;			
					//$objWorkSheet->setCellValue('A'.$j, $bd->domain_name);
					$objWorkSheet->setCellValue('A'.$j, $bd->promo_code);
					$objWorkSheet->setCellValue('B'.$j, $bd->widget_name);
					if($bd->Number_of_clicks_desktop=='')$bd->Number_of_clicks_desktop=0;
					if($bd->Number_of_clicks_mobile=='')$bd->Number_of_clicks_mobile=0;
					$objWorkSheet->setCellValue('C'.$j, $bd->Number_of_clicks_desktop);
					$objWorkSheet->setCellValue('D'.$j, $bd->Number_of_clicks_mobile);
					$objWorkSheet->setCellValue('E'.$j, $bd->TotalClick);			
				}
				$objPHPExcel->setActiveSheetIndex($i)->setTitle($getallwidget[0]->domain_name);
				$i++;
			}
			
		}
		if($i==0)
		{
			//echo "hey"; exit;
			$objWorkSheet->setCellValue('A1', 'No Records Found');
		}
		//$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Top-5-Brand-Campaign-Report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	function brand_campaign_report()
	{
		$daterange =($_COOKIE['daterange']=="") ?   date("F j, Y", strtotime("-29 days"))." - ".date("F j, Y") 
												:   $_COOKIE['daterange']; 
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		$brands = $_POST['brandId'];
		$brandIds = implode(',',$brands);
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		
		//==== Create new PHPExcel object
		$objPHPExcel 	= new PHPExcel();
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		
		//looop the sheet brand wise
		$i=0;
		foreach((array)$brands as $val)
		{
			$getallwidget 	= $this->archive_model->brand_campaign_report($val,$daterange);
			
			if(count($getallwidget)>0)
			{
				if($i>0)
					$objWorkSheet 		= $objPHPExcel->createSheet($i);
				//else
				//	$objWorkSheet = $objPHPExcel->getActiveSheet($i);
				$objPHPExcel->setActiveSheetIndex($i)->getRowDimension('1')->setRowHeight('20');
				$objPHPExcel->setActiveSheetIndex($i)->getRowDimension('2')->setRowHeight('17');
				
				$counter 			= 1;
				
				$objWorkSheet->mergeCells("A".$counter.":E".$counter);
				$objWorkSheet->getStyle("A".$counter.":E".$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objWorkSheet->setCellValue('A'.$counter, $getallwidget[0]->domain_name);
				$counter ++;
				$objWorkSheet->getStyle('A'.$counter.':E'.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
				//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
				$objWorkSheet->setCellValue('A'.$counter, '  Campaign Code  ');
				$objWorkSheet->setCellValue('B'.$counter, '  Campaign Name ');
				$objWorkSheet->setCellValue('C'.$counter, '  Desktop Hits  ');
				$objWorkSheet->setCellValue('D'.$counter, '  Mobile Hits  ');
				$objWorkSheet->setCellValue('E'.$counter, '  Total Hits  ');
				
				$j = '3';
				foreach($getallwidget as $bd)
				{
					$j++;			
					//$objWorkSheet->setCellValue('A'.$j, $bd->domain_name);
					$objWorkSheet->setCellValue('A'.$j, $bd->promo_code);
					$objWorkSheet->setCellValue('B'.$j, $bd->widget_name);
					if($bd->Number_of_clicks_desktop=='')$bd->Number_of_clicks_desktop=0;
					if($bd->Number_of_clicks_mobile=='')$bd->Number_of_clicks_mobile=0;
					$objWorkSheet->setCellValue('C'.$j, $bd->Number_of_clicks_desktop);
					$objWorkSheet->setCellValue('D'.$j, $bd->Number_of_clicks_mobile);
					$objWorkSheet->setCellValue('E'.$j, $bd->TotalClick);			
				}
				$objPHPExcel->setActiveSheetIndex($i)->setTitle($getallwidget[0]->domain_name);
				$i++;
			}
			
		}
		if($i==0)
		{
			//echo "hey"; exit;
			$objWorkSheet->setCellValue('A1', 'No Records Found');
		}
		//$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Brand-Campaign-Report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	//==== Function for Most Active IP Hits Report
	public function mostip_hits_report()
	{
		$daterange =($_COOKIE['daterange']=="") ?   date("F j, Y", strtotime("-29 days"))." - ".date("F j, Y") 
												:   $_COOKIE['daterange']; 
		
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		$objPHPExcel 	= new PHPExcel();
		
		$styleArray = array(
							'borders' => array(
								'outline' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							)
						);
		$max_col = 'F';
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight('20');
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight('17');
		$counter = 1;
		$objWorkSheet->mergeCells("A".$counter.":".$max_col.$counter);
		$objWorkSheet->getStyle("A".$counter.":".$max_col.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objWorkSheet->setCellValue('A'.$counter, 'Most Active IP Hits Report ('.$daterange.')');
		$counter ++;
		$objWorkSheet->getStyle('A'.$counter.':'.$max_col.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
		$objWorkSheet->setCellValue('A'.$counter, '  ');
		$objWorkSheet->setCellValue('B'.$counter, '  IP ');
		$objWorkSheet->setCellValue('C'.$counter, '  Campaign Name  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Campaign Code  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Count  ');
		$objWorkSheet->setCellValue('F'.$counter, '  Last Hit Date  ');
		
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);
		$getallwidget	= $this->archive_model->getMostipHitsReport	($daterange);
		
		if(!empty($getallwidget))
		{			
			foreach((array)$getallwidget as $value)
			{
				$counter ++;				
				$objWorkSheet->setCellValue("B".$counter,$value->ip);
				$objWorkSheet->setCellValue('C'.$counter, $value->title.'('.$value->hash_key.')');
				$objWorkSheet->setCellValue('D'.$counter, $value->promo_code);
				if(!$value->total_click)
					$value->total_click = '0';
				$objWorkSheet->setCellValue('E'.$counter, $value->TotalClick);
				$objWorkSheet->setCellValue('F'.$counter, $value->LastclickTime);
				
				//$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);				
			}
			$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$max_col.$counter)->applyFromArray($styleArray);
			
			//set Col A and merege
			$objWorkSheet->mergeCells("A3:A".$counter);
			$objWorkSheet->getStyle("A3:A".$counter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$objWorkSheet->setCellValue("A3", $value->type);			
		}
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Most Active IP Hits Report');		
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Most-Active-IP-Hits-Report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}