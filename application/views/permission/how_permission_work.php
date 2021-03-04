<?php include("./application/views/common/template-top.php"); ?>
<!-- page content -->
<div class="right_col" role="main">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2><?php echo $title;?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="container">
					<p>
  A registered campaign portal user can be restricted to access certain *areas or *data.
						There are two level of access restriction.<br><br>
						&nbsp;&nbsp;&nbsp;<b>1. Module Level Access:</b> Here the registered user can be restricted to access the specific user modules or the pages/functionality under the module.<br><br>
						&nbsp;&nbsp;&nbsp;<b>2. Data Level Access:</b> This can further be categorised as:<br><br>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>a. Brand Level Data Access Permission:</b> If admin grants the brand access permission, then user can view all brands specific campaign and its analytic reporting.<br><br>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>b. Record Level Access Permission:</b> If record level access is given the user can view the specific record only. To do this Admin first need to authorize Brand Data Access, &amp; then 
									Record Level access permission can be exercised.<br><br>
  <b>Note:</b> <i>If no permission is granted to the registered user (default), one can exercise all user specific modules and brand data . While admin user have all the access permission through out the portal</i>		
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->
<?php include("./application/views/common/template-bottom.php"); ?> 
