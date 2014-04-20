<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = (object) $_POST;

$leder = new leder( $data->ID );
$leder->set('l_type', $data->type);
$leder->set('l_navn', $data->navn);
$leder->set('l_mobilnummer', $data->mobil);
$leder->set('l_epost', $data->epost);

$data->success = $leder->update();

die( json_encode( $data ));