<?php
require_once('UKM/monstring.class.php');

// Informasjon om mønstringen vi kommer fra
$m = new monstring( get_option('pl_id') );
$m->season = $m->g('season');
$m->pl_start 	= $m->g('pl_start');
$m->pl_stop		= $m->g('pl_stop');
$m->type		= $m->get('pl_type');

// Informasjon om mønstringen vi videresender til
$videresendtil = new stdClass();
$vt = $m->videresendtil(true);
$videresendtil->ID			= $vt->g('pl_id');
$videresendtil->navn 		= $vt->g('pl_name');
$videresendtil->frist	 	= $vt->g('pl_deadline');
$videresendtil->frist2 		= $vt->g('pl_deadline2');
$videresendtil->registrert	= $vt->registered();
$videresendtil->opened		= $vt->subscriptionOpened();
$videresendtil->mottakelig	= $vt->subscribable();
$videresendtil->pl_start	= $vt->g('pl_start');
$videresendtil->pl_stop		= $vt->g('pl_stop');
$videresendtil->infotekst = stripslashes(get_site_option('videresending_info_pl'.$videresendtil->ID));

// Info om brukeren, og overstyring av videresending hvis superadmin
$current_user_id = get_current_user_id();
if( is_super_admin() ) {
	$videresendtil->mottakelig = true;
}

$TWIG['site_type'] = get_option('site_type');
$TWIG['videresendtil'] 	= $videresendtil;

// Informasjon om statistikk er mottatt eller ikke
require_once('statistikk.controller.php');

// Informasjon om fylket har videresendingsskjema eller ikke
require_once('skjema.controller.php');

$TWIG['page'] = $_GET['page'];
$TWIG['m'] = $m;

$TWIG['overnatting_pris_hotell']	= (int) get_site_option('UKMFvideresending_hotelldogn_pris_'.get_option('season'));

$TWIG['overnatting']['deltakere']	= 'Deltakerlandsbyen';
$TWIG['overnatting']['hotell']		= 'Lederhotellet';
$TWIG['overnatting']['privat']		= 'Privat/annet';

$TWIG['ledermaltid']	= new stdClass();
$TWIG['ledermaltid']->tittel= 'Ledermiddag';
$TWIG['ledermaltid']->sted	= get_site_option('UKMFvideresending_ledermiddag_sted_'.get_option('season'));;
$TWIG['ledermaltid']->dag	= get_site_option('UKMFvideresending_ledermiddag_dag_'.get_option('season'));
$TWIG['ledermaltid']->tid	= 'kl '.get_site_option('UKMFvideresending_ledermiddag_tid_'.get_option('season'));
$TWIG['ledermaltid']->pris	= (int) get_site_option('UKMFvideresending_ledermiddag_avgift_'.get_option('season'));
