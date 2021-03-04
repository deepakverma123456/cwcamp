<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/create-campaign-validation.js"></script> 

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title"><?php echo $title; ?></h4>
    </div>
	
    <form action="" id="productform" method="post" >
        <div class="modal-body">
			<div class="row"><div class="col-md-12 col-sm-12 col-xs-12"><div id="msgp"></div></div></div>
			<div class="row">
				<div id="msg"></div></div>
			<div class="form-group" >
				<label for="productName">Retailer Product Url<i class="fa fa-asterisk red required" aria-hidden="true"></i></label><input type="text" readonly="readonly" class="form-control" name="product_url" placeholder="Enter your product url." id="product_url" value="">
			</div>
			<div class="form-group" id="productDiv">
				<label for="productName">Retailer Product Name<i class="fa fa-asterisk red required" aria-hidden="true"></i></label><input type="text" class="form-control" name="product_name" placeholder="Enter your product name." id="product_name" value="">
			</div>
			<div class="form-group">
				<label for="productUrl">Retailer Product Image Url<i class="fa fa-asterisk red required" aria-hidden="true"></i></label><input type="text" class="form-control" name="product_image_url" placeholder="Enter your product Image url." id="product_image_url" value="">
			</div>
			<div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="password">Retailer Product Category</label>
                        <input type="text" class="form-control" name="cat" placeholder="Type Retailer Product Category." id="cat" value="">                     
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="passwordr">Retailer Product Sub Category</label>
                        <input type="text" class="form-control" name="subcat" placeholder="Type Retailer Product Sub Category." id="subcat" value="">                        
                    </div>
                </div>
            </div>
			
        </div>
			<input type="hidden" name="retailerid" value="">
			<input type="hidden" name="productid" value="">
			<input type="hidden" name="domainid" value="">
			<input type="hidden" name="brandname" id="brandname" value="">
        <div class="modal-footer">
           <button type="button" class="btn btn-default btn-icon pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
            <button type="input" name="submit" id="submit" value="newAccount" class="btn btn-success btn-icon pull-right"><i class="fa fa-check"></i> Create Retailer Product</button>
            <div class="btn fsubmit pull-right"></div>
		</div>
    </form>
</div>
<script>
	$(function(){
		//var product_id = $('input[name="product_id"]:selected').val();
		var purl = $('input[name="userCampaignUrl"]').val();
		var rid = $('#retailer_id').val();
		var pid = $('input[name="product_id"]').val();
		var did = $('input[name="domain_id"]').val();
		var bname = $('input[name="brand"]').val();
		
		//alert(purl);
		$('input[name="product_url"]').val(purl);
		$('input[name="retailerid"]').val(rid);
		$('input[name="productid"]').val(pid);
		$('input[name="domainid"]').val(did);
		$('input[name="brandname"]').val(bname);
		var ptitle = $('input[name="product_id"]:checked').parent().attr('title');
		$('input[name="product_name"]').val(ptitle);
		//$("#promo_code_generate span").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
	});
</script>