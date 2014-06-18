<?php

class DetalleConsecutivos extends ActiveRecord{


	public function initialize(){
		
       $this->belongsTo("consecutivos");
	   $this->belongsTo("tipo_documento");
	   $this->belongsTo("empresa_id");
	   
	}
	
	


}

?>