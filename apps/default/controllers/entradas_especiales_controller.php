<?php
			
	class entradas_especialesController extends ApplicationController {
		
		
		//declaramos variables publicas para todas las vistas asociadas
		
		public $prefijo;
		public $tipo_documento;
		public $tipo_documento_nombre;
		public $id_consecutivo;
		
		
		
				
		public function initialize() {
			
			//$this->setTemplateAfter("a_bit_boxy");
			// $this->setTemplateAfter("menu_azul");
			$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
			/*$this->Proveedores->setLogger("ProveedoresDebug.txt");
			$this->EntradasEspeciales->setLogger("EntradasEspecialesDebug.txt");
			$this->Kardex->setLogger("KardexDebug.txt");
			$this->DetalleEntradasEspeciales->setLogger("DetalleEntradasEspecialesDebug.txt");
			$this->DetalleEntradasEspecialesTmp->setLogger("DetalleEntradasEspecialesTmpDebug.txt");
			$this->TipoDocumento->setLogger("TipoDocumentoDebug.txt");
			$this->DetalleConsecutivos->setLogger("DetalleConsecutivosDebug.txt");*/

			
		}	
		
		
	 public function beforeFilter(){
			
			 	
			
		 //if accion es agregar entonces ejecuta
			if( Router::getAction()=='agregar' ){	
			
				//validando si tipo documento existe para la empresa logueada
				
				
				$td = new TipoDocumento();
				$tipo_document = $td->findFirst("nombre = '".Router::getController()."' and empresa_id = '".Session::get('id_empresa')."' ");
					
							 
				if(!$tipo_document){
					Flash::error("tipo de documento no existe en la bd - tipo_documento");
					Flash::error("se recomienda crear este tipo de documento en el apartado de configuraciones");
					Flash::error("esta accion es solo la pueden hacer los administradores del sistema");
					//$this->redirect("administrador/index");
					Router::routeTo("controller: menu", "action: index");
					return false;
				}else{
				
					//cargando las variables globales para este controlador
					$this->tipo_documento = $tipo_document->id;
					$this->tipo_documento_nombre = $tipo_document->nombre;
					
				}//fin si o no existe docuemnto
				
				$det= new DetalleConsecutivos();
				
				$cons = $det->findFirst("tipo_documento_id = '$this->tipo_documento' and empresa_id = '".Session::get('id_empresa')."' and activo = '0' ");
				
				if(!$cons){
					Flash::error("tipo de documento no existe en la bd - Consecutivos del Sistema o no se la ha Asignado un Consecutivo valido");
					Flash::error("se recomienda crear este tipo de documento en el apartado de configuraciones consecutivos");
					Flash::error("esta accion es solo la pueden hacer los administradores del sistema");
					//$this->redirect("administrador/index");
					Router::routeTo("controller: menu", "action: index");
					return false;
				}else{
				
					//cargando las variables globales para este controlador
					$this->prefijo = $cons->prefijo;
					$this->id_consecutivo = $cons->id;
					
				}//fin si o no existe docuemnto
				
		
			} //fin si comprobacion action = agregar
			
			/*CODIGO DE PERIMISOS DE USUARIO*/
			 $role = Session::get('role');
			  if($role==""){  $role = 'Public';   }
				  $acl = new Acl('Model', 'className: AccessList');
				  $resourceName = $this->getControllerName();
				  $operationName = $this->getActionName();
			 
			  if($acl->isAllowed($role, $resourceName, $operationName)==false){
					 /*  if($this->getControllerName()!='administrador'){
							//$this->routeTo("controller: appmenu");
							
							 Router::routeTo("controller: menu", "action: index");
					   } else {
							throw new ApplicationControllerException("No tiene permiso para usar esta aplicación");
					   }*/
				   Router::routeTo("controller: menu", "action: index");	   
				   $authLog = new Logger("File", "auth_failed.txt");
				   $authLog->log("Autenticación fallo para el rol '$role' en el recurso '". $this->getControllerName()."/".$this->getActionName()."'");
				   Flash::addMessage("No tiene permiso para usar esta aplicacion '". $this->getControllerName()."/".$this->getActionName()."' ",Flash::ERROR);
				   Router::routeTo("controller: menu", "action: index");
				   return false;
			  }
			     /*}*/
			/*FIN*/ /*CODIGO DE PERIMISOS DE USUARIO*/
					 
		 }
		
		
			
		public function indexAction(){

		}
		
		
			
		public function trae_proveedoresAction(){
			
			//$this->setResponse('ajax');
			$this->setTemplateAfter("default");

		}
		
		
		
		public function agregarAction(){
			
		}
		
		
		public function addAction(){
		
			$this->setResponse('view');
			
			//$rem = new Remisiones();
			//$dtrem = new DetalleRemisiones();
			//$tdtrem = new DetalleEntradasEspecialesTmp();
			//$cons = new Consecutivos;
			//$dcons = new DetalleConsecutivos();
			$sw=0;
			
			
			//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
					//abriando transacciones
				Flash::success("EMPEZANDO A GUARDAR LOS REGISTOS...");	
				$transaction = new ActiveRecordTransaction(true);   
				
				//$this->Remisiones->setTransaction($transaction);  
				
				//try principal
				try{
					$transaction = TransactionManager::getUserTransaction(); 
					$this->Consecutivos->setTransaction($transaction); 
					$this->DetalleConsecutivos->setTransaction($transaction); 
					
					if( $_REQUEST["id"] == ''){
					    
						$cons = $this->DetalleConsecutivos->findFirst("activo = '0' and tipo_documento_id = '".$_REQUEST['tipo_documento_id']."'");
						
						$id = $cons->id;
						$prefijo = $cons->prefijo;
						$consecutivo = $cons->desde;
						$tipo_documento_id = $cons->tipo_documento_id;
						Flash::notice("activo = '0' and tipo_documento_id = '".$_REQUEST['tipo_documento_id']."'");
						$dtc = $this->DetalleConsecutivos->findFirst("id = '$id'");
						$dtc->setTransaction($transaction);
						$dtc->desde = $dtc->desde+1;
							if($dtc->save()==false){
								foreach($dtc->getMessages() as $message){ 
									Flash::error("TABLA DETALLE CONSECUTIVOS: ".$message); 
								}
							$transaction->rollback();
							}
							
					}else{
					//	$id = $_REQUEST["id"];
						$prefijo = $_REQUEST["prefijo"];
						$consecutivo = $_REQUEST["consecutivo"];
						$tipo_documento_id = $_REQUEST["tipo_documento_id"];
					}
					
					
					$ent = new EntradasEspeciales();
					$ent->setTransaction($transaction);
					//para traer el mismo modelo ya instanciado
					$ent->id                           = $_REQUEST["id"];
					$ent->proveedores_id               = $_REQUEST['proveedores_id'];
					$ent->bodegas_id                   = $_REQUEST['bodegas_id'];
					$ent->prefijo                      = $prefijo;
					$ent->consecutivo                  = $consecutivo;
					$ent->empresa_id                   = $_REQUEST['empresa_id'];
					$ent->tipo_documento_id            = $tipo_documento_id ;
					$ent->fecha                        = $_REQUEST['fecha'];
					$ent->fecha_act                    = date("Y-m-d H:i:s");
					$ent->hora                         = $_REQUEST['hora'];
					$ent->hora_act                     = date("H:i:s");
					$ent->anulado                      ='0';
					
					if($ent->save()==false){
						foreach($ent->getMessages() as $message){ 
							Flash::error("Error Guardando Entradas Especial ".$message); 
						}
						$transaction->rollback();
					}
					
					$detalles_item = str_replace("]\"","]",str_replace("\"[","[",str_replace("\\","",$_POST["detalles"])));
					if(json_decode($detalles_item)){
						Flash::success("Json Correcto");
						$detalles_item = json_decode($detalles_item);
					}else{
						Flash::error("Error json");
						$transaction->rollback();
					}	
					
					
					foreach( $detalles_item as $items):
						$detalles = new DetalleEntradasEspeciales();
						$detalles->setTransaction($transaction);
						if( trim(substr($items->id,0,4)) == trim('temp') ){
							$detalles->id                     = '';
							//Flash::error(substr($items->id,0,4));
						}else{
							$detalles->id                     = $items->id;
							//Flash::notice(substr($items->id,0,4));
							}
						$detalles->kardex_id              = $items->kardex_id;
						$detalles->entradas_especiales_id = $ent->id;
						$detalles->costo                  = $items->costo;
						$detalles->cantidad               = $items->cantidad;
						$detalles->total                  = $items->costo*$items->cantidad;
						if($items->anulado == "SI") {$detalles->anulado = '1';}
						if($items->anulado == "NO") {$detalles->anulado = '0';}
						$total +=  $detalles->total;
							if($detalles->save()==false){
								foreach($detalles->getMessages() as $message){ 
									Flash::error(" TABLA DETALLE ENTRADAS ESPECIALES : ".$message); 
								}
								$transaction->rollback();
							}	
					endforeach;			
		
				$transaction->commit();
				Flash::success("ENTRADA ESPECIAL GUARDADA SATISFACTORIAMENTE");	
				echo "<script>alert('ENTRADA ESPECIAL GUARDADA SATISFACTORIAMENTE');";
				echo "redireccionar_action('entradas_especiales/agregar/?id=$ent->id&=msgconfirm=true');</script>";
					
				}catch(TransactionFailed $e){		
					Flash::error($e->getMessage());
				//cierre cacth try
				}
		  }//cierra if todo bien

		}
		

		
		public function showAction($id){
		
			 $this->setParamToView("encabezado",$this->EntradasEspeciales->find(" id = '$id' "));
			 $this->setParamToView("idt",$id);								
			 $this->setParamToView("detalles",$this->DetalleEntradasEspeciales->find(" entradas_especiales_id = '$id' and anulado = 0"));				
 			
		}
		
		 
		 
	 
		public function agregar_itemAction(){
		
		
		
		$this->setResponse("ajax");
		//$request = $this->getRequestInstance();
		//if($request->isAjax()==true){
			$kardex = new Kardex();
			$db = DbBase::rawConnect();
			$emp = new Empresa();
			$tipo_costeo = $emp->FindFirst("id = '".Session::get("id_empresa")."'")->tipo_costeo;
			
			$costo = $db->fetchOne("SELECT calcular_costo('".$_REQUEST["tmp_kardex_id"]."','".$_REQUEST["fecha"]."','".$_REQUEST["bodegas_id"]."','$tipo_costeo') as costo ");
			$id = '';
			if(isset($_REQUEST["tmp_id"])){ $id = $_REQUEST["tmp_id"]; }else{ $id = 'temp'.rand(); }
			
			$responce["id"]          = $id;
			$responce["kardex_id"]   = $_REQUEST["tmp_kardex_id"];
			$responce["referencia"]  = $_REQUEST["tmp_codigo"];	
			$responce["nombre"]      = $_REQUEST["tmp_referencia"];	
			$responce["costo"]       = $costo["costo"];
			$responce["cantidad"]    = $_REQUEST["tmp_cantidad"];
			$responce["total"]  = $costo["costo"]*$_REQUEST["tmp_cantidad"];
			$responce["anulado"]  = "NO";	
			$responce["query"]  = "SELECT calcular_costo('".$_REQUEST["tmp_kardex_id"]."','".$_REQUEST["bodegas_id"]."','".$_REQUEST["fecha"]."','$tipo_costeo') as costo ";
			
			echo json_encode($responce);
			
			
	}
	
		
	
		
	
		public function buscarAction(){
			
		}
		
		
		public function detalle_buscarAction(){
				
				$this->setResponse('ajax');
				
				if( isset( $_REQUEST['proveedores_id']   )==true ) { if( $_REQUEST['proveedores_id'] != '' ) { $sql .= " and {#Proveedores}.id = '".$_REQUEST["proveedores_id"]."'"; } }
				if( isset( $_REQUEST['finicial']       )==true ) { if( $_REQUEST['fecha_inicio'] != ''       ) { $sql .= " and {#EntradasEspeciales}.fecha >=  '".$_REQUEST['fecha_inicio']."'";           } }
				if( isset( $_REQUEST['ffinal']         )==true ) { if( $_REQUEST['fecha_fin'] != ''         ) { $sql .= " and {#EntradasEspeciales}.fecha <=  '".$_REQUEST['fecha_fin']."'";           } }
			
				//$empresa_id  = $_REQUEST["empresa_id"];
				$empresa_id  = Session::get("id_empresa");
			
				$query = new ActiveRecordJoin(array(
						"entities" => array("EntradasEspeciales","Proveedores","Empresa","TipoDocumento"),
					 	"fields"=>array(
										"{#EntradasEspeciales}.id",
										"{#EntradasEspeciales}.prefijo",
										"{#EntradasEspeciales}.consecutivo",
										"{#EntradasEspeciales}.fecha",
										"{#Proveedores}.razon_social"
										),
						"conditions"=>(" {#EntradasEspeciales}.empresa_id = '$empresa_id' $sql ")
				));
				
				
				
				
				//$clientes = $this->Clientes->findAllBySql($sql);
				//Flash::error($query->getSqlQuery());
				$this->setParamToView('detalles',$query->getResultSet());
				//$this->setParamToView('query',$query);
				//$this->setParamToView('query2',"hola");
				
		}
	
		
		
		
		
		
		

	}
	
?>
