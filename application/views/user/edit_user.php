<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.validate.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/create-user-validation.js"></script> 

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <h4 class="modal-title"><?php echo $title; ?></h4>
    </div>
	
    <form action="" id="editform" method="post" >
        <div class="modal-body">
			<div class="row"><div id="msg"></div></div>
			<div class="form-group">
                <label for="userName">Full Name<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
                <input type="text" class="form-control" name="name" id="name" value="<?php echo $result[0]->name; ?>">               
            </div>
            <div class="form-group">
                <label for="userEmail">Email Address<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
                <input type="text" class="form-control" name="email" placeholder="Your email address is also used to log in." id="email" value="<?php echo $result[0]->email; ?>">               
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label for="password">Password<i class="fa fa-asterisk red required" aria-hidden="true"></i></label>
                        <input type="password" class="form-control" name="password" id="password" value="">              
						<a id="show_password">Show</a>						 
					</div>					
                </div> 
            </div>
			<div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>Account Type</label>
						<p>
                        <input type="radio" class="flat" name="role_id" value="1" <?php if($result[0]->role_id==1){ echo "checked='checked'"; } ?>>&nbsp;User&nbsp;&nbsp;                   
						<input type="radio" class="flat" name="role_id" value="2" <?php if($result[0]->role_id==2){ echo "checked='checked'"; } ?>> Admin 
						</p>
					</div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>Check to send mail to user</label>
						<p>
                        <input type="checkbox" class="flat" name="sendmail">
						</p>
                    </div>
                </div>
            </div>
        </div>	

        <div class="modal-footer">
           <button type="button" class="btn btn-default btn-icon pull-right" data-dismiss="modal"><i class="fa fa-times-circle"></i> Cancel</button>
            <button type="input" name="submit" id="submit" value="newAccount" class="btn btn-success btn-icon pull-right"><i class="fa fa-check"></i> Update My Account</button>
            <input type="hidden" value="<?php echo $_REQUEST['id']; ?>" name="id" id="id">
			<div class="btn fsubmit pull-right"></div>
		</div>
    </form>
</div>
<div id="pas" style="display:none"><?php echo decrypt($result[0]->password); ?></div>