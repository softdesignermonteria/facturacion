<?php

class Turnos extends ActiveRecord{


	public function initialize(){
		
       
	}
	
	
	public function before_save(){

            if($this->tipo_usuario == 0 ){
                Flash::error("Debe escoger un tipo de usuario");
                return 'cancel';
            }
      
    }

}

?>