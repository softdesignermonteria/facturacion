<?php 
class EntradasEspeciales extends ActiveRecord{

	public function initialize(){
		
		$this->belongsTo("tipo_documento");
		//$this->belongsTo("clientes");
		$this->belongsTo("proveedores");
		$this->belongsTo("empresa");
        $this->hasMany("detalle_entradas_especiales");
	}
		
		
}
?>