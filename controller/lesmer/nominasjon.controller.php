<?php
$VIEW = 'oversikt_lesmer_nominasjon';

$TWIG['frister'] = get_site_option('UKMFvideresending_nominasjon_frister');

$season = ($month > 7) ? date('Y')+1 : date('Y');
$TWIG['skjema_ua'] = get_site_option('UKMFvideresending_nominasjon_ua_'.$season);
$TWIG['skjema_ukmmedia'] = get_site_option('UKMFvideresending_nominasjon_ukmmedia_'.$season);
$TWIG['skjema_konf'] = get_site_option('UKMFvideresending_nominasjon_konf_'.$season);