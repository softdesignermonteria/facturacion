<?php

class TipoDocumento extends ActiveRecord{


	public function initialize(){

		//$this->hasMany("remisiones");
		//$this->hasMany("devoluciones");
		$this->hasMany("factura");
		//$this->hasMany("anticipos");
		$this->hasMany("recibos_caja");
	    $this->hasMany("detalle_consecutivos");
   	    $this->hasMany("entradas_especiales");
		$this->hasMany("traslados");
		$this->hasMany("salidas_especiales");
	}
	
	
}

?>