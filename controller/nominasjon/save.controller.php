<?php
require_once('UKM/logger.class.php');
require_once('UKM/write_nominasjon.class.php');
require_once('UKM/write_nominasjon_media.class.php');
#require_once('UKM/write_nominasjon_arrangor.class.php');
#require_once('UKM/write_nominasjon_konferansier.class.php');

$monstring = new monstring_v2( get_option('pl_id') );

#echo '<pre>';var_dump( $_POST );echo'</pre>';

global $current_user;
UKMlogger::setID( 'wordpress', $current_user->ID, get_option('pl_id') );

$nominert = new innslag_v2( $_GET['id'] );

// DO CREATE GENERIC NOMINASJON
$nominasjon = write_nominasjon::create( 
	$nominert->getId(),				// Innslag ID
	get_option('season'), 			// Sesong
	'land', 						// TODOondemand: støtt også nominasjon fra lokal til fylke
	$nominert->getKommune(),		// Innslagets kommune
	$nominert->getType()->getKey()	// Type nominasjon
);

$nominasjon->setSamarbeid( utf8_encode( $_POST['samarbeid'] ) );
$nominasjon->setErfaring( utf8_encode( $_POST['erfaring'] ) );
$nominasjon->save();

$voksen = write_nominasjon::createVoksen( $nominasjon->getId() );
$voksen->setNavn( utf8_encode( $_POST['voksen-navn'] ) );
$voksen->setMobil( $_POST['voksen-mobil'] );
$voksen->setRolle( utf8_encode( $_POST['voksen-rolle'] ) );
$voksen->save();

write_nominasjon::saveNominertState( $nominasjon, true );