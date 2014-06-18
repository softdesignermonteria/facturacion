<?php

	class EmpresaController extends ApplicationController {
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		    //$this->setTemplateAfter("menu_azul");
			$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}
		
		public function indexAction(){
			
			$this->setParamToView("detalles",$this->Empresa->find());
			
		}
			
		
		public function agregarAction(){
		}
		
		public function addAction(){
			//primero instanciamos clase clientes
			$emp  = new Empresa();
			//cargamos el objeto mediantes los metodos setters
			$emp->id = '0';
			$emp->nombre_empresa = $this->getPostParam("nombre_empresa");
			$emp->nit = $this->getPostParam("nit");
			$emp->direccion = $this->getPostParam("direccion");
			$emp->logo = $this->getPostParam("imagen");
			$emp->regimen_id = $this->getPostParam("regimen_id");
								
			if($emp->save()){
				Flash::success("Se insertó correctamente el registro");
				print("<script>document.location.replace(".core::getInstancePath()."'empresa/mostrar/$emp->id');</script>");
			}else{
				Flash::error("Error: No se pudo insertar registro");	
			}
					
	    }
		
		public function showAction($id){
          
            $empresa = $this->Empresa->find("id='$id'");
            $this->setParamToView("empresa", $empresa);
	
		}
		
		public function modificarAction($id){
			
			$empresa = $this->Empresa->find("id='$id'");
            $this->setParamToView("empresa", $empresa);
		}
		
		public function updateAction(){
			
			$this->setResponse('view');
			$id = $this->getPostParam("id");
			//primero instanciamos clase clientes
			//$emp  = new Empresa();
			//cargamos el objeto mediantes los metodos setters
			$emp = $this->Empresa->findFirst("id = '$id'");
			$emp->nombre_empresa = $this->getPostParam("nombre_empresa");
			$emp->nit = $this->getPostParam("nit");
			$emp->direccion = $this->getPostParam("direccion");
			$emp->logo = 'sin logo';
			$emp->regimen_id = $this->getPostParam("regimen_id");
			$emp->telefonos = $this->getPostParam("telefonos");
			$emp->celular = $this->getPostParam("celular");
			$emp->web = $this->getPostParam("web");
			$emp->correo = $this->getPostParam("correo");
								
			if($emp->save()){
				Flash::success("Se actualizo correctamente el registro");
				print("<script>document.location.replace('".core::getInstancePath()."empresa/show/$emp->id');</script>");
			}else{
				Flash::error("Error: No se pudo insertar registro");	
				foreach($emp->getMessages() as $mensaje):
					Flash::error("error ".$mensaje);
				endforeach;
			}
					
	    }
  
    }
?>