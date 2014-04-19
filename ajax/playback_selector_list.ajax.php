<?php

$i = new innslag( $_POST['b_id'] );

$innslag = new stdClass();
$innslag->ID 		= $i->g('b_id');
$innslag->navn		= $i->g('b_name');

$data['playback']	= $i->playback();
$data['selector']	= $_POST['selector'];
$data['innslag']	= $innslag;
$data['b_id']		= $_POST['b_id'];
$data['t_id']		= $_POST['t_id'];

die( json_encode( $data ) );