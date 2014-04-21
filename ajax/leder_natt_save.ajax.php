<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = (object) $_POST;

$data->leder = new leder( $_POST['leder'] );
$data->natt = $data->leder->natt( $_POST['dato'], $_POST['sted'] );

$data->success = $data->natt != false;

die( json_encode( $data ) );