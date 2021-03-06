<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />

<style>
.tab-content{
	border-bottom: 1px solid #ddd;
	border-left: 1px solid #ddd;
	border-right: 1px solid #ddd;
	padding: 4px 4px 33px;
}
#datatable_filter{
	height: 24px;
}
#datatable_filter input[type='search']{
	float: right;
    height: 21px;
    width: 73%;
	border:1px solid #ddd;
}
.pimg-thumb{
	border: 1px solid #d8d8d8;
    margin: 0 10px 0px 5px;
    padding: 3px;
	width: 50px;
	height:50px;
	border-radius: 50%;
}
.txt-small{
	padding: 6px !important;
	font-size:13px;
}
#copycode{
  background-color: #677c91;
    border-radius: 0 0 4px 4px;
    color: #fff;
    font-size: 12px;
    padding: 0 2px;
    position: absolute;
    right: 6px;
  
}
#textarea_code{
  width: 355px; height: 82px;
  padding: 5px;
}
</style>
<!-- page content -->
<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
		
	  <div class="x_panel">
		
		  <div class="x_title">
			<h2>Update Campaign</small></h2>&nbsp;&nbsp;&nbsp;&nbsp;
				
			<div class="clearfix"></div>
		  </div>

		<div class="x_content">
			<div class="row" >
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="col-md-11 col-sm-11 col-xs-12 pull-left" style="padding-left:0px;margin-bottom:10px">
					<div class="tabs" id="tabs">
						<ul class="nav nav-tabs">
							<li class="active"><a href="#tabs-1" data-toggle="tab">Brand Products</a></li>
						</ul>
					</div>
					<div class="tab-content">
						<div id="tabs-1" class="tab-pane fade in active">
							<table id="datatable" class="table table-striped table-bordered bulk_action">
								<thead>
										<tr><th align="center"><?php echo @$result[0]->brand ?></th></tr>
								</thead>
								<tbody>
								<?php if(count($result)>0)
									 {
									 //echo "<pre>";print_r($result);die;
									 foreach($result as $row)
									 {
									 ?>
								<tr>
								   <td >
										<div class="wrapper"><div class="pull-left">
										<img class="pimg-thumb"  src="<?php echo $row->image_url;?>" alt="Product Image" title="<?php echo $row->title;?>" /></div><div class="tooltip" data-toggle="tooltip" style="margin-left: 20px;"><img src="<?php echo $row->image_url;?>" alt="" height="200px" width="200px"/></div></div>
									   <div class="pull-left"><?php echo $row->title;?>
									   (<?php echo $row->prd_sku;?>)
									   <br/><?php echo $row->category.' / '.$row->sub_category;?></div>
									   <div class="pull-right"  title="<?php echo $row->title;?>" ><input type="radio" name="product_id" value="<?php echo $row->id;?>" checked="checked" ></div>
								   </td>
								</tr>
								<?php $i++;
										   }?>
								<?php }
									   else
									   { ?>
									 <tr> 
									   <td>Sorry, no product available for the selected brand.</td>
									 </tr>
									<?php
								   } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				</div>
			   <div id="campaign_from" class="col-md-6 col-sm-6 col-xs-12  pull-right" style="min-height: 500px; border-left:1px dotted #d4d4d4;">
					<div class="col-md-11 col-sm-11 col-xs-12 pull-right">
				<div class="tabs" id="tabs">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tabs-1" data-toggle="tab">Campaign Details</a></li>
					</ul>
				</div>
				<div class="tab-content">
					<div id="tabs-1" class="tab-pane fade in active">
					  
					  <form action="" id="update_campaign_form" method="post">
						<div id="msg"></div>
						
						<div id='inner_campaign_div'>
						  <div class="modal-body">
							
							  <div class="form-group col-md-7 col-sm-7 col-xs-12 nopadding-lr">
								<label for="userCampaign">Campaign Name<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
								<input type="text" class="form-control" required="" name="campaign_name" value="<?php echo $result[0]->widget_name; ?>">
							  </div>
							  <div class="form-group col-md-5 col-sm-5 col-xs-12 pull-right nopadding-r">
								<label for="userEmail">Code<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
								<input type="text" readonly="readonly" class="form-control" required="" name="promo_code" id="promo_code" value="<?php echo $result[0]->promo_code; ?>">
								<a href="#" class="help-block" id="promo_code_generate" ><span></span> Generate Code</a>
								<!--<div class="btn1 fsubmitcode col-xs-12"></div>-->
							  </div>
					  
							  <div class="form-group">
								<label for="userCampaignUrl">Choose a retailer<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
								<select name="retailer_id" id="retailer_id" class="form-control">
								  <option value="">-- Select --</option>
								  <?php foreach($retailers as $val){ ?>
								  <option value="<?php echo $val['retailer_id']; ?>" <?php if($val['retailer_id']==$result[0]->retailrid){ ?> selected="selected" <?php } ?>><?php echo $val['retailer_name']; ?></option>
								  <?php } ?>
								</select>				  
							  </div>
					  
							<div class="form-group">
							  <label for="userCampaignUrl">Retailer Url<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
							  <input type="text" class="form-control" required="" name="userCampaignUrl" id="userCampaignUrl" value="<?php echo $result[0]->buy_now_url; ?>" />
							  <span class="help-block" id="promotion_final">Enter a retailer URL, where the campaign will redirect.</span>
							</div>
						</div>
							<div class="modal-footer">
							  <input type="hidden" name="campaign_id" value="<?php echo $result[0]->widget_id;?>">
								<input type="hidden" name="productid" value="<?php echo $retailers[0]['product_id']?>">
								<input type="hidden" name="domain_id" value="<?php echo $retailers[0]['domain_id']?>">
								<input type="hidden" name="brand" id="brand" value="<?php echo $retailers[0]['domain_name'];?>">
								<div class="btn fsubmit_campaign col-xs-12"></div>
								<a href="<?php echo base_url(); ?>campaign/all"><button type="button" class="btn btn-default btn-icon pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button></a>
								<button type="input" name="submit_campaign" id="submit_campaign"  value="newAccount" class="btn btn-success btn-icon" ><i class="fa fa-check"></i> Update Campaign</button>
							</div>
						</div>
					</form>
					  
					  <div id="model_link" class="text_warning">Note: Users are recommended to add sanitized URL. <a id="<?php echo base_url(); ?>campaign/how_it_work" href="javascript:void(0)" class="loadForm" name="#myModal">Click to explore</a><div></div></div>
					
			</div>
					</div>
				</div>
			   </div>
			</div>	
		</div>
		
		
		
		
	</div>	
  </div>
</div>

<!-- /page content -->
<?php include("./application/views/common/template-bottom.php"); ?> 
<script type='text/javascript' src="<?php echo base_url(); ?>/assets/js/dataTables.bootstrap.min.js"></script>
<script type='text/javascript'>
 $(document).ready(function() {
	$('#datatable').DataTable({
		"bLengthChange": false,
		"bFilter": false,
		"bInfo": false,
		"bAutoWidth": false,
		"bPaginate": false,
		"ordering": false,
		"pageLength": 5,
		"pagingType": "numbers",
		"oLanguage": { "sSearch": "" } 
	});
	//$('.dataTables_filter, .dataTables_filter label').addClass('pull-left');
});
 
 $(function () {
  $('[data-toggle="tooltip"]').tooltip()
}) 
</script>
<script>
$(document).on("click",".loadForm",function(e){
	var modalId = this.name;
	var url         = this.id;
	$.get(url, function( data ) {
			$("#loadData").html(data);
			$(modalId).modal('show');
		});
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

<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/update-campaign-validation.js"></script>