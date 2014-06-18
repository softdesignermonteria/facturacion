<?php

class Compras extends ActiveRecord{


	public function initialize(){
		
	   $this->belongsTo("bodegas");
       $this->belongsTo("proveedores");
	   
	   
	}
	
	
}

?>