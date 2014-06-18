<?php

class DetalleRecibosCaja extends ActiveRecord{


	public function initialize(){
		
      // $this->setIdGenerator("Uniqid", "id"); 
	  $this->belongsTo("anticipos");
	  $this->belongsTo("RecibosCaja");
	
	   
	}
	
	


}

?>