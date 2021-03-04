<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />

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
			  
								   <form name="cp_form" id="cp_form" action="<?php echo base_url()?>profile/update_password" method="post" enctype="multipart/form-data" class="form-horizontal" onsubmit="return validateChangePassword()">
									<div class="modal-body">
										<div class="form-group col-md-6 col-sm-6 col-xs-12 nopadding-lr" id="msg_hide">
											<div id="msg"></div>
										</div>
										<div class="clearfix"></div>
								   <div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
										<div id="msg"></div>
										  <div class="form-group">
												 <label >Old Password<i class="fa fa-asterisk red required" aria-hidden="true" aria-required="true"></i></label>
												 <input type="password" name="old_password" id="old_password" class="form-control" required  />
										  </div>	
								   </div>
								   <div class="clearfix"></div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
										  <div class="form-group">
												 <label >New Password<i class="fa fa-asterisk red required" aria-hidden="true" aria-required="true"></i></label>
												 <input type="password" name="new_password" id="new_password" class="form-control" required  />
										  </div>	
								   </div>
<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
										  <div class="form-group">
												 <label	 >Confirm Password<i class="fa fa-asterisk red required" aria-hidden="true" aria-required="true"></i></label>
												 <input type="password" name="confirm_password" id="confirm_password" class="form-control" required  />
										  </div>	
								   </div>							
							<div class="clearfix"></div>
							<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
							<button type="submit" id="submit" class="btn btn-primary">Submit</button>
							 <div class="btn fsubmit pull-left"></div>
							</div>
							</form>
					</div>
			
			</div>	
		</div>
		
		
		
		
	</div>	
  </div>
</div>

<!-- /page content -->
<?php include("./application/views/common/template-bottom.php"); ?> 

<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/update-campaign-validation.js"></script>