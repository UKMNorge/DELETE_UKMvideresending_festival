<?php
require_once('UKM/monstring.class.php');
require_once('UKM/monstringer.class.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	if( in_array( $_GET['save'], ['nettredaksjon','media','konferansier','arrangor'] ) ) {
		require_once('save.controller.php');
	}
}

$TWIG['nominasjonstyper'] = [
	'arrangor' => 'Arrangører',
	'konferansier' => 'Konferansierer',
	'nettredaksjon' => 'UKM Media',
];

$monstring = new monstring_v2( get_option('pl_id') );

foreach( $monstring->getInnslag()->getAll() as $innslag ) {
	if( !in_array( $innslag->getType()->getId(), [4,5,8] ) ) {
		continue;
	}

	$TWIG['innslag'][ $innslag->getType()->getKey() ][] = $innslag;
}

$TWIG['festivalen'] = monstringer_v2::land( $monstring->getSesong() );