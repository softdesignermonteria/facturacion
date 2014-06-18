<?php

class BackupController extends ApplicationController {

	public function initialize() {
		//$this->setTemplateAfter("a_bit_boxy");
		 //$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
		 //$this->setTemplateAfter("$temp");
		 	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		 
	}

	public function indexAction(){
		
		
	}
	
	
	public function exportarAction(){
		//$this->ProcessContainer(); 
		$this->setResponse("ajax");
	
	}
	
	public function listarAction(){
		//$this->ProcessContainer(); 
		
		
		
	
	}
	
	
	public function importarAction(){
		//$this->ProcessContainer(); 
		
	}
	
	public function importar2Action(){
		//$this->ProcessContainer(); 
	
	}
	public function importar3Action(){
		//$this->ProcessContainer(); 
	
	}
	
	public function borrarAction(){
		//$this->ProcessContainer(); 
		

		
		
	
	}
	
	
	

}

?>