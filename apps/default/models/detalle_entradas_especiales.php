<?php

class DetalleEntradasEspeciales extends ActiveRecord{


	public function initialize(){
		
		$this->belongsTo("entradas_especiales");
		$this->belongsTo("kardex_id");
       
	}
	
	


}

?>