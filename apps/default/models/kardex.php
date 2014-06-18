<?php
 
  class Kardex extends ActiveRecord{
  		
		public function initialize(){
			
			$this->belongsTo('grupos');
		    $this->hasMany("saldo_remisiones");
			$this->hasMany("movimiento_inventarios");
		}
		
  }

?>