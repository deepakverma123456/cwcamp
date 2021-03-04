<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />


<!-- page content -->
<div class="right_col" role="main">
	<div class="col-md-9 col-sm-9 col-xs-12" id="holder">
        <span class="notify">the notification</span>
  </div>
  <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
	<div class="x_panel">
	  <div class="x_title">
		<h2><?php echo $title; ?></small></h2>
			<ul class="nav navbar-right panel_toolbox">	
				<li><button class="btn btn-warning btn-sm loadForm" type="button" id="<?php echo base_url(); ?>user/create" name="#myModal">Create User</button></li>				
				<li><button class="btn btn-primary btn-l fa fa-refresh refereshbtn" id="refresh" type="button"></button></li>
			</ul>		 
		<div class="clearfix"></div>
	  </div>
	  <div class="x_content">		
		<table id="datatable" class="table table-striped table-bordered bulk_action">
		  <thead>
			<tr>
			  <th>S. No</th>
			  <th>Name</th>
			  <th>Email</th>
			  <th>Account Type</th>
			  <th>Created/Modified By</th>
			  <th>Created/Modified Date</th>
			  <th>Status</th>
			  <th>Action</th>
			</tr>
		  </thead>
		  <tbody>
			<?php  
			if(count($alluserlists)>0)
				{
					$i = 1;
					 foreach($alluserlists as $row)
					 {
				?>
			<tr id="row_<?php echo $row->id;?>" class="<?php echo ($row->status=="Deleted") ? "deltr" : "";?>">
			  <td><?php echo $i;?></td>
			  <td><?php echo ucwords($row->name);?></td>
			  <td><?php echo $row->email;?></td>
				<td><?php echo $row->role;?></td>
			  <td><?php echo $row->CreatedBy;?></td>
			  <td><?php echo date("d-m-Y", strtotime($row->updated_date));?></td>
			  <td><?php if($row->status!="Deleted" && $row->id!=1000) { ?>
					<a id="<?php echo $row->id;?>" name="<?php echo $row->status; ?>" href="javascript:void(0)" class="status_checks"> 
					<?php echo ($row->status); ?>
					</a>
					<?php } else { echo $row->status; }?>				
					</td>
				<td><?php if($row->status!="Deleted") { ?>
					<button class="btn btn-primary btn-xs loadForm fa fa-edit" <?php if($row->id==1000) { ?>disabled="disabled"<?php } ?> type="button" id="<?php echo base_url(); ?>user/edit_user?id=<?php echo encrypt($row->id); ?>" name="#myModal"></button>
					<button class="btn btn-danger btn-xs fa fa-times" <?php if($row->id==1000) { ?>disabled="disabled"<?php } ?> type="button" id="<?php echo $row->id;?>" name="<?php echo $row->id;?>"></button>
					<?php } ?>
				</td>
			</tr>
			<?php $i++; }}else{  ?>
			<tr>
			  <td colspan="7">Record not found.</td>			  
			</tr>
			<?php } ?>		
			
		  </tbody>
		</table>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->
<?php include("./application/views/common/template-bottom.php"); ?> 
<script type='text/javascript' src="<?php echo base_url(); ?>/assets/js/dataTables.bootstrap.min.js"></script>
<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/listall-user-validation.js"></script>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="container">
            <div class="row" id="loadData">
							
            </div>
        </div>
    </div>
</div>