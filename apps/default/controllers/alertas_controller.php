<?php

class AlertasController extends ApplicationController {



	public function initialize() {
		//$this->setTemplateAfter("a_bit_boxy");
		 //$this->setTemplateAfter("menu_azul","ayuda");
		 	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		 // $this->setTemplateAfter("menu_azul");
		 //Tag::addJavascript("dtree");
		 //Tag::stylesheetLink("dtree");
	}

	public function indexAction(){
		
		
	}
	
	
	
	public function agregarAction(){
		
		
	}
	
	
	public function addAction(){
		
		$this->setResponse("view");
			
			$sw = 0;
			
			if( $this->getPostParam("fecha")       == ''){ $sw=1; Flash::error("Fecha es Requerido"); }
			if( $this->getPostParam("placa")       == ''){ $sw=1; Flash::error("Placa es Requerido"); }
			if( $this->getPostParam("observacion") == ''){ $sw=1; Flash::error("observacion es Requerido"); }
			if( $this->getPostParam("admin_id")    == ''){ $sw=1; Flash::error("Usuario es Requerido"); }
			
			if($sw==0){
			
				$encabezado  = new Alertas();
				//$encabezado = $this->Turnos->findFirst("id = '".$this->getPostParam("id")."' ");
				//cargamos el objeto mediantes los metodos setters
				$encabezado->id             = $this->getPostParam("id");
				$encabezado->fecha          = $this->getPostParam("fecha");
				$encabezado->fecha_act      = date("Y-m-d H:i:s");
				$encabezado->placa          = $this->getPostParam("placa");
				$encabezado->observacion    = $this->getPostParam("observacion");
				$encabezado->anulado        = $this->getPostParam("anulado");
				$encabezado->admin_id       = $this->getPostParam("admin_id");
						
				if($encabezado->save()){

					Flash::success("Alerta Creada / Actualizada satisfactoriamente");

				 }else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($encabezado->getMessages() as $message):
						Flash::error("Error: ".$message);
					endforeach;
				 }
				
			}//fin si todo bien
		
	}
	
	
	public function buscarAction(){
		
		
	}
	
	public function alertasAction(){
		$this->setResponse("view");
	}
	
	
	public function detalle_buscarAction(){
		$this->setResponse("view");
		
			$condicion = '';
			if( $_REQUEST["inicio"] != ''){ $condicion .= ' and fecha >= '.$_REQUEST["inicio"];}
			if( $_REQUEST["fin"]    != ''){ $condicion .= ' and fecha <= '.$_REQUEST["fin"];}
			if( $_REQUEST["placa"]    != ''){ $condicion .= ' and placa = '.$_REQUEST["placa"];}
			//if( $_REQUEST["empleado_id"]  != ''){ $condicion .= ' and empleado_id = '.$_REQUEST["empleado_id"];}
			
			$this->setParamToView( "detalles",  $this->Alertas->find(" 1 = 1 $condicion and anulado = 0 ","order: fecha,admin_id") );	
		
	}
	
	
	

}

?>