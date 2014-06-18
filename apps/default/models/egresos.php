<?php
 
  class Egresos extends ActiveRecord{
  
  	public function initialize(){

				$this->belongsTo("tipo_documento");
				$this->belongsTo("proveedores");
				$this->belongsTo("empresa");
				$this->belongsTo("forma_pago");
				
	}
  
  }

?>