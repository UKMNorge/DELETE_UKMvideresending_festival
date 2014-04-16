<?php
require_once('UKM/tittel.class.php');
require_once('UKM/innslag.class.php');
$data = load_kontroll_data( $m, $videresendtil );

$innslag = new innslag( $data->ID );

if( $data->tittellos ) {
	$ID = array();
	$whoami = explode( '|', $_POST['id'] );
	foreach( $whoami as $iam ) {
		$who = explode( ':', $iam );
		$ID[ $who[0] ] = $who[1];
	}

	$person = new person( $ID['person'], $ID['innslag'] );
	$data->videresendt = $innslag->videresend( $m->g('pl_id'), $videresendtil->ID);
	$data->videresendt = $person->videresend( $m->g('pl_id'), $videresendtil->ID, 'notitle');
} else {
	$data->videresendt = $innslag->videresend( $m->g('pl_id'), $videresendtil->ID, $data->tittel->t_id );
}

die( json_encode( $data ) );