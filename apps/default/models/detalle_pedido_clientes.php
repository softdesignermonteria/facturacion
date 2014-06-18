<?php

class DetallePedidoClientes extends ActiveRecord{


	public function initialize(){
		
		$this->belongsTo("pedido_clientes");
       
	}
	
	
}

?>