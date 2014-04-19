<?php

$i = new innslag( $_POST['b_id'] );

$related_items = $i->related_items();

$innslag = new stdClass();
$innslag->ID 		= $i->g('b_id');
$innslag->navn		= $i->g('b_name');

$alle_bilder = $related_items['image'];

foreach( $alle_bilder as $b ) {
	$bilde = new stdClass();
	$bilde->ID = $b['rel_id'];
	# DEVELOPMENT !!! 
	$bilde->baseUrl = 'http://'. $_SERVER['HTTP_HOST'].'/wp-content/uploads/sites/'. str_replace('1151','17',$b['blog_id']).'/';
	
	if(isset( $b['post_meta']['sizes']['large'] ) ) {
		$bilde->full = $b['post_meta']['sizes']['large']['file'];
	} elseif( isset( $b['post_meta']['sizes']['medium'] ) ) {
		$bilde->full = $b['post_meta']['sizes']['medium']['file'];
	} else {
		$bilde->full = $b['post_meta']['file'];
	}
	
	if( isset( $b['post_meta']['sizes']['thumbnail'] ) ) {
		$bilde->thumb = $b['post_meta']['sizes']['thumbnail']['file'];
	} else {
		$bilde->thumb = $b['post_meta']['file'];
	}
	$data['bilder'][] = $bilde;
}

$data['selector']	= $_POST['selector'];
$data['innslag']	= $innslag;
$data['b_id']		= $_POST['b_id'];
$data['t_id']		= $_POST['t_id'];

die( json_encode( $data ) );