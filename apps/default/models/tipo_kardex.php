<?php

class TipoKardex extends ActiveRecord{


	public function initialize(){

		$this->hasMany("kardex");
		
	}
	
	
}

?>