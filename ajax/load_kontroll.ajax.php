<?php
require_once('UKM/tittel.class.php');

$data = load_kontroll_data( $m, $videresendtil );
$data->videresendtil = $videresendtil;
die( json_encode( $data ) );