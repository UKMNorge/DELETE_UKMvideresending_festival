<?php

$pl_id = get_option('pl_id');
	
// CLEAN
$SQLdel = new SQLdel('smartukm_videresending_ledere_nattleder', array('pl_id_from' => $pl_id) );
$SQLdel->run();

ini_set("error_log", dirname( __FILE__ ).'/error_log');
echo dirname( __FILE__ ).'/error_log';

error_log('NATTLEDER_SAVE: '. $SQLdel->debug());

foreach( $_POST as $key => $val ) {
	if( strpos( $key, 'nattleder_' ) !== false ) {
		$SQL = new SQLins('smartukm_videresending_ledere_nattleder');
		$SQL->add('l_id', $val);
		$SQL->add('dato', str_replace('nattleder_', '', $key ));
		$SQL->add('pl_id_from', $pl_id);
		$SQL->run();
		error_log('NATTLEDER_SAVE: '. $SQL->debug());
	}
}
$result = true;

die( json_encode( $result ) );