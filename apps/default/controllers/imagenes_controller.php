
<?php

	class imagenesController extends ApplicationController {
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		   // $this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}



		public function indexAction(){
		
		}
				
		
		public function buscar_imagenesAction(){
		
			$this->setTemplateAfter("default");
		
		}
				
		
		
	  
   }
?>