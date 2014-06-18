<?php 
class Remisiones extends ActiveRecord{

	public function initialize(){
		
		$this->belongsTo("tipo_documento");
		$this->hasMany("saldo_remisiones");
		$this->belongsTo("clientes");
		$this->belongsTo("direccion");
		$this->belongsTo("empresa");
       
	}
		
		
}
?>