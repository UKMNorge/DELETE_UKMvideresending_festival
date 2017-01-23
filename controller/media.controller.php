<?php
require_once('UKM/innslag.class.php');
require_once('UKM/tittel.class.php');
require_once( PLUGIN_DIR_PATH.'functions.php' );

$videresendte = $m->videresendte();

$media_ok = true;

foreach( $videresendte as $inn ) {
	$i = new innslag( $inn['b_id'] );
	
	// Hvis fylket videresender til festivalen skal også konferansierene ha media
	if( get_option('site_type') == 'fylke' ) {
		if( $i->tittellos() && $i->g('bt_id') != 4 ) {
			continue;
		}
	// Lokalt til fylke skal ikke ha media på konferansierene
	} elseif( $i->tittellos() ) {
		continue;
	}
		
	$innslag = new stdClass();
	$innslag->ID 		= $i->g('b_id');
	$innslag->navn 		= $i->g('b_name');
	$innslag->media		= new stdClass();

	$related_media = $i->related_items();
	
	switch( $i->g('bt_form') ) {
		case 'smartukm_titles_video':
			$sort = 'film';
			if( sizeof( $related_media['tv'] ) == 0 ) {
				$innslag->media->film = 'none_related';
				$media_ok = false;
			} else {
				$innslag->media->film = $related_media['tv'];
			}
			break;
		case 'smartukm_titles_exhibition':
			$sort = 'kunst';
			
			if( sizeof( $related_media['image'] ) == 0 ) {
				$innslag->media->kunstner = 'none_uploaded';
				$media_ok = false;
			} else {
				$innslag->media->kunstner = image_selected( $innslag, 0, 'bilde_kunstner' );
				if( $innslag->media->kunstner == 'none_selected' ) {
					$media_ok = false;
				}
			}
			
			$titler = $i->titler( $m->g('pl_id'), $videresendtil->ID );
			
			if( is_array( $titler ) ) {
				foreach( $titler as $tittel ) {
					$tittel->media = new stdClass();
					if( sizeof( $related_media['image'] ) == 0 ) {
						$tittel->media->image = 'none_uploaded';
						$media_ok = false;
					} else {
						$tittel->media->image = image_selected( $innslag, $tittel->t_id );
						if( $tittel->media->image == 'none_selected' ) {
							$media_ok = false;
						}
					}
					$innslag->titler[] = $tittel;
				}
			}

			break;
		case 'smartukm_titles_other':
			$sort = 'konferansier';
			if( sizeof( $related_media['image'] ) == 0 ) {
				$innslag->media->image = 'none_uploaded';
				$media_ok = false;
			} else {
				$innslag->media->image = image_selected( $innslag );
				if( $innslag->media->image == 'none_selected' ) {
					$media_ok = false;
				}
			}
			if( $i->har_playback() ) {
				$innslag->playback = $i->playback();
			} else {
				$innslag->playback = false;
			}
			break;
		default:
			$sort = 'scene';
			
			if( sizeof( $related_media['image'] ) == 0 ) {
				$innslag->media->image = 'none_uploaded';
				$media_ok = false;
			} else {
				$innslag->media->image = image_selected( $innslag );
				if( $innslag->media->image == 'none_selected' ) {
					$media_ok = false;
				}
			}
			
			if( sizeof( $related_media['tv'] ) == 0 ) {
				$innslag->media->film = 'none_related';
				$media_ok = false;
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

update_option('UKMvideresending_festival_mediaOK', $media_ok );

$TWIG['site_type'] = get_option('site_type');