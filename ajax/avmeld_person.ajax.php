<?php
$data = new stdClass();

$ID = array();
$whoami = explode( '|', $_POST['id'] );
foreach( $whoami as $iam ) {
	$who = explode( ':', $iam );
	$ID[ $who[0] ] = $who[1];
}


$data->selector = $_POST['selector'];
$data->success = true;

die(json_encode($data));