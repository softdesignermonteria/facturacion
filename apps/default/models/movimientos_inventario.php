<?php

class MovimientosInventario extends ActiveRecord{


	public function initialize(){
		
     	 $this->belongsTo('kardex');
		 $this->belongsTo('empresa');
		 $this->belongsTo('bodegas');
	   
	}
	
	


}

?>