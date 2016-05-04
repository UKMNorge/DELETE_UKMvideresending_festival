<?php

if( isset( $_POST ) && sizeof( $_POST ) > 0 ) {
	
	// Eksisterende rad?
	$sqlTest = new SQL("SELECT `skjema_id`
						FROM `smartukm_videresending_infoskjema`
						WHERE `pl_id` = '#pl_to'
						AND `pl_id_from` = '#pl_from'",
					array(	'pl_to' 	=> $videresendtil->ID,
							'pl_from'	=> $m->g('pl_id')
						)
					);
	$testRes = $sqlTest->run();
	
	if( !$testRes || mysql_num_rows( $testRes ) == 0 ) {
		$sql = new SQLins('smartukm_videresending_infoskjema');
	} else {
		$sql = new SQLins(	'smartukm_videresending_infoskjema', 
							array(	'pl_id' 	=> $videresendtil->ID,
									'pl_id_from'	=> $m->g('pl_id')
								)
						);
	}
	
	$sql->add('pl_id',		$videresendtil->ID);
	$sql->add('pl_id_from',	$m->g('pl_id'));
	
/*
	$sql->add('systemet_overnatting_spektrumdeltakere', 0);
	$sql->add('avvik_overnatting_spektrumdeltakere', 0);
	$sql->add('overnatting_spektrumdeltakere, '');
*/


	// ANKOMST
	$sql->add('reise_inn_mate',			utf8_encode($_POST['reise_ank_mate']));
	$sql->add('reise_inn_dato',			$_POST['reise_ank_dato']);
	$sql->add('reise_inn_tidspunkt',	utf8_encode($_POST['reise_ank_tid']));
	$sql->add('reise_inn_sted',			utf8_encode($_POST['reise_ank_sted']));
	$sql->add('reise_inn_samtidig',		empty( $_POST['reise_ank_annet'] ) ? 'nei' : 'ja');
	$sql->add('reise_inn_samtidig_nei', utf8_encode($_POST['reise_ank_annet']));
	
	// AVREISE
	$sql->add('reise_ut_dato',			$_POST['reise_avr_dato']);
	$sql->add('reise_ut_tidspunkt',		utf8_encode($_POST['reise_avr_tid']));
	$sql->add('reise_ut_sted',			'');
	$sql->add('reise_ut_samtidig',		empty( $_POST['reise_avr_annet'] ) ? 'nei' : 'ja');
	$sql->add('reise_ut_samtidig_nei', 	utf8_encode($_POST['reise_avr_annet']));
	
	// MAT
	$sql->add('mat_vegetarianere', 	utf8_encode($_POST['matogallergi_vegetarianere']));
	$sql->add('mat_soliaki', 		utf8_encode($_POST['matogallergi_soliaki']));
	$sql->add('mat_svinekjott', 	utf8_encode($_POST['matogallergi_svinekjott']));
	$sql->add('mat_annet',		 	utf8_encode($_POST['matogallergi_annet']));

	// TILRETTELEGGING
	$sql->add('tilrettelegging_bevegelseshemninger',	utf8_encode($_POST['tilrettelegging_bevegelseshemninger']));
	$sql->add('tilrettelegging_annet',		 			utf8_encode($_POST['tilrettelegging_annet']));

	$res = $sql->run();
		
	if( false === $res || $res == -1) {
		$TWIG['message'] = array( 	'success' => false,
									'title' => 'En feil har oppstått!',
									'body'	=> 'Det har dessverre oppstått en feil ved lagring, og skjemaet ble derfor ikke lagret. '
												.'Vennligst prøv igjen, evt kontakt UKM Norge om problemet vedvarer'
								);
	} else {
		$TWIG['message'] = array(	'success' => true,
									'title' => 'Informasjonen er nå lagret!'
								);
	}
}

$load = new SQL("SELECT *
					FROM `smartukm_videresending_infoskjema`
					WHERE `pl_id` = '#pl_to'
					AND `pl_id_from` = '#pl_from'",
				array(	'pl_to' 	=> $videresendtil->ID,
						'pl_from'	=> $m->g('pl_id')
					)
				);
$db = $load->run('array');

$reise = new stdClass();
$reise->ank = new stdClass();
$reise->avr = new stdClass();

$reise->ank->dato = $db['reise_inn_dato'];
$reise->ank->tid = $db['reise_inn_tidspunkt'];
$reise->ank->sted = $db['reise_inn_sted'];
$reise->ank->mate = $db['reise_inn_mate'];
$reise->ank->annet = $db['reise_inn_samtidig_nei'];

$reise->avr->dato = $db['reise_ut_dato'];
$reise->avr->tid = $db['reise_ut_tidspunkt'];
$reise->avr->annet = $db['reise_ut_samtidig_nei'];

$matogallergi = new stdClass();
$matogallergi->vegetarianere = $db['mat_vegetarianere'];
$matogallergi->soliaki = $db['mat_soliaki'];
$matogallergi->svinekjott = $db['mat_svinekjott'];
$matogallergi->annet = $db['mat_annet'];

$tilrettelegging = new stdClass();
$tilrettelegging->bevegelseshemninger = $db['tilrettelegging_bevegelseshemninger'];
$tilrettelegging->annet = $db['tilrettelegging_annet'];

$TWIG['reise'] = $reise;
$TWIG['matogallergi'] = $matogallergi;
$TWIG['tilrettelegging'] = $tilrettelegging;