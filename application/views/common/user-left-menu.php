<div class="col-md-3 left_col">
  <div class="left_col scroll-view">
		<div class="profile clearfix">
			<div class="profile_pic">
				<img src="<?php echo base_url(); ?>assets/images/logo_cartwire.png" style="width:210px;margin:10px" >
			</div>
		</div>
		<?php
		
		//a($menu);
		?>
  
		<!-- sidebar menu -->
		<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
			<div class="menu_section">

				<ul class="nav side-menu">
					
					<!--Dashboard-->
					
					<li <?php if($title=='user/dashboard'){ echo 'class="active">'; } ?>  >
						<a href="<?php echo base_url(); ?>user/dashboard"><i class="fa fa-dashboard"></i>Dashboard</a>
					</li>
					
				<?php foreach((array)$menu as $val){
				if($val['menu']->inner_pages)
					$inner = explode(',',$val['menu']->inner_pages);
				else
					$inner = array();
				 ?>
					<li <?php if(($title==$val['menu']->filename) || in_array($title, $inner )){ echo 'class="active">'; } ?>  >
						<a href="<?php if (!@$val['submenu']) echo base_url().$val['menu']->filename; else echo 'javascript:void(0);' ?>"><i class="<?php echo $val['menu']->menu_image; ?>"></i><?php echo $val['menu']->menu_label; ?>
				
					<?php if (@$val['submenu']) { ?>
					<span class="fa fa-chevron-down"></span></a>
					<ul class="nav child_menu" style="display:none!important;">
						<?php foreach((array)$val['submenu'] as $res) { ?>
							<li><a href="<?php echo base_url().$res->filename; ?>"><?php echo $res->menu_label; ?></a></li>
							
					<?php } ?>
					</ul>
					<?php } else echo '</a>' ?>
					</li>
				
				<?php } ?>
		</ul>
	  </div>
	</div>
	<!-- /sidebar menu -->

	<!-- /menu footer buttons -->
	<div class="sidebar-footer hidden-small">
		  <?php include("clock.php"); ?>
	 	  <a data-toggle="tooltip" class="pull-right" data-placement="top" href="<?php echo base_url().'logout'; ?>" title="Logout">
		<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
	  </a>
	</div>
	<!-- /menu footer buttons -->
  </div>
</div>