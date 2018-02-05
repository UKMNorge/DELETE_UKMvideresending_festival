<?php
$data = new stdClass();

$ID = array();
$whoami = explode( '|', $_POST['id'] );
foreach( $whoami as $iam ) {
	$who = explode( ':', $iam );
	$ID[ $who[0] ] = $who[1];
}

$person = new person( $ID['person'], $ID['innslag'] );
$person->videresend( $m->g('pl_id'), $videresendtil->ID, $ID['tittel']);

$data->selector = $_POST['selector'];
$data->success = true;
$data->personSelector = 'person-'. $ID['person'];

rekalkuler_videresendte_personer($m->g('pl_id'), $videresendtil->ID);

die(json_encode($data));