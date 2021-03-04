<div class="top_nav">
  <div class="nav_menu">
	<nav>
	  <div class="nav toggle">
		<a id="menu_toggle"><i class="fa fa-bars"></i></a>
	  </div>

	  <ul class="nav navbar-nav navbar-right">
		<li class="">
		  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
			<img src="<?php echo base_url(); ?>assets/images/user.png" alt=""><?php echo USERNAME(); ?>
			<span class=" fa fa-angle-down"></span>
		  </a>
		  <ul class="dropdown-menu dropdown-usermenu pull-right">
			<li>
			  <a href="javascript:void(0)">
					<b><?php echo $this->session->userdata('email'); ?></b>
				<br>
				<span style="text-align">UserId: <?php echo $this->session->userdata('user_id'); ?></span>
			  </a>
			</li>
			<li>
			  <a href="<?php echo base_url().'profile/change_password'; ?>">
				<span>Change Password</span>
			  </a>
			</li>
			
			<li><a href="<?php echo base_url().'logout'; ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
		  </ul>
		</li>

		<li role="presentation" class="dropdown">
		  
		  <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
			<li>
			  <a>
				<span class="image"><img src="<?php echo base_url(); ?>assets/images/user.png" alt="Profile Image" /></span>
				<span>
				  <span>John Smith</span>
				  <span class="time">3 mins ago</span>
				</span>
				<span class="message">
				  Film festivals used to be do-or-die moments for movie makers. They were where...
				</span>
			  </a>
			</li>
			<li>
			  <a>
				<span class="image"><img src="<?php echo base_url(); ?>assets/images/user.png" alt="Profile Image" /></span>
				<span>
				  <span>John Smith</span>
				  <span class="time">3 mins ago</span>
				</span>
				<span class="message">
				  Film festivals used to be do-or-die moments for movie makers. They were where...
				</span>
			  </a>
			</li>
			<li>
			  <a>
				<span class="image"><img src="<?php echo base_url(); ?>assets/images/user.png" alt="Profile Image" /></span>
				<span>
				  <span>John Smith</span>
				  <span class="time">3 mins ago</span>
				</span>
				<span class="message">
				  Film festivals used to be do-or-die moments for movie makers. They were where...
				</span>
			  </a>
			</li>
			<li>
			  <a>
				<span class="image"><img src="<?php echo base_url(); ?>assets/images/user.png" alt="Profile Image" /></span>
				<span>
				  <span>John Smith</span>
				  <span class="time">3 mins ago</span>
				</span>
				<span class="message">
				  Film festivals used to be do-or-die moments for movie makers. They were where...
				</span>
			  </a>
			</li>
			<li>
			  <div class="text-center">
				<a>
				  <strong>See All Alerts</strong>
				  <i class="fa fa-angle-right"></i>
				</a>
			  </div>
			</li>
		  </ul>
		</li>
	  </ul>
	</nav>
  </div>
</div>