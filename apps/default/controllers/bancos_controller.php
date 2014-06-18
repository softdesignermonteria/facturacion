<?php

	class bancosController extends ApplicationController {
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		   // $this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}



		public function indexAction(){
		
		}
				
		
		
		/****
			agregarAction vista en la cual se mostrara el 
			formulario para agregar clientes
		***/
		public function agregarAction(){
					
        }
		
		
		
		
		public function addAction(){
			
			$this->setResponse('view');
				
			$bancos  = new Bancos();
			
			
			$bancos->id = '0';
			$bancos->descripcion = $_REQUEST['banco'];
								
			if($bancos->save()){
				
				Flash::success("Entidad Bancaria Guardada Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Guardar el registro...");	
				
				foreach($bancos->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
					
	    }
		
		
		
		public function updateAction(){
			
			
			$this->setResponse('view');
			
			$bancos  = new Bancos();
			
			
			$bancos->id = $_REQUEST['id'];
			$bancos->descripcion = $_REQUEST['banco'];
			
						
			//Flash::success(print_r($bancos));		
					
			if($bancos->save()){
				
				Flash::success("Entidad bancaria Actualizada y Guardada Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Actualizar y Guardar el registro...");	
				
				foreach($bancos->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
					
	    }
		
		
		
		public function deleteAction(){
			
			
			
			$this->setResponse('view');
			
			$bancos  = new Bancos();
			
			if($this->Egresos->count("bancos_id = '".$_REQUEST["id"]."' ") == 0 ){
				
				if($bancos->delete(" id = '".$_REQUEST["id"]."' ")){
					
					Flash::success("Entidad Bancaria Eliminada Satisfactoriamente");
	
				}else{
				
					Flash::error("Error: No se pudo Eliminar .");	
					
					foreach($bancos->getMessages() as $message){
							  Flash::error("Error Eliminando el banco ".$message->getMessage());
					}	  
					
				}
				
			}else{
				Flash::error("Error: No se pudo Eliminar este bando ya tiene movimiento...");	
			
			}
			
			
			
					
	    }
		
		
		public function find_buscarAction(){
			$this->setResponse("ajax");
		}
		
		public function find_detalle_buscarAction(){
			$this->setResponse("ajax");
		}
		
	
	  
   }
?>