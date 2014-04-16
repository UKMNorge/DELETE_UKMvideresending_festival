<?php

function load_kontroll_data( $m, $videresendtil ) {
	$data = new stdClass();

	$ID = array();
	$whoami = explode( '|', $_POST['id'] );
	foreach( $whoami as $iam ) {
		$who = explode( ':', $iam );
		$ID[ $who[0] ] = $who[1];
	}

	$data = load_innslag_kontroll_data( $ID, $m, $videresendtil );
	
	$data->tittellos = $ID['tittellos'] == 'true';
	
	$data->varighet = new stdClass();
	$data->varighet->min = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
	$data->varighet->sek = array(0,5,10,15,20,25,30,35,40,45,50,55);
	
	$data->selector = $_POST['selector'];
	$data->selectorID = $_POST['id'];	
	return $data;
}

function load_innslag_stdclass( $i, $m, $videresendtil ) {
	// Lag STDclass-objekt for TWIG
	$innslag = new stdClass();
	$innslag->ID 			= $i->g('b_id');
	$innslag->navn			= $i->g('b_name');
	$innslag->tittellos		= $i->tittellos();
	$innslag->alle_personer	= $innslag->tittellos ? true : ($i->g('bt_form') == 'smartukm_titles_scene');
	$innslag->beskrivelse	= $i->g('b_description');
	$innslag->tekniske_behov= $i->g('td_demand');
	$innslag->konferansier	= $i->g('td_konferansier');
	$innslag->kommune		= $i->g('kommune');
	$innslag->fylke			= $i->g('fylke');
	$innslag->type			= $i->g('bt_name');
	$innslag->titteltabell	= $i->g('bt_form');
	$innslag->kategori		= $i->g('kategori');
	$innslag->sjanger		= $i->g('sjanger');

	$innslag->personer		= array();
	$innslag->titler		= array();
	
	$personer = $i->personer();
	if ( is_array( $personer ) ) {
		foreach ( $personer as $pers ) {
			$p = new person( $pers['p_id'], $innslag->ID );
			
			$person = new stdClass();
			$person->ID			= $p->g('p_id');
			$person->fornavn	= $p->g('p_firstname');
			$person->etternavn	= $p->g('p_lastname');
			$person->navn		= $p->g('name');
			$person->mobil		= $p->g('p_phone');
			$person->instrument	= $p->g('instrument');
			$person->alder		= $p->getAge( $m->videresendtil(true) );
			$person->videresendt= $p->videresendt( $videresendtil->ID );
			$innslag->personer[] = $person;
		}
	}
	
	return $innslag;
}

function load_innslag_kontroll_data( $ID, $m, $videresendtil ) {
	$i = new innslag($ID['innslag']);
	$i->loadGEO();
	
	$innslag = load_innslag_stdclass( $i, $m, $videresendtil );
	
	$innslag->tittel = new tittel( $ID['tittel'], $i->g('bt_form') );

	$innslag->tittel->personer = array();
	foreach( $innslag->personer as $person ) {
		$p = new person( $person->ID, $innslag->ID );
		$tittelID = $innslag->tittellos ? 'notitle' : $innslag->tittel->t_id;
		$innslag->tittel->personer[ $p->g('p_id') ] = $p->videresendt( $videresendtil->ID, $tittelID );
	}

	$varighet = $innslag->tittel->varighet;
	$innslag->tittel->varighet = new stdClass();
	$innslag->tittel->varighet->total = $varighet;
	$innslag->tittel->varighet->min = (int) ($varighet / 60);
	$innslag->tittel->varighet->sek = (int) ($varighet % 60);

	return $innslag;
}