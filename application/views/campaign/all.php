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
		  <li><a href="<?php echo base_url(); ?>campaign/create" ><button class="btn btn-warning btn-sm">Create Campaign</button></a></li>
		  <!--<li><button class="btn btn-warning btn-sm loadForm" type="button" id="<?php echo base_url(); ?>campaign/products_list_all" name="#myModal">Create Campaign</button>
</li>-->
		</ul>
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">
		<table id="datatable" class="table table-striped table-bordered bulk_action">
		  <thead>
			<tr>
			  <th>Campaign Code</th>
			  <th>Campaign Name</th>
			  <th>Product Name</th>
			  <th>Mapped URL</th>
			  <th>Created Date</th>
			  <th>Created By</th>
			  <th>Action</th>
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
									//echo "<pre>";print_r($result);die;
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
					<tr >
					  
					  <td >
<div class="wrapper"><?php if($row->promo_code) echo $row->promo_code; else echo '';?><div class="tooltip" data-toggle="tooltip" ><?php echo base_url(); ?>deploy/campaign?hkey=<?php echo $row->hash_key; ?></div></div>

</td>
					  <td><?php  echo $row->widget_name;?></td>
					  <td>
						 <div class="wrapper"><div id="showImage_<?php echo $i;?>" style="cursor: pointer;" onmouseover="return showImageOnHover(<?php echo $i;?>,'<?php echo $row->image_url;?>');"><?php  echo $row->title;?><br/><span class="h6">(<?php  echo $row->brand;?>)</span></div>
						  <div  class="tooltip" data-toggle="tooltip" style="margin-left: 30px;"><div style="margin-left: 90px;"><i style="margin-right: 100px;" class="btn fsubmit"></i></div><img id="imgPopup_<?php echo $i;?>" alt="" height="200px" width="200px" style="opacity: 0;"/>
						  </div>
						 </div>
						 </td>
					  <td ><a href="<?php echo $row->buy_now_url;?>" target="_blank" >View</a> </td>
						<td ><?php echo date("M d,Y", strtotime($row->created_on));?></td>
						<td ><?php echo $row->name;?></td>
					  
					  <td><a class="btn btn-warning btn-xs fa fa-edit" href="<?php echo base_url(); ?>campaign/update?product_id=<?php echo $row->product_id; ?>&cid=<?php echo $row->widget_id; ?>" class="btn"></a></td>
					   
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
	$('#datatable').dataTable( {
  "ordering": false
} );
});
 
 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
function showImageOnHover(id,imgUrl)
{
	if($('#imgPopup_'+id).css("opacity")=="1")
	{
	$(".fsubmit").hide();
	return false;
	}
	else
	{
	$(".fsubmit").show();
	}

	$('#imgPopup_'+id).one('load', function() {
	 
		setTimeout(function()
		{
			$(".fsubmit").hide();
			$('#imgPopup_'+id).css("opacity", "1");
		}, 2000);

	}).attr('src', imgUrl);
}
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