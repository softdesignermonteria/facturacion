<?php

	class RegimenController extends ApplicationController {
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		   // $this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}
		
		public function indexAction(){
		
		}
			
		public function agregarAction(){
		}
		
		public function addAction(){
			//primero instanciamos clase clientes
			$reg  = new Regimen();
			//cargamos el objeto mediantes los metodos setters
			$reg->descripcion = $this->getPostParam("descripcion");
									
			if($reg->save()){
				Flash::success("Se insertó correctamente el registro");
				print("<script>document.location.replace(".core::getInstancePath()."'regimen/mostrar/$reg->id');</script>");
			}else{
				Flash::error("Error: No se pudo insertar registro");	
			}
					
	    }
		
		public function mostrarAction($id){
          
            $regimen = $this->Regimen->find("id='$id'");
            $this->setParamToView("regimen", $regimen);
	
		}
		
		public function modificarAction($id){
			
			$regimen = $this->Regimen->find("id='$id'");
            $this->setParamToView("regimen", $regimen);
		}
		
		public function actualizarAction($id){
			//primero instanciamos clase clientes
			$reg  = new Regimen();
			//cargamos el objeto mediantes los metodos setters
			$reg = $reg->findFirst("id = '$id'");
			$reg->descripcion = $this->getPostParam("descripcion");
											
			if($reg->save()){
				Flash::success("Se actualizo correctamente el registro");
				print("<script>document.location.replace(".core::getInstancePath()."'regimen/mostrar/$reg->id');</script>");
			}else{
				Flash::error("Error: No se pudo insertar registro");	
			}
					
	    }
  
    }
?>