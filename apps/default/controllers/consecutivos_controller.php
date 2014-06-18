<?php

class ConsecutivosController extends ApplicationController {

		
		 public function beforeFilter(){
		
			 
		 }		

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
			$this->setResponse('view');
			$con  = new DetalleConsecutivos();
			$con2  = new DetalleConsecutivos();
			//cargamos el objeto mediantes los metodos setters
			$con->id = '0';
			$con->empresa_id = Session::get("id_empresa");
			$con->descripcion = $_REQUEST["descripcion"];
			$con->prefijo = $_REQUEST["prefijo"];
			$con->desde = $_REQUEST["desde"];
			$con->hasta = $_REQUEST["hasta"];
			$con->resolucion_dian = $_REQUEST["resolucion_dian"];
			$con->fecha = $_REQUEST["fecha"];
			$con->tipo_documento_id = $_REQUEST["tipo_documento_id"];
			$con->activo = $_REQUEST['activo'];
			
			if($con2->count("activo = '0' and tipo_documento_id = '$con->tipo_documento_id'")==0){
															
					if($con->save()){
						Flash::success("Se insertó correctamente el registro");
						/*echo "<script>jQuery('#consecutivos_add').reset();</script>";*/
					}else{
						Flash::error("Error: No se pudo insertar registro");	
						foreach($con->getMessages() as $mensajes){
							Flash::error("$mensajes");				
						}
					}
			}else{
				
				Flash::error("YA SE ENCUENTRA ACTIVO ESTE TIPO DE DOCUMENTO ");
				
			}
					
	    }
		
		public function mostrarAction($id){
          
            $consecutivos = $this->DetalleConsecutivos->find("id='$id'");
            $this->setParamToView("consecutivos", $consecutivos);
	
		}
		
		public function modificarAction($id){
			
			$consecutivos = $this->DetalleConsecutivos->find("id='$id'");
            $this->setParamToView("consecutivos", $consecutivos);
		}
		
		public function actualizarAction(){
			//primero instanciamos clase clientes
			$this->setResponse('view');
			$con  = new DetalleConsecutivos();
			$con2  = new DetalleConsecutivos();
			//cargamos el objeto mediantes los metodos setters
			$con->id = $_REQUEST["idt"];
			$con->empresa_id = Session::get("id_empresa");
			$con->descripcion = $_REQUEST["descripcion"];
			$con->prefijo = $_REQUEST["prefijo"];
			$con->desde = $_REQUEST["desde"];
			$con->hasta = $_REQUEST["hasta"];
			$con->resolucion_dian = $_REQUEST["resolucion_dian"];
			$con->fecha = $_REQUEST["fecha"];
			$con->tipo_documento_id = $_REQUEST["tipo_documento_id"];
			$con->activo = $_REQUEST['activo'];
			
			if($con2->count("activo = '0' and tipo_documento_id = '$con->tipo_documento_id' and id <> '$con->id' ")==0){
															
					if($con->save()){
						Flash::success("Se Actualizado correctamente el registro");
						/*echo "<script>jQuery('#consecutivos_add').reset();</script>";*/
					}else{
						Flash::error("Error: No se pudo Actualizar registro");	
						foreach($con->getMessages() as $mensajes){
							Flash::error("$mensajes");				
						}
					}
			}else{
				
				Flash::error("YA SE ENCUENTRA ACTIVO ESTE TIPO DE DOCUMENTO ");
				
			}
					
	    }

}

?>