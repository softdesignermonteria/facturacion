<?php

class SaldoRemisiones extends ActiveRecord{


	public function initialize(){
		
     	 $this->belongsTo("clientes");
		 $this->belongsTo("kadex");
		 $this->belongsTo("empresa");
		 $this->belongsTo("remisiones");
	   
	}
	
	


}

?>