<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {
	
	public function __construct()
	{        
	    parent::__construct();
		$this->load->model('permission_model');
        $this->load->model('user_model');
		$this->load->model('campaign_model');
		$this->load->model('report_model');
		$this->load->helper('custom_helper');
    }
	
	//==== Function for all campaign report
	public function campaign_all()
	{	
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'Campaign Report';
		if($this->session->userdata('id')=='')
		{
			$data['result'] = $this->report_model->getall_campaign();		
			$this->load->view('report/campaign_all',$data);
		}
		else
		{
			$this->load->view('/');
		}		
	}
	
	//==== Function for all campaign report
	function exportToExcelCampaignReport($widgetId) 
	{	
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
		
		//==== Create new PHPExcel object
		$objPHPExcel 	= new PHPExcel();
		$widgetId 	 	= $_REQUEST['widgetId']; 		
		$params 		= array('num' => $config['per_page'], 'offset' => $this->data['page']);
		$getallwidget 	= $this->report_model->getallcampaign($widgetId);
		$getwidget 		= $this->report_model->getCampaignUrlWise($widgetId);
			

		foreach($getallwidget as $bd)
		{  
			$totalcount =  $totalcount+ $bd->TotalClick;
		}		
		
		$sheettitle="Product Title: ".$bd->title; 
		$sheettitle.="\nPromo Code: ".$bd->promo_code;
		$sheettitle.="\nHKEY: ".$bd->hash_key;		 
		$sheettitle.="\nTotal Click: ".$totalcount;	

		//==== Add second sheet
		
		$objWorkSheet 		= $objPHPExcel->createSheet(1);
		$sheet 				= $objPHPExcel->getActiveSheet(1);  
		$counter 			= 1;
		
		$objWorkSheet->getStyle('A'.$counter.':E'.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		$objWorkSheet->setCellValue('A'.$counter, '  Campaign URL.  ');
		$objWorkSheet->setCellValue('B'.$counter, '  Duration ');
		$objWorkSheet->setCellValue('C'.$counter, '  Desktop  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Mobile  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Total Clicks  ');	

		foreach($getwidget as $row)
		{   
			$counter++;
			$fromDate 	= date_create($row->fromDate);
			$toDate  	= date_create($row->toDate);
			//	date_format($date,"y-M-DD");
			$objWorkSheet->setCellValue('A'.$counter, $row->target_url);
			$objWorkSheet->setCellValue('B'.$counter, date_format($fromDate,"d").'-'.date_format($fromDate,"M").'-'.date_format($fromDate,"y").' To '.date_format($toDate,"d").'-'.date_format($toDate,"M").'-'.date_format($toDate,"y"));
			if($row->DektopClick=='')$row->DektopClick=0;
			if($row->MobileClick=='')$row->MobileClick=0;
			$objWorkSheet->setCellValue('C'.$counter, $row->DektopClick);
			$objWorkSheet->setCellValue('D'.$counter, $row->MobileClick);
			$objWorkSheet->setCellValue('E'.$counter, $row->TotalClick);			
		}
		$objPHPExcel->setActiveSheetIndex(1)->setTitle('URL-Wise Hits');
		
		//==== Add first sheet
		

		$objWorkSheet 		= $objPHPExcel->createSheet(0);
		$sheet 				= $objPHPExcel->getActiveSheet(0);  
		$counter 			= 1;
		
		$objWorkSheet->setCellValue('A1', $sheettitle);
		$objWorkSheet->getStyle('A1')->getAlignment()->setWrapText(true);
		$objWorkSheet->getColumnDimensionByColumn('A1')->setWidth('40');
		$objPHPExcel->setActiveSheetIndex()->mergeCells('A1:E1');
		$objWorkSheet->getColumnDimensionByColumn('B1')->setWidth('20');
		$objWorkSheet->getRowDimension('1')->setRowHeight('60');
		$objWorkSheet->getRowDimension('2')->setRowHeight('15');
		$counter 			= 2;
		$objWorkSheet->getStyle('A'.$counter.':E'.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		$objWorkSheet->setCellValue('A'.$counter, '  Campaign URL.  ');
		$objWorkSheet->setCellValue('B'.$counter, '  Click On Date ');
		$objWorkSheet->setCellValue('C'.$counter, '  Desktop  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Mobile  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Total Clicks  ');			 
		
		foreach($getallwidget as $bd)
		{   
			$counter++;
			$date=date_create($bd->created_by);
			//	date_format($date,"y-M-DD");
			$objWorkSheet->setCellValue('A'.$counter, $bd->target_url);
			$objWorkSheet->setCellValue('B'.$counter, date_format($date,"d").'-'.date_format($date,"M").'-'.date_format($date,"y"));
			if($bd->DektopClick=='')$bd->DektopClick=0;
			if($bd->MobileClick=='')$bd->MobileClick=0;
			$objWorkSheet->setCellValue('C'.$counter, $bd->DektopClick);
			$objWorkSheet->setCellValue('D'.$counter, $bd->MobileClick);
			$objWorkSheet->setCellValue('E'.$counter, $bd->TotalClick);			
		}					
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Date-Wise Hits');
		






































		$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Campaign-Analytical-'.$widgetId.'.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	
	//==== Function for list user brand
	public function brand_campaign_report()
	{		
		$data['title'] = 'Brand List';			
		if(!empty($_REQUEST['id']))
		{
			if($_REQUEST['id'] == "All")
			{
				$brands = $this->report_model->get_user_brands();
				echo '<div class="form-group">';	
							if(count($brands)>0)
							{
								$i = 1;
								foreach($brands as $brand)
								{						
							echo '<div class="col-md-4 col-sm-4 col-xs-12">
								<label style="text-align: left; width: 100%;margin:5px;" for="brandId'.$i.'" class="btn btn-info"> '.$brand->domain_name.'<input type="checkbox" class="badgebox" name="brandId[]" id="brandId'.$i.'" value="'.$brand->id.'"><span class="badge">&check;</span></label>
							</div>';
							$i++; }}else{ 
							echo '<div class="col-md-12 col-sm-12 col-xs-12">
								Brand not found.
							</div>';					
							 } 						
					echo '</div>';
				exit;
			}
			else
			{
				$brands = $this->report_model->get_user_brands($_REQUEST['id']);
				echo '<div class="form-group">';	
							if(count($brands)>0)
							{
								$i = 1;
								foreach($brands as $brand)
								{						
							echo '<div class="col-md-4 col-sm-4 col-xs-12">
								<label style="text-align: left; width: 100%;margin:5px;" for="brandId'.$i.'" class="btn btn-info"> '.$brand->domain_name.'<input type="checkbox" class="badgebox" name="brandId[]" id="brandId'.$i.'" value="'.$brand->id.'"><span class="badge">&check;</span></label>
							</div>';
							$i++; }}else{ 
							echo '<div class="col-md-12 col-sm-12 col-xs-12">
								Brand not found.
							</div>';					
							 } 						
					echo '</div>';
				exit;
			}
		}
		else
		{
			$data['brands'] = $this->report_model->get_user_brands();
			$this->load->view('report/brand_campaign_report',$data);
		}
	}
	
	//==== Function for all campaign report
	public function view_brand_list()
	{
		$brandIds = implode(",",$_POST['brandId']);
		$data['menu']  = $this->user_model->get_menus();
		$data['title'] = 'Brand Campaign Report';
		if(!empty($brandIds))
		{
			$data['result'] = $this->report_model->getallbrandcampaign($brandIds);						
			$this->load->view('report/view_brand_list',$data);
		}
		else
		{
			$this->load->view('report/view_brand_list',$data);
		}		
	}
	
	//==== Function for all campaign report
	function exportToExcelBrandWiseCampaignReport() 
	{
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		$brandIds = $this->input->post('brandId');
		$brands = explode(',',$brandIds);
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		
		//==== Create new PHPExcel object
		$objPHPExcel 	= new PHPExcel();
		
		//create overall summary of brand report
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight('20');
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight('17');
		$counter = 1;
		$objWorkSheet->mergeCells("A".$counter.":E".$counter);
		$objWorkSheet->getStyle("A".$counter.":E".$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objWorkSheet->setCellValue('A'.$counter, 'Brand Wise Report');
		$counter ++;
		$objWorkSheet->getStyle('A'.$counter.':E'.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
		$objWorkSheet->setCellValue('A'.$counter, '  Brand Name ');
		$objWorkSheet->setCellValue('B'.$counter, '  Total Campaigns ');
		$objWorkSheet->setCellValue('C'.$counter, '  Total Desktop Hits  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Total Mobile Hits  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Total Hits  ');
		$counter ++;
		
		$getallwidget_totals 	= $this->report_model->TotalBrandWiseReport($brandIds);		
		foreach($getallwidget_totals as $getallwidget_total)
		{
			$counter++;
			$objWorkSheet->setCellValue('A'.$counter, $getallwidget_total->domain_name );
			$objWorkSheet->setCellValue('B'.$counter, $getallwidget_total->total_campaigns);
			if(!$getallwidget_total->total_desktop_click)
				$getallwidget_total->total_desktop_click = '0';
			$objWorkSheet->setCellValue('C'.$counter, $getallwidget_total->total_desktop_click);
			if(!$getallwidget_total->total_mobile_click)
				$getallwidget_total->total_mobile_click = '0';
			$objWorkSheet->setCellValue('D'.$counter, $getallwidget_total->total_mobile_click);
			if(!$getallwidget_total->total_click)
				$getallwidget_total->total_click = '0';			
			$objWorkSheet->setCellValue('E'.$counter, $getallwidget_total->total_click);			
		}
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Brand Report');
		
		//looop the sheet brand wise
		$i=1;
		foreach((array)$brands as $val)
		{
			$getallwidget 	= $this->report_model->getbrandWiseExport($val);
			
			if(count($getallwidget)>0)
			{
				//if($i>1)
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
		//$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Brand-Wise-Campaign-Analytical.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
	/**
	* Function product_campaign_report
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
	function product_campaign_report()
	{
		ini_set('max_execution_time', 600); //600 seconds = 10 minutes
		//==== If session has expired, redirect on ligin page
		if($this->session->userdata('user_id') == '')
		{ 
			redirect(base_url()); 
		}
		$widgetIds = $this->input->post('widget_id');
		$widgets = explode(',',$widgetIds);
		
		ob_end_clean();
		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
		//==== Include PHPExcel 
		require_once 'PHPExcel.php';
		
		//==== Create new PHPExcel object
		$objPHPExcel 	= new PHPExcel();
		
		$styleArray = array(
							'borders' => array(
								'allborders' => array(
									'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							)
						);
		//currentmonth
		
		$curmonth =  12; //intval($now->format('m')); //12
		$azRange = array('F','G','H','I','J','K','L','M','N','O','P','Q');
		$max_col = $azRange[$curmonth-1]; 
		
		
		
		$objWorkSheet = $objPHPExcel->getActiveSheet(0);
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('1')->setRowHeight('20');
		$objPHPExcel->setActiveSheetIndex(0)->getRowDimension('2')->setRowHeight('17');
		$counter = 1;
		$objWorkSheet->mergeCells("A".$counter.":".$max_col.$counter);
		$objWorkSheet->getStyle("A".$counter.":".$max_col.$counter)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objWorkSheet->setCellValue('A'.$counter, 'Campaigns Report');
		$counter ++;
		$objWorkSheet->getStyle('A'.$counter.':'.$max_col.$counter)->getFill()->applyFromArray(array('type'=>PHPExcel_Style_Fill::FILL_SOLID,'startcolor' => array('rgb' => 'FFFF00'),));
		//$objWorkSheet->setCellValue('A'.$counter, '  Brand Name  ');
		$objWorkSheet->setCellValue('A'.$counter, '  ');
		$objWorkSheet->setCellValue('B'.$counter, '  Brands ');
		$objWorkSheet->setCellValue('C'.$counter, '  Campaign Name  ');
		$objWorkSheet->setCellValue('D'.$counter, '  Total Hits  ');
		$objWorkSheet->setCellValue('E'.$counter, '  Duration  ');
		
		for ($i=0; $i<12; $i++) {
			$month = date('F', strtotime("-$i month")); 
			$year = date('Y', strtotime("-$i month"));
			$m = intval(date('m', strtotime("-$i month"))).'-'.$year;
			$new_array[$m] = $azRange[$i];
			$objWorkSheet->setCellValue($azRange[$i].$counter, $month.' - '.$year);
		}
		//a($new_array); exit;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);
		$getallwidget	= $this->report_model->getProductWiseReport($widgetIds);
		
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
			$brand_count = $occurences[$value->id_domain];
			$count = $brand_count + $counter-1; 
			if($brand_count>1 && in_array($value->id_domain,$brand_unique))
			{
				//set Col B and merege
				$objWorkSheet->mergeCells("B".$counter.":B".$count);
				$objWorkSheet->getStyle("B".$counter.":B".$count)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				$objWorkSheet->setCellValue("B".$counter,$value->domain_name);
				if(($key = array_search($value->id_domain, $brand_unique)) !== false) {
					$brand_unique[$key] = '';
					
						unset($brand_unique[$key]);
					}
				
			}
			elseif(in_array($value->id_domain,$brand_unique) && $brand_count=='1')  {
				$objWorkSheet->setCellValue("B".$counter,$value->domain_name);
				
			}
			
			
			$objWorkSheet->setCellValue('C'.$counter, $value->title.'('.$value->hash_key.')');
			if(!$value->total_click)
				$value->total_click = '0';
			$objWorkSheet->setCellValue('D'.$counter, $value->TotalClick);
			$objWorkSheet->setCellValue('E'.$counter, $value->start_date.' - '.$value->end_date);
			$get_monthly_hits = $this->user_model->getcampaign_monthly_value($value->widget_id);
			//a($get_monthly_hits); exit;
			foreach((array)$get_monthly_hits as $res)
			{
				$objWorkSheet->setCellValue($new_array[$res->month.'-'.$res->y].$counter, $res->TotalClick);
			}
			$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':'.$max_col.$counter)->applyFromArray($styleArray);
			
			
		}
		
		
		//set Col A and merege
		$objWorkSheet->mergeCells("A3:A".$counter);
		$objWorkSheet->getStyle("A3:A".$counter)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objWorkSheet->setCellValue("A3", 'Campaigns');
		
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(17);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		
		
		$objPHPExcel->setActiveSheetIndex(0)->setTitle('Campaigns Report');
		
		//$objPHPExcel->getSheetByName('Worksheet')->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Brand-Wise-Campaign-Analytical.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		
	}
	
	//==== Function for get last 100 campaign
	public function list_all_campaign()
	{		
		$data['title'] = 'Campaign List';
		if(!empty($_REQUEST['widget_id']))
		{
			$data['result'] = $this->report_model->getall_campaign_list($_REQUEST['widget_id']);		
			$this->load->view('report/list_all_campaign',$data);
		}
		else
		{
			$this->load->view('/');
		}		
	}
}