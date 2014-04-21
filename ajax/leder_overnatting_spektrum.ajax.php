<?php

$data = (object) $_POST;

$data->success = update_infoskjema_field( $m->g('pl_id'), $videresendtil->ID, 'overnatting_spektrumdeltakere', $data->antall);

die( json_encode( $data ) );