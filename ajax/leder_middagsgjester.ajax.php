<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$data = (object) $_POST;

$sql = new SQL("SELECT `skjema_id`
				FROM `smartukm_videresending_ledere_middag`
				WHERE `pl_to` = '#pl_to'
				AND `pl_from` = '#pl_from'",
			array(	'pl_to' => $videresendtil->ID,
					'pl_from'=> $m->g('pl_id')
				)
			);
$res = $sql->run();
if( $res && mysql_num_rows( $res ) > 0 ) {
	$r = SQL::fetch( $res );
	$SQL = new SQLins('smartukm_videresending_ledere_middag', array('skjema_id' => $r['skjema_id']));
} else {
	$SQL = new SQLins('smartukm_videresending_ledere_middag');
	$SQL->add('pl_to', $videresendtil->ID);
	$SQL->add('pl_from', $m->g('pl_id'));
}
$SQL->add('ledermiddag_ukm', $_POST['gjest_ukm']);
$SQL->add('ledermiddag_fylke1', $_POST['gjest_fylke1']);
$SQL->add('ledermiddag_fylke2', $_POST['gjest_fylke2']);

$data->success = $SQL->run() != -1;

// SAVE

$ledere = new SQL("SELECT `l_id`
					FROM `smartukm_videresending_ledere_ny`
					WHERE `pl_id_from` = '#pl_from'
					AND `pl_id_to` = '#pl_to'
					AND `season` = '#season'
					ORDER BY `l_navn` ASC",
				array(	'pl_from' => $m->g('pl_id'),
						'pl_to' => $videresendtil->ID,
						'season' => get_option('season'),
					)
				);
$res = $ledere->run();

while( $r = SQL::fetch( $res ) ) {
	$data->ledere[] = new leder( $r['l_id'] );
}

$data->gjester = middagsgjester( $videresendtil, $m );

die( json_encode( $data ) );