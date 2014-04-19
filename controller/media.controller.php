<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');

$videresendte = $m->videresendte();

foreach( $videresendte as $inn ) {
	$i = new innslag( $inn['b_id'] );
	
	if( $i->tittellos() )
		continue;
		
	$innslag = new stdClass();
	$innslag->ID 		= $i->g('b_id');
	$innslag->navn 		= $i->g('b_name');

	$related_media = $i->related_items();
	
	switch( $i->g('bt_form') ) {
		case 'smartukm_titles_video':
			$sort = 'film';
			if( sizeof( $related_media['tv'] ) == 0 ) {
				$innslag->media->film = 'none_related';
			} else {
				$innslag->media->film = $related_media['tv'];
			}
			break;
		case 'smartukm_titles_exhibition':
			$sort = 'kunst';
			
			if( sizeof( $related_media['image'] ) == 0 ) {
				$innslag->media->kunstner = 'none_uploaded';
			} else {
				$innslag->media->kunstner = image_selected( $innslag, 0, 'bilde_kunstner' );
			}
			
			$titler = $i->titler( $m->g('pl_id'), $videresendtil->ID );
			
			if( is_array( $titler ) ) {
				foreach( $titler as $tittel ) {
					if( sizeof( $related_media['image'] ) == 0 ) {
						$tittel->media->image = 'none_uploaded';
					} else {
						$tittel->media->image = image_selected( $innslag, $tittel->t_id );
					}
					$innslag->titler[] = $tittel;
				}
			}

			break;
		default:
			$sort = 'scene';
			
			if( sizeof( $related_media['image'] ) == 0 ) {
				$innslag->media->image = 'none_uploaded';
			} else {
				$innslag->media->image = image_selected( $innslag );
			}
			
			if( sizeof( $related_media['tv'] ) == 0 ) {
				$innslag->media->film = 'none_related';
			} else {
				$innslag->media->film = $related_media['tv'];
			}
			
			if( $i->har_playback() ) {
				$innslag->playback = $i->playback();
			} else {
				$innslag->playback = false;
			}

			break;
	}	
	
	$TWIG['videresendte'][$sort][] = $innslag;
}


function image_selected( $innslag, $tittel = false, $type = 'bilde' ) {
	$valgtBilde = new SQL("SELECT *
						   FROM `smartukm_videresending_media` AS `media`
						   JOIN `ukmno_wp_related` ON (`ukmno_wp_related`.`rel_id` = `media`.`rel_id`)
						   WHERE `media`.`b_id` = '#bid'
						   AND `m_type` = '#type'
  						   AND (`t_id` = '0' OR `t_id` = '#tid' OR `t_id` IS NULL)",
						  array('bid'=>$innslag->ID,'tid'=>$tittel, 'type' => $type)
						 );
	$bilde = $valgtBilde->run('field','rel_id');
	
	if( $bilde == null )
		return 'none_selected';
	
	// CALC IMAGE DATA
	$bilde = $valgtBilde->run('array');

	$post_meta = unserialize( $bilde['post_meta'] );
	if( isset( $post_meta['sizes']['thumbnail']['file'] ) ) {
		$src = $post_meta['sizes']['thumbnail']['file'];
	} else {
		$src = $post_meta['file'];
	}
	
	$src = 'http://'. $_SERVER['HTTP_HOST'].'/wp-content/uploads/sites/'. str_replace('1151','17',$bilde['blog_id']).'/' . $src;

	
	$vb = new stdClass();
	$vb->ID = $bilde['rel_id'];
	$vb->src = $src;
	return $vb;
}