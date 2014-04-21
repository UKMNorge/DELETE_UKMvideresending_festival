<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');

$data = (object) $_POST;

$ID = array();
$whoami = explode( '|', $_POST['ID'] );
foreach( $whoami as $iam ) {
	$who = explode( ':', $iam );
	$ID[ $who[0] ] = $who[1];
}

// Oppdateringer av innslag
$innslag = new innslag( $ID['innslag'] );
if( isset( $_POST['innslag_tekniske_behov'] ) ) {
	$innslag->update('td_demand', 'innslag_tekniske_behov');
}

// Oppdateringer av tittel
$tittel = new tittel($ID['tittel'], $innslag->g('bt_form'));

if( isset( $_POST['tittel_tittel'] ) ) {
	$tittel->update( 'tittel' , 'tittel_tittel');
}
if( isset( $_POST['tittel_varighet_min'] ) ) {
	$_POST['tittel_varighet'] = (60*(int)$_POST['tittel_varighet_min']) + (int) $_POST['tittel_varighet_sek'];
	$tittel->update( 'varighet' , 'tittel_varighet');
}
if( isset( $_POST['tittel_beskrivelse'] ) ) {
	$tittel->update( 'beskrivelse' , 'tittel_beskrivelse');
}
if( isset( $_POST['tittel_type'] ) ) {
	$tittel->update( 'type' , 'tittel_type');
}
if( isset( $_POST['tittel_teknikk'] ) ) {
	$tittel->update( 'teknikk' , 'tittel_teknikk');
}
if( isset( $_POST['tittel_format'] ) ) {
	$tittel->update( 'format' , 'tittel_format');
}

if( isset( $_POST['person'] ) && is_array( $_POST['person'] ) ) {
	foreach( $_POST['person'] as $p_id => $p_data ) {
		$person = new person( $p_id, $ID['innslag'] );
		if( isset( $p_data['mobil'] ) ) {
			$_POST['person_mobil'] = $p_data['mobil'];
			$person->update('p_phone', 'person_mobil');
		}
		if( isset( $p_data['alder'] ) ) {
			if( $p_data['alder'] > 0 ) {
				$year = (int) date('Y');
				$date = '1 January ' . ($year - (int) $p_data['alder']);
				$_POST['person_alder'] = strtotime( $date );
			} else {
				$_POST['person_alder'] = 0;
			}
			$person->update('p_dob', 'person_alder');
		}
		if( isset( $p_data['instrument'] ) ) {
			$_POST['person_instrument'] = $p_data['instrument'];
			$person->update('instrument', 'person_instrument');
		}
	}
}

$data->success = true;

die( json_encode( $data ) );