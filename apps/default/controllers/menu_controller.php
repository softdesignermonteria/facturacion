<?php

class MenuController extends ApplicationController {

	public function initialize() {
		//$this->setTemplateAfter("menu_azul");
		 //$this->setTemplateAfter("menu_azul");
		 	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
	}

	public function indexAction(){
		
		
	}

}

?>