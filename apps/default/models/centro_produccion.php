<?php

class CentroProduccion extends ActiveRecord{


	public function initialize(){
		
       $this->hasMany("pedido_clientes");
	}
	
	
	public function before_save(){

            if($this->tipo_usuario == 0 ){
                Flash::error("Debe escoger un tipo de usuario");
                return 'cancel';
            }
      
    }

}

?>