<?php

class PedidoClientes extends ActiveRecord{


	public function initialize(){
		
		$this->hasMany("detalle_pedido_clientes");
		$this->belongsTo("empresa");
		$this->belongsTo("centro_produccion");
       
	}
	
	
}

?>