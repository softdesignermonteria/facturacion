<?php

class Consecutivos extends ActiveRecord{


	public function initialize(){
		
		$this->hasMany("detalle_consecutivos");
		
       
	}
	
	


}

?>