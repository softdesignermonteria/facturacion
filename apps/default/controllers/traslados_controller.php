<?php
			
	class trasladosController extends ApplicationController {
		
		
		//declaramos variables publicas para todas las vistas asociadas
		
		public $prefijo;
		public $tipo_documento;
		public $tipo_documento_nombre;
		public $id_consecutivo;
		
		
		
				
		public function initialize() {
			
			//$this->setTemplateAfter("a_bit_boxy");
			 //$this->setTemplateAfter("menu_azul");
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
			$sw=0;
			if($_REQUEST["desde_bodegas_id"]==$_REQUEST["hasta_bodegas_id"]){ $sw=1; Flash::error("Error las Bodegas No pueden Ser Iguales..."); }
			//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
					//abriando transacciones
				Flash::success("EMPEZANDO A GUARDAR LOS REGISTOS...");	
				$transaction = new ActiveRecordTransaction(true);   
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
					
					
					$encabezado = new Traslados();
					$encabezado->setTransaction($transaction);
					//para traer el mismo modelo ya instanciado
					$encabezado->id                           = $_REQUEST["id"];
					$encabezado->desde_bodegas_id             = $_REQUEST['desde_bodegas_id'];
					$encabezado->hasta_bodegas_id             = $_REQUEST['hasta_bodegas_id'];
					$encabezado->prefijo                      = $prefijo;
					$encabezado->consecutivo                  = $consecutivo;
					$encabezado->empresa_id                   = $_REQUEST['empresa_id'];
					$encabezado->tipo_documento_id            = $tipo_documento_id ;
					$encabezado->fecha                        = $_REQUEST['fecha'];
					$encabezado->fecha_act                    = date("Y-m-d H:i:s");
					$encabezado->hora                         = $_REQUEST['hora'];
					$encabezado->hora_act                     = date("H:i:s");
					$encabezado->anulado                      ='0';
					
					if($encabezado->save()==false){
						foreach($encabezado->getMessages() as $message){ 
							Flash::error("Error Guardando Traslado ".$message); 
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
						$detalles = new DetalleTraslados();
						$detalles->setTransaction($transaction);
						if( trim(substr($items->id,0,4)) == trim('temp') ){
							$detalles->id                     = '';
							//Flash::error(substr($items->id,0,4));
						}else{
							$detalles->id                     = $items->id;
							//Flash::notice(substr($items->id,0,4));
							}
						$detalles->kardex_id              = $items->kardex_id;
						$detalles->traslados_id           = $encabezado->id;
						$detalles->costo                  = $items->costo;
						$detalles->cantidad               = $items->cantidad;
						$detalles->total                  = $items->costo*$items->cantidad;
						if($items->anulado == "SI") {$detalles->anulado = '1';}
						if($items->anulado == "NO") {$detalles->anulado = '0';}
						$total +=  $detalles->total;
							if($detalles->save()==false){
								foreach($detalles->getMessages() as $message){ 
									Flash::error(" TABLA DETALLE TRASLADOS : ".$message); 
								}
								$transaction->rollback();
							}	
					endforeach;			
		
				$transaction->commit();
				Flash::success("TRASLADO GUARDADA SATISFACTORIAMENTE");	
				Flash::addMessage("TRASLADO GUARDADA SATISFACTORIAMENTE",FLASH::SUCCESS);
				echo "<script>alert('ENTRADA ESPECIAL GUARDADA SATISFACTORIAMENTE');";
				/*echo "redireccionar_action('menu');</script>";*/
					
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
				
				//if( isset( $_REQUEST['razon_social']   )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and {#Proveedores}.razon_social like '%".str_replace(' ',"%",$_REQUEST['razon_social'])."%'"; } }
			
				if( isset( $_REQUEST['fecha_inicio']      )==true ) { if( $_REQUEST['fecha_inicio'] != ''      ) { $sql .= " and {#Traslados}.fecha >=  '".$_REQUEST['fecha_inicio']."'";           } }
				if( isset( $_REQUEST['fecha_fin']         )==true ) { if( $_REQUEST['fecha_fin'] != ''         ) { $sql .= " and {#Traslados}.fecha <=  '".$_REQUEST['fecha_fin']."'";           } }
				
				if( isset( $_REQUEST['desde_bodegas_id']  )==true ) { if( $_REQUEST['desde_bodegas_id'] != ''  ) { $sql .= " and {#Traslados}.desde_bodegas_id =  '".$_REQUEST['desde_bodegas_id']."'";           } }
				if( isset( $_REQUEST['hasta_bodegas_id']  )==true ) { if( $_REQUEST['hasta_bodegas_id'] != ''  ) { $sql .= " and {#Traslados}.hasta_bodegas_id =  '".$_REQUEST['hasta_bodegas_id']."'";           } }
				
				
				$empresa_id  = $_REQUEST["empresa_id"];
				$empresa_id  = Session::get("id_empresa");
			
				$query = new ActiveRecordJoin(array(
						"entities" => array("Traslados","Empresa","TipoDocumento"),
					 	"fields"=>array(
										"{#Traslados}.id",
										"{#Traslados}.prefijo",
										"{#Traslados}.consecutivo",
										"{#Traslados}.fecha",
										"{#Traslados}.desde_bodegas_id",
										"{#Traslados}.hasta_bodegas_id",
										),
						"conditions"=>(" {#Traslados}.empresa_id = '$empresa_id' $sql ")
				));
				
				
				
				
				//$clientes = $this->Clientes->findAllBySql($sql);
				//Flash::error($query->getSqlQuery());
				$this->setParamToView('detalles',$query->getResultSet());
				//$this->setParamToView('query',$query);
				//$this->setParamToView('query2',"hola");
				
		}
	
		
		
		
		public function traslado_automaticoAction(){
			
			$this->setResponse('ajax');
			
			$idt = $_REQUEST["id"];
			$pd = new PedidoClientes();
			$pd = $this->PedidoClientes->findFirst("id = '$idt'");
			
			$consecutivos = new TipoDocumento();
			  $db = DbBase::rawConnect();
			$detalle_consecutivos = new DetalleConsecutivos();
			
			try{
					$transaction = TransactionManager::getUserTransaction(); 
					$consecutivos->setTransaction($transaction); 
					$detalle_consecutivos->setTransaction($transaction); 
					$consecutivos->findFirst(" nombre = 'TRASLADOS'  ");
					$pd->setTransaction($transaction);
					$cons = $detalle_consecutivos->findFirst("activo = '0' and tipo_documento_id = '$consecutivos->id'");
						
					$id = $cons->id;
					$prefijo = $cons->prefijo;
					$consecutivo = $cons->desde;
					$tipo_documento_id = $cons->tipo_documento_id;

					$dtc = $detalle_consecutivos->findFirst("id = '$id'");
					$dtc->setTransaction($transaction);
					$dtc->desde = $dtc->desde+1;
					
					if($dtc->save()==false){
						foreach($dtc->getMessages() as $message){ 
							Flash::error("TABLA DETALLE CONSECUTIVOS: ".$message); 
						}
					$transaction->rollback();
					}
			
					$ent = new traslados();
					$ent->setTransaction($transaction);
					//para traer el mismo modelo ya instanciado
					$ent->id                           = '';
					
					$ent->desde_bodegas_id             = 1;
					$ent->hasta_bodegas_id             = $pd->bodegas_id;
					$ent->prefijo                      = $prefijo;
					$ent->consecutivo                  = $consecutivo;
					$ent->empresa_id                   = $pd->empresa_id;
					$ent->tipo_documento_id            = $tipo_documento_id ;
					$ent->fecha                        = date("Y-m-d");
					$ent->fecha_act                    = date("Y-m-d H:i:s");
					$ent->hora                         = date("H:i:s");
					$ent->hora_act                     = date("H:i:s");
					$ent->anulado                      ='0';
					$ent->observaciones                  ='Realizado Automaticamente';
					
					if($ent->save()==false){
						foreach($ent->getMessages() as $message){ 
							Flash::error("Error Guardando Traslados ".$message); 
						}
						$transaction->rollback();
					}	
					
					foreach($this->DetallePedidoClientes->find(" pedido_clientes_id = '$pd->id' and kardex_id <> '".$this->Empresa->findFirst("id = '$ent->empresa_id'")->kardex_id_default."' and anulado=0") as $detalles):
						
						$costo = $db->fetchOne("SELECT ifnull(calcular_costo('$detalles->kardex_id','".date("Y-m-d")."','$ent->desde_bodegas_id','".$this->Empresa->findFirst("id = '$ent->empresa_id'")->tipo_costeo."'),0) as costo ");
						$costo2 =$costo["costo"]; 
						
						$detalles_ent = new DetalleTraslados();
						$detalles_ent->setTransaction($transaction);
						$detalles_ent->id                     = '';
						$detalles_ent->kardex_id              = $detalles->kardex_id;
						$detalles_ent->traslados_id           = $ent->id;
						$detalles_ent->costo                  = $costo2;
						$detalles_ent->cantidad               = $detalles->cantidad;
						$detalles_ent->total                  = $costo2*$detalles_ent->cantidad;
						$detalles_ent->anulado                = '0';
						$total +=  $detalles_ent->total;
						
							if($detalles_ent->save()==false){
								foreach($detalles_ent->getMessages() as $message){ 
									Flash::error(" ERROR DETALLE TRASLADOS : ".$message); 
								}
								$transaction->rollback();
							}	
					endforeach;
					
					$pd->trasladado = 1;
					$pd->entradas_especiales_id = $ent->id;
					if($pd->save()==false){
								foreach($pd->getMessages() as $message){ 
									Flash::error("pedido no actualizado : ".$message); 
								}
								$transaction->rollback();
							}
					
					
					$transaction->commit();
					Flash::success(" REALIZADO ".$ent->prefijo.$ent->consecutivo);
				
			}catch(TransactionFailed $e){		
				Flash::error($e->getMessage());
			//cierre cacth try
			}	
			
		}
		
		
		

	}
	
?>
