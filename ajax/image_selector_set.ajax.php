<?php
if($_POST['kunstner'] == 'true') {
	$m_type = 'bilde_kunstner';
	$t_id = 0;
} else {
	$m_type = 'bilde';
	$t_id = (int)$_POST['t_id'];
}

$slett_relasjon = new SQLdel('smartukm_videresending_media',
					array('pl_id'=>$videresendtil->ID,
						  'b_id'=>$_POST['b_id'],
						  't_id'=>$t_id,
						  'm_type'=>$m_type)
						  );
$slett_relasjon->run();

$lagre_ny_relasjon = new SQLins('smartukm_videresending_media');
$lagre_ny_relasjon->add('pl_id', $videresendtil->ID);
$lagre_ny_relasjon->add('b_id', $_POST['b_id']);
$lagre_ny_relasjon->add('t_id', $t_id);
$lagre_ny_relasjon->add('rel_id', $_POST['rel_id']);
$lagre_ny_relasjon->add('m_type', $m_type);
$lagre_ny_relasjon->run();

die( json_encode( $_POST ) );
?>