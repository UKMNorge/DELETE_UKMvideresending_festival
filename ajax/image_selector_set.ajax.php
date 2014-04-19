<?php

$slett_relasjon = new SQLdel('smartukm_videresending_media',
					array('pl_id'=>$videresendtil->ID,
						  'b_id'=>$_POST['b_id'],
						  'm_type'=>'bilde'));
$slett_relasjon->run();

$lagre_ny_relasjon = new SQLins('smartukm_videresending_media');
$lagre_ny_relasjon->add('pl_id', $videresendtil->ID);
$lagre_ny_relasjon->add('b_id', $_POST['b_id']);
$lagre_ny_relasjon->add('t_id', (int)$_POST['t_id']);
$lagre_ny_relasjon->add('rel_id', $_POST['rel_id']);
$lagre_ny_relasjon->add('m_type', 'bilde');
$lagre_ny_relasjon->run();

die( json_encode( $_POST ) );
?>