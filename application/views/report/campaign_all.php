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
		<ul class="nav navbar-right panel_toolbox">		  
			<li><div id="camp" style="display: none;"><button class="btn btn-warning btn-sm" type="button" id="prod-report" ><i class="fa fa-download"></i> Download Product Report</button></div>
			</li>
			<li><button class="btn btn-warning btn-sm loadForm" type="button" id="<?php echo base_url(); ?>report/brand_campaign_report" name="#myModal">Brand Campaign Report</button>
			</li>
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		 <form name="form1" id="form1" action="" method="post">
		<table id="datatable" class="table table-striped table-bordered bulk_action">
		  <thead>
			<tr>
			  <th>#<!--<input type="checkbox" onclick="Select_all();" name="selectall" id="selectall" title="click here to select or deselect all" />--></th>
				<th>Brand Name</th>
			  <th>Campaign Code</th>
			  <th>Campaign Name</th>
			  <th>Hits Count</th>	
				<th>Last Clicked</th>
				<th>Action</th>
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
					  <td><input type="checkbox" name="campId[]" id="campId<?php echo $row->id;?>" onclick="getCheckboxvalues(this.value);" value="<?php echo $row->id;?>" /></td>
						<td><?php  echo $row->domain_name;?></td>
					  <td ><?php if($row->promo_code) echo $row->promo_code; else echo '';?></td>
					  <td ><?php  echo $row->widget_name;?></td>
					  <td align="right"><span style="text-decoration:underline"><a id="<?php echo base_url(); ?>report/list_all_campaign?widget_id=<?php  echo $row->widget_id;?>" href="javascript:void(0)" class="loadForm" name="#myModal"><?php  echo $row->TotalClick;?></a></span></td>					
						<td align="right"><?php echo date("M d,Y", strtotime($row->LastclickTime));?></td>
						<td ><a href="<?php echo base_url('report/exportToExcelCampaignReport?widgetId='.$row->widget_id);?>"><button class="btn btn-warning btn-xs fa fa-download" title="Export in excel" type="button" id="<?php echo $row->widget_id;?>" name="<?php echo $row->widget_id;?>"></button></a></td>	
					</tr>
					 <?php $i++; }}	else { ?>
							<tr> 
								<td colspan="8"> No Record Available.</td>
							</tr>
						 <?php
						} ?>
		  </tbody>
		</table>
		</form>
	  
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

$(document).ready(function(){	
    $('input[type="checkbox"]').click(function(){			
		  if($('input:checkbox:checked').length=='0')
			{
				$('#camp').fadeOut('slow');
			}
			else
			{
				$('#camp').fadeIn('slow');
			}
    });
		$('#prod-report').click(function()
		{
			
			var hidval = $('#widget_id').val();
			if(hidval!='')
			{
					//submit the form and generate report
					$('#wid-form').submit();
					
			}
			
		});
});
//function Select_all()
//{
//	if ($('#selectall').is(':checked')) {
//		//check all widgets
//		$('input[name="campId[]"]').prop('checked',true);
//		
//     $('input[name="campId[]"]').each(function() {
//       getCheckboxvalues($(this).val());
//     });
//	}
//	else
//	{
//			$('input[name="campId[]"]').removeAttr('checked');
//			
//			$('input[name="campId[]"]').each(function() {
//				getCheckboxvalues($(this).val());
//			});
//	}
//	
//}
function getCheckboxvalues(id) {
	
	var arr = [];
	var hidval = $('#widget_id').val();
	if( hidval !== '')
	  arr = hidval.split(",");
	
	var index = arr.indexOf(id);
	if (index > -1) 
	  arr.splice(index, 1);
	else
	  arr.push(id);
	
	$('#widget_id').val(arr);
}
</script>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container">
					<form id="wid-form" action ="<?php echo base_url(); ?>report/product_campaign_report" method="POST">
							<div class="row" id="loadData">
							<input type="hidden" value="" name="widget_id" id="widget_id">
					</form>            
            </div>
        </div>
    </div>
</div>