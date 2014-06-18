<?php
			
	class cuadre_cajaController extends ApplicationController {
		
		
		//declaramos variables publicas para todas las vistas asociadas
		
		public $prefijo;
		public $tipo_documento;
		public $tipo_documento_nombre;
		public $id_consecutivo;
				
		public function initialize() {
			 //$this->setTemplateAfter("menu_azul");
		 	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
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
		
		
		public function agregarAction(){
			
		}
	
		
		public function agregar_itemAction(){
			
			
			$this->setResponse("ajax");
			
	
				$responce["id"]          = "temp".rand();
				$responce["cxc_id"]      = $_REQUEST["cxc_id"];
				$responce["prefijo"]     = $_REQUEST["prefijo"];	
				$responce["consecutivo"] = $_REQUEST["consecutivo"];
				$responce["hora_salida"] = $_REQUEST["hora_salida"];	
				$responce["valor"]       = $_REQUEST["valor"];	
				$responce["anulado"]     = "NO";
				echo json_encode($responce);
		}
		
		
		public function addAction(){
		
			$this->setResponse('view');
			$sw=0;
			
			$db = DbBase::rawConnect();
			
			//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
					//abriando transacciones
				Flash::success("EMPEZANDO A GUARDAR LOS REGISTOS...");	
				$transaction = new ActiveRecordTransaction(true);   

				try{
					$transaction = TransactionManager::getUserTransaction(); 
					$this->Consecutivos->setTransaction($transaction); 
					$this->DetalleConsecutivos->setTransaction($transaction); 
					$prefijo = '';  	$consecutivo = ''; 	$tipo_documento_id = '';

					if( $_REQUEST["id"] == ''){
					    
						$cons = $this->DetalleConsecutivos->findFirst("activo = '0' and tipo_documento_id = '".$_REQUEST['tipo_documento_id']."'");
						
						$id = $cons->id;
						$prefijo = $cons->prefijo;
						$consecutivo = $cons->desde;
						$tipo_documento_id = $cons->tipo_documento_id;
					
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
							$prefijo = $_REQUEST["prefijo"];
							$consecutivo = $_REQUEST["consecutivo"];
							$tipo_documento_id = $_REQUEST["tipo_documento_id"];
						}

						//Flash::success("VERIFICANDO LOS CONSECUTIVOS PARA ESTE DOCUMENTO");		
						
							$encabezado = new CuadreCaja();
							$encabezado->setTransaction($transaction);
							//para traer el mismo modelo ya instanciado
							 $encabezado->id                    = $_REQUEST["id"];
							 $encabezado->prefijo               = $prefijo;
							 $encabezado->consecutivo           = $consecutivo;
							 $encabezado->empleado_id           = $_REQUEST["empleado_id"];
							 $encabezado->empresa_id            = $_REQUEST["empresa_id"];
							 $encabezado->tipo_documento_id     = $tipo_documento_id; 
							 $encabezado->fecha                 = $_REQUEST["fecha"];
							 $encabezado->fecha_act             = date("Y-m-d H:i:s");
							 $encabezado->turnos_id             = $_REQUEST["turnos_id"];
							// $encabezado->fecha_cierre          = $_REQUEST["fecha_cierre"];
							 $encabezado->valor_caja_defecto    = $_REQUEST["valor_caja_defecto"];
							 $encabezado->deposito_caja         = $_REQUEST["deposito_caja"];
							 $encabezado->visto_bueno           = $_REQUEST["visto_bueno"];
							 $encabezado->total_ingresos        = $_REQUEST["total_ingresos"];
							 $encabezado->total_egresos         = $_REQUEST["total_egresos"];

							 $encabezado->anulado               = $_REQUEST["anulado"];
							 $encabezado->visto_bueno           = $_REQUEST["visto_bueno"];
						
							if($encabezado->save()==false){
								foreach($encabezado->getMessages() as $message){ 
									Flash::error("TABLA CUADRE CAJA: ".$message); 
								}
								$transaction->rollback();
							}
							
							$total = 0;
							$detalles_item = str_replace("]\"","]",str_replace("\"[","[",str_replace("\\","",$_POST["detalles"])));
							//Flash::notice($detalles_item);
							
							if($detalles_item!='[]'){	
								
								if(json_decode($detalles_item)){
									Flash::success("Json Correcto");
									$detalles_item = json_decode($detalles_item);
								}else{
									Flash::error("Error json");
									$transaction->rollback();
								}
	
								//$dttmp = new CxcCuadreCaja();
								//$dttmp->setTransaction($transaction);
								//$dttmp->delete("pedido_clientes_id = '$encabezado->id'");
								//Flash::notice("Recorriendo Los Items de Cuadre de Caja");
								
								foreach( $detalles_item as $items):
									Flash::notice("Recorriendo Los Items ".$items->id);
									//print_r($items);
									
									$detalles = new CxcCuadreCaja();
									$detalles->setTransaction($transaction);
									//$detalles->id                     = $items->id;
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$detalles->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$detalles->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$detalles->cxc_id                 = $items->cxc_id;
									$detalles->cuadre_caja_id         = $encabezado->id;
									if($items->anulado == "SI") {     $detalles->anulado = '1';}
									if($items->anulado == "NO") {     $detalles->anulado = '0'; $total += $items->valor; }  
									//Flash::notice($total);
									if($detalles->save()==false){
										foreach($detalles->getMessages() as $message){ 
											Flash::error(" TABLA CUADRE DE CAJA : ".$message); 
										}
										Flash::error("error guardando detalles de cuadre de caja ".$items->id);
										$transaction->rollback();
									}	
									//Flash::notice("$items->cxc_id");
									if($this->Cxc->count(" id = '$items->cxc_id' ") == 0){
											Flash::error("error cxc no encontrado..".$items->cxc_id);
											$transaction->rollback();
										}
									
									$cxc = new Cxc();
									$cxc->setTransaction($transaction);
									$cxc2 = $cxc->findFirst(" id = '$items->cxc_id' ");
									$cxc2->id = $items->cxc_id;
									if($items->anulado == "SI") {  $cxc2->pagado =  $cxc2->pagado - $items->valor; }
									if($items->anulado == "NO") {  $cxc2->pagado =  $cxc2->pagado + $items->valor; }  
									//Flash::notice(" anulado = $items->anulado "); 
									if($cxc2->save()==false){
										foreach($cxc2->getMessages() as $message){ 
											Flash::error("Error Actualizando Tabla Cxc : ".$message); 
										}
										$transaction->rollback();
									}	
										
								endforeach;	
								
										
					}
					
					$turnos = new Turnos();
					$turnos->setTransaction($transaction);
					if($this->Turnos->count(" id = '$encabezado->turnos_id' and anulado = 0 ") > 0){
						$this->Turnos->findFirst(" id = '$encabezado->turnos_id' and anulado = 0 ");
					}else{
						$transaction->rollback();
						Flash::error("Turnos no Encontrados");	
						}
					Flash::notice($total);
					$encabezado2 = new CuadreCaja();
					$encabezado2 = $encabezado;
					$encabezado2->setTransaction($transaction);
					$encabezado2->id = $encabezado->id;
					$encabezado2->total_ingresos = $total;
					$db = DbBase::rawConnect();
					$sql = " select sum( dt.total) as total 
									from 
										egresos e, detalle_egresos dt 
										where 
											e.id = dt.egresos_id 
											and dt.anulado = 0 
											and e.anulado = 0 
											and e.fecha > '$turnos->inicio' 
											and e.fecha <= '$turnos->fin' ";
											
					$result = $db->query($sql);
					$row = $db->fetchArray($result); 
					
					$encabezado2->total_egresos = $row["total"];
					if($encabezado2->save()==false){
						foreach($encabezado2->getMessages() as $message){ 
							Flash::error(" Actualizando Saldos de Caja : ".$message); 
						}
						$transaction->rollback();
					}	
					
					$transaction->commit();
					Flash::success("Cuadre de Caja Guardado SATISFACTORIAMENTE");	
						echo "<script>redireccionar_action('cuadre_caja/agregar/?id=$encabezado->id');</script>";
							
					}catch(TransactionFailed $e){		
						Flash::error($e->getMessage());
					//cierre cacth try
					}
		  }//cierra if todo bien
		  

		}
		
	
		
		
		public function showAction($id){
		
			 $this->setParamToView("encabezado",$this->Factura->find(" id = '$id' "));
			 $this->setParamToView("idt",$id);								
			 $this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id = '$id' and anulado = 0 "));				
 			
		}
	
	
		
		
		public function buscarAction(){
			
		}
		
		public function traer_todoAction(){
			
			$this->setResponse('ajax');
			$cxc = new Cxc();
			$i=1;
			$empresa_id = $_REQUEST["empresa_id"];
			$f1 = $_REQUEST["fecha_apertura"];
			$f2 = $_REQUEST["fecha_cierre"];
			
			$query = new ActiveRecordJoin(array(
					"entities" => array("Cxc","PedidoClientes"),
					"fields"=>array("{#Cxc}.id","{#Cxc}.prefijo","{#Cxc}.consecutivo","{#Cxc}.total"),
					"noRelations"=>array(true),
					"conditions"=>("{#Cxc}.anulado = '0' 
									and {#PedidoClientes}.anulado = 0 
									and {#PedidoClientes}.empresa_id = '$empresa_id'
									and {#Cxc}.prefijo = {#PedidoClientes}.prefijo 
									and {#Cxc}.consecutivo = {#PedidoClientes}.consecutivo
									and {#Cxc}.tipo_documento_id = {#PedidoClientes}.tipo_documento_id
									and {#Cxc}.total - {#Cxc}.pagado <> 0
									and {#PedidoClientes}.hora_salida > '$f1' 
									and {#PedidoClientes}.hora_salida <= '$f2' ")
			));

			
			//Flash::success($query->getSqlQuery());
			foreach($query->getResultSet() as $cxc):
				$responce[$i]["id"]          = "temp".rand();
				$responce[$i]["cxc_id"]      = $cxc->id;
				$responce[$i]["prefijo"]     = $cxc->prefijo;	
				$responce[$i]["consecutivo"] = $cxc->consecutivo;
				$responce[$i]["hora_salida"] = $this->PedidoClientes->findFirst("prefijo = '$cxc->prefijo' and consecutivo = '$cxc->consecutivo'")->hora_salida;	
				$responce[$i]["valor"]       = $cxc->total;	
				$responce[$i]["anulado"]     = "NO";
				
				$i++;
			endforeach;
			echo json_encode($responce);
			
		}
		
		
		public function detalle_buscarAction(){
				$this->setResponse('ajax');
				
				
		}
		
		public function caja_abiertaAction(){
			$condicion = '';
			if(isset($_REQUEST["id"])){
				$id = $_REQUEST["id"];
				$ccj = new CuadreCaja();
				$ccj = $this->CuadreCaja->findFirst(" id = '$id' ");
				$ccj->visto_bueno = 1;
				if( $ccj->save() == false){
					foreach($ccj->getMessages as $mensajes):
						Flash::error(" Error Cerrando Caja ".$mensajes);
					endforeach;
				}
			}
			$detalles = $this->CuadreCaja->find("visto_bueno = 0 $condicion");
			$this->setParamToView("detalles",$detalles);
			
		}
		
		public function caja_cerradaAction(){
		
			$condicion = '';
			if(isset($_REQUEST["id"])){
				$id = $_REQUEST["id"];
				$ccj = new CuadreCaja();
				$ccj = $this->CuadreCaja->findFirst(" id = '$id' ");
				if( $ccj->visto_bueno == 0 ){ $ccj->visto_bueno = 1; }else{ $ccj->visto_bueno = 0; } 
				if( $ccj->save() == false){
					foreach($ccj->getMessages as $mensajes):
						Flash::error(" Error Cerrando Caja ".$mensajes);
					endforeach;
				}
			}
			
			$detalles = $this->CuadreCaja->find(" 1=1 $condicion ");
			$this->setParamToView("detalles",$detalles);
			
		}
		
		
		
	}
	
?>
