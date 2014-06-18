<?php

	class EmpleadosController extends ApplicationController {
		
		
		
				
		
		public function initialize() {
		  // $this->setTemplateAfter("a_bit_boxy");
		   //$this->setTemplateAfter("menu_azul");
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
			if($this->Empleado->count("cedula = '".$this->getPostParam("cedula")."'")>0){
				Flash::error("DOCUMENTO DE IDENTIFICACION (NIT/CEDULA) YA SE EXISTE EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
				$empleado  = new Empleado();
				//cargamos el objeto mediantes los metodos setters
				$empleado->id                = $this->getPostParam("id");
				$empleado->empresa_id        = $this->getPostParam("empresa_id");
				$empleado->cedula            = $this->getPostParam("cedula");
				$empleado->nombre_completo   = $this->getPostParam("nombre_completo");
				$empleado->direccion         = $this->getPostParam("direccion");
				$empleado->email             = $this->getPostParam("email");
				$empleado->telefono          = $this->getPostParam("telefono");
				$empleado->celular           = $this->getPostParam("celular");
				$empleado->web               = $this->getPostParam("web");
				$empleado->activo            = $this->getPostParam("activo");
				$empleado->email             = $this->getPostParam("email");
				$empleado->web               = $this->getPostParam("web");
				$empleado->fecha_act         = date("Y-m-d H:i:s");
				$empleado->fecha_ingreso     = $this->getPostParam("fecha_ingreso");
				$empleado->fecha_vencimiento = $this->getPostParam("fecha_vencimiento");
				$empleado->salario           = $this->getPostParam("salario");
				
						
				if($empleado->save()){
					
					Flash::success("Empleado Creado satisfactoriamente");
					echo "<script>redireccionar_action('empleados/show/$empleado->id');</script>";	

				 }else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($empleado->getMessages() as $message):
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
          
            $empleado = $this->Empleado->findFirst(" id = '$id' ");
			$municipios  = $this->Municipios->findFirst(" id = '$proveedores->municipios_id' ");
			//Flash::error($proveedores->municipios_id."-".$municipios->departamentos_id);
			$departamentos = $this->Departamentos->findFirst("id='$municipios->departamentos_id'");
			
            $this->setParamToView("empleado", $empleado);
			$this->setParamToView("municipios", $municipios);
			$this->setParamToView("departamentos", $departamentos);
	
		}
	
		/****
			actualizarAction metodo en el cual se actualizaran
			los datos del proveedor
		***/
		public function modificarAction($id){
			//$this->setResponse("ajax");
			if( isset($id) ){
					
				$id_empleado = $id;
						
				$empl = $this->Empleado->findFirst(" id = '$id_empleado' ");
		/*		$dpto= $this->Departamentos->findFirst("id = '$cli->departamentos_id'");
				$mpio= $this->Municipios->findFirst("id = '$cli->municipios_id'");*/
				Tag::displayTo("id",$empl->id);
				Tag::displayTo("cedula",$empl->cedula);
				Tag::displayTo("nombre_completo",$empl->nombre_completo);
				Tag::displayTo("direccion",$empl->direccion);
				Tag::displayTo("email",$empl->email);
				Tag::displayTo("telefono",$empl->telefono);
				Tag::displayTo("celular",$empl->celular);
				Tag::displayTo("web",$empl->web);
				Tag::displayTo("activo",$empl->activo);
				Tag::displayTo("fecha_ingreso",$empl->fecha_ingreso);
				Tag::displayTo("fecha_vencimiento",$empl->fecha_vencimiento);
				Tag::displayTo("salario",$empl->salario);
							
			}else{
					Flash::error("Parametro Incorrecto Volver a Buscar Ies para modificar.");
				}
		}
		
		public function updateAction(){
			
			$this->setResponse("ajax");
			$cli= new Empleado();
			$sw=0;
			//$usuario = $Usuario->findFirst("username = '".$_REQUEST['username']."'");
			
			if($sw==1){
				Flash::error("Error: Clientes ya existe!!!");
			
			}else{
				
				if($sw==0){
					
						$empl = $this->Empleado->findFirst(" id = '".$_REQUEST["id"]."' ");
						//$Usuario->setId($this->getPostParam("id"));
						$empl->cedula = $this->getPostParam("cedula");
						$empl->nombre_completo = $this->getPostParam("nombre_completo");
						$empl->direccion = $this->getPostParam("direccion");
						$empl->email = $this->getPostParam("email");
						$empl->telefono = $this->getPostParam("telefono");
						$empl->celular = $this->getPostParam("celular");
						$empl->web = $this->getPostParam("web");
						$empl->activo = $this->getPostParam("activo");
						$empl->fecha_ingreso = $this->getPostParam("fecha_ingreso");
						$empl->fecha_vencimiento = $this->getPostParam("fecha_vencimiento");
						$empl->salario = $this->getPostParam("salario");
									
						if($empl->save()){
							  Flash::success("Se Actualizo correctamente el registro");
							  /*echo "<script>redireccionar_action('menu');</script>";	*/
							  echo "<script>quitar_mensajes();</script>";
							  
						}else{
							 Flash::error("Error: No se pudo Actualizar el registro");	
							 /*echo "<script>redireccionar_action('menu');</script>";	*/
									
						}
				  }
			  }
			
		}
		
		
		public function buscarAction(){
		
		}
		
		public function detalle_buscarAction(){
				
				$this->setResponse('view');
				
				//$nom1 = $_REQUEST['nom1'];
				//$nom2 = $_REQUEST['nom2'];
				//$ape1 = $_REQUEST['ape1'];
				//$ape2 = $_REQUEST['ape2'];
				$nit = $_REQUEST['nit'];
				$ordenar = $_REQUEST['ordenar'];
				
				$sql = ' 1=1 ';
				
				if( isset( $_REQUEST['codigo']       )==true ) { if( $_REQUEST['codigo'] != ''       ) { $sql .= " and id like '".$_REQUEST['codigo']."%'";                 } }
				if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and cedula like '".$_REQUEST['nit']."%'";                   } }
				if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and razon_social like '%".$_REQUEST['razon_social']."%'"; } }
				
				/*if($nom1!=''){ $sql .= " and nombre1 like '%$nom1%' "; }
				if($nom2!=''){ $sql .= " and nombre2 like '%$nom2%' "; }
				if($ape1!=''){ $sql .= " and apellido1 like '%$ape1%' "; }
				if($ape2!=''){ $sql .= " and apellido2 like '%$ape2%' "; }*/
				
				$detalles = $this->Empleado->find(" $sql ","order: $ordenar");
          		$this->setParamToView("detalles", $detalles);
		
		}
	
		
		
		public function find_buscarAction(){
				$this->setResponse('ajax');		
		}
		
		public function find_detalle_buscarAction(){
				$this->setResponse('ajax');
		}
		
		public function validarAction($id,$opcion){
			
			$this->setResponse("view");
			
			$sw=0;
			$emp = new Empleado();
			if( $this->Empleado->count(" id = '$id' ") > 0 ){
				$emp = $this->Empleado->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#".$opcion."_id\").val(\"$emp->id\");jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"".$emp->nombre_completo."\");</script>";
				
			}else{
				Flash::error("No se Encontro Solicitud");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#$opcion\").val(\"\");</script>";
			}
			
			
			//public $scaffold = false;
			
			
			
		}
		
	  
   }
?>