<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');
require_once('UKM/person.class.php');

// Hvilke typer innslag kan videresendes?
$PLvideresendTilTillatt = $vt->getBandTypes();
foreach($PLvideresendTilTillatt as $i => $bt) {
	$tillattForVideresending[] = $bt['bt_id'];
}	


if( !$videresendtil->mottakelig ) {
	$alle_videresendte = $m->videresendte();
	$de_videresendte = array();
	foreach( $alle_videresendte as $innslag ) {
		$de_videresendte[] = $innslag['b_id'];
	}
}

$alle_innslag = $m->innslag_alpha();
if(is_array($alle_innslag)) {
	foreach($alle_innslag as $inn) {
		$i = new innslag($inn['b_id']);
		
		if(!in_array($i->g('bt_id'),$tillattForVideresending))
			continue;

		if( !$videresendtil->mottakelig && !in_array($i->g('b_id'), $de_videresendte))
			continue;
		
		$i->loadGEO();
		
		$innslag = load_innslag_stdclass( $i, $m, $videresendtil );
		if( !$innslag->tittellos ) {
			$titler = $i->titler( $m->g('pl_id') );
			
			if( is_array( $titler ) ) {
				foreach( $titler as $t ) {
					$tittel 			= new stdClass();
					$tittel->ID 		= $t->g('t_id');
					$tittel->varighet	= $t->g('varighet');
					$tittel->navn		= $t->g('tittel');
					$tittel->videresendt= $t->videresendt( $videresendtil->ID );
					$tittel->beskrivelse= $t->g('beskrivelse');
					$tittel->type		= $t->g('type');
					$tittel->teknikk	= $t->g('teknikk');
					$tittel->format		= $t->g('format');
					
					$innslag->titler[] = $tittel;
				}
			}
		}

	
	$TWIG['alle_innslag'][$innslag->type][] = $innslag;
	}
}

if( is_array( $TWIG['alle_innslag']))
	ksort($TWIG['alle_innslag']);