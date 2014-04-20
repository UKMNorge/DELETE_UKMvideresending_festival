<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = $_POST;
$data['dager'] = netter( $videresendtil );

$leder = new leder();
$leder->set( 'l_type', 'reise' );
$leder->create( $m->g('pl_id'), $videresendtil->ID, get_option('season') );


$data['leder'] = $leder;
$data['overnatting'] = $TWIG['overnatting'];

die( json_encode( $data ) );