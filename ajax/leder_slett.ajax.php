<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = (object) $_POST;

$leder = new leder( $data->ID );

$data->success = $leder->delete( $m->g('pl_id') );

die( json_encode( $data ));