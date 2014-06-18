<?php

class Bodegas extends ActiveRecord{


	public function initialize(){
		
       $this->hasMany("compras");
	   $this->hasMany("movimiento_inventario");
	   
	}
	
	
}

?>