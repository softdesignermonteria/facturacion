<?php
 
  class Grupos extends ActiveRecord{
  		
			
			
		public function initialize(){
			
		   	$this->hasMany("kardex");
		}
		
  }

?>