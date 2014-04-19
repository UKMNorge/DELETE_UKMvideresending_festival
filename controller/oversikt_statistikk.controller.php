<?php
if( isset($_POST) and sizeof( $_POST ) > 0 ) {
	
	$m->update('pl_missing');
	$m->update('pl_public');
	
	$TWIG['message'] = array('success' => true,
							 'body' => 'Publikum og uregistrerte er nå lagret');
}

$m = new monstring( $m->g('pl_id') );

$statistikk = new stdClass();
$statistikk->missing = $m->g('pl_missing');
$statistikk->publikum = $m->g('pl_public');

$TWIG['statistikk'] = $statistikk;