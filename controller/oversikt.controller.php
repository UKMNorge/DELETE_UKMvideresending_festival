<?php
// TOTAL
$kvote_param[]	= (object) array(	'id' 		=> 'total_antall_deltakere',
									'verdi'		=>	30,
									'tittel'	=> 'Totalt antall deltakere',
									'enhet'		=>	'deltaker',
									'flertall'	=> 'e'
								);
						
$kvote_param[]	= (object) array(	'id' 		=> 'total_antall_ledere',
									'verdi'		=>	3,
									'tittel'	=> 'Antall ledere',
									'enhet'		=>	'leder',
									'flertall'	=> 'e'
								);
						
$kvote_param[]	= (object) array(	'id' 		=> 'total_deltakere_per_leder',
									'verdi'		=>	10,
									'tittel'	=> 'Antall deltakere per leder',
									'enhet'		=>	'leder',
									'flertall'	=> 'e'
								);
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
									'flertall'	=> 'er'
								);

$kvote_param[]	= (object) array(	'id' 		=> 'film_varighet',
									'verdi'		=>	5,
									'tittel'	=> 'Varighet filmer',
									'enhet'		=>	'minutt',
									'flertall'	=> 'er'
								);

$kvote_param[]	= (object) array(	'id' 		=> 'film_personer',
									'verdi'		=>	2,
									'tittel'	=> 'Antall filmskapere',
									'enhet'		=>	'person',
									'flertall'	=> 'er'
								);

// KUNST
$kvote_param[]	= (object) array(	'id' 		=> 'kunst_antall',
									'verdi'		=>	4,
									'tittel'	=> 'Antall kunstverk',
									'enhet'		=>	'verk',
									'flertall'	=> ''
								);

$kvote_param[]	= (object) array(	'id' 		=> 'kunst_personer',
									'verdi'		=>	4,
									'tittel'	=> 'Antall kunstnere',
									'enhet'		=>	'person',
									'flertall'	=> 'er'
								);

// TITTELLØSE
$kvote_param[]	= (object) array(	'id' 		=> 'nettredaksjon_antall',
									'verdi'		=>	3,
									'tittel'	=> 'Antall nettredaksjon',
									'enhet'		=>	'person',
									'flertall'	=> 'er'
								);

$kvote_param[]	= (object) array(	'id' 		=> 'arrangor_antall',
									'verdi'		=>	2,
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


$TWIG['kvoter']			= $kvote_param;

require_once('oversikt_statistikk.controller.php');