<?php

	class tarifa_habitacionController extends ApplicationController {
		
		
		
				
		
		public function initialize() {
		 	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}



		public function indexAction(){
		
		}
				
	
	
		/****
			agregarAction vista en la cual se mostrara el 
			formulario para agregar proveedores
		***/
		public function agregarAction(){
					
        }
		/****
			addAction metodo en la cual se insertarán
			los datos del proveedor
		***/
		public function addAction(){
			//primero instanciamos clase proveedores
			
			$this->setResponse("view");
			
			$sw = 0;
			if($this->getPostParam("hora_inicio")==$this->getPostParam("hora_fin")){
				Flash::error("Las Hora Inicio y Fin no pueden Ser iguales");
			}
			$sql = "tipo_habitacion_id = '".$this->getPostParam("tipo_habitacion_id")."' 
					and hora_inicio = '".$this->getPostParam("hora_inicio")."' 
					and hora_fin = '".$this->getPostParam("hora_fin")."' 
					and yyyy = '".$this->getPostParam("yyyy")."'";
			if($this->TarifaHabitacion->count($sql)>0){
				Flash::error("Tarifa Habitacion  YA SE EXISTE o esta Activo EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
			
				$encabezado  = new TarifaHabitacion();
				//cargamos el objeto mediantes los metodos setters
				$encabezado->id              = $this->getPostParam("id");
				$encabezado->yyyy           = $this->getPostParam("yyyy");
				$encabezado->hora_inicio           = $this->getPostParam("hora_inicio");
				$encabezado->hora_fin            = $this->getPostParam("hora_fin");
				$encabezado->tipo_habitacion_id     = $this->getPostParam("tipo_habitacion_id");
				$encabezado->valor         = $this->getPostParam("valor");
						
				if($encabezado->save()){
					
					Flash::success("TarifaHabitacion Creado satisfactoriamente");
					
				

				 }else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($encabezado->getMessages() as $message):
						Flash::error("Error: ".$message);
					endforeach;
				 }
				
			}//fin si todo bien
					
	    }
		/****
			mostrarAction vista en la cual se mostrarán
			los datos del proveedor
		***/
		public function showAction($id){
          
            $proveedores = $this->Proveedores->findFirst(" id = '$id' ");
			$municipios  = $this->Municipios->findFirst(" id = '$proveedores->municipios_id' ");
			Flash::error($proveedores->municipios_id."-".$municipios->departamentos_id);
			$departamentos = $this->Departamentos->findFirst("id='$municipios->departamentos_id'");
			
            $this->setParamToView("proveedores", $proveedores);
			$this->setParamToView("municipios", $municipios);
			$this->setParamToView("departamentos", $departamentos);
	
		}
	
		/****
			actualizarAction metodo en el cual se actualizaran
			los datos del proveedor
		***/
	
		
		
		public function find_buscarAction(){
				$this->setResponse('ajax');		
		}
		
		public function find_detalle_buscarAction(){
				$this->setResponse('ajax');
		}
		
	  
   }
?>