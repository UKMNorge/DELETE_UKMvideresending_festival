<?php
require_once('UKM/innslag.class.php');
require_once('UKM/person.class.php');

$data = new stdClass();

$ID = array();
$whoami = explode( '|', $_POST['id'] );
foreach( $whoami as $iam ) {
	$who = explode( ':', $iam );
	$ID[ $who[0] ] = $who[1];
}

$innslag = new innslag( $ID['innslag'] );
$innslag->avmeld( $m->g('pl_id'), $videresendtil->ID, $ID['tittel'] );
if( $innslag->tittellos() ) {
	$person = new person( $ID['person'], $ID['innslag'] );
	$person->avmeld( $m->g('pl_id'), $videresendtil->ID, 'notitle' );
}

$data->selector = $_POST['selector'];
$data->selectorID = $_POST['id'];
$data->success = true;

die(json_encode($data));