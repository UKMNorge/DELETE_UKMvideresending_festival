<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );
$TWIG['dager'] = netter( $videresendtil );

// Last inn eller opprett hoved- og utstillingsleder
// HOVEDLEDER
	$hovedleder = new leder();
	$hovedleder_load = $hovedleder->load_by_type( $m->g('pl_id'), $videresendtil->ID, 'hoved');
	
	if( !$hovedleder_load ) {
		$hovedleder->set('type', 'hoved');
		$hovedleder_create = $hovedleder->create( $m->g('pl_id'), $videresendtil->ID, get_option('season'));
	}

// UTSTILLINGSLEDER
	$utstillingleder = new leder();
	$utstillingleder_load = $utstillingleder->load_by_type( $m->g('pl_id'), $videresendtil->ID, 'utstilling');
	
	if( !$utstillingleder_load ) {
		$utstillingleder->set('type', 'utstilling');
		$utstillingleder_create = $utstillingleder->create( $m->g('pl_id'), $videresendtil->ID, get_option('season'));
	}

$TWIG['ledere'][] = $hovedleder;
$TWIG['ledere'][] = $utstillingleder;

$ledere = new SQL("SELECT `l_id`
					FROM `smartukm_videresending_ledere_ny`
					WHERE `pl_id_from` = '#pl_from'
					AND `pl_id_to` = '#pl_to'
					AND `season` = '#season'
					AND (`l_type` != 'utstilling' AND `l_type` != 'hoved')",
				array(	'pl_from' => $m->g('pl_id'),
						'pl_to' => $videresendtil->ID,
						'season' => get_option('season'),
					)
				);
$res = $ledere->run();

while( $r = mysql_fetch_assoc( $res ) ) {
	$TWIG['ledere'][] = new leder( $r['l_id'] );
}

$TWIG['sove'] = new stdClass();
$TWIG['sove']->system_deltakere = 32;
$TWIG['sove']->deltakere = 30;