<?php

$data = (object) $_POST;

$data->success = update_infoskjema_field( $m->g('pl_id'), $videresendtil->ID, 'overnatting_kommentar', $data->kommentar);

die( json_encode( $data ) );