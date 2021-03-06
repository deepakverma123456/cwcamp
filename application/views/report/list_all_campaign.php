<!-- page content -->
<div class="right_col" role="main">
	<div class="col-md-9 col-sm-9 col-xs-12" id="holder">
        <span class="notify">the notification</span>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
			 <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
		<h2><b><?php echo $result[0]->widget_name; ?>(<?php echo $result[0]->promo_code; ?>)</b></small>
		<?php if(count($result)>99){ ?><br /><div class="small"><p class="text-danger">showing last 100 records</p></div><?php } ?>
		</h2>
			
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">		
		<table id="datatable2" class="table table-striped table-bordered bulk_action">
					<thead>
					<tr>
						<th>S. No</th>
						<th>IP Address</th>			  
						<th>Last Clicked</th>
						<th>Device</th>
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
								<td ><?php if($row->ip) echo $row->ip; else echo '';?></td>
								<td ><?php echo date("M d,Y", strtotime($row->time_loaded));?></td>
								<td ><?php echo $row->device_version;?></td>
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
<script type='text/javascript'>
 $(document).ready(function() {
	//$('#datatable2').DataTable();
	$('#datatable2').dataTable( {
		"bInfo" : false,
  "searching": false
} );
});
</script>
