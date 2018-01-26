jQuery(document).on('change', 'input.nominasjonstatus', function(){
	var innslag = jQuery(this).parents('tr.nominert-header');

	jQuery(document).trigger('nominasjon:'+ (jQuery(this).is(':checked') ? 'show':'hide'), [innslag.attr('data-id')]);
});


jQuery(document).on('nominasjon:show', function( e, id ) {
	jQuery('#nominert-header-'+id).addClass('alert-success border-nominasjon');
	jQuery('#nominert-data-' + id ).addClass('border-nominasjon').slideDown(); //.addClass('alert-success')
});


jQuery(document).on('nominasjon:hide', function( e, id ) {
	jQuery('#nominert-header-'+id).removeClass('alert-success border-nominasjon');
	jQuery( '#nominert-data-' + id ).removeClass('alert-success border-nominasjon').slideUp();
});

jQuery(document).on('click', '.nominasjon-sms-purring', function(e) {
	e.preventDefault();
	
	jQuery('#nominasjon-reminder-recipient').val( jQuery(this).attr('data-tel') );
	jQuery('#nominasjon-reminder').submit();
});