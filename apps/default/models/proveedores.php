<?php
 
  class Proveedores extends ActiveRecord{
  
  		public function initialize(){
		
		  
		   $this->hasMany("egresos");
		   $this->hasMany("entradas_especiales");
		   $this->hasMany("salidas_especiales");
		   $this->hasMany("compras");
		  
	   
	   
	}
  
  }

?>