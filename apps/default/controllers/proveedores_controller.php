<?php

	class ProveedoresController extends ApplicationController {
		
		
		
				
		
		public function initialize() {
		  // $this->setTemplateAfter("a_bit_boxy");
		  // $this->setTemplateAfter("menu_azul");
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
			if($this->Proveedores->count("nit = '".$this->getPostParam("nit")."'")>0){
				Flash::error("DOCUMENTO DE IDENTIFICACION (NIT/CEDULA) YA SE EXISTE EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
				$pro  = new Proveedores();
				//cargamos el objeto mediantes los metodos setters
				$pro->id = '0';
				$pro->nit = $this->getPostParam("nit");
				$pro->nombre1 = $this->getPostParam("nombre1");
				$pro->nombre2 = $this->getPostParam("nombre2");
				$pro->apellido1 = $this->getPostParam("apellido1");
				$pro->apellido2 = $this->getPostParam("apellido2");
				$pro->razon_social = $this->getPostParam("razon_social");
				$pro->direccion_casa = $this->getPostParam("direccion_casa");
				$pro->direccion_oficina = $this->getPostParam("direccion_oficina");
				$pro->telefono1 = $this->getPostParam("telefono1");
				$pro->telefono2 = $this->getPostParam("telefono2");
				$pro->celular = $this->getPostParam("celular");
				//$pro->departamentos_id = $this->getPostParam("dptos");
				$pro->municipios_id = $this->getPostParam("municipios_id");
				$pro->activo = $this->getPostParam("activo");
				$pro->correo = $this->getPostParam("email");
				$pro->web = $this->getPostParam("web");
				
						
				if($pro->save()){
					
					Flash::success("Prooveedor Creado satisfactoriamente");
					
					if( isset( $_REQUEST["ventana"] ) == true ){
							if($this->getPostParam("ventana")==1){
									echo "<script>";
									echo "jQuery('#proveedores_id').val('$pro->id')";
									echo "jQuery('#nombre_proveedores').val('$pro->razon_social')";
									echo "</script>"; 
								}						
						}else{
								echo "<script>redireccionar_action('proveedores/show/$pro->id');</script>";
							}

				 }else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($pro->getMessages() as $message):
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
			//Flash::error($proveedores->municipios_id."-".$municipios->departamentos_id);
			$departamentos = $this->Departamentos->findFirst("id='$municipios->departamentos_id'");
			
            $this->setParamToView("proveedores", $proveedores);
			$this->setParamToView("municipios", $municipios);
			$this->setParamToView("departamentos", $departamentos);
	
		}
		
		
		public function actualizarAction($id){
			
			
			$this->setResponse("view");
			
			$proveedores  = new Proveedores();
			
			$pro = $proveedores->findFirst("id = '$id'");
			//cargamos el objeto mediantes los metodos setters
			$pro->nit = $this->getPostParam("nit");
			/*$pro->nombre1 = $this->getPostParam("pn");
			$pro->nombre2 = $this->getPostParam("sn");
			$pro->apellido1 = $this->getPostParam("pa");
			$pro->apellido2 = $this->getPostParam("sa");*/
			$pro->nombre1 = '';
			$pro->nombre2 = '';
			$pro->apellido1 = '';
			$pro->apellido2 = '';
			$pro->razon_social = $this->getPostParam("rs");
			$pro->direccion_casa = $this->getPostParam("dc");
			$pro->direccion_oficina = $this->getPostParam("dof");
			$pro->telefono1 = $this->getPostParam("tf1");
			$pro->telefono2 = $this->getPostParam("tf2");
			$pro->celular = $this->getPostParam("celular");
			$pro->departamentos_id = $this->getPostParam("dptos");
			$pro->municipios_id = $this->getPostParam("municipios");
			$pro->activo = $this->getPostParam("activo");
			$pro->correo = $this->getPostParam("email");
			$pro->web = $this->getPostParam("web");
			$pro->activo = $this->getPostParam("activo");
					
			if($pro->save()){
				Flash::success("Se actualizo correctamente el registro");
				print("<script>/*alert('Se actualizo correctamente el registro');*/document.location.replace('".core::getInstancePath()."proveedores/mostrar/$pro->id');</script>");
			}else{
				Flash::error("Error: No se pudo actualizar el registro");	
				foreach($pro->getMessages() as $message):
						Flash::error("Error: ".$message);
					endforeach;
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
				if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and nit like '".$_REQUEST['nit']."%'";                   } }
				if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and razon_social like '%".$_REQUEST['razon_social']."%'"; } }
				
				/*if($nom1!=''){ $sql .= " and nombre1 like '%$nom1%' "; }
				if($nom2!=''){ $sql .= " and nombre2 like '%$nom2%' "; }
				if($ape1!=''){ $sql .= " and apellido1 like '%$ape1%' "; }
				if($ape2!=''){ $sql .= " and apellido2 like '%$ape2%' "; }*/
				
				$detalles = $this->Proveedores->find(" $sql ","order: $ordenar");
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
			$prov = new Proveedores();
			if( $this->Proveedores->count(" id = '$id' ") > 0 ){
				$prov = $this->Proveedores->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#".$opcion."_id\").val(\"$prov->id\");jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"".$prov->razon_social."\");</script>";
				
			}else{
				Flash::error("No se Encontro Proveedor");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#$opcion\").val(\"\");</script>";
			}
			
			
			//public $scaffold = false;
			
			
			
		}
		
		
		public function modificarAction($id){
			//$this->setResponse("ajax");
		
			if( isset($id) ){
					
				$id_proveedores =$id;
						
				$prov = $this->Proveedores->findFirst(" id = '$id_proveedores' ");
				$dpto= $this->Departamentos->findFirst("id = '$cli->departamentos_id'");
				$mpio= $this->Municipios->findFirst("id = '$cli->municipios_id'");
							
				Tag::displayTo("id",$prov->id);
				Tag::displayTo("nit",$prov->nit);
				Tag::displayTo("nombre1",$prov->nombre1);
				Tag::displayTo("nombre2",$prov->nombre2);
				Tag::displayTo("apellido1",$prov->apellido1);
				Tag::displayTo("apellido2",$prov->apellido2);
				Tag::displayTo("razon_social",$prov->razon_social);
				Tag::displayTo("direccion_casa",$prov->direccion_casa);
				Tag::displayTo("direccion_oficina",$prov->direccion_oficina);
				Tag::displayTo("telefono1",$prov->telefono1);
				Tag::displayTo("telefono2",$prov->telefono2);
				Tag::displayTo("celular",$prov->celular);
				Tag::displayTo("departamentos",$prov->departamentos_id);
				Tag::displayTo("departamento",$dpto->departamento);
				Tag::displayTo("municipios",$prov->municipios_id);
				Tag::displayTo("municipio",$mpio->municipio);
				Tag::displayTo("email",$prov->correo);
				Tag::displayTo("web",$prov->web);
				
			}else{
					Flash::error("Parametro Incorrecto Volver a Buscar Ies para modificar.");
				}
		}
		
	
		
		
		public function deleteAction($id){
		
 			//$this->setResponse('ajax');
			//$this->setTemplateAfter("default");
			
			$p = $this->Proveedores->findFirst(" id = '$id' ");
			$sw=0;
			
			if( $this->Egresos->count(" proveedores_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Proveedor tiene movimiento en egresos");
			}
			
			if( $this->SalidasEspeciales->count(" proveedores_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Proveedor tiene movimiento en salidas especiales");
			}
			
			if( $this->EntradasEspeciales->count(" proveedores_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Proveedor tiene movimiento en entradas especiales");
			}
			
			if($sw==0){
				if(!$p->delete()){
					Flash::error("no se pudo borrar proveedor");
				}else{
					Flash::success("proveedor borrado satisfactoriamente");
				}
			}
		
		
		}
	  
   }
?>