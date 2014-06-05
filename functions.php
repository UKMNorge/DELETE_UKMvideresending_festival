<?php

function image_selected( $innslag, $tittel = false, $type = 'bilde', $size='thumbnail' ) {
	$valgtBilde = new SQL("SELECT *
						   FROM `smartukm_videresending_media` AS `media`
						   JOIN `ukmno_wp_related` ON (`ukmno_wp_related`.`rel_id` = `media`.`rel_id`)
						   WHERE `media`.`b_id` = '#bid'
						   AND `m_type` = '#type'
  						   AND (`t_id` = '0' OR `t_id` = '#tid' OR `t_id` IS NULL)",
						  array('bid'=>$innslag->ID,'tid'=>$tittel, 'type' => $type)
						 );
	$bilde = $valgtBilde->run('field','rel_id');
	
	if( $bilde == null )
		return 'none_selected';
	
	// CALC IMAGE DATA
	$bilde = $valgtBilde->run('array');
	
	$post_meta = unserialize( $bilde['post_meta'] );
	if( $size == 'thumbnail' && isset( $post_meta['sizes']['thumbnail']['file'] ) ) {
		$src = $post_meta['sizes']['thumbnail']['file'];
	} else {
		$src = $post_meta['file'];
	}
	
	$src = 'http://'. $_SERVER['HTTP_HOST'].'/wp-content/blogs.dir/'. $bilde['blog_id'].'/files/' . $src;

	
	$vb = new stdClass();
	$vb->ID = $bilde['rel_id'];
	$vb->src = $src;
	return $vb;
}


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

function netter( $videresendtil ) {
	$netter = array();
	
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
		$netter[] = clone $crnt;
	
		$crnt->dag++;
	}
	return $netter;
}
function middagsgjester( $videresendtil, $m ) { 
	$middagsgjester = array('ukm'=>0,'fylke1'=>0,'fylke2'=>0);
	$sql = new SQL("SELECT `ledermiddag_ukm`,
							`ledermiddag_fylke1`,
							`ledermiddag_fylke2`
					FROM `smartukm_videresending_ledere_middag`
					WHERE `pl_to` = '#pl_to'
					AND `pl_from` = '#pl_from'",
				array( 'pl_to' => $videresendtil->ID,
						'pl_from' => $m->g('pl_id')
					)
				);
	$res = $sql->run();
	
	if( $res && mysql_num_rows( $res ) > 0 ) {
		$r = mysql_fetch_assoc( $res );
		$middagsgjester['ukm'] = $r['ledermiddag_ukm'];
		$middagsgjester['fylke1'] = $r['ledermiddag_fylke1'];
		$middagsgjester['fylke2'] = $r['ledermiddag_fylke2'];
	}
	return $middagsgjester;
}

function update_infoskjema_field( $pl_from, $pl_to, $field, $value ) {
	$sql = new SQL("SELECT `#field`
					FROM `smartukm_videresending_infoskjema`
					WHERE `pl_id` = '#pl_to'
					AND `pl_id_from` = '#pl_from'",
				array( 'pl_to' => $pl_to,
						'pl_from' => $pl_from,
						'field' => $field
					)
				);
	$res = $sql->run();
	
	$row = mysql_fetch_assoc( $res );
	if( $row['field'] == $value )
		return true;
	
	if( mysql_num_rows( $res ) == 0 ) {
		$SQLins = new SQLins('smartukm_videresending_infoskjema');
		$SQLins->add('pl_id',$pl_to);
		$SQLins->add('pl_id_from',$pl_from);
	} else {
		$SQLins = new SQLins('smartukm_videresending_infoskjema', array('pl_id'=>$pl_to, 'pl_id_from'=>$pl_from));
	}
	$SQLins->add($field, utf8_encode($value));
	$res = $SQLins->run();
	return $res != -1;
}

function rekalkuler_videresendte_personer($pl_from, $pl_to) {
	$m = new monstring( $pl_from );
	$videresendte_innslag = $m->videresendte();
	$unike_personer = array();
	
	foreach( $videresendte_innslag as $inn ) {
		$i = new innslag($inn['b_id']);
		$i->videresendte( $pl_to );
		
		$personer = $i->personer();
		foreach( $personer as $pers )
			$unike_personer[ $pers['p_id'] ] = $pers;
	}

	return update_infoskjema_field( $pl_from, $pl_to, 'systemet_overnatting_spektrumdeltakere', sizeof($unike_personer));
}