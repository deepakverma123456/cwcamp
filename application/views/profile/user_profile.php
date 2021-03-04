<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />
<script>

$(function() {
			
			$("#name").blur(function(){
				///alert($(this).val());
				$(this).val($(this).val().replace(/\s\s+/, ' '));
			  });
			
		   $('#name').keyup(function() {
                if (this.value.match(/[^a-zA-Z0-9 ]/g)) {
                    this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
                }
            });
			
			$('#website_url').keyup(function() {
                if (this.value.match(/[^a-zA-Z0-9:\///_.-]/g)) {
                    this.value = this.value.replace(/[^a-zA-Z0-9:\///_.-]/g, '');
                }
            });
			$('#phone').keyup(function() {
                if (this.value.match(/[^0-9]/g)) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                }
            });
			
			
        });
</script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
		
	  <div class="x_panel">
		
		  <div class="x_title">
			<h2>Change Password</small></h2>&nbsp;&nbsp;&nbsp;&nbsp;
				
			<div class="clearfix"></div>
			<?php		
			  if($this->session->userdata('msgchange') != '')
					 {
					 
						echo '<span style="border: 0 solid rgb(1, 1, 1);color: red;margin: auto auto auto 150px;padding: 5px;text-align: left;width: 65%;">'.$this->session->userdata('msgchange').'</span>'; 
					 }
			  ?>
		  </div>

		<div class="x_content">
			<div class="row" >
			  
			   <div id="campaign_from" class="col-md-6 col-sm-6 col-xs-12" style="min-height: 500px; border-left:1px dotted #d4d4d4;">
					<div class="col-md-11 col-sm-11 col-xs-12 ">
					 
					     <div class="container">
     
      <?php		
		/* if($this->session->userdata('msg') != '')
			 {
				echo '<div class="msg">'.$this->session->userdata('msg').'</div>'; 
			 }*/
		?>
      <form name="registration_form" id="registration_form" action="<?php echo base_url()?>user_registration/edit_profile" method="post" enctype="multipart/form-data" class="form-horizontal" onsubmit="return validateEditProfile()">
        <div class="row-fluid" style=" width:100%"> <br>
          <?php
		if($this->session->userdata('user_status')==2)
		{
		?>
          <div style="text-align:center;font-weight:bold;"><span class="error">*</span>Your account has been Paused by Admin.<br>
            &nbsp;</div>
          <?php
		}
		?>
          <div class="createWidgetWrap">
            <div class="frmBlckCont">
              <div class="creatFrmRw cFrmR">
                <label> Full Name:</label>
                <input type="text" name="name" id="name" maxlength="100" value="<?php echo $user_data[0]->name; ?>" class="form-control" />
                <span class="recom">*</span> <span id="name_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <?php /*?><div class="creatFrmRw">
                <label> Last Name:</label>
                <input type="text"  name="last_name" id="last_name" maxlength="100"  value="<?php echo $user_data[0]->last_name; ?>" />
                <span class="recom">*</span> <span id="last_name_err" class="error"></span>
                <div class="clear"></div>
              </div><?php */?>
              <div class="creatFrmRw cFrmR">
                <label> Email:</label>
                <input type="text" name="user_email" id="user_email"  value="<?php echo $user_data[0]->email; ?>" readonly class="form-control" />
              <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> Website Url:</label>
                <input type="text" name="website_url" id="website_url"  placeholder="http://www.example.com" value="<?php echo $user_data[0]->website_url; ?>" class="form-control" />
                <span id="website_url_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> Street Address 1:</label>
                <input type="text" name="street_address1" id="street_address1"  value="<?php echo $user_data[0]->street_address; ?>"/>
                <span class="recom">*</span><span id="street_address_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> Street Address 2:</label>
                <input type="text" name="street_address2" id="street_address2" value="<?php echo $user_data[0]->street_address2; ?>" class="form-control"/>
                <span id="street_address_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> Country:</label>
                <select name="country" id="country" onchange="return editCheckCountryType();">
                    <option value="">Please select</option>
                  <?php foreach($country_list as $countries) {?>
                  <option value="<?php echo $countries->country; ?>" <?php if($countries->country == $user_data[0]->country){echo 'selected="selected"';}?>><?php echo $countries->country; ?></option>
                  <?php } ?>
                </select>
                <span class="recom">*</span><span id="country_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> State:</label>
                <div id="state1" <?php if($user_data[0]->country == 'United States') {echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
                  <select name="state_us" id="state_us">
                    <?php foreach($state_list as $states) {?>
                    <option value="<?php echo $states->statename; ?>" <?php if($states->statename == $user_data[0]->state){echo 'selected="selected"';}?>><?php echo $states->statename; ?></option>
                    <?php } ?>
                  </select>
                  <span class="recom">*</span></div>
                <div id="state2" <?php if($user_data[0]->country != 'United States') {echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
                  <input type="text" name="state_nonus" id="state_nonus" value="<?php echo $user_data[0]->state; ?>">
               <span class="recom">*</span> </div>
                <span id="state_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> City:</label>
                <input type="text" name="city" id="city" value="<?php echo $user_data[0]->city; ?>"/>
                <span class="recom">*</span> <span id="city_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
                <label> Zip</label>
                <input type="text" name="zip" id="zip" maxlength="12" value="<?php echo $user_data[0]->zip; ?>"/>
                <span class="recom">*</span><span id="zip_err" class="error"></span>
                <div class="clear"></div>
              </div>
              <div class="creatFrmRw cFrmR">
              	<div class="cFlft">
                    <label> Phone:</label>
                    <input type="text" name="phone" id="phone" maxlength="15" placeholder="Phone" value="<?php echo $user_data[0]->phone; ?>"/><span class="recom">*</span>
                    <span id="phone_err" class="error"></span>
                    <div class="clear"></div>
                </div>
                <div class="cFrht">
                    <label> Type:</label>
                    <input type="hidden" name="user_type" id="user_type" value="<?php echo $user_data[0]->user_type; ?>"/>
                    <input type="text" readonly  value=" <?php if($user_data[0]->user_type==0){echo ucfirst('user');}else{ echo ucfirst('agency');}?>" style="cursor:"/>
                   <span class="recom"></span>
                    <span id="phone_err" class="error"></span>
                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
              </div>
              
              <div class="creatFrmRw cFrmR">
             	<div class="cFpic">
                    <label> Photo:</label>
                    <input type="file" name="user_pic" id="user_pic" value=""/><span class="hintTxt2" style="margin-left:185px;">(Upload jpeg, jpg, gif and png files only.)</span>
                    <input type="hidden" name="user_pic_url" id="user_pic_url" value="<?php echo $user_data[0]->image_url; ?>"/><span class="recom"></span><span id="user_pic_err" class="error"></span>    
                </div>         
                <div class="cFimg">
                	<span id="user_pic_err1" class="error">
					<?php if($user_data[0]->image_url!=''){ ?>
                    
                    <img id="logoBeforeUploadPreview" src="<?php echo base_url().$user_data[0]->image_url; ?>" alt="" style="height:60px;width:60px;" />
                    <?php }else{ ?>
                    
                     <img id="logoBeforeUploadPreview" src="<?php echo base_url();?>/images/no-image.jpg" alt="" style="height:60px;width:60px;" />
                    <?php } ?>
                    </span>
                    <div class="clear"></div>
                </div>
              </div>
              <div class="clear"></div>
            </div>
           <div class="creatFrmRw cFrmR">
                <label>&nbsp;</label>
              <a href="<?php echo base_url().'user/dashboard'?>">
             <input type="button" name="Cancel" Value="Cancel" class="btn btn-continue"> &nbsp;&nbsp;<input type="submit" name="Submit" Value="Update" class="btn btn-continue">
              </a> </div>
          </div>
        </div>
      </form>
    </div>

					 
					 
								   <form name="registration_form" id="registration_form" action="<?php echo base_url()?>profile/update_password" method="post" enctype="multipart/form-data" class="form-horizontal" onsubmit="return validateChangePassword()">
      
								   <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
										  <div class="input-group">
												 <span class="input-group-addon">Old Password*</span>
												 <input type="password" name="old_password" id="old_password" class="form-control" required  />
										  </div>	
								   </div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
										  <div class="input-group">
												 <span class="input-group-addon">New Password*</span>
												 <input type="password" name="new_password" id="new_password" class="form-control" required  />
										  </div>	
								   </div>
<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
										  <div class="input-group">
												 <span class="input-group-addon">Confirm Password*</span>
												 <input type="password" name="confirm_password" id="confirm_password" class="form-control" required  />
										  </div>	
								   </div>							
							<div class="clearfix"></div>
							<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
								   <a href="<?php echo base_url(); ?>user/dashboard"><button type="button" class="btn btn-default btn-icon" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button></a>
							<button type="submit" class="btn btn-primary">Submit</button>
							</div>
							</form>
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
 
 
 