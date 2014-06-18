<?php

class CxcCuadreCaja extends ActiveRecord{


	public function initialize(){
		
		$this->belongsTo("cxc");
		//$this->belongsTo("clientes");
       
	}
	

}

?>