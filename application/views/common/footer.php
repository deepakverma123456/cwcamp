<?php

	if($this->session->userdata('userHistoryId'))
	{
		$lastLoginActivity 	    = $this->user_model->fetchUserLastLoginActivity();
		$lastHitUrl			 	= $this->config->base_url($this->uri->uri_string());
		$succ					= $this->user_model->updateUserLastActivity($lastHitUrl);
	}
	
?>
<footer>
	<div class="pull-left">
		<?php echo 'IP  '.$lastLoginActivity->ip.', '.$lastLoginActivity->brwsr; ?>
		<a style="cursor:pointer;text-decoration: underline;"  onclick="window.open('<?php echo $this->config->base_url().'user/fetchUserTenDaysActivity';?>', 'newwindow', 'width=640, height=600'); return false;">Details</a>
  	</div>
	<div class="pull-right">
		<?php echo _COPYRIGHT_; ?>
	</div>
  <div class="clearfix"></div>
</footer>