<?php

$i = new innslag( $_POST['b_id'] );

$related_items = $i->related_items();

$innslag = new stdClass();
$innslag->ID 		= $i->g('b_id');
$innslag->navn		= $i->g('b_name');

$filmer = $related_items['tv'];

foreach( $filmer as $film ) {
	$film->full_embed_code = $film->embedcode();
}


$data['filmer']		= $filmer;
$data['selector']	= $_POST['selector'];
$data['innslag']	= $innslag;
$data['b_id']		= $_POST['b_id'];
$data['t_id']		= $_POST['t_id'];

die( json_encode( $data ) );