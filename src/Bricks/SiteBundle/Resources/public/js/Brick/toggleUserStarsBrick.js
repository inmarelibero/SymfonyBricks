/**
 * initialize all .toggle-user-stars-brick elements to toggle the "Starred" state to a brick
 */
(function($) {
    $('.toggle-user-stars-brick').each(function (i, e) {
		
		// source element that called this function
		var src = $(this);
    	
    	// jquery element of the source to be replaced with a different text
    	var text = src.find('#text');
    	
		$(this).click(function() {
			
			// url to toggle the "star" state
			var url = Routing.generate('user_brick_toggle_star', {'brick_id': src.data('brick-id')});
			
			//console.log(url);
			
			/**
			 * perform ajax call
			 */
			$.ajax({
		        url:        url,
		        //dataType :  "jsonp",
		        success:    function(data) {
		        	
		        	// update the src element
		            if (data.action == 'starred') {
		            	
		            	// update 'state' data
		            	src.data('state', 'starring');
		            	
		            	// update text
		            	text.html(src.data('starring-text'));
		            	
		            	// add 'active' class
		            	src.addClass('active');
		            } else if (data.action == 'unstarred') {
		            	
		            	// update 'state' data
		            	src.data('state', 'notstarring');
		            	
		            	// update text
		            	text.html(src.data('notstarring-text'));
		            	
		            	// remove 'active' class
		            	src.removeClass('active');
		            }
		        },
		        error:		function(event) {
		        	// @TODO: display a modal login box
		        }
		    });
		});

    });
}(jQuery));
