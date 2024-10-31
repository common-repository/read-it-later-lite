
(function($){



//ADD POST TO RIL
	jQuery('.addToReadItLaterButton').click(function(e){
		
		e.preventDefault();
		
		var postID = jQuery(this).attr('data-readitlater-id');
		
		
		jQuery.ajax({
		  type: 'POST',
		  url: gema75_readitlater_js_strings.admin_ajax_url,
		  data: {
			action: 'maybe_add_to_ril_ajax',
			post:  postID
		  },
		  dataType: "json",
		  success: function(response, textStatus, XMLHttpRequest){
			
			
			//check response 
			if(response==='postAlreadyInRIL'){
			
				//console.log('Already Exists in RIL');
				
				jQuery('.addToReadItLaterButton[data-readitlater-id="'+ postID +'"]').append('<span class="alreadyExists">' + gema75_readitlater_js_strings.AlreadyExists + '</span>');
				
			}else{
				
				//replace "add to RIL" with "added to RIL"
				jQuery('.addToReadItLaterButton[data-readitlater-id="'+ postID +'"]').html(gema75_readitlater_js_strings.addedToRilList);
				//change the class
				jQuery('.addToReadItLaterButton[data-readitlater-id="'+ postID +'"]').addClass('addedToRilListButton');
				jQuery('.addToReadItLaterButton[data-readitlater-id="'+ postID +'"]').removeClass('addToReadItLaterButton');
				
			 }
			
		  },
		  error: function(MLHttpRequest, textStatus, errorThrown){
			console.log(errorThrown);
		  }
		});		
		

	});



	
	

//REMOVE SINGLE POST FROM RIL	

	jQuery("body").on("click",".removeFromRILButton",function(e) {
	

		e.preventDefault();
		
		var postID = jQuery(this).attr('data-readitlater-id');
		
		var elementiMeIndex = jQuery(this).parent().parent().index();
		
		jQuery.ajax({
		  type: 'POST',
		  url: gema75_readitlater_js_strings.admin_ajax_url,
		  data: {
			action: 'remove_post_from_ril_list_ajax',
			post:  postID
		  },
		  dataType: "json",
		  success: function(response, textStatus, XMLHttpRequest){
			console.log(response);

			location.reload();

		  },
		  error: function(MLHttpRequest, textStatus, errorThrown){
			console.log(errorThrown);
		  }
		});		
		

	});


	
//REMOVE ALL POSTS FROM RIL	

	jQuery("body").on("click",".gema75_removeAllFromRILButton",function(e) {
		
		e.preventDefault();

		jQuery.ajax({
		  type: 'POST',
		  url: gema75_readitlater_js_strings.admin_ajax_url,
		  data: {
			action: 'remove_all_posts_from_ril_ajax'
		  },
		  dataType: "json",
		  success: function(response, textStatus, XMLHttpRequest){

		  },
		  error: function(MLHttpRequest, textStatus, errorThrown){
			console.log(errorThrown);
		  }
		});		
		

	});	

		
})(jQuery);
