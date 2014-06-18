<?php
class tarifa_ivaController extends StandardForm {
  
	public $scaffold = true;

	
	public function initialize(){
			//$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("adminiziolite");
	}
	
	

}
?>