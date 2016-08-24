<?php  
/* 
Plugin Name: UKM Videresending festival
Plugin URI: http://www.ukm-norge.no
Description: Videresendingsfunksjoner fra fylkesmønstringer til UKM-Festivalen
Author: UKM Norge / M Mandal 
Version: 1.0 
Author URI: http://mariusmandal.no
*/

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	require_once('functions.php');
	require_once('UKM/inc/twig-js.inc.php');

	global $blog_id;
	if($blog_id != 1) {
		add_action('UKM_admin_menu', 'UKMvideresending_festival_menu');

		add_action('wp_ajax_UKMvideresending_festival_ajax', 'UKMvideresending_festival_ajax');
	}

	add_filter( 'UKMWPNETWDASH_messages', 'UKMvideresending_check_documents');
	
	define('PLUGIN_DIR_PATH', dirname(__FILE__).'/');
}

function UKMvideresending_festival_ajax() {
	error_reporting( E_NONE );
	require_once('controller/layout.controller.php');
	require_once('ajax/'. $_POST['subaction'] .'.ajax.php');
	die();
}

## CREATE A MENU
function UKMvideresending_festival_menu() {
	global $UKMN;
	if( get_option('site_type') == 'fylke' || get_option('site_type') == 'kommune' ) {
		UKM_add_menu_page('monstring', 'Videresending', 'Videresending2', 'editor', 'UKMvideresending_festival', 'UKMvideresending_festival', 'http://ico.ukm.no/paper-airplane-20.png',20);
		UKM_add_scripts_and_styles( 'UKMvideresending_festival', 'UKMvideresending_festival_script' );
	}
}

function UKMvideresending_check_documents($MESSAGES) {
	$month = date('n');
	$season = ($month > 7) ? date('Y')+1 : date('Y');

	$info1 = get_site_option('UKMFvideresending_info1_'.$season);
	$ua_nom = get_site_option('UKMFvideresending_nominasjon_ua_'.$season); 
	$media_nom = get_site_option('UKMFvideresending_nominasjon_ukmmedia_'.$season);

	if ($month < 6) {
		if( !$info1 || empty($info1) ) {
			$MESSAGES[] = array(	'level' => 'alert-warning', 
									'module' => 'UKMvideresending_festival', 
									'header' => 'Info 1-dokumentet er ikke oppdatert fra i fjor!', 
									'body' => 'Rett dette ved å legge inn rett dokument i Mønstringsmodulen.',
									'link' => 'http://ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
								);
			return $MESSAGES;
		}	
	}

	### Nominasjonsskjema sjekkes i mars
	if ($month < 6 && $month > 2) {
		# UA
		if( !$ua_nom || empty($ua_nom)) {
			$MESSAGES[] = array(	'level' => 'alert-warning', 
									'module' => 'UKMvideresending_festival', 
									'header' => 'Nominasjons-dokumentet for UA er ikke oppdatert!', 
									'body' => 'Rett dette ved å legge inn rett dokument i Mønstringsmodulen.',
									'link' => 'http://ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
								);
		}
		# UKM Media
		if( !$media_nom || empty($media_nom)) {
			$MESSAGES[] = array(	'level' => 'alert-warning', 
									'module' => 'UKMvideresending_festival', 
									'header' => 'Nominasjons-dokumentet for UKM Media er ikke oppdatert!', 
									'body' => 'Rett dette ved å legge inn rett dokument i Mønstringsmodulen.',
									'link' => 'http://ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
								);
		}
	}
	return $MESSAGES;
}

## INCLUDE SCRIPTS
function UKMvideresending_festival_script() {
	wp_enqueue_script('handlebars_js');
	wp_enqueue_script('TwigJS');

	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
	wp_enqueue_style( 'UKMvideresending_festival_style', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.css');

	if(get_option('site_type') == 'kommune') {
		wp_enqueue_script('UKMvideresending_lokal_script', plugin_dir_url( __FILE__ ) .'ukmvideresending_lokal.js');
	}
	elseif(get_option('site_type') == 'fylke') {
		wp_enqueue_script( 'UKMvideresending_festival_script', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.js');
	}
	
}

## SHOW STATS OF PLACES
function UKMvideresending_festival() {
	$TWIG = array();
	require_once('controller/layout.controller.php');
	
	$VIEW = isset( $_GET['action'] ) ? $_GET['action'] : 'oversikt';
	$TWIG['tab_active'] = $VIEW;
	require_once('controller/'. $VIEW .'.controller.php');
	
	
	echo TWIG($VIEW .'.twig.html', $TWIG, dirname(__FILE__), true);
	echo TWIGjs( dirname(__FILE__) );
}
