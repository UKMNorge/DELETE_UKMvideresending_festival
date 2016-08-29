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
$videresendtil->frist2 	= $vt->g('pl_deadline2');
$videresendtil->registrert = $vt->registered();
$videresendtil->opened 	   = $vt->subscriptionOpened();
$videresendtil->mottakelig = $vt->subscribable();
$videresendtil->pl_start	= $vt->g('pl_start');
$videresendtil->pl_stop		= $vt->g('pl_stop');

$videresendtil->infotekst = get_site_option('videresending_info_pl'.$videresendtil->ID);

$current_user_id = get_current_user_id();
if( $current_user_id == 1 ) 
	$videresendtil->mottakelig = true;

$TWIG['site_type'] = get_option('site_type');
$TWIG['videresendtil'] 	= $videresendtil;

$tabs = array();

$tabs[] = (object) array( 'link' 		=> 'oversikt',
						  'header' 		=> 'Oversikt',
						  'icon'		=> 'info-button-256',
						  'description'	=> 'Start på denne fanen');

if ($videresendtil->opened) {				  
	$tabs[] = (object) array( 'link' 		=> 'videresendte',
							  'header' 		=> 'Videresendte',
							  'icon'		=> 'people-256',
							  'description'	=> 'Velg deltakere her');
} else {

}
						  
$tabs[] = (object) array( 'link' 		=> 'media',
						  'header' 		=> 'Media',
						  'icon'		=> 'video-256',
						  'description'	=> 'Bilder, film og playback');
if($TWIG['site_type'] == 'fylke') {
	$tabs[] = (object) array( 'link' 		=> 'ledere',
							  'header' 		=> 'Ledere',
							  'icon'		=> 'user-business-256',
							  'description'	=> 'Ledere og overnatting');
							  
	$tabs[] = (object) array( 'link' 		=> 'reiseinfo',
							  'header' 		=> 'Reiseinfo',
							  'icon'		=> 'buss-256',
							  'description'	=> 'Reise og tilrettelegging');
}
elseif(get_option('site_type') == 'kommune') {
	$tabs[] = (object) array( 'link'		=> 'ekstra',
							  'header'		=> 'infoskjema',
							  'icon'		=> 'chart-256',
							  'description' => 'Info til fylkeskontakten'
							);
}

$TWIG['tabs'] = $tabs;

$TWIG['m'] = $m;

$TWIG['overnatting_pris_hotell']	= (int) get_site_option('UKMFvideresending_hotelldogn_pris_'.get_option('season'));

$TWIG['overnatting']['deltakere']	= 'Spektrum';
$TWIG['overnatting']['hotell']		= 'Bakeriet Hotell';
$TWIG['overnatting']['privat']		= 'Privat/annet';

$TWIG['ledermaltid']	= new stdClass();
$TWIG['ledermaltid']->tittel= 'Ledermiddag';
$TWIG['ledermaltid']->sted	= get_site_option('UKMFvideresending_ledermiddag_sted_'.get_option('season'));;
$TWIG['ledermaltid']->dag	= get_site_option('UKMFvideresending_ledermiddag_dag_'.get_option('season'));
$TWIG['ledermaltid']->tid	= 'kl '.get_site_option('UKMFvideresending_ledermiddag_tid_'.get_option('season'));
$TWIG['ledermaltid']->pris	= (int) get_site_option('UKMFvideresending_ledermiddag_avgift_'.get_option('season'));
