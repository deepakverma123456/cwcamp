 $(document).ready(function() {
	$('#datatable').DataTable();
	$("#datatable").on('click',".status_checks",function(){ 
		var id 			= this.id;
		var status 	= this.name;				
		var url 		= _BASEPATH_+"user/update_user_status";
		$("#"+id).html('<i class="fa fa-spinner" aria-hidden="true"></i>');
		$.ajax({
			type:"POST",
			url: url,
			data: {id:id,status:status},
			success: function(data)
			{
				if(data=='Active' || data=='Blocked')
					{										
						$('#holder span.notify').html("User status changed successfully.");
						$('#holder').fadeIn();
						setTimeout(function(){										
							$("#"+id).attr("name",data).html(data);
							$('#holder').fadeOut();
						}, 2000);	
					}
					else
					{
						$('#holder span.notify').html("Some error occured. Please try again.");
						$('#holder').fadeIn();
						setTimeout(function(){										
							$('#holder').fadeOut();
						}, 2000);
					}						
			}
		});				
	});
	$("#datatable").on('click',".fa-times",function(){ 
		var id 			= this.id;				
		if(confirm("Are you sure you want to delete this user?")){
			var url 		= _BASEPATH_+"user/delete_user";	
			$(this).addClass("fa-spinner").removeClass("fa-times");
			$.ajax({
				type:"POST",
				url: url,
				data: {id:id},
				success: function(data)
				{							
						if(data==1)
						{									
							$('#holder span.notify').html("User deleted successfully.");
							$('#holder').fadeIn();
							setTimeout(function(){										
								$('#row_'+id).addClass("deltr").find( "td:last" ).html("");
								$('#'+id).parent().html("Deleted");
								$('#holder').fadeOut();
							}, 2000);	
						}
						else
						{									
							$('#holder span.notify').html("Some error occured. Please try again.");
							$('#holder').fadeIn();
							setTimeout(function(){										
								$('#holder').fadeOut();
							}, 2000);
						}								
				}
			});
		}
	});
	
});