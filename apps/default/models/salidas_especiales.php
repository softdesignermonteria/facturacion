<?php 
class SalidasEspeciales extends ActiveRecord{

	public function initialize(){
		
		$this->belongsTo("tipo_documento");
		//$this->belongsTo("clientes");
		//$this->belongsTo("direccion");
		$this->belongsTo("empresa");
       
	}
		
		
}
?>