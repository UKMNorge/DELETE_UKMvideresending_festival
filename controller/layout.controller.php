<?php
require_once('UKM/monstring.class.php');

$m = new monstring( get_option('pl_id') );
$m->season = $m->g('season');
$m->pl_start 	= $m->g('pl_start');
$m->pl_stop		= $m->g('pl_stop');

$videresendtil = new stdClass();
$vt = $m->videresendtil(true);
$videresendtil->ID		= $vt->g('pl_id');
$videresendtil->navn 	= $vt->g('pl_name');
$videresendtil->frist 	= $vt->g('pl_deadline');
$videresendtil->registrert = $vt->registered();
$videresendtil->mottakelig = true;#DEBUG $vt->subscribable();
$videresendtil->pl_start	= $vt->g('pl_start');
$videresendtil->pl_stop		= $vt->g('pl_stop');

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
						  
$tabs[] = (object) array( 'link' 		=> 'ledere',
						  'header' 		=> 'Ledere',
						  'icon'		=> 'user-business-256',
						  'description'	=> 'Ledere og overnatting');
						  
$tabs[] = (object) array( 'link' 		=> 'reiseinfo',
						  'header' 		=> 'Reiseinfo',
						  'icon'		=> 'buss-256',
						  'description'	=> 'Reise- og lederskjema');


$TWIG['tabs'] = $tabs;

$TWIG['m'] = $m;


$TWIG['overnatting']['deltakere']	= 'Spektrum';
$TWIG['overnatting']['hotell']		= 'Bakeriet Hotell';
$TWIG['overnatting']['privat']		= 'Privat/annet';

$TWIG['ledermaltid']	= new stdClass();
$TWIG['ledermaltid']->sted	= 'Clarion Brattøra';
$TWIG['ledermaltid']->dag	= 'Lørdag';
$TWIG['ledermaltid']->tid	= 'kl xx:xx';