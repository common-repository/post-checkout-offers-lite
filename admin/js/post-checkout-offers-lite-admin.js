jQuery(document).ready( function($){

	jQuery('.mwb_wcpcolite_delete_old_created_offers').on("click",function(e){
		e.preventDefault();
    	var btn_id = $(this).data("id");
		jQuery("div.new_created_offers[data-id='" + btn_id + "']").slideUp( "normal", function() { $(this).remove(); } );
	});

	jQuery('.wc-funnel-product-search').select2({
  		ajax:{
    			url: ajaxurl,
    			dataType: 'json',
    			delay: 200,
    			data: function (params) {
      				return {
        				q: params.term,
        				nonce:localized_data.mwb_wcpcolite_search_products_nonce,
        				action: 'search_products_for_funnel_lite'
      				};
    			},
    			processResults: function( data ) {
				var options = [];
				if ( data ) 
				{
					$.each( data, function( index, text )
					{
						text[1]+='( #'+text[0]+')';
						options.push( { id: text[0], text: text[1]  } );
					});
				}
				return {
					results:options
				};
			},
			cache: true
		},
		minimumInputLength: 3 // the minimum of symbols to input before perform a search
	});
	jQuery('.wc-offer-product-search').select2({
  		ajax:{
    			url: ajaxurl,
    			dataType: 'json',
    			delay: 200,
    			data: function (params) {
      				return {
        				q: params.term,
        				nonce:localized_data.mwb_wcpcolite_search_products_nonce,
        				action: 'search_products_for_offers_lite'
      				};
    			},
    			processResults: function( data ) {
				var options = [];
				if ( data ) 
				{
					$.each( data, function( index, text )
					{
						text[1]+='( #'+text[0]+')';
						options.push( { id: text[0], text: text[1]  } );
					});
				}
				return {
					results:options
				};
			},
			cache: true
		},
		minimumInputLength: 3 // the minimum of symbols to input before perform a search
	});
	jQuery('button#create_new_offer').on("click",function(e){
		e.preventDefault();
		var index = $('.new_created_offers:last').data('id');
		var funnel = $(this).data('id'); 
		var nonce = $(this).data('create_new_offer_nonce');
		$("#mwb_wcpcolite_loader").removeClass('hide');
		$("#mwb_wcpcolite_loader").addClass('show');
		send_post_request(index,funnel,nonce);	

	});
	function send_post_request(index,funnel,nonce)
	{
		++index;
		$.ajax({
		    type:'POST',
		    url :localized_data.ajax_url,
		    data:{action:'mwb_wcpcolite_return_offer_content',mwb_wcpcolite_flag:index,mwb_wcpcolite_funnel:funnel,create_new_offer_nonce:nonce},
		    success:function(data)
		    {
		    	jQuery("#mwb_wcpcolite_loader").removeClass('show');
				jQuery("#mwb_wcpcolite_loader").addClass('hide');
		    	jQuery('.new_offers').append(data);
		    	jQuery('.new_created_offers').slideDown(1500);
		    	jQuery('#create_new_offer').hide();
		    	jQuery('.wc-offer-product-search').select2({
			  		ajax:{
			    			url: ajaxurl,
			    			dataType: 'json',
			    			delay: 200,
			    			data: function (params) {
			      				return {
			        				q: params.term, 
			        				nonce:localized_data.mwb_wcpcolite_search_products_nonce,
			        				action: 'search_products_for_offers_lite'
			      				};
			    			},
			    			processResults: function( data ) {
							var options = [];
							if ( data ) 
							{
								$.each( data, function( index, text )
								{
									text[1]+='( #'+text[0]+')';
									options.push( { id: text[0], text: text[1]  } );
								});
							}
							return {
								results:options
							};
						},
						cache: true
					},
					minimumInputLength: 3 // the minimum of symbols to input before perform a search
				});
		    }
	   });
    }   

    jQuery(document).on('click' , '#mwb_wcpcolite_creation_setting_save' , function (e){
    	
    	var submit = true ; 
    	
    	html = "" ; 
    	if($('.wc-funnel-product-search').val() == null){
    		submit = false ; 
    		html += "<p>"+localized_data.mwb_wcpcolite_target_notice+"</p>" ; 
    	} 

    	if($('.wc-offer-product-search').val() == null){
    		submit = false ; 
    		html += "<p>"+localized_data.mwb_wcpcolite_offer_notice+"</p>" ;
    	}

    	if($('#mwb_wcpcolite_offer_discount').val() == "" || 
    		$('#mwb_wcpcolite_offer_discount').val() == "0"){
    		submit = false ; 
    		html += "<p>"+localized_data.mwb_wcpcolite_discount_notice+"</p>" ;
    	}

    	if(!submit){
    		e.preventDefault();
    		$('.wcpcolite_notice').html(html);
    		$('.wcpcolite_notice').slideDown();
    		$('html, body').animate({
     			scrollTop: $("body").offset().top

     		} , 1000);
    	}

    });
});