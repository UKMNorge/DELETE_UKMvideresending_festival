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
	
	define('PLUGIN_DIR_PATH', dirname(__FILE__).'/');
}

function UKMvideresending_festival_ajax() {
	require_once('controller/layout.controller.php');
	require_once('ajax/'. $_POST['subaction'] .'.ajax.php');
	die();
}

## CREATE A MENU
function UKMvideresending_festival_menu() {
	global $UKMN;
	UKM_add_menu_page('monstring', 'Videresending', 'Videresending', 'editor', 'UKMvideresending_festival', 'UKMvideresending_festival', 'http://ico.ukm.no/paper-airplane-20.png',20);
	UKM_add_scripts_and_styles( 'UKMvideresending_festival', 'UKMvideresending_festival_script' );

}

## INCLUDE SCRIPTS
function UKMvideresending_festival_script() {
	wp_enqueue_script('handlebars_js');
	wp_enqueue_script('TwigJS');

	wp_enqueue_script('WPbootstrap3_js');
	wp_enqueue_style('WPbootstrap3_css');
	wp_enqueue_style( 'UKMvideresending_festival_style', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.css');
	wp_enqueue_script( 'UKMvideresending_festival_script', plugin_dir_url( __FILE__ ) .'ukmvideresending_festival.js');
	
}

## SHOW STATS OF PLACES
function UKMvideresending_festival() {
	$TWIG = array();
	require_once('controller/layout.controller.php');
	
	$VIEW = isset( $_GET['action'] ) ? $_GET['action'] : 'oversikt';
	
	require_once('controller/'. $VIEW .'.controller.php');
	
	$TWIG['tab_active'] = $VIEW;
	
	echo TWIG($VIEW .'.twig.html', $TWIG, dirname(__FILE__), true);
	echo TWIGjs( dirname(__FILE__) );
}