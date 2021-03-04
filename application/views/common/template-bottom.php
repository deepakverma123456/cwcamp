		<!-- footer content -->
         <?php include("footer.php"); ?>
        <!-- /footer content -->
      </div>
    </div>
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
	<script type = 'text/javascript' src = "<?php echo base_url(); ?>assets/js/custom.js"></script>
	<script>var _BASEPATH_= '<?php echo base_url(); ?>';
	$(document).ajaxSend(function(e, xhr) {
	  $.ajax({
		  url :_BASEPATH_+'ajaxlogout/index', type: 'GET', global: false, success:
		  function(session) {
			  //alert(session['result'])
			  if(session['result']!='success') {
				  window.location.href = _BASEPATH_+'login';
			  }else{
				 //window.location.href = _BASEPATH_+'login';
			  }
		  }, dataType: 'json'
		});
	});
	</script>
  </body>
</html>
