<?php

	class tipo_documentoController extends ApplicationController {
	
	
		public function initialize() {
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
			
			$this->setResponse('ajax');
		
				
			$tp  = new TipoDocumento();
			
			
			$tp->id = '0';
			$tp->nombre = $_REQUEST['nombre'];
			$tp->empresa_id = Session::get("id_empresa");
			$tp->logo = $_REQUEST['logos'];
			$tp->ancho = $_REQUEST['ancho'];
			$tp->alto = $_REQUEST['alto'];
			$tp->mostrar_logo = $_REQUEST['mostrar_logo'];
			$tp->size = $_REQUEST['size'];
			$tp->font = $_REQUEST['font'];
			$tp->tipo_letra = $_REQUEST['tipo_letra'];
			$tp->papel = $_REQUEST['papel'];
		
					
			if($tp->save()){
				
				Flash::success("Tipo Documento Guardado Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Guardar el registro...");	
				
				foreach($tp->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
					
	    }
		
		
		
		public function updateAction(){
			
			
			$this->setResponse('view');
			
			$tp  = new TipoDocumento();
			
			
			$tp->id = $_REQUEST['idt'];
			$tp->nombre = $_REQUEST['nombre'];
			$tp->empresa_id = Session::get("id_empresa");
			$tp->logo = $_REQUEST['logo'];
			$tp->ancho = $_REQUEST['ancho'];
			$tp->alto = $_REQUEST['alto'];
			$tp->mostrar_logo = $_REQUEST['mostrar_logo'];
			$tp->size = $_REQUEST['size'];
			$tp->font = $_REQUEST['font'];
			$tp->tipo_letra = $_REQUEST['tipo_letra'];
			$tp->papel = $_REQUEST['papel'];
					
			if($tp->save()){
				
				Flash::success("Tipo Documento Actualizado y Guardado Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Actualizar y Guardar el registro...");	
				
				foreach($tp->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
					
	    }
		
		
		
		public function deleteAction(){
			
			
			
			$this->setResponse('view');
			
			$tp  = new TipoDocumento();
			$sw=0;

			if($this->Remisiones->count("tipo_documento_id = '".$_REQUEST["idt"]."'")>0){
				$sw=1;
				Flash::error("Existe este tipo de documento en la tabla de Remisiones");
			}
			if($this->Devoluciones->count("tipo_documento_id = '".$_REQUEST["idt"]."'")>0){
				$sw=1;
				Flash::error("Existe este tipo de documento en la tabla de Devoluciones");
			}
			if($this->Factura->count("tipo_documento_id = '".$_REQUEST["idt"]."'")>0){
				$sw=1;
				Flash::error("Existe este tipo de documento en la tabla de Factura o Cuenta de Cobro");
			}
			if($this->RecibosCaja->count("tipo_documento_id = '".$_REQUEST["idt"]."'")>0){
				$sw=1;
				Flash::error("Existe este tipo de documento en la tabla de Recibos de Caja");
			}
			if($this->Egresos->count("tipo_documento_id = '".$_REQUEST["idt"]."'")>0){
				$sw=1;
				Flash::error("Existe este tipo de documento en la tabla de Egresos");
			}
			
			if($sw==0){
				if($tp->delete(" id = '".$_REQUEST["idt"]."' ")){
					
					Flash::success("Tipo Documento Eliminado Satisfactoriamente");
	
				}else{
				
					Flash::error("Error: No se pudo Eliminar y Guardar el registro...");	
					
					foreach($tp->getMessages() as $message){
							  Flash::error($message->getMessage());
					}	  
					
				}
			}//fin si todo bien
			
			
			
					
	    }
		
		
	
	  
   }
?>