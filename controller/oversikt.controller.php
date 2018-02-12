<?php
if( isset( $_GET['lesmer'] ) ) {
	require_once('lesmer/'. $_GET['lesmer'] .'.controller.php');
} else {
	$TWIG['info1'] = get_site_option('UKMFvideresending_info1_'.$season);
}