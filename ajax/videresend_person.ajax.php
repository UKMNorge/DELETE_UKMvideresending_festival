<?php
$data = new stdClass();

$ID = array();
$whoami = explode( '|', $_POST['id'] );
foreach( $whoami as $iam ) {
	$who = explode( ':', $iam );
	$ID[ $who[0] ] = $who[1];
}


require_once('UKM/write_innslag.class.php');
require_once('UKM/write_person.class.php');
require_once('UKM/monstring.class.php');
require_once('UKM/logger.class.php');

global $current_user;
get_currentuserinfo();  
UKMlogger::setID( 'wordpress', $current_user->ID, get_option('pl_id') );

$monstring = new monstring_v2( $videresendtil->ID );
$innslag = $monstring->getInnslag()->get( $ID['innslag'] );
$person = $innslag->getPersoner()->get( $ID['person'] );

try {
	$innslag->getPersoner()->leggTil( $person );
	write_person::leggTil( $person );
	$data->success = true;
} catch( Exception $e ) {
	$data->success = false;
	$data->error = $e->getMessage();
}

$data->selector = $_POST['selector'];

rekalkuler_videresendte_personer($m->g('pl_id'), $videresendtil->ID);

$data->personSelector = 'person-'. $ID['person'].'-i-'.$ID['innslag'];

die(json_encode($data));