<?php

require_once('UKM/sql.class.php');
require_once('UKM/fylke.class.php');
require_once('UKM/fylker.class.php');


class Videresendingsskjema {
	public function __construct($f_id) {
		$this->f_id = $f_id;
		$this->fylke = Fylker::getById($f_id);
	}	

	public function getQuestions() {
		// TODO: Load from database
		$sql = new SQL("SELECT * FROM `smartukm_videresending_fylke_sporsmal`
						WHERE `f_id` = '#f_id'", array('f_id' => $this->f_id));

		#echo $sql->debug();
		$res = $sql->run();
		$questions = array();
		while ($row = mysql_fetch_assoc($res)) {
			$questions[] = $this->getQuestionFromData($row);
		}
		// Returner et ferdig sortert array i rett rekkefølge
		#$questions = array($this->getQuestion(4), $this->getQuestion(7), $this->getQuestion(2));
		$questions = $this->orderQuestions($questions);
		return $questions;
		#return array();
	}

	private function orderQuestions($questions) {
		#usort($questions, array($this, "cmp"));
		usort($questions, function($a, $b) {
			return $a->order - $b->order;
		});
		return $questions;
	}

	public function getQuestionAnswer($question) {


		// TODO: Load from database;

		if ($question->type == 'janei') {
			if (rand(1, 10) > 5) 
				return 'true';
			return 'false';
		}
		// TODO: Logikk for å sortere ut __||__ fra DB.
		if ($question->type == 'kontakt') {
			$answer = new stdClass();
			$answer->navn = 'Navn';
			$answer->mobil = '98765432';
			$answer->epost = 'support@ukm.no';
		}

		return 'Lang eller kort tekst eller noe';
	}

	public function getQuestion($id) {
		// TODO: Load from database

		// TODO: Dummy data no more
		$q = new stdClass();
		$q->id = $id;
		$q->title = 'Spørsmålstittel'.$id; // TODO: DROPP ID
		$q->type = 'kontakt'; // May be 'janei', 'korttekst', 'langtekst', 'kontakt', 'overskrift'. Mulig mer?
		$q->help = 'Hjelpetekst til spørsmålet'; 
		$q->order = $id; // Rekkefølgehjelper, for å sortere elementer.
		$q->f_id = $this->f_id; // Fylke-ID - trengs denne?
		$q->fylke = $this->fylke;
		$q->value = $this->getQuestionAnswer($q);
		return $q;
	}

	public function getQuestionFromData($data) {
		$q = new stdClass();
		$q->id = $data['q_id'];
		$q->title = $data['q_title']; // TODO: DROPP ID
		$q->type = $data['q_type']; // May be 'janei', 'korttekst', 'langtekst', 'kontakt', 'overskrift'. Mulig mer?
		$q->help = $data['q_help']; 
		$q->order = $data['order']; // Rekkefølgehjelper, for å sortere elementer.
		$q->f_id = $data['f_id']; // Fylke-ID - trengs denne?
		$q->fylke = $this->fylke;
		$q->value = $this->getQuestionAnswer($q);
		return $q;
	}

	public function answerQuestion($q_id, $pl_id, $answer) {
		// Check if question is answered before
		$sql = new SQL("SELECT COUNT(*) FROM 'smartukm_videresending_fylke_svar' 
						WHERE `q_id` = '#q_id'", array('q_id' => $q_id));
		$res = $sql->run('field', 'count(*)');
		if($res > 0) 
			$sql = new SQLins('smartukm_videresending_fylke_svar', array('q_id' => $q_id));
		else
			$sql = new SQLins('smartukm_videresending_fylke_svar');

		if (is_array($answer)) {
			$a = '';
			foreach ($answer as $sub) {
				$a = $a . '__||__' . $sub;
			}
			$answer = trim($a, '__||__');
		}

		$sql->add('q_id', $q_id);
		$sql->add('pl_id', $pl_id);
		$sql->add('answer', $answer);

		$res = $sql->run();
	}
}