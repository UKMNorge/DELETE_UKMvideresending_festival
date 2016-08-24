jQuery(document).ready(function() {
	jQuery("#infoskjema_submit").click(function( e ) {
		e.preventDefault();
		// Find vars

		var form = {}
		var skjema = jQuery.each(jQuery("#infoskjema").serializeArray(), function() {
			form[this.name] = this.value;
		});
		//console.log(skjema);
		//console.log(form);
		
		

		// Post AJAX-call to save-handler
		var postdata = {
			action: 'UKMvideresending_festival_ajax',
			subaction: 'infoskjema_save',
			skjema: form
		};
		
		jQuery.post(ajaxurl, postdata, function(response) {
			var data = jQuery.parseJSON( response );
			console.log(data);
			//jQuery("#UKM_content_wrapper").append()
		});

	})
});