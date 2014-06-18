<?php
	

	class TurnosController extends ApplicationController {
		
		
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
		
		public function buscarAction(){
					
        }
		
		public function detalle_buscarAction(){
			
			$this->setResponse("ajax");
			$condicion = '';
			if( $_REQUEST["inicio"] != ''){ $condicion .= ' and inicio >= '.$_REQUEST["inicio"];}
			if( $_REQUEST["fin"]    != ''){ $condicion .= ' and fin <= '.$_REQUEST["fin"];}
			if( $_REQUEST["empleado_id"]  != ''){ $condicion .= ' and empleado_id = '.$_REQUEST["empleado_id"];}
			
			$this->setParamToView( "turnos",  $this->Turnos->find(" 1 = 1 $condicion and anulado = 0 ","order: inicio,empleado_id") );	
        }
		/****
			addAction metodo en la cual se insertarán
			los datos del proveedor
		***/
		public function addAction(){
			//primero instanciamos clase proveedores
			
			$this->setResponse("view");
			
			$sw = 0;
			if($this->getPostParam("fecha_inicio")==$this->getPostParam("fecha_fin")){
				Flash::error("Las Fechas Inicio y Fin no pueden Ser iguales");
			}
			if($this->Turnos->count("empleado_id = '".$this->getPostParam("empleado_id")."' and inicio = '".$this->getPostParam("fecha_inicio")."' and fin = '".$this->getPostParam("fecha_fin")."' and anulado = '".$this->getPostParam("anulado")."' and anulado = 0")>0){
				Flash::error("Turno para este empleado YA SE EXISTE o esta Activo EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
			
				$encabezado  = new Turnos();
				//$encabezado = $this->Turnos->findFirst("id = '".$this->getPostParam("id")."' ");
				//cargamos el objeto mediantes los metodos setters
				$encabezado->id              = $this->getPostParam("id");
				$encabezado->inicio           = $this->getPostParam("fecha_inicio");
				$encabezado->fin            = $this->getPostParam("fecha_fin");
				$encabezado->empleado_id     = $this->getPostParam("empleado_id");
				$encabezado->anulado         = $this->getPostParam("anulado");
						
				if($encabezado->save()){
					
					Flash::success("Turnos Creado satisfactoriamente");
					
				

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