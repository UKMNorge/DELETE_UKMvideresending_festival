<?php
if( isset( $_GET['lesmer'] ) ) {
	require_once('oversikt_lesmer_'. $_GET['lesmer'] .'.controller.php');
} else {
	$season = get_option('season');
	// TOTAL
	$kvote_param[]	= (object) array(	'id' 		=> 'total_personer',
										'verdi'		=>	(int) get_site_option('UKMFvideresending_kvote_deltakere_'.$season),
										'tittel'	=> 'Totalt antall deltakere',
										'enhet'		=>	'person',
										'flertall'	=> 'er'
									);
	
	if(get_option('site_type' == 'fylke')) {						
		$kvote_param[]	= (object) array(	'id' 		=> 'total_ledere',
											'verdi'		=>	(int) get_site_option('UKMFvideresending_kvote_ledere_'.$season),
											'tittel'	=> 'Antall ledere',
											'enhet'		=>	'person',
											'flertall'	=> 'er'
										);
							
		$kvote_param[]	= (object) array(	'id' 		=> 'total_deltakere_per_leder',
											'verdi'		=>	10,
											'tittel'	=> 'Antall deltakere per leder',
											'enhet'		=>	'deltaker',
											'flertall'	=> 'e'
										);
	}
	// SCENE
	$kvote_param[]	= (object) array(	'id' 		=> 'scene_antall',
										'verdi'		=>	5,
										'tittel'	=> 'Antall sceneinnslag',
										'enhet'		=>	'innslag',
										'flertall'	=> ''
									);
							
	$kvote_param[]	= (object) array(	'id' 		=> 'scene_varighet',
										'verdi'		=>	25,
										'tittel'	=> 'Varighet sceneinnslag',
										'enhet'		=>	'minutt',
										'flertall'	=> 'er'
									);
	
	// FILM
	$kvote_param[]	= (object) array(	'id' 		=> 'film_antall',
										'verdi'		=>	1,
										'tittel'	=> 'Antall filmer',
										'enhet'		=>	'film',
										'flertall'	=> 'er',
										'kommentar' => '2 filmer hvis total varighet er under 6 minutter'
									);
	
	$kvote_param[]	= (object) array(	'id' 		=> 'film_varighet',
										'verdi'		=>	5,
										'tittel'	=> 'Varighet filmer',
										'enhet'		=>	'minutt',
										'flertall'	=>  'er',
										'kommentar' => '2 filmer gir utvidet kvote: 6 minutter'
									);
	
	$kvote_param[]	= (object) array(	'id' 		=> 'film_personer',
										'verdi'		=>	2,
										'tittel'	=> 'Antall filmskapere',
										'enhet'		=>	'person',
										'flertall'	=> 'er',
										'kommentar' => '2 filmer gir utvidet kvote: 4 personer'
									);
	
	// KUNST
	$kvote_param[]	= (object) array(	'id' 		=> 'kunst_antall',
										'verdi'		=>	4,
										'tittel'	=> 'Antall kunstverk',
										'enhet'		=>	'verk',
										'flertall'	=> ''
									);
	
	$kvote_param[]	= (object) array(	'id' 		=> 'kunst_personer',
										'verdi'		=>	8,
										'tittel'	=> 'Antall kunstnere',
										'enhet'		=>	'person',
										'flertall'	=> 'er'
									);
	
	// TITTELLØSE
	$kvote_param[]	= (object) array(	'id' 		=> 'nettredaksjon_antall',
										'verdi'		=>	'nomineres',
										'tittel'	=> 'Antall nettredaksjon',
										'enhet'		=>	'person',
										'flertall'	=> 'er'
									);
	
	$kvote_param[]	= (object) array(	'id' 		=> 'arrangor_antall',
										'verdi'		=>	'nomineres',
										'tittel'	=> 'Antall arrangører',
										'enhet'		=>	'person',
										'flertall'	=> 'er'
									);
	
	$kvote_param[]	= (object) array(	'id' 		=> 'konferansier_antall',
										'verdi'		=>	1,
										'tittel'	=> 'Antall konferansierer',
										'enhet'		=>	'person',
										'flertall'	=> 'er'
									);
	
	
	#$TWIG['kvoter']			= $kvote_param;
	
	foreach( $kvote_param as $param ) {
		$videresendte[ $param->id ] = 0;
		$TWIG['kvoter'][ $param->id ] = $param;
	}
	
	$videresendte_innslag = $m->videresendte();
	$unike_personer = array();
	
	foreach( $videresendte_innslag as $inn ) {
		$i = new innslag($inn['b_id']);
		$i->videresendte( $videresendtil->ID );
		
		switch( $i->g('bt_id') ) {
			case 1:
			case 7:
				$videresendte['scene_antall']++;
				
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['scene'][ $pers['p_id'] ] = $pers;
					
				$videresendte['scene_varighet'] += (int) $i->varighet( $m->g('pl_id'), $videresendtil->ID );
				break;
			case 2:
				$videresendte['film_antall']++;
				
				// 2 filmer gir totalt 6 min varighet og 4 pers
				if( $videresendte['film_antall'] > 1 ) {
					$TWIG['kvoter']['film_varighet']->verdi = 6;
					$TWIG['kvoter']['film_personer']->verdi = 4;
				}
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['film'][ $pers['p_id'] ] = $pers;
	
				$videresendte['film_varighet'] += (int) $i->varighet( $m->g('pl_id'), $videresendtil->ID );
	
				break;
			case 3:
				$videresendte['kunst_antall']++;
				
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['kunst'][ $pers['p_id'] ] = $pers;
				break;
			case 4:
				$videresendte['konferansier_antall']++;
				
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['tittellose'][ $pers['p_id'] ] = $pers;
	
				break;
			case 5:
			case 10:
				$videresendte['nettredaksjon_antall']++;
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['tittellose'][ $pers['p_id'] ] = $pers;
				break;
			case 6:
				$videresendte['matkultur']++;
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['tittellose'][ $pers['p_id'] ] = $pers;
				break;
			case 8:
			case 9:
				$videresendte['arrangor_antall']++;
				$personer = $i->personer();
				foreach( $personer as $pers )
					$unike_personer['tittellose'][ $pers['p_id'] ] = $pers;
				break;
		}
	}
	
	$videresendte['kunst_personer'] = sizeof( $unike_personer['kunst'] );
	$videresendte['film_personer'] = sizeof( $unike_personer['film'] );
	$videresendte['scene_personer'] = sizeof( $unike_personer['scene'] );
	
	$videresendte['total_personer'] = sizeof($unike_personer['tittellose']) + sizeof( $unike_personer['kunst'] ) + sizeof( $unike_personer['film'] ) + sizeof( $unike_personer['scene'] );
	
	$videresendte['scene_tid'] = sec_to_min( $videresendte['scene_varighet']);
	$videresendte['film_tid'] = sec_to_min( $videresendte['film_varighet']);
	
	
	// LEDERE
	$ledere = new SQL("SELECT COUNT(`l_id`) AS `num_ledere`
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
	$videresendte['total_ledere'] = $ledere->run('field','num_ledere');
	if( $videresendte['total_personer'] > 0 && $videresendte['total_ledere'] > 0)
		$videresendte['total_deltakere_per_leder'] = ceil( $videresendte['total_personer'] / $videresendte['total_ledere'] );
	else 
		$videresendte['total_deltakere_per_leder'] = $videresendte['total_personer'];
	
	$TWIG['videresendt'] = $videresendte;
	
	$TWIG['mediaOK'] = get_option('UKMvideresending_festival_mediaOK');
	
	update_infoskjema_field( $m->g('pl_id'), $videresendtil->ID, 'systemet_overnatting_spektrumdeltakere', $videresendte['total_personer']);
	
	require_once('oversikt_statistikk.controller.php');

	$season = ($month > 7) ? date('Y')+1 : date('Y');
	$TWIG['info1'] = get_site_option('UKMFvideresending_info1_'.$season);
	#$TWIG['info1'] = get_site_option('UKMFvideresending_info1');

}






function sec_to_min( $sec ) {
	$q = floor($sec / 60);
	$r = $sec % 60;

	if ($q == 0)
		return $r.' sek';

	if ($r == 0)
		return $q.' min';

	return $q.'m '.$r.'s';
}
