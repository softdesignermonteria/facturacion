<?php

class Empresa extends ActiveRecord{


	public function initialize(){
		
       $this->hasMany("detalle_consecutivos");
	   $this->hasMany("factura");
	   $this->hasMany("egresos");
	   $this->hasMany("remisiones");
	   $this->hasMany("anticipos");
	   $this->hasMany("recibos_caja");
	   $this->hasMany("saldo_remisiones");
	   $this->hasMany("devoluciones");
	   $this->hasMany("entradas_especiales");
	   $this->hasMany("salidas_especiales");
	   $this->hasMany("traslados");
	   $this->hasMany("movimientos_inventarios");
	   $this->hasMany("pedido_clientes");
	  
	   
	   
	}
	
	


}

?>