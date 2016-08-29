<?php

require_once(PLUGIN_DIR_PATH . 'class/videresendingsskjema.class.php');

$data = $_POST['skjema'];
$f_id = $data['f_id'];
$pl_id = $data['pl_id'];
if(!is_numeric($f_id) || !is_numeric($pl_id)) 
	die(json_encode('Kan kun ta i mot numeriske f_id og pl_id'));
$skjema = new Videresendingsskjema($f_id);

$questions = array();

foreach($data as $key => $value) {
    if (strpos($key, 'question_') === 0) {
        // value starts with book_
        $str = explode('_', $key); // $str[0] = "question", $str[1] == id, $str[2] (if any) == "navn"/"mobil"/"epost"
		$q_id = $str[1];

		$debug[] = array($str, $value);
		if(count($str) > 2) {
			$questions[$q_id][$str[2]] = $value;
		} else {
			$questions[$q_id] = $value;
		}
    }
}
/*$res[] = $data;
$res[] = $questions;
$res[] = $debug;
die(json_encode($res));*/
$results = array();
#$i = 0;
$numQ = 0;
foreach ($questions as $q_id => $answer ) {
	#if($i == 4) $debug = false;
	#else $debug = true;
	$res = $skjema->answerQuestion($q_id, $pl_id, $answer, $debug );
	if(!is_numeric($res))
		$results[] = $res->error();
	$numQ++;
	#if($i == 4) die(json_encode(array($answer, $questions)));
	#$i++;
}

if ( count($results) == 0 ) {
	$message = new stdClass();
	$message->success = true;
	$message->title = 'Dine svar er lagret';
	$message->body = 'Svarene du oppga er lagret.';
}
else {
	$message = new stdClass();
	$message->success = false;
	$message->title = 'En feil oppstod!';
	$message->body = 'Ett eller flere av svarene dine ble ikke lagret. Prøv igjen.';
	$message->num = $numQ;
	$message->results = $results;
}
die(json_encode($message));