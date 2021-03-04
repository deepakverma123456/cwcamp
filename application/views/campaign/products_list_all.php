<?php include("./application/views/common/template-top.php"); ?>
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/dataTables.bootstrap.min.css" />
<link rel = "stylesheet" type = "text/css" href = "<?php echo base_url(); ?>assets/css/responsive.bootstrap.min.css" />
<!-- page content -->
<div class="right_col" role="main">
  <div class="col-md-12 col-sm-12 col-xs-12">
	<div class="x_panel">
	  <div class="x_title">
		<h2><a href='javascript:void(0)' class='loadForm' name='#myModal'>Click to add</a>
Create Campaign</small></h2>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if($_REQUEST['domain']){ ?>
			<ul class="nav navbar-right panel_toolbox">
				<li>						
					<select id="domain_name" name="domain_name" value="domain_name" class="form-control">
					  <option>-- Select --</option>
					  <?php foreach($brands as $row){?>
							<option value="<?php echo $row->id;?>"  <?php if($_REQUEST['domain']==$row->id) echo "selected"; ?>><?php echo $row->domain_name;?></option>
					  <?php } ?>
					 </select>
				</li>
			</ul>
		<?php } ?>
		<div class="clearfix"></div>
	  </div>
	
	<?php if($_REQUEST['domain']){ ?>
		<div class="x_content">
			<div class="row" >
				
				<div class="col-md-5 col-sm-5 col-xs-12">
					<h5 class="text-center"><b>Brand Products</b></h5>
					<table id="datatable" class="table table-striped table-bordered bulk_action">
						<thead>
							<tr><th align="center"><?php echo @$result[0]->brand ?> Product List</th></tr>
						</thead>
					    <tbody>
						<?php if(count($result)>0)
							 {
							 //echo "<pre>";print_r($result);die;
							 foreach($result as $row)
							 {
							 ?>
						<tr>
						   <td >
								<div class="pull-left"><img style="padding:3px;margin: 0px 10px 5px 5px;border: 1px solid #f2f2f2" src="<?php echo $row->image_url;?>" alt="Product Image" title="<?php echo $row->title;?>" width="50" height="50" /></div>
								   <div class="pull-left"><?php echo $row->title;?>
								   (<?php echo $row->prd_sku;?>)
								   <br/><?php echo $row->category.' / '.$row->sub_category;?></div>
								   <div class="pull-right"  title="<?php echo $row->title;?>" ><input type="radio" name="product_id" value="<?php echo $row->id;?>" ></div>
							   </td>
						</tr>
						<?php $i++;
								   }?>
						<?php }
							   else
							   { ?>
							 <tr> 
							   <td>Sorry, no product available for the selected brand.</td>
							 </tr>
							<?php
						   } ?>
						</tbody>
				   </table>
			   </div>
			  
			   <div id="campaign_from" class="col-md-6 col-sm-6 col-xs-12  pull-right" style="min-height: 500px; border-left:1px dotted #d4d4d4;">
					<h4 class="text-center"><br/><br/><br/><br/><br/><br/><i class="btn fsubmit"></i><div class="col-xs-12">Choose a product, and continue to create campaign.</div></h4>
			   </div>
			</div>	
		</div>
				<?php }else{ ?>
		<div class="x_content" style="min-height: 300px;">
			<div class="row" >
				<br/><br/><br/>
				<div class="col-md-12 col-sm-12 col-xs-12 text-center">
					<h4>Please select a brand and proceed to the next step for creating campaign.</h4>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-12 col-md-offset-4">
					<select id="domain_name" name="domain_name" value="domain_name" class="form-control">
						 <option>-- Select --</option>
					  <?php
					  foreach($brands as $row){?>
							
							<option value="<?php echo $row->id;?>"  <?php if($_REQUEST['domain']==$row->id) echo "selected"; ?>><?php echo $row->domain_name;?>
							</option>
					  <?php }?>
					</select>
				</div>
				
			</div>	
		</div>
	<?php } ?>
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
		"bFilter": true,
		"bInfo": false,
		"bAutoWidth": false,
		"pageLength": 5,
		"pagingType": "numbers",
		"oLanguage": { "sSearch": "" } 
	});
	//$('.dataTables_filter, .dataTables_filter label').addClass('pull-left');
});
 
$(function(){
      // bind change event to select
      $('#domain_name').on('change', function () {
          var domain = $(this).val(); // get selected value
          var url=_BASEPATH_+'campaign/create_campaign?domain='+domain;
		  //alert(url)
		  if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });

	$(function(){
	  
	  $("#datatable").on('change',"input[name='product_id']",function(e){ 
		// Do something interesting here
		$(".fsubmit").show();
		$("#campaign_from").html('<h4 class="text-center"><br/><br/><br/><br/><br/><br/><i class="btn fsubmit"></i><div class="col-xs-12">Choose a product, and continue to create campaign.</div></h4>');
		var product_id = $('input[name="product_id"]:checked').val();
		//alert("asas");
				$.ajax({
					  url :_BASEPATH_+'campaign/products_campaign_create',
					  type: "post",
					  data: { product_id: product_id},
					  beforeSend: function () {
						$('.fsubmit').show();
					  },
					  success: function (data) {
						  setTimeout(function(){
							$("#campaign_from").html(data);
							 $('.fsubmit').hide();
						   }, 1000);	 
					  }
				  });
	  });
	  // bind change event to select
   });
	
	$(document).ready(function(){
		$(".loadForm").click(function(e){
		var modalId = this.name;
		var url         = this.id;
		$.get(url, function( data ) {
				$("#loadData").html(data);
				$(modalId).modal('show');
			});
		});
		$('#refresh').click(function() {
			location.reload();
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