<?php
require_once( PLUGIN_DIR_PATH.'class/leder.class.php' );

$start = (int)$videresendtil->pl_start;
$stop  = (int)$videresendtil->pl_stop;

$num_dager = floor( ($stop - $start) /(60*60*24));


$crnt = new stdClass();
$crnt->dag = (int)date('d', $start);
$crnt->mnd= (int)date('m', $start);
$crnt->ar  = (int)date('Y', $start);

for( $i=0; $i < $num_dager+1; $i++ ) {	
	if( $crnt->dag > cal_days_in_month( CAL_GREGORIAN, $crnt->mnd, $crnt->ar ) ) {
		$crnt->dag = 1;
		$crnt->mnd++;
	}
	$crnt->timestamp = strtotime( $crnt->dag.'-'.$crnt->mnd.'-'.$crnt->ar );
	$TWIG['dager'][] = clone $crnt;

	$crnt->dag++;
}

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
/*
$TWIG['overnatting'][] = 'deltakere';
$TWIG['overnatting'][] = 'hotell';
$TWIG['overnatting'][] = 'privat';
*/
