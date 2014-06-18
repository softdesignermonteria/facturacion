<?php

class AyudaController extends ApplicationController {



	public function initialize() {
		//$this->setTemplateAfter("a_bit_boxy");
		 $this->setTemplateAfter("menu_azul","ayuda");
		 // $this->setTemplateAfter("menu_azul");
		 Tag::addJavascript("dtree");
		 Tag::stylesheetLink("dtree");
	}

	public function indexAction(){
		
		
	}
	
	
	

}

?>