$(document).ready(function () {
    $("#update_campaign_form").validate({
        rules: {
			"campaign_name": {
                required: true,
            },
            "promo_code": {
                required: true,
            },
			"retailer_id": {
                required: true,
            },
			"userCampaignUrl": {
                required: true,
				url: true,
            }
        },
        messages: {
			campaign_name:"Please provide a campaign name.",
            promo_code: {
                required: "Campaign code is required, click on the link below to generate",
            },
			retailer_id: {
                required: "Please select a retailer.",
            },
			userCampaignUrl: {
                required: "Enter a valid retailer page url, where the campaign will redirect.",
				url: "URL doesn't seems valid, please enter a valid url.",
            }
			
        },
        submitHandler: function (form) {
			var formData = $(form).serialize();
			//alert(formData);
            $.ajax({
                url :_BASEPATH_+"campaign/update",
                type: "post",
                data: formData,
                beforeSend: function () {
                  $('.fsubmit_campaign').show();
                  $("#submit").prop('disabled', true);
                },
                success: function (data) {
					//alert(data);
					var json = $.parseJSON(data);
					//alert(json['hash_key']);
					$('.fsubmit_campaign').hide();
                    $("#submit").prop('disabled', false);
					if (json['result']=='success') {
					$('#msg').html('<div class="modal-body"><div class="form-group col-md-12 col-sm-12 col-xs-12 nopadding-lr" id="msg_hide"><div class="alert alert-success alert-dismissable">'+json['mesg']+'</div></div><div class="form-group col-md-12 col-sm-12 col-xs-12 nopadding-lr"><label for="userCampaign">Camapign Code</label>:'+json['promo_code']+'</div><div class="form-group col-md-12 col-sm-12 col-xs-12 nopadding-lr"><label for="userCampaign">Camapign Name</label>:'+json['widget_name']+'</div><div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12"><div aria-multiselectable="true" role="tablist" id="accordion_19" class="panel-group full-body"><div class="panel panel-info"><div id="heading_2" role="tab" class="panel-heading"><h4 class="panel-title"><a aria-controls="collapse_2" aria-expanded="true" href="#collapse_2" id="collapse_module_2" aria-expanded="true" data-toggle="collapse" class="" role="collapsed">Get Campaign Url<span class="fa fa-chevron-down pull-right"></span></a></h4></div><div aria-labelledby="heading_2" role="tabpanel" class="panel-collapse collapse in" id="collapse_2"><button class="btn btn-default" type="button" id="copycode" data-toggle="tooltip" data-placement="button" title="Copy to Clipboard" >Copy</button><p id="textarea_code">'+_BASEPATH_+'deploy/campaign?hkey='+json['hash_key']+'</p></div></div></div></div></div>').fadeIn();
					$('#inner_campaign_div').hide();
					setTimeout(function(){
						$('#msg_hide').html('');
					}, 2000);
					}else{
						
						//
					}
					//$('#msg').html('');
					//setTimeout(function(){
					//	$('#msg').html('');
					//}, 2000);
					//header('Location: '+_BASEPATH_+'campaign/campaign_list_all');
					//window.location.href = _BASEPATH_+'campaign/all';
					$('#form').each (function(){
						this.reset();
					});
				}
            });
            
        }
    });
	
	$("#cp_form").validate({
        rules: {
			"old_password": {
                required: true,
            },
            "new_password": {
                required: true,
            },
			"confirm_password": {
                required: true,
				equalTo : "#new_password"
            }
        },
        messages: {
			old_password:"Please provide a Old Password.",
            new_password: {
                required: "Please provide a New Password.",
            },
			confirm_password: {
                required: "Please provide a Confirm Password.",
				equalTo : "Please match a Confirm Password."
            }
			
        },
        submitHandler: function (form) {
			var formData = $(form).serialize();
			//alert(formData);
            $.ajax({
                url :_BASEPATH_+"profile/update_password",
                type: "post",
                data: formData,
                beforeSend: function () {
				  $('.fsubmit').show();
                  $("#submit").prop('disabled', true);
                },
                success: function (data) {
					//alert(data);
					var json = $.parseJSON(data);
					$('.fsubmit').hide();
                    $("#submit").prop('disabled', false);
					if (json['result']=='success') {
						setTimeout(function(){
						$('#msg').html('<div class="alert alert-success alert-dismissable">'+json['mesg']+'</div>').fadeIn();
						}, 1000);
					}else{
						setTimeout(function(){
						$('#msg').html('<div class="alert alert-danger alert-dismissable">'+json['mesg']+'</div>').fadeIn();
						}, 1000);
					}
					//$('#msg').html('');
					//setTimeout(function(){
					//	$('#msg').html('');
					//}, 2000);
					//header('Location: '+_BASEPATH_+'campaign/campaign_list_all');
					//window.location.href = _BASEPATH_+'campaign/all';
					$('#form').each (function(){
						this.reset();
					});
				}
            });
            
        }
    });
	
	function CopyToClipboard(containerid) {
		if (document.selection) {
			var range = document.body.createTextRange(); range.moveToElementText(document.getElementById(containerid)); range.select().createTextRange(); document.execCommand("Copy");
			} else if (window.getSelection) {
				var range = document.createRange();
				range.selectNode(document.getElementById(containerid));
				window.getSelection().addRange(range);
				var success = document.execCommand('copy');
				
				if (success) {
				  $('#copycode').trigger('copied', ['Copied!']);
				} else {
				  $('#copycode').trigger('copied', ['Copy with Ctrl-c']);
				}
				
				if (window.getSelection) { if (window.getSelection().empty) {
					// Chrome
					window.getSelection().empty();
					} else if (window.getSelection().removeAllRanges) {
						// Firefox
						window.getSelection().removeAllRanges();
					}
				} else if (document.selection) {
							// IE?
							document.selection.empty();
				}
			}
		}
	//$("#copycode").on("change",function(e){
	$('#copycode').tooltip();
	$("body").on("click","#copycode",function(e){ CopyToClipboard('textarea_code');
			//alert("sdss")
			$('#copycode').bind('copied', function(event, message) {
				//alert(message)
					setTimeout(function(){
						$('#copycode').attr('title', message)
						.tooltip('fixTitle')
						.tooltip('show')
						.attr('title', "Copy to Clipboard")
						.tooltip('fixTitle');
					}, 1000);
			});
			
	});
	
    $("#productform").validate({
        rules: {
			"product_url": {
                required: true,
				url: true,
            },
			"product_name": {
                required: true,
            },
            "product_image_url": {
                required: true,
            }
        },
        messages: {
			product_url: {
				required: "Enter a valid retailer product url.",
				url: "URL doesn't seems valid, please enter a valid url.",
            },
			product_name: {
				required: "Enter a retailer product name.",
            },
			product_image_url:{
				required: "Enter a retailer product image url.",
			}
        },
        submitHandler: function (form) {		
            var formData = $(form).serialize();
            $.ajax({
                url :_BASEPATH_+"campaign/create_product",
                type: "post",
                data: formData,
                beforeSend: function () {
                  $('.fsubmit').show();
                  $("#submit").prop('disabled', true);
                },
                success: function (data) {
					
                    //$("#submit").prop('disabled', false);not required here
					var json = $.parseJSON(data);
					
					if (json['result']=='success') {
						$('#model_link').html('<input type="hidden" name="product_retailer_id" value="'+json['id']+'"><span style="color:#169F85">&nbsp;<i class="fa fa-check"></i>'+json['mesg']+'.</span>').fadeIn();
					}else{
						$('#model_link').html('<div class="alert class="error"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+json['mesg']+'.</div>').fadeIn();
					}
					setTimeout(function(){
						$('.fsubmit').hide();
						$('#msgp').html('<div class="alert alert-success alert-dismissable"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+json['mesg']+'.</div>').fadeIn();
					}, 2000);
					setTimeout(function(){
						$('#msgp').fadeOut().html('');
						$( ".close" ).trigger( "click" );
						 $("#submit_campaign").prop('disabled', false);
					}, 5000);
						
					$('#form').each (function(){
						//this.reset();
						this.attr('readonly', true);
					});
				}
            });
            
        }
    });

});

$(function(){
		var product_id = $('input[name="product_id"]:checked').val();
		var url=_BASEPATH_+'campaign/create_promo';
		//$("#promo_code_generate span").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		$("#promo_code_generate").click(function(){
			$("#promo_code_generate span").html('<i class="fa fa-spinner" aria-hidden="true"></i>');
			$.ajax({
				  type: 'GET',
				  url: url,
				  data: { product_id: product_id},
				  datatype: 'html',
				  cache: 'false',
				  success: function(response) {
					setTimeout(function(){
									 $('#promo_code').val(response);
									 $("#promo_code_generate span").html('');
							   }, 3000);
					},
					error: function(){
					  alert('Error');
					}
			});
		});

		$("#userCampaignUrl").blur(function(){
		  var userCampaignUrl = $(this).val();
		  var retailer_id	= $("#retailer_id").val();
		  var brand 		= $("#brand").val();
		  var product_id 	= $('input[name="product_id"]:checked').val();
		  var url			= _BASEPATH_+'campaign/check_campagn_url';
		  $("#submit_campaign").prop('disabled', true);
		  if(userCampaignUrl == "") return false;
		  $.ajax({
			  type: 'GET',
			  url: url,
			  data: { product_id: product_id,retailer_id: retailer_id,campaign_url:userCampaignUrl,brand:brand},
			  datatype: 'html',
			  cache: 'false',
			  success: function(response) {
				//alert(response)
				var json = $.parseJSON(response);
				//alert(json)
				if (json['result']=='success') {
					  //code		
					  setTimeout(function(){
								 $('#promotion_final').html('<input type="hidden" name="product_retailer_id" value="'+json['id']+'"><span style="color:#169F85">&nbsp;<i class="fa fa-check"></i> Click the button below to create product campaign.&nbsp;</span>').fadeIn();
								 $("#submit_campaign").prop('disabled', false);
								 $('#productDiv').hide();
						   }, 1000);
				}else{
				  $('#promotion_final').html('<div id="model_link" class="error">Retailer URL does not exist in our database. <a id="'+_BASEPATH_+'campaign/create_product" href="javascript:void(0)" class="loadForm" name="#myModal"><b>Click to add</b></a><div>').fadeIn();
				 $("#submit_campaign").prop('disabled', true);
				}
			  },
			  error: function(){
				  alert('Fuuuuuuuuuuuuuu');
			  }
		  }); // End Ajax  
		});
		
	$("#retailer_id").on("change",function(e){
		$("#userCampaignUrl").val('');
		//if($(this).val() == '') { $("#userCampaignUrl").attr('readonly', true).val(""); $("#submit_campaign").prop('disabled', true);$("#promotion_final").html(''); } 
		//else $("#userCampaignUrl").removeAttr('readonly');
	});	
});

