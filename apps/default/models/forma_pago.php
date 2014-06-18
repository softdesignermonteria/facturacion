<?php

class FormaPago extends ActiveRecord{


	public function initialize(){
		
		//$this->belongsTo("egresos");
		$this->hasMany("egresos");
       
	}
	
	
}

?>