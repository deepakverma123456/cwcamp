<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />
<!-- page content -->
<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2>Manage Campaigns</small></h2>
		<ul class="nav navbar-right panel_toolbox">
		  <li><a href="<?php echo base_url(); ?>campaign/create" class="btn">Create Campaign</a></li>
		  <!--<li><button class="btn btn-warning btn-sm loadForm" type="button" id="<?php echo base_url(); ?>campaign/products_list_all" name="#myModal">Create Campaign</button>
</li>-->
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<table id="datatable" class="table table-striped table-bordered bulk_action">
		  <thead>
			<tr>
			  <th><input type="checkbox" id="check-all" class="flat"></th>
			  <th>Name</th>
			  <th>Product title</th>
			  <th>Promo Code</th>
			  <th>Start date</th>
			  <th>Target url</th>
			</tr>
		  </thead>
		  <tbody>
			<?php if(count($result)>0)
							    {?>
						            <?php 
									$limit = $_REQUEST['per_page'];
									if($limit=='')
									{
										$i=1;
									}
									else
									{
										$i=$limit+1;
									}
									foreach($result as $row)
									{
										if($row->status =='0')
										{
											$status = "Inactive";
										}
										else
										{
										    $status = "Active";
										}
									?>
					<tr>
					  <td><input type="checkbox" class="flat" name="table_records"></td>
					  <td ><?php  echo $row->widget_name;?></td>
					  <td ><?php  echo $row->title;?></td>
					  <td ><?php if($row->promo_code) echo $row->promo_code; else echo '';?></td>
					  <td ><?php echo date("M d,Y", strtotime($row->created_on));?></td>
					  <td ><a href="<?php echo $row->buy_now_url;?>" target="_blank" >View</a></td>
					</tr>
					 <?php $i++;
							        }?>
					     <?php }
								else
								{ ?>
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