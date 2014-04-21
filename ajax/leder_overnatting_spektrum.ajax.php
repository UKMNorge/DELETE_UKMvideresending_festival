<?php

$data = (object) $_POST;

$sql = new SQL("SELECT `overnatting_spektrumdeltakere`
				FROM `smartukm_videresending_infoskjema`
				WHERE `pl_id` = '#pl_to'
				AND `pl_id_from` = '#pl_from'",
			array( 'pl_to' => $videresendtil->ID,
					'pl_from' => $m->g('pl_id')
				)
			);
$res = $sql->run('field','overnatting_spektrumdeltakere');

if( $res ) {
	if( $res == $data->antall ) {
		$data->success = true;
	} else {
		$SQLins = new SQLins('smartukm_videresending_infoskjema', array('pl_id'=>$videresendtil->ID, 'pl_id_from'=>$m->g('pl_id')));
		$SQLins->add('overnatting_spektrumdeltakere', $data->antall);
		$res = $SQLins->run();
		$data->success = $res != -1;
	}
} else {
	$SQLins = new SQLins('smartukm_videresending_infoskjema');
	$SQLins->add('pl_id', $videresendtil->ID);
	$SQLins->add('pl_id_from', $m->g('pl_id'));
	$SQLins->add('overnatting_spektrumdeltakere', $data->antall);
	$res = $SQLins->run();

	$data->success = $res != -1;
}

die( json_encode( $data ) );