<?php
$kvoter = new stdClass();
$kvoter->scene	= (object) array('antall'	=> 5,
								 'tid'		=> 25);
$kvoter->film	= (object) array('antall'	=> 1,
								 'tid'		=> 5);
$kvoter->kunst	= (object) array('antall'	=> 4,
								 'personer'	=> 8);
$kvoter->nettred =(object) array('antall' => 3);
$kvoter->arrangor=(object) array('antall' => 2);
$kvoter->konferansier  =(object) array('antall' => 1);
$kvoter->total	= (object) array('deltakere' => 30,
								 'ledere'	 => 3,
								 'deltakere_per_leder' => 10);
$TWIG['kvote']			= $kvoter;