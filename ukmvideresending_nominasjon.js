jQuery(document).on('change', 'input.nominasjonstatus', function(){
	var innslag = jQuery(this).parents('li.nominert-header');

	jQuery(document).trigger('nominasjon:'+ (jQuery(this).is(':checked') ? 'show':'hide'), [innslag.attr('data-id')]);
});


jQuery(document).on('nominasjon:show', function( e, id ) {
	jQuery('#nominert-header-'+id).addClass('nominert');
	jQuery('#nominert-data-' + id ).addClass('nominert').slideDown(); //.addClass('alert-success')
});


jQuery(document).on('nominasjon:hide', function( e, id ) {
	jQuery('#nominert-header-'+id).removeClass('nominert');
	jQuery( '#nominert-data-' + id ).removeClass('nominert').slideUp();
});

jQuery(document).on('click', '.nominasjon-sms-purring', function(e) {
	e.preventDefault();
	
	jQuery('#nominasjon-reminder-recipient').val( jQuery(this).attr('data-tel') );
	jQuery('#nominasjon-reminder').submit();
});


jQuery(document).on('change', 'input.nominasjonstatus', function(){
	console.log( jQuery( this ).is(':checked') );
	
	data = {
		action: 'UKMnominasjon_toggleStatus',
		innslag: jQuery(this).parents('li.nominert-header').attr('data-id'),
		status: jQuery(this).is(':checked')
	};
	
	jQuery.post(ajaxurl, data, function(response ) {
		//console.log(response);
	});
});