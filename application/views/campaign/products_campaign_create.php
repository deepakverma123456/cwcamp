<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/create-campaign-validation.js"></script> 
					
<div class="col-md-12 col-sm-12 col-xs-12 pull-right">
	<div class="tabs" id="tabs">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#tabs-1" data-toggle="tab">Campaign Details</a></li>
		</ul>
	</div>
	<div class="tab-content">
		<div id="tabs-1" class="tab-pane fade in active">
			<form action="" id="campaign_form" method="post">
			  <div id="msg"></div>
			  <div id='inner_campaign_div'>
				<div class="modal-body">
					<div class="form-group col-md-7 col-sm-7 col-xs-12 nopadding-lr">
					  <label for="userCampaign">Campaign Name<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
					  <input type="text" class="form-control" required="" name="campaign_name" value="">
					</div>
					<div class="form-group col-md-5 col-sm-5 col-xs-12 pull-right nopadding-r">
					  <label for="userEmail">Code<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
					  <input type="text" readonly="readonly" class="form-control" required="" name="promo_code" id="promo_code" value="">
					  <a href="#" class="help-block" id="promo_code_generate" ><span></span> Generate Code</a>
					  <!--<div class="btn1 fsubmitcode col-xs-12"></div>-->
					</div>

					<div class="form-group">
					  <label for="userCampaignUrl">Choose a retailer<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
					  <select name="retailer_id" id="retailer_id" class="form-control">
						<option value="">-- Select --</option>
						<?php foreach($retailers as $val){ ?>
						<option value="<?php echo $val['retailer_id']; ?>"><?php echo $val['retailer_name']; ?></option>
						<?php } ?>
					  </select>				  
					</div>

					<div class="form-group">
					  <label for="userCampaignUrl">Retailer Url<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
					  <input type="text" class="form-control" required="" name="userCampaignUrl" id="userCampaignUrl" value="" readonly />
					  <span class="help-block" id="promotion_final">Enter a retailer URL, where the campaign will redirect.</span>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" name="productid" value="<?php echo $retailers[0]['product_id']?>">
					<input type="hidden" name="domain_id" value="<?php echo $retailers[0]['domain_id']?>">
					<input type="hidden" name="brand" id="brand" value="<?php echo $retailers[0]['domain_name'];?>">
					<div class="btn fsubmit_campaign col-xs-12"></div>
					<button type="input" name="submit_campaign" id="submit_campaign"  value="newAccount" class="btn btn-success btn-icon" disabled><i class="fa fa-check"></i> Create Campaign</button>
				</div>
			  </div>
			</form>
						
			<div id="model_link" class="text_warning">Note: Users are recommended to add sanitized URL. <a id="<?php echo base_url(); ?>campaign/how_it_work" href="javascript:void(0)" class="loadForm" name="#myModal">Click to explore</a><div></div></div>
		  </div>
		</div>
	</div>	
</div>

<script>
$(function(){
		var product_id = $('input[name="product_id"]:checked').val();
		var ptitle = $('input[name="product_id"]:checked').parent().attr('title');
		var url=_BASEPATH_+'campaign/create_promo';
		$('input[name="campaign_name"]').val(ptitle);
		$("#promo_code_generate span").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		$.ajax({
			  type: 'GET',
			  url: url,
			  data: { product_id: product_id},
			  datatype: 'html',
			  cache: 'false',
			  success: function(response) {
				setTimeout(function(){
								 $('#promo_code').val(response);
								 $("#promo_code_generate span").html('');
						   }, 3000);
				},
				error: function(){
				  alert('Error');
				}
		});
		$("#promo_code_generate").on("click",function(e){
			$("#promo_code_generate span").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
				$.ajax({
				  type: 'GET',
				  url: url,
				  data: { product_id: product_id},
				  datatype: 'html',
				  cache: 'false',
				  success: function(response) {
					setTimeout(function(){
									 $('#promo_code').val(response);
									 $("#promo_code_generate span").html('');
							   }, 1000);
					},
					error: function(){
					  alert('Error');
					}
			});
		});
});
$(document).on("click",".loadForm",function(e){
	var modalId = this.name;
	var url         = this.id;
	$.get(url, function( data ) {
			$("#loadData").html(data);
			$(modalId).modal('show');
		});
		
});

</script>