// Custom Javascript of chat
jQuery(document).ready(function($) {

	$('#chatmetrics_switch').change(function() { 
		var data = {
			'action': 'chatmetrics_switch',
			'chatenable': $(this).is(":checked"),
			'_ajax_nonce': ajax_object.nonce
		};		
		jQuery.post(ajax_object.ajax_url, data, function(response) {
			$('#chatenable-text').text(response.msg);
		});
    });

});