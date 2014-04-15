<?php
require_once('UKM/tittel.class.php');

$data = load_kontroll_data( $m, $videresendtil );

die( json_encode( $data ) );