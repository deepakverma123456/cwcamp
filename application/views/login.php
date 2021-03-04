<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Login :: CartWire - China</title>
	<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/bootstrap.css"> 
	<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/login.css"> 
	<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/font-awesome.css"> 
    <script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script> 	
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/additional-methods.js"></script> 
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/login-validation.js"></script>
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
	<script>var _BASEPATH_= '<?php echo base_url(); ?>'; </script>
	

	<script type = 'text/javascript'>
	   $(document).ready(function(){
		   if($(window).height() < 1366){
		        $('.container.login-cont').css('min-height', $(window).height());
		   }
	   });
	</script>
	
<!--
    <style type="text/css">
		#loginbox{ width:500px; margin: 80px auto 0 auto;}
	</style>
-->

	
</head>
<body style="background-image:url('<?php echo base_url();  ?>/assets/images/top-bg.jpg');">
<div class="container login-cont" style="min-height:400px">    
    <!--<div id="loginbox" class="mainbox col-md-5 col-md-offset-4 col-sm-5 col-sm-offset-4"> -->
    <div id="loginbox" class="col-md-5 col-sm-5 box-center"> 
        
        <div class="row">                
            <div class="iconmelon">
              <img src="<?php echo base_url();  ?>/assets/images/logo_cartwire.png" />
            </div>
        </div>
		<div class="modal"><div class="modal-element">Authenticating...</div></div>
        <div class="panel panel-default" id="login"  style="position:absolute;width:95%;">
           <div class="panel-heading">
				<div class="panel-title text-center">Login <i class="fa fa-lock" aria-hidden="true"></i></div>
			</div>      

            <div class="panel-body" >
				<div id="msg"></div>

				<?php $attributes = array('class' => 'form-horizontal', 'id' => 'form','method'=>'POST');
					echo form_open(base_url().'login', $attributes); ?>
                   
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="email" type="text" class="form-control required" name="email" value="" placeholder="Email" />
                    </div>

                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="password" type="password" class="form-control required" name="password" placeholder="Password" />
                    </div>                                                                  

                    <div class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button id="submit" type="submit" href="#" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-log-in"></i> Log in</button>
							<div class="pull-right lnk-mid"><a class="fget" href="javascript:void(0)">Forget Password?</a> |&nbsp;&nbsp;</div>		
                        </div>
                    </div>

                <?php echo form_close(); ?>
				

            </div>                     
        </div>
		<div class="panel panel-default" style="display:none; position:absolute;width:95%;" id="forget">
			<div class="panel-heading">
				<div class="panel-title text-center">Forget Password <i class="fa fa-key " aria-hidden="true"></i></div>
			</div>   
			<div class="panel-body" >
				<div id="passmsg"></div>
					<?php $attributes = array('class' => 'form-horizontal', 'id' => 'forgotpass','method'=>'POST');
					echo form_open(base_url().'user/forgotpassword', $attributes); ?>
					<div class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
						<input id="email" type="text" class="form-control required" name="email" value="" placeholder="Email" />
					</div><br/>                                                           
					<div class="form-group">
						<!-- Button -->
						<div class="col-sm-12 controls">
							<button type="submit" id="btnfget" class="btn btn-primary pull-right"><i class="glyphicon glyphicon-log-in"></i> Submit</button>
							<div class="lnk-mid pull-right"><a class="fgetl" href="javascript:void(0)">Login</a> |&nbsp;&nbsp;</div>                          
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>                     
		</div>
    </div>
	<div class="model-overlay"></div>
</div>
<div class="container copyright">
	<?php echo _COPYRIGHT_; ?>
</div>
</body>
</html>