<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title;?></title>
	<script src="<?php echo $this->config->base_url();?>assets/js/jquery.js"></script>
	<script>
	function show_details(id)
		{
			var a = id.split("_",[2]);
			$('#info_'+a[1]).show();
	        $('#hide_'+a[1]).show();
	        $('#show_'+a[1]).css("display", "none");
		}
	function hide_details(id)
		{
			var a = id.split("_",[2]);
			$('#info_'+a[1]).hide();
	        $('#show_'+a[1]).show();
	        $('#hide_'+a[1]).css("display", "none");
		}
	</script>
</head>
<body style="font-family:verdana,sans-serif;background-color:#ffbdb5;font-size:11px;">
<h1>Activity on this account</h1>
<p>
	This feature provides information about the last activity on this cartwire account or any concurrent activity.
</p> 
<b>Recent activity:</b>
	<table width="100%" border="1" cellspacing="0" cellpadding="4" style="margin-top: 30px;border-collapse:collapse;background-color:#fff">
		<tr>
			<th width="35%">Access Type:</th>
			<th>IP Address:</th>
			<th>Date/Time</th>
		</tr>
		
			<?php
				if(count($recentRecords)>0)
				{ 
					$i=1;
					foreach ($recentRecords as $row) {
						
					?>
				<tr>
					<td><?php echo $row->brwsr;?>&nbsp; 
						<span id="show_<?php echo $row->id;?>" class="show_<?php echo $row->id;?>" onclick="return show_details(this.id);" style="color: #0000CC;text-decoration: underline; cursor: pointer;">Show details</span>
						<span id="hide_<?php echo $row->id;?>" class="hide_<?php echo $row->id;?>" onclick="return hide_details(this.id);" style="color: #0000CC;text-decoration: underline; cursor: pointer;display: none;">Hide details</span>
						<div id="info_<?php echo $row->id;?>" class="info_<?php echo $row->id;?>" style="margin-left: 2em; display: none;"><i>"<?php echo $row->report; ?>"</i></div>
					</td>
					<td><?php echo $row->ip; ?></td>
					<td><?php echo $row->last_account_activity; ?></td>
				</tr>
			<?php
			$i++;
					}

				}else{

				?>
				<td colspan="3">No Recent Activity</td>
			<?php

				}

				?>
	
	</table>
</body>
</html>
