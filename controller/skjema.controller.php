<?php
require_once(PLUGIN_DIR_PATH . 'class/videresendingsskjema.class.php');

if( get_option('site_type') == 'kommune' ) {
	// $vt er fra layout.controller.php - monstring::videresendtil(true)
	$skjema = new Videresendingsskjema($vt->info['fylke_id'], get_option('pl_id'));
	$TWIG['f_id'] = $vt->info['fylke_id'];
	$TWIG['pl_id'] = get_option('pl_id');
	
	$skjema->har = sizeof( $skjema->getQuestions() ) > 0;
} else {
	$skjema = new stdClass();
	$skjema->har = false;
}

$TWIG['videresendingsskjema'] = $skjema;