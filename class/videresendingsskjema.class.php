<?php

require_once('UKM/sql.class.php');
require_once('UKM/fylke.class.php');
require_once('UKM/fylker.class.php');


class Videresendingsskjema {
	public function __construct($f_id, $pl_id = null) {
		$this->f_id = $f_id;
		$this->pl_id = $pl_id;
		$this->fylke = Fylker::getById($f_id);
	}	

	public function getQuestions() {
		$sql = new SQL("SELECT * FROM `smartukm_videresending_fylke_sporsmal`
						WHERE `f_id` = '#f_id'", array('f_id' => $this->f_id));

		$res = $sql->run();
		$questions = array();
		while ($row = mysql_fetch_assoc($res)) {
			$questions[] = $this->getQuestionFromData($row);
		}

		// Returner et ferdig sortert array i rett rekkefølge
		$questions = $this->orderQuestions($questions);
		return $questions;
	}

	private function orderQuestions($questions) {
		usort($questions, function($a, $b) {
			return $a->order - $b->order;
		});
		return $questions;
	}

	public function getQuestionAnswer($question) {
		if(!$this->pl_id) {
			return null;
			#die('pl_id må være satt for at Videresendingsskjema skal kunne laste ut svar på spørsmål.');
		}

		$sql = new SQL("SELECT * FROM `smartukm_videresending_fylke_svar`
						WHERE `q_id` = '#q_id' AND `pl_id` = '#pl_id'", 
						array('q_id' => $question->id, 'pl_id' => $this->pl_id));
		$res = $sql->run('array');

		if ($question->type == 'kontakt') {
			$str = explode('__||__', $res['answer']);
			$answer = new stdClass();
			$answer->navn = $str[0];
			$answer->mobil = $str[1];
			$answer->epost = $str[2];

			$res['answer'] = $answer;
		}

		return $res['answer'];
		#return 'Lang eller kort tekst eller noe';
	}

	/**
	 * Test-method only, provides dummy data.
	 *
	 */
	public function getQuestion($id) {
		$q = new stdClass();
		$q->id = $id;
		$q->title = 'Spørsmålstittel'.$id; // TODO: DROPP ID
		$q->type = 'kontakt'; // May be 'janei', 'korttekst', 'langtekst', 'kontakt', 'overskrift'. Mulig mer?
		$q->help = 'Hjelpetekst til spørsmålet'; 
		$q->order = $id; // Rekkefølgehjelper, for å sortere elementer.
		$q->f_id = $this->f_id; // Fylke-ID - trengs denne?
		$q->fylke = $this->fylke;
		if($this->pl_id)
			$q->value = $this->getQuestionAnswer($q);
		return $q;
	}

	public function getQuestionFromData($data) {
		$q = new stdClass();
		$q->id = $data['q_id'];
		$q->title = $data['q_title']; 
		$q->type = $data['q_type']; // May be 'janei', 'korttekst', 'langtekst', 'kontakt', 'overskrift'. Mulig mer?
		$q->help = $data['q_help']; 
		$q->order = $data['order']; // Rekkefølgehjelper, for å sortere elementer.
		$q->f_id = $data['f_id']; // Fylke-ID - trengs denne?
		$q->fylke = $this->fylke;
		$q->value = $this->getQuestionAnswer($q);
		return $q;
	}

	public function answerQuestion($q_id, $pl_id, $answer, $debug = false) {

		#if($debug) die(json_encode(array($answer, gettype($answer), count($answer))));
		// Check if question is answered before
		$sql = new SQL("SELECT COUNT(*) FROM smartukm_videresending_fylke_svar 
						WHERE `q_id` = '#q_id' AND `pl_id` = '#pl_id'", array('q_id' => $q_id, 'pl_id' => $pl_id));
		#die(json_encode($sql->debug()));
		$res = $sql->run('field', 'COUNT(*)');
		#if($debug) die(json_encode($res));
		if($res > 0) 
			$sql = new SQLins('smartukm_videresending_fylke_svar', array('q_id' => $q_id, 'pl_id' => $pl_id));
		else
			$sql = new SQLins('smartukm_videresending_fylke_svar');

		if (is_array($answer) && (count($answer) > 1) ) {
			$a = '';
			foreach ($answer as $sub) {
				$a = $a . '__||__' . $sub;
			}
			$a = trim($a, '__||__');
		} 
		else
			$a = $answer;

		$sql->add('q_id', $q_id);
		$sql->add('pl_id', $pl_id);
		$sql->add('answer', (string)$a);
		
		$res = $sql->run();
		if ($res != 1)
			if ($sql->error())
				return $sql; 
		return $res;
	}
}