<?php
require_once('UKM/monstring.class.php');

$m = new monstring( get_option('pl_id') );

$videresendtil = new stdClass();
$vt = $m->videresendtil(true);
$videresendtil->ID		= $vt->g('pl_id');
$videresendtil->navn 	= $vt->g('pl_name');
$videresendtil->frist 	= $vt->g('pl_deadline');
$videresendtil->registrert = $vt->registered();
$videresendtil->mottakelig = true;#DEBUG $vt->subscribable();

$TWIG['videresendtil'] 	= $videresendtil;

$tabs = array();

$tabs[] = (object) array( 'link' 		=> 'oversikt',
						  'header' 		=> 'Oversikt',
						  'icon'		=> 'info-button-256',
						  'description'	=> 'Kort oppsummering');
						  
$tabs[] = (object) array( 'link' 		=> 'videresendte',
						  'header' 		=> 'Videresendte',
						  'icon'		=> 'people-256',
						  'description'	=> 'Hvem skal videresendes');
						  
$tabs[] = (object) array( 'link' 		=> 'media',
						  'header' 		=> 'Media',
						  'icon'		=> 'video-256',
						  'description'	=> 'Last opp / endre');
						  
$tabs[] = (object) array( 'link' 		=> 'reiseinfo',
						  'header' 		=> 'Reiseinfo',
						  'icon'		=> 'buss-256',
						  'description'	=> 'Reise- og lederskjema');
						  
$tabs[] = (object) array( 'link' 		=> 'statistikk',
						  'header' 		=> 'Statistikk',
						  'icon'		=> 'graph-menu',
						  'description'	=> 'Publikum og uregistrerte');

$TWIG['tabs'] = $tabs;