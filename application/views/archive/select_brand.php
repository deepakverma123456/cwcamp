<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script>
$(document).ready(function(){	
    $('input[type="checkbox"]').click(function(){			
		  if($('input:checkbox:checked').length=='0')
			{
				$(':input[type="submit"]').prop('disabled', true);
			}
			else
			{
				$(':input[type="submit"]').prop('disabled', false);
			}
    });		
});	
</script>

<style type="text/css">
/* Hiding the checkbox, but allowing it to be focused */
.btn .badge {
    position: absolute !important;
    right: 9px !important;
    top: 14px !important;
}
.badgebox
{
    opacity: 0;
	
	margin:10px;
}

.badgebox + .badge
{
    /* Move the check mark away when unchecked */
    text-indent: -999999px;
    /* Makes the badge's width stay the same checked and unchecked */
	width: 27px;
}

.badgebox:focus + .badge
{
    /* Set something to make the badge looks focused */
    /* This really depends on the application, in my case it was: */
   
    /* Adding a light border */
    box-shadow: inset 0px 0px 5px;
    /* Taking the difference out of the padding */
}
.badgebox:checked + .badge
{
    /* Move the check mark back when checked */
	text-indent: 0;
}
</style>
<?php

if($_REQUEST['type']=='topfive')
{
	$report = 'archive/topfive_brand_campaign_report';
}
else
	$report = 'archive/brand_campaign_report';

?>
<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title"><?php echo $title; ?></h4>
    </div>
	
    <form action="<?php echo base_url().$report ?>" id="brandform" method="post" >
        <div class="modal-body">
			<div class="row"><div id="msg"></div></div>			
			<div class="row" style="margin-right:0px">               
                <div class="form-group">
						<?php
						//print_r($brands);
						if(count($brands)>0)
						{
							$i = 1;
							foreach($brands as $brand)
							{
						?>
						<div class="col-md-4 col-sm-4 col-xs-12">
							<label style="text-align: left; width: 100%;margin:5px;" for="brandId<?php echo $i ?>" class="btn btn-info"><?php echo $brand->domain_name;?> <input type="checkbox" class="badgebox" name="brandId[]" id="brandId<?php echo $i ?>" value="<?php echo $brand->id;?>"><span class="badge">&check;</span></label>
						</div>
						<?php $i++; }}else{  ?>
						<div class="col-md-12 col-sm-12 col-xs-12">
							Brand not found.
						</div>					
						<?php } ?>							
                </div>
            </div>
        </div>

        <div class="modal-footer">
           <button type="button" class="btn btn-default btn-icon pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
            <button type="submit" name="submit" id="brasubmit" value="newAccount" class="btn btn-success btn-icon pull-right" disabled><i class="fa fa-check"></i> Download</button>
            <div class="btn fsubmit pull-right"></div>
		</div>
    </form>
</div>