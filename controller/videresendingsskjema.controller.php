<?php
require_once(__DIR__.'/../class/videresendingsskjema.class.php');
$m = new monstring_v2(get_option('pl_id'));
$f_id = $m->getFylke()->getId();
$skjema = new Videresendingsskjema($f_id);
$sporsmalsListe = $skjema->getQuestions();

// Skal vi slette noe?
if(isset($_GET['delete'])) {
	$res = $skjema->deleteQuestion($_GET['delete']);
	// Hent spørsmål på nytt ved suksess
	if($res)
		$sporsmalsListe = $skjema->getQuestions();
	// Feilet det å slette? Ok, we don't care.
}
// Eller er dette et nytt spørsmål?
elseif(isset($_POST['nyTittel'])) {
	$order = count($sporsmalsListe)+1;
	$res = $skjema->addQuestion($_POST['nyTittel'], $_POST['nyType'], $_POST['nyHjelpetekst'], $order);
	// Hent spørsmål på nytt ved suksess
	if($res)
		$sporsmalsListe = $skjema->getQuestions();
}

// Eller er det gjort endringer på et eller flere spørsmål?
elseif(isset($_POST['changes'])) {
	// Lag et array med IDer i sortert rekkefølge.
	$toOrder = explode(",", $_POST['q_order']);

	// Finn IDer på inputs
	$input = array();
	foreach($_POST as $key => $value) {
		$id = null;
		$id = explode('-', $key);
		$id = $id[0];
		if(is_numeric($id)) {
			$input[$id] = array();
		}
	}

	$order = 0;
	foreach($toOrder as $id) {
		$order++;
		$input[$id]['title'] = $_POST[$id.'-title'];
		$input[$id]['type'] = $_POST[$id.'-type'];
		$input[$id]['help'] = $_POST[$id.'-help'];
		$input[$id]['order'] = $order;
	}

	// Oppdater spørsmål
	foreach($input as $id => $data) {
		$res = $skjema->updateQuestion($id, $data['title'], $data['type'], $data['help'], $data['order']);
	}

	// Hent spørsmål på nytt.
	$sporsmalsListe = $skjema->getQuestions();
}

$TWIGdata['sporsmalsListe'] = $sporsmalsListe;

$TWIGdata['draogslippikon'] = '/testfylke/wp-content/plugins/UKMprogram/draogslipp.png';

$sporsmalsTyper = array();
$sporsmalsTyper['overskrift'] = 'Overskrift';
$sporsmalsTyper['janei'] = 'Ja/Nei-spørsmål';
$sporsmalsTyper['korttekst'] = 'Korttekst';
$sporsmalsTyper['kontakt'] = 'Kontakt-informasjon';
$sporsmalsTyper['langtekst'] = 'Langtekst';
$TWIGdata['sporsmalsTyper'] = $sporsmalsTyper;