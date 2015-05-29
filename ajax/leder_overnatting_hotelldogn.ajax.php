<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = (object) $_POST;

$data->success = update_infoskjema_field( $m->g('pl_id'), $videresendtil->ID, 'overnatting_hotelldogn', $data->hotelldogn);


die( json_encode( $data ) );