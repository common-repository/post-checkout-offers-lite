jQuery(document).ready( function($){
	
	$("#mwb_wcpcolite_go_to_parent").on("click" ,function(){
		
		$.ajax({
			type:'POST',
			url :ajax_url, 
			dataType: "json",
			data:{action:'mwb_wcpcolite_go_to_parent'},
			success:function(data){
				
				if(data.success == 'true'){
					
					window.location = data.url ;
				}
			} 

		});	
	});
});


