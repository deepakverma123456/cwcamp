<?php include("./application/views/common/template-top.php");
//echo base64_encode(base64_encode(base64_encode('123456')));die;?>

<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />
<!-- page content -->
<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2><?php echo $title;?></h2>
		<form action="<?php echo base_url(); ?>report/exportToExcelBrandWiseCampaignReport" id="brandform" method="post" >    
			<ul class="nav navbar-right panel_toolbox">		 
				<li>
					<button type="input" name="submit" id="submit" value="newAccount" class="btn btn-warning btn-sm"><i class="fa fa-download"></i> Download Complete Report</button>
					<button class="btn btn-warning btn-sm loadForm" type="button" id="<?php echo base_url(); ?>report/brand_campaign_report" name="#myModal">Change Brand</button>
				</li>
				<input type="hidden" name="brandId" id="brandId" value="<?php echo implode(",",$_POST['brandId']);?>">
			</ul>
		</farm>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<table id="datatable" class="table table-striped table-bordered bulk_action">
		  <thead>
			<tr>
			  <th>S. No</th>
				<th>Brand Name</th>			 
				<th>Mobile Click</th>
				<th>Desktop Click</th>
			  <th>Total Click</th>	
			</tr>
		  </thead>
		  <tbody>
					<?php				
					if(count($result)>0)
					{
							$i = 1;	
							foreach($result as $row)
							{								
					?>
					<tr>
					  <td><?php echo $i;?></td>
						<td ><?php  echo $row->domain_name;?></td>
						<td align="right"><?php  echo $row->Number_of_clicks_mobile;?></td>
						<td align="right"><?php  echo $row->Number_of_clicks_desktop;?></td>
					  <td align="right"><?php  echo $row->TotalClick;?></td>	
					</tr>
					 <?php $i++; }}	else { ?>
							<tr> 
								<td colspan="8"> No Record Available.</td>
							</tr>
						 <?php
						} ?>
		  </tbody>
		</table>
		
	  </div>
	</div>
  </div>
</div>

<!-- /page content -->
<?php include("./application/views/common/template-bottom.php"); ?> 
<script type='text/javascript' src="<?php echo base_url(); ?>/assets/js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript'>
 $(document).ready(function() {
	$('#datatable').DataTable();
});
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container">
            <div class="row" id="loadData">
                            
            </div>
        </div>
    </div>
</div>
