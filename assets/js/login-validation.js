$(document).ready(function () {
	$('#msg').hide();
    $("#form").validate({
        rules: {
			"email": {
				required: true,
				email: true
            },
            "password": {
                required: true
            }
        },
        messages: {
            email: {
                required: "Please enter CartWire registered email address.",
                email: "Please enter a valid email address."
            },
            password:"Please enter password"
        },
        submitHandler: function (form) {
		
            var formData = $(form).serialize();
			//var csrf = $('input[name="csrf_test_name"]').val();
			//formData.csrf_test_name = csrf;
			$('.modal-element').html('Authenticating...');
			$('#msg').html('');
			$('body').addClass("loading");
			$("#submit").prop('disabled', true);
			// AJAX Code To Submit Form.
			$.ajax({
				type: "POST",
				url: _BASEPATH_+"login/authenticate",
				data: formData,
				cache: false,
				success: function(res){
					var json = $.parseJSON(res);
					//alert(json['result'])
					if (json['result']=='success')
					{
						setTimeout(function(){
							$('.modal-element').hide().html('<span class="label label-success"><i class="glyphicon glyphicon-ok">&nbsp;</i>Redirecting</span>').fadeIn();
							setTimeout(function(){
								window.location = _BASEPATH_+"user/dashboard";
							}, 2000);
							
						}, 2000);
						
					}
					else
					{
						setTimeout(function(){
							$('.modal-element').hide().html('<span class="label label-danger"><i class="glyphicon glyphicon-remove">&nbsp;</i>Failed</span>').fadeIn();	
							setTimeout(function(){
								$('body').removeClass("loading");
								$("#submit").prop('disabled', false);
								$('#msg').html('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+json['mesg']+'</div>').fadeIn();
							}, 1000);
							
						}, 2000);
					}
				}
			});
        }
    });
    
	$(".fgetl, .fget").click(function () {
		// Set the effect type
		var effect = 'slide';
		  
		// Set the duration (default: 400 milliseconds)
		var duration = 800;		 
		 $('#login').toggle(effect, { direction: "down" }, duration);
		 $('#forget').toggle(effect, { direction: "down" }, duration);
	});
	$("#forgotpass").validate({
        rules: {			
            "email": {
                required: true,
                email: true,  
            }            		
        },
        messages: {			
            email: {
                required: "Please enter CartWire registered email address.",
                email: "Please enter a valid email address.",               
            },            		
        },
        submitHandler: function (form) {		
            var formData = $(form).serialize();
			$('.modal-element').html('Validating...');
			$('#msg').html('');
            $.ajax({
                url :_BASEPATH_+"login/forgotpassword",
                type: "post",
                data: formData,
                beforeSend: function () {
                  //$('.fsubmit').show();
				  $('body').addClass("loading");
                  $("#btnfget").prop('disabled', true);
                },
                success: function (data) {
					//alert(data);
					if(data=='true')
					{ 
						
						setTimeout(function(){
							$('.modal-element').hide().html('<span class="label label-success"><i class="glyphicon glyphicon-ok">&nbsp;</i>Sending Mail..</span>').fadeIn();
							setTimeout(function(){
								$('#passmsg').html('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Password has been sent successfully to your registered email address.</div>').fadeIn();
								$('body').removeClass("loading");
								$("#btnfget").prop('disabled', false);
								$('#forgotpass').each (function(){
									this.reset();
								});	
							}, 2000);	
						}, 2000);
						
					}
					else
					{
						setTimeout(function(){
							$('.modal-element').hide().html('<span class="label label-danger"><i class="glyphicon glyphicon-remove">&nbsp;</i>Failed</span>').fadeIn();	
							setTimeout(function(){	
								$('#passmsg').html('<div class="alert alert-danger alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Sorry! we don\'t find any account with the provided email. Please check once and try again.</div>').fadeIn();
								$('body').removeClass("loading");
								$("#btnfget").prop('disabled', false);
								$('#forgotpass').each (function(){
									this.reset();
								});	
							}, 2000);	
						}, 2000);
					}
				}
            });            
        }
    });
});