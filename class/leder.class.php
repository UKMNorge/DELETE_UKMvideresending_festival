<?php

class leder {
	var $table = array('l_navn','l_epost','l_mobilnummer','l_type');
	
	public function __construct( $id=false ) {
		$this->ID = $id;
		
		if( $id ) {
			$this->_load();
		}
	}
	
	public function set( $key, $val ) {
		$this->$key = $val;
	}
	
	public function update() {
		$sql = new SQLins('smartukm_videresending_ledere_ny', array('l_id' => $this->ID));
		$this->_add_sql_values( $sql );
		$res = $sql->run();
		return $res != -1;
	}
	
	public function create( $pl_from, $pl_to, $season ) {
		$this->pl_from = $pl_from;
		$this->pl_to = $pl_to;
		$this->season = $season;
		
		$sql = new SQLins('smartukm_videresending_ledere_ny');
		$this->_add_sql_values( $sql );
		$res = $sql->run();
		
		$this->ID = $sql->insid();
	}
	
	public function load_by_type( $pl_from, $pl_to, $type ) {
		$this->l_type = $type;
		$sql = new SQL("SELECT `l_id`
						FROM `smartukm_videresending_ledere_ny`
						WHERE `pl_id_from` = '#from'
						AND `pl_id_to` = '#to'
						AND `l_type` = '#type' ",
					array(	'from' => $pl_from,
							'to' => $pl_to,
							'type' => $type
						)
					);
		$this->ID = $sql->run('field', 'l_id');
		return $this->_load();
	}
	
	public function delete( $pl_from ) {
		$sql = new SQLdel('smartukm_videresending_ledere_ny', array('l_id' => $this->ID, 'pl_id_from' => $pl_from ));
		$res = $sql->run();
		
		if( $res != -1 ) {
			$sql = new SQLdel('smartukm_videresending_ledere_natt', array('l_id' => $this->ID));
			return $sql->run() != -1;
		}
		return false;
	}
	
	private function _add_sql_values( $sql ) {
		foreach( $this->table as $key ) {
			if( $key == 'l_mobilnummer')
				$sql->add( $key, (int)$this->$key );
			else
				$sql->add( $key, $this->$key );
		}
		$sql->add('pl_id_to', $this->pl_to);
		$sql->add('pl_id_from', $this->pl_from);
		$sql->add('season', $this->season);
		return $sql;
	}
	
	
	private function _load() {
		$sql = new SQL("SELECT * 
						FROM `smartukm_videresending_ledere_ny`
						WHERE `l_id` = '#l_id'",
					array('l_id' => $this->ID)
					);
		$res = $sql->run();
		
		if( mysql_num_rows( $res ) == 0 )
			return false;
		
		$row = mysql_fetch_assoc( $res );
		
		foreach( $row as $key => $val ) {
			$this->$key = $val;
		}
		
		$this->pl_to = $this->pl_id_to;
		$this->pl_from = $this->pl_id_from;
		
		return true;
	}
}