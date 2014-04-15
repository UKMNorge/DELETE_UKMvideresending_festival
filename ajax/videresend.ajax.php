<?php
require_once('UKM/tittel.class.php');
$data = load_kontroll_data( $m, $videresendtil );

$data->videresendt = $data->tittellos == false;

die( json_encode( $data ) );