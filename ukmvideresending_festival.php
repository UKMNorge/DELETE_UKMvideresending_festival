<?php  
/* 
Plugin Name: UKM Videresending festival
Plugin URI: http://www.ukm-norge.no
Description: Videresendingsfunksjoner for alle mønstringer.
Author: UKM Norge / M Mandal / A Hustad
Version: 1.0 
Author URI: http://mariusmandal.no
*/

## HOOK MENU AND SCRIPTS
if(is_admin()) {
	require_once('functions.php');
	require_once('UKM/inc/twig-js.inc.php');

	global $blog_id;
	if($blog_id != 1) {
		add_action('UKM_admin_menu', 'UKMvideresending_festival_menu', 101);
		add_action('wp_ajax_UKMvideresending_festival_ajax', 'UKMvideresending_festival_ajax');
		add_action('wp_ajax_UKMvideresendingsskjema_preview', 'UKMVideresendingsskjema2_preview');
		add_action('wp_ajax_UKMnominasjon_toggleStatus', 'UKMnominasjon_ajax');
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
		UKM_add_menu_page('monstring', 'Videresending', 'Videresending', 'editor', 'UKMvideresending_festival', 'UKMvideresending_festival', '//ico.ukm.no/paper-airplane-20.png',20);
		UKM_add_scripts_and_styles( 'UKMvideresending_festival', 'UKMvideresending_festival_script' );
	}
	
	if(get_option('site_type') == 'fylke') {
		// Legg videresendingsskjemaet som en submenu under Mønstring.
		UKM_add_submenu_page('UKMMonstring', 'Videresendingsskjema', 'Lag skjema for videresending', 'editor', 'UKMvideresendingsskjema2', 'UKMvideresendingsskjema2');
		UKM_add_scripts_and_styles( 'UKMvideresendingsskjema2', 'UKMvideresendingsskjema_script');


		// Legg nominasjon som en submenu under Mønstring.
		UKM_add_submenu_page('UKMvideresending_festival', 'Nominasjon', 'Nominasjoner', 'editor', 'UKMnominasjon', 'UKMnominasjon');
		UKM_add_scripts_and_styles( 'UKMnominasjon', 'UKMvideresending_nominasjon_script');
	}
}

function UKMvideresendingsskjema2() {
	$TWIGdata = array();
	require_once(__DIR__.'/controller/videresendingsskjema.controller.php');
	echo TWIG('videresendingsskjema.html.twig', $TWIGdata, dirname(__FILE__));
}

function UKMvideresendingsskjema_script() {
	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
	wp_enqueue_script('UKMVideresendingsskjema2_script', plugin_dir_url(__FILE__) . 'videresendingsskjema2.script.js');
}

function UKMvideresending_nominasjon_script() {
	UKMvideresendingsskjema_script();
	wp_enqueue_script('UKMvideresending_nominasjon', plugin_dir_url( __FILE__ ) .'ukmvideresending_nominasjon.js');
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
									'link' => '//ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
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
									'link' => '//ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
								);
		}
		# UKM Media
		if( !$media_nom || empty($media_nom)) {
			$MESSAGES[] = array(	'level' => 'alert-warning', 
									'module' => 'UKMvideresending_festival', 
									'header' => 'Nominasjons-dokumentet for UKM Media er ikke oppdatert!', 
									'body' => 'Rett dette ved å legge inn rett dokument i Mønstringsmodulen.',
									'link' => '//ukm.no/festivalen/wp-admin/admin.php?page=UKMMonstring' 
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

	wp_enqueue_script('UKMvideresending_base_script', plugin_dir_url( __FILE__ ) .'ukmvideresending_base.js');

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


## SHOW STATS OF PLACES
function UKMnominasjon() {
	$TWIG = array();
	require_once('controller/layout.controller.php');

	$VIEW = isset( $_GET['action'] ) && $_GET['action'] != 'nominasjon' ? $_GET['action'] : 'oversikt';
	$TWIG['tab_active'] = 'nominasjon';
	require_once('controller/nominasjon/'. $VIEW .'.controller.php');
	
	echo TWIG('nominasjon/'. $VIEW .'.html.twig', $TWIG, dirname(__FILE__), true);
}

function UKMnominasjon_ajax() {
	require_once('controller/nominasjon/ajax.controller.php');
	die('false');
}

function UKMVideresendingsskjema2_preview() {
	$TWIG = array();
	$m = new monstring_v2(get_option('pl_id'));
	$vt = new stdClass();
	$vt->info['fylke_id'] = $m->getFylke()->getId();
	#var_dump($vt);
	require_once('controller/ekstra.controller.php');
	echo TWIG('infoskjema.twig.html', $TWIG, dirname(__FILE__), true);
	#echo TWIGjs( dirname(__FILE__) );
	die();
}
