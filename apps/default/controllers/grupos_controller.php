<?php

	class GruposController extends ApplicationController {
	
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
			$gru  = new Grupos();
			$this->setResponse("view");
			//cargamos el objeto mediantes los metodos setters
			$gru->nombre_grupo = $this->getPostParam("nombre_grupo");
									
			if($gru->save()){
				
				Flash::success("Se insertó correctamente el registro");
				print("<script>$('grupos_add').reset();</script>");
				//Flash::success("Se insertó correctamente el registro");
				
			}else{
				Flash::error("Error: No se pudo insertar registro");	
			}
					
	    }
		
		public function mostrarAction($id){
          
            $grupos = $this->Grupos->find("id='$id'");
            $this->setParamToView("grupos", $grupos);
	
		}
		
				
		public function updateAction(){
			//primero instanciamos clase clientes
			
			$this->setResponse("view");
			$id = $_REQUEST["id"];
			//cargamos el objeto mediantes los metodos setters
			$gru = $this->Grupos->findFirst("id = '$id'");
			
			$gru->nombre_grupo = $_REQUEST["nombre_grupo"];
											
			if($gru->save()){
				Flash::success("Se actualizo correctamente el registro");
				
			}else{
				Flash::error("Error: No se pudo actualizar registro");	
			}
					
					
	    }
  		
		
		public function deleteAction($id){
			//primero instanciamos clase clientes
			$this->setResponse("view");
			if($this->Kardex->count(" grupos_id = '$id' ") > 0 ){
				$sw=1;
				Flash::error("NO SE PUDO BORRAR EL REGISTRO POR QUE ESTE GRUPO TIENE RELACIONADOS REFERENCIAS ....");
			}			
			
			
			if($sw==0){
				$gru = $this->Grupos->findFirst("id = '$id'");
				if($gru->delete()){
					Flash::success("Se elimino correctamente el registro");
					
				}else{
					Flash::error("Error: No se pudo borrar registro");	
				}
			}//fin todo bien sw==0;
					
	    }
  
  
    }
?>