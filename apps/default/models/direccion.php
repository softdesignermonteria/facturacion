<?php

class Direccion extends ActiveRecord{


	public function initialize(){
		
		$this->hasMany("remisiones");
		$this->hasMany("devoluciones");
		$this->hasMany("factura");
		$this->hasMany("anticipos");
		$this->hasMany("cxc");
		$this->belongsTo("clientes");
       
	}
	
}

?>