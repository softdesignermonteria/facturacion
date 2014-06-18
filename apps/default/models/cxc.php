<?php

class Cxc extends ActiveRecord{


	public function initialize(){
		
		
		
		$this->hasMany("cxc_cuadre_caja");
       
	}
	

}

?>