<?php

class DetalleTraslados extends ActiveRecord{


	public function initialize(){
		
		$this->belongsTo("traslados");
		$this->belongsTo("kardex_id");
       
	}
	
	


}

?>