jQuery(document).ready(function(){
	jQuery('.videresend_item').each(function(){
		if( jQuery(this).attr('data-videresendt') == 'true' ) {
			load_kontroll( jQuery(this).attr('data-id'), jQuery(this).attr('id') );
		}
	});
});

// INFORMASJON OM HVORDAN LASTE OPP BILDER
jQuery(document).on('click', '.alertImageUpload', function( e ) {
	e.preventDefault();
	jQuery('#pageContainer').slideUp();
	jQuery('#pageAlertContainer').html( twigJSalertimageupload.render() ).slideDown();
});

// SKJUL INFORMASJON, VIS OVERSIKT I STEDET
jQuery(document).on('click', '.cancelAlert', function() {
	jQuery('#pageAlertContainer').html( 'Vennligst vent..' ).slideUp();
	jQuery('#pageContainer').slideDown();
});

// VELG (OG FORHÅNDVSVIS) ET BILDE
jQuery(document).on('click', '.image_selector_thumb', function(){
	var selected = jQuery('#image_selected_container');
	var image = jQuery('#image_selector_big');
	var select = jQuery(this).find('img');
	selected.slideDown();
	image.attr('data-id', select.attr('data-id') );
	image.attr('data-full', select.attr('data-full') );
	image.attr('data-thumb', select.attr('src') );
	image.attr('src', select.attr('data-full') );
});

// VELG (OG LAGRE) BILDE FOR GITT INNSLAG
jQuery(document).on('click', '#image_selector_select', function(){
	var selected = jQuery('#image_selector_big');
	
	var data = {
				action:			'UKMvideresending_festival_ajax',
				subaction:		'image_selector_set',
				rel_id:			selected.attr('data-id'),
				b_id:			jQuery(this).attr('data-bid'),
				t_id:			jQuery(this).attr('data-tid'),
				selector:		jQuery(this).attr('data-selector'),
				image_full:		selected.attr('data-full'),
				image_thumb:	selected.attr('data-thumb')
			};


	jQuery('#pageAlertContainer').html('Vent, lagrer...');

	jQuery.post(ajaxurl, data, function( response ) {
		var innslag = jQuery('#' + data.selector );
		var imageCont = innslag.find('td.imageSelect');
		var image = jQuery('<img />').attr('src', data.image_thumb); 
		var button = jQuery('<a />').attr('href','#').addClass('imageSelector btn btn-default btn-xs').html('Velg annet bilde');
		
		imageCont.removeClass('alert-danger').addClass('alert-success').html( image ).append('<br />').append( button );
		
		jQuery('#pageAlertContainer').slideUp().html( 'Vennligst vent..' );
		jQuery('#pageContainer').slideDown();
	});
});

// LAST INN BILDER AV GITT INNSLAG
jQuery(document).on('click', '.imageSelector', function( e ) {
	var innslag = jQuery(this).parents('tr');
	
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'image_selector_list', 
		b_id: innslag.attr('data-bid'),
		t_id: innslag.attr('data-tid'),
		selector: innslag.attr('id')
	};

	jQuery('#pageContainer').slideUp();
	jQuery('#pageAlertContainer').html( twigJSimageselectorload.render( {navn: innslag.attr('data-navn') }) ).slideDown();

	
	jQuery.post(ajaxurl, data, function(response) {
		var data = jQuery.parseJSON( response );
		
		jQuery('#pageAlertContainer').html( twigJSimageselectorshow.render( data ) );
	});
});


jQuery(document).on('click', 'input.videresend', function() {
	var innslag = jQuery(this).parents('tr.videresend_item');
	var detaljer = innslag.find('.videresend_detaljer');
		
	jQuery(this).attr('disabled',true);
	
	if( jQuery(this).is(':checked') ) {
		innslag.addClass('alert info');
		detaljer.html('Vent, videresender innslag...').slideDown();
		innslag.attr('data-status', 'videresender');
		
		videresend( innslag.attr('data-id'), innslag.attr('id') );
	} else {
		avmeld( innslag.attr('data-id'), innslag.attr('id') );
	}
});

jQuery(document).on('click', 'input.videresend_person', function(){
	var person = jQuery(this).parents('tr.person');

	jQuery(this).attr('disabled', true);
	if( jQuery(this).is(':checked') ) {
		person.find('.person_navn').html('Vent, videresender...');
		videresend_person( person.attr('data-id'), person.attr('id') );
	} else {
		person.find('.person_navn').html('Vent, melder av...');
		avmeld_person( person.attr('data-id'), person.attr('id') );
	}
});

function avmeld( ID, IDselector ) {
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'avmeld',
		id: ID,
		selector: IDselector
	};

	var innslag = jQuery('#' + IDselector);
	var detaljer = innslag.find('.videresend_detaljer');
		
	innslag.find('input.videresend').attr('disabled',true);
	detaljer.html('Vent, melder av...');

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		var data = jQuery.parseJSON( response );
		var innslag = jQuery('#'+ data.selector);

		if( data.success ) {
			display_avmeld( data.selector );
		} else {
			alert('Kunne ikke avmelde!');
			detaljer.html('Vent, laster detaljer');
			load_kontroll( data.selectorID, data.selector)
			innslag.find('input.videresend').attr('checked', true);
		}
		innslag.find('input.videresend').attr('disabled', false)
	});

}

function videresend( ID, IDselector) {
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'videresend',
		id: ID,
		selector: IDselector
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		handleVideresendToggle( response );
	});
}

function load_kontroll( ID, IDselector ) {
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'load_kontroll',
		id: ID,
		selector: IDselector
	};

	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(ajaxurl, data, function(response) {
		data = jQuery.parseJSON( response );
		jQuery('#'+data.selector).find('.videresend_detaljer').html( twigJSkontrollertittel.render( data ) );
	});
}

function videresend_person( ID, IDselector ) {	
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'videresend_person',
		id: ID,
		selector: IDselector
	};

	jQuery.post(ajaxurl, data, function(response) {
		data = jQuery.parseJSON( response );
		var person = jQuery( '#' + data.selector );

		if( data.success ) {
			person.addClass('success');
		} else {
			alert('Kunne ikke videresende person!');
			person.removeClass('success');
			person.find('input.videresend_person').attr('checked', false);
		}
		person.find('input.videresend_person').attr('disabled', false);
		person.find('.person_navn').html( person.find('.person_navn').attr('data-navn') );
	});
}

function avmeld_person( ID, IDselector ) {	
	var data = {
		action: 'UKMvideresending_festival_ajax',
		subaction: 'avmeld_person',
		id: ID,
		selector: IDselector
	};

	jQuery.post(ajaxurl, data, function(response) {
		data = jQuery.parseJSON( response );
		var person = jQuery( '#' + data.selector );

		if( data.success ) {
			person.removeClass('success');
		} else {
			alert('Kunne ikke avmelde person!');
			person.addClass('success');
			person.find('input.videresend_person').attr('checked', true);
		}
		person.find('input.videresend_person').attr('disabled', false);
		person.find('.person_navn').html( person.find('.person_navn').attr('data-navn') );
	});
}
function handleVideresendToggle( response ) {
	data = jQuery.parseJSON( response );
	var innslag = jQuery( '#' + data.selector );
	var detaljer = innslag.find('.videresend_detaljer');
	var checkbox = innslag.find('input.videresend');

	if( !data.videresendt ) {
		alert('Kunne ikke videresende!');
		display_avmeld( data.selector );
	} else {
		innslag.addClass('success').removeClass('info').attr('data-status', 'videresendt');
		detaljer.html('Innslag videresendt, last kontrolliste!');
		detaljer.html( twigJSkontrollertittel.render( data ) );
		checkbox.attr('disabled',false);
	}
}

function display_avmeld( selector ) {
	var innslag = jQuery( '#' + selector );
	innslag.removeClass('success').removeClass('info').attr('data-status', 'ikke_videresendt');
	innslag.find('.videresend_detaljer').slideUp();
	innslag.find('input.videresend').attr('disabled',false).attr('checked',false);
}

/*jQuery(document).ready(function(){
//    twigJShelloworld.render({world: 'cupcake'})
});
*/