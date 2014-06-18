<?php
			
	class pedido_clientesController extends ApplicationController {
		
		
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
		
		public function anular_minutosAction(){
			
		}
		
		public function anular_administradorAction(){
			
		}
		
		public function anular_administrador2Action(){
			
			$transaction = new ActiveRecordTransaction(true);   
			try{
					$transaction = TransactionManager::getUserTransaction(); 
					$id = $_REQUEST["id"];
					$prefijo = $_REQUEST["prefijo"];
					$consecutivo = $_REQUEST["consecutivo"];
					$pdClientes = new PedidoClientes();
					$pdClientes->setTransaction($transaction);
					$pdClientes = $this->PedidoClientes->findFirst(" id = '$id' ");
					$pdClientes->anulado = '1';
					
					if($pdClientes->save()==false){
						foreach($pdClientes->getMessages() as $message){ 
							Flash::error("TABLA PEDIDO CLIENTES: ".$message); 
						}
						$transaction->rollback();
					}else{
						Flash::success("Pedido Cliente y/o Control de Ingresio Anulado satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("movimientos_inventario",$fields,$values,"tipo_documento_id = '$pdClientes->tipo_documento_id' and prefijo = '$pdClientes->prefijo' and consecutivos = '$pdClientes->consecutivo' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("Detalle Anulados satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("detalle_pedido_clientes",$fields,$values,"pedido_clientes_id = '$id' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("Detalle Anulados satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("cxc",$fields,$values,"prefijo = '$prefijo' and consecutivo = '$consecutivo' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("tabla cxc actualizada satisfactoriamente");
					}
					
					
			}catch(TransactionFailed $e){		
				Flash::error($e->getMessage());
			//cierre cacth try
			}
			
		}
		
		public function anularAction(){
			$transaction = new ActiveRecordTransaction(true);   
			try{
					$transaction = TransactionManager::getUserTransaction(); 
					$id = $_REQUEST["id"];
					$prefijo = $_REQUEST["prefijo"];
					$consecutivo = $_REQUEST["consecutivo"];
					$pdClientes = new PedidoClientes();
					$pdClientes->setTransaction($transaction);
					$pdClientes = $this->PedidoClientes->findFirst(" id = '$id' ");
					$pdClientes->anulado = '1';
					
					if($pdClientes->save()==false){
						foreach($pdClientes->getMessages() as $message){ 
							Flash::error("TABLA PEDIDO CLIENTES: ".$message); 
						}
						$transaction->rollback();
					}else{
						Flash::success("Pedido Cliente y/o Control de Ingresio Anulado satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("movimientos_inventario",$fields,$values,"tipo_documento_id = '$pdClientes->tipo_documento_id' and prefijo = '$pdClientes->prefijo' and consecutivos = '$pdClientes->consecutivo' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("Detalle Anulados satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("detalle_pedido_clientes",$fields,$values,"pedido_clientes_id = '$id' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("Detalle Anulados satisfactoriamente");
					}
					
					$db = DbBase::rawConnect();
					$fields = array("anulado");
					$values = array("1");
					if($db->update("cxc",$fields,$values,"prefijo = '$prefijo' and consecutivo = '$consecutivo' ",true)==false){
						$transaction->rollback();
					}else{
						Flash::success("tabla cxc actualizada satisfactoriamente");
					}
					
					
			}catch(TransactionFailed $e){		
				Flash::error($e->getMessage());
			//cierre cacth try
			}
					
		}
		
		
		public function colorautocompleteAction(){
			$this->setResponse("ajax");
		}
		
		public function placaautocompleteAction(){
			$this->setResponse("ajax");
		}
		
		public function agregar_itemAction(){
			
			
			$this->setResponse("ajax");
			
			
			if($_REQUEST["liquidar"]==1){
					$responce["id"]          = "temp".rand();
					$responce["kardex_id"]   = $_REQUEST["tmp_kardex_id"];
					$responce["referencia"]  = $_REQUEST["tmp_referencia"];	
					$responce["nombre"]      = $_REQUEST["tmp_nombre"];	
					$responce["cantidad"]    = "1";	
					$responce["valor"]       = $_REQUEST["tmp_valor"];	
					$responce["total"]       = $_REQUEST["tmp_valor"];	
					$responce["anulado"]     = "NO";
				}else{
						$responce["id"]          = "temp".rand();
						$responce["kardex_id"]   = $_REQUEST["tmp_kardex_id"];
						$responce["referencia"]  = $_REQUEST["tmp_referencia"];	
						$responce["nombre"]      = $_REQUEST["tmp_nombre"];	
						$responce["cantidad"]    = $_REQUEST["tmp_cantidad"];	
						$responce["valor"]       = $_REQUEST["tmp_valor"];	
						$responce["total"]       = $_REQUEST["tmp_valor"]*$_REQUEST["tmp_cantidad"];	
						$responce["anulado"]     = "NO";
					
					}				
					
				
			
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
								echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
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
						
							$pdClientes = new PedidoClientes();
							$pdClientes->setTransaction($transaction);
							//para traer el mismo modelo ya instanciado
							 $pdClientes->id                    = $_REQUEST["id"];
							 $pdClientes->prefijo               = $prefijo;
							 $pdClientes->consecutivo           = $consecutivo;
							 $pdClientes->centro_produccion_id  = $_REQUEST["centro_produccion_id"];
							 $pdClientes->bodegas_id            = $_REQUEST["bodegas_id"];
							 $pdClientes->empresa_id            = $_REQUEST["empresa_id"];
							 $pdClientes->tipo_documento_id     = $tipo_documento_id; 
							 $pdClientes->fecha                 = $_REQUEST["fecha"];
							 $pdClientes->hora_entrada          = $_REQUEST["hora_entrada"];
							 $pdClientes->hora_salida           = $_REQUEST["hora_salida"];
							 $pdClientes->clase_vehiculo_id     = $_REQUEST["clase_vehiculo_id"];
							 $pdClientes->tipo_auto_id          = $_REQUEST["tipo_auto_id"];
							 $pdClientes->placa                 = $_REQUEST["placa"];
							 $pdClientes->color                 = $_REQUEST["color"];
							 $pdClientes->anulado               = '0';
							 $pdClientes->liquidado             = '0';
							 if( $_REQUEST["id"] == ''){
								 $pdClientes->trasladado               = '0';
								 $pdClientes->entradas_especiales_id             = '0';
							 }
							if($pdClientes->save()==false){
								echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
								foreach($pdClientes->getMessages() as $message){ 
									Flash::error("TABLA FACTURA: ".$message); 
								}
								$transaction->rollback();
								
							}
							
							$total = 0;
							$detalles_item = str_replace("]\"","]",str_replace("\"[","[",str_replace("\\","",$_POST["detalles"])));
							Flash::notice($detalles_item);
							
							if($detalles_item!='[]'){	
								
								if(json_decode($detalles_item)){
									Flash::success("Json Correcto");
									$detalles_item = json_decode($detalles_item);
								}else{
									echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
									Flash::error("Error json");
									$transaction->rollback();
									
								}
	
								$dttmp = new DetallePedidoClientes();
								$dttmp->setTransaction($transaction);
								$dttmp->delete("pedido_clientes_id = '$pdClientes->id'");
								
								foreach( $detalles_item as $items):
									$dtpedido = new DetallePedidoClientes();
									$dtpedido->setTransaction($transaction);
									//$dtpedido->id                     = $items->id;
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$dtpedido->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$dtpedido->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$dtpedido->kardex_id              = $items->kardex_id;
									$dtpedido->pedido_clientes_id     = $pdClientes->id;
									$dtpedido->valor                  = $items->valor;
									$dtpedido->cantidad               = $items->cantidad;
									$dtpedido->total                  = $items->total;
									//$dtpedido->anulado                = '0';
									if($items->anulado == "SI") {$dtpedido->anulado = '1';}
									if($items->anulado == "NO") {  $dtpedido->anulado = '0';  $total += $dtpedido->total; }
									
										if($dtpedido->save()==false){
											echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
											foreach($dtpedido->getMessages() as $message){ 
												Flash::error(" TABLA DETALLE PEDIDO CLIENTES : ".$message); 
											}
											$transaction->rollback();
											
										}	
										
										if($dtpedido->kardex_id != $this->Empresa->findFirst("id = '$pdClientes->empresa_id'")->kardex_id_default){
											  $db = DbBase::rawConnect();
												 $kardex = new Kardex();
												$emp = new Empresa();
												$tipo_costeo = $emp->FindFirst("id = '".Session::get("id_empresa")."'")->tipo_costeo;
												
												  
											  $mi = new MovimientosInventario();
											  
											  $idtmov = $this->MovimientosInventario->findFirst(" idt='$dtpedido->id' and  tipo_documento_id = '$pdClientes->tipo_documento_id' and prefijo = '$pdClientes->prefijo' and consecutivos = '$pdClientes->consecutivo' and empresa_id ='$pdClientes->empresa_id' and anulado = 0");
											  $mov_id ='';
											  if($idtmov->id!=''){ $mov_id=$idtmov->id; }else{ $mov_id='';} 
											  $mi->id                 = $mov_id;
											  $mi->idt                = $dtpedido->id;
											  $mi->bodegas_id         = $pdClientes->bodegas_id;
											  $mi->tipo_documento_id  = $pdClientes->tipo_documento_id;
											  $mi->prefijo            = $pdClientes->prefijo;
											  $mi->consecutivos       = $pdClientes->consecutivo;
											  $mi->empresa_id         = $pdClientes->empresa_id;
											  $mi->nit                = $pdClientes->centro_produccion_id;
											  $mi->kardex_id          = $dtpedido->kardex_id;
											  $mi->fecha              = date("Y-m-d");
											  $mi->fecha_act          = date("Y-m-d H:i:s");
											  $costo = $db->fetchOne("SELECT calcular_costo('$mi->kardex_id','$mi->fecha','$mi->bodegas_id','$tipo_costeo') as costo ");
											  $costo2 =$costo["costo"]; 	
											 // Flash::notice("costo = ".$costo." costo2: ".$costo2." cantidad = ".$dtpedido->cantidad);	
											  $mi->costo              = $costo2;
											  $mi->cantidad           = $dtpedido->cantidad;
											  $mi->descuento          = '0';
											  $mi->total              = $costo2*$dtpedido->cantidad; 
											  $mi->anulado            = $dtpedido->anulado;
											  $mi->multiplica         ="-1";
											  if($mi->save()==false){
													echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
													foreach($mi->getMessages() as $message){ 
														Flash::error(" Error descargando de inventario : ".$message); 
													}
													$transaction->rollback();
													
												}	
													  
										} //si kardex es de movimiento...
										
										
										
								endforeach;			
					}
					
					
					//Flash::error("cuenta por cobrar");							
					$cxc = new Cxc();
					$cxc->setTransaction($transaction);
					//$idcxc = $this->Cxc->findFirst("tipo_documento_id = '$tipo_documento_id' and prefijo = '$prefijo' and consecutivo = '$consecutivo' and anulado = 0");
					//$cxc_id = '';
					//if($idcxc->id != '' ){ $cxc_id = $idcxc->id; }else{ $cxc_id = ''; }
					$cxc->id = $this->Cxc->findFirst("tipo_documento_id = '$tipo_documento_id' and prefijo = '$prefijo' and consecutivo = '$consecutivo' and anulado = 0")->id; 
					$cxc->prefijo            = $prefijo;
					$cxc->consecutivo        = $consecutivo;
					$cxc->tipo_documento_id  = $tipo_documento_id;
					$cxc->fecha              = $_REQUEST["fecha"];
					$cxc->anticipo           = 0;
					$cxc->total              = $total;
					$cxc->pagado             = 0;
					$cxc->anulado            = 0;
					$cxc->activo             = 0;
					$cxc->descontado         = 0;
					if($cxc->save()==false){
						echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
						foreach($cxc->getMessages() as $message){ 
							Flash::error(" TABLA CXC ERROR : ".$message); 
						}
						$transaction->rollback();
						
					}
					
					
					
					if($_REQUEST["liquidada"]==1){
					
							$pdClientes2 = new PedidoClientes();
							$pdClientes2 = $pdClientes;
							$pdClientes2->setTransaction($transaction);
							$pdClientes2->id = $pdClientes->id;
							$pdClientes2->liquidado = 1;
							if($pdClientes2->save()==false){
								echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
								foreach($pdClientes2->getMessages() as $message){ 
									Flash::error(" TABLA PEDIDO CLIENTES NO FUE ACTUALIZADO: ".$message); 
								}
								$transaction->rollback();
								
							}	
					}						
						
												
					$transaction->commit();
					Flash::success("PEDIDO DE CLIENTES GUARDADO SATISFACTORIAMENTE");	
						
						if($pdClientes2->liquidado==1){
								echo "<script>redireccionar_action('menu');</script>";	
							}else{
							
								echo "<script>redireccionar_action('menu');</script>";
							}
							
					}catch(TransactionFailed $e){		
						echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
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
		
		public function pedidos_liquidadosAction(){
			
		}
	
		public function pedidos_liquidados_jsonAction(){
			$this->setResponse('ajax');
				
					

		}	
		
		public function pedidos_no_liquidadosAction(){
			
		}
	
		public function pedidos_no_liquidados_jsonAction(){
			$this->setResponse('ajax');
				
					

		}	
		
	
		
		
		
		public function calc_fechasAction(){
		
				$this->setResponse('ajax');
				
				if(Session::get("cobro_tarifa")=="INTERVALO"){
						
						$db = DbBase::rawConnect();
						$customer = $db->fetchOne("SELECT TIMEDIFF('".$_REQUEST["hora_salida"]."','".$_REQUEST["hora_entrada"]."') as hora");
						$customer["hora"];
						Flash::notice($customer["hora"]);
						
						$th = $this->CentroProduccion->findFirst("id = '".$_REQUEST["centro_produccion"]."'")->tipo_habitacion_id;
						$valor = $this->TarifaHabitacion->findFirst(" '".$customer["hora"]."' <= hora_fin and tipo_habitacion_id = '$th' ")->valor;
						$num = $this->TarifaHabitacion->count(" '".$customer["hora"]."' <= hora_fin and tipo_habitacion_id = '$th' ");
						Flash::notice("hora_inicio > '".$customer["hora"]."' and  '".$customer["hora"]."' < hora_fin and tipo_habitacion_id = '$th' ");
						Flash::notice("Centro Produccion = ".$_REQUEST["centro_produccion"]);
						Flash::notice("Tipo habitacion = ".$th);
						Flash::notice("Tarifa Encontrada = ".$num);
						Flash::notice("valor = ".$valor);
						$horatmp = explode(":",$customer["hora"]);
						//$valor = $_REQUEST["valor"];
							//$hora = $horatmp[0];
							
							/*if($horatmp[1]>30){
								$hora = $hora + 1;
							}
							
							if($hora < 1 ){
								$hora = 1;
							}
							*/
							if( $num==0 ){
									echo 
									"<script>
										jQuery('#tmp_total2').removeAttr('readonly');
										jQuery('#tmp_valor2').removeAttr('readonly');
										jQuery('#tmp_total2').removeAttr('style');
										jQuery('#tmp_valor2').removeAttr('style');
									</script>
									";
								}
							
							echo   "<script>
									jQuery('#tmp_cantidad2').val('1');
									jQuery('#tmp_valor2').val('".$valor."');
									jQuery('#tmp_total2').val('".$valor."');
									</script>";
				}else{
						if(Session::get("cobro_tarifa")=="PORMINUTO"){
								$db = DbBase::rawConnect();
								//SELECT TIMESTAMPDIFF(MINUTE,"2012-12-12 07:00:00","2012-12-12 14:30:00") AS MINUTOS;
								$customer = $db->fetchOne("SELECT TIMESTAMPDIFF(MINUTE,'".$_REQUEST["hora_entrada"]."','".$_REQUEST["hora_salida"]."') as MINUTOS");
								$tmp = $customer["MINUTOS"];
								
								$th = $this->CentroProduccion->findFirst("id = '".$_REQUEST["centro_produccion"]."'")->tipo_habitacion_id;
								$valor = 0;
								//Flash::notice("hora entrada ".$_REQUEST["hora_entrada"]);
								//Flash::notice("hora salida ".$_REQUEST["hora_salida"]);
								
								Flash::notice("Duracion en Minutos ".$customer["MINUTOS"]);
								//Flash::notice("Duracion en Minutos ".$customer["MINUTOS"]/60);
								//$tmp = $customer["MINUTOS"];							
								Flash::notice($tmp/60);
								$acumulado = 0;
								$valor = 0;
								for($i=1;$i<($tmp/60) -1;$i++){
									$acumulado += 60; 
									Flash::notice("$i acumulado = ".$acumulado);
									$mult  = $this->TarifaHabitacionMinutos->findFirst("yyyy = ".date("Y")." and  fin <= '".$acumulado."' and tipo_habitacion_id = '$th' ","order: fin desc")->valor;
									$valor += 60 * $mult;
									//Flash::error("yyyy = ".date("Y")." and  inicio > '".$acumulado."' and  '".$acumulado."' <= fin and tipo_habitacion_id = '$th' ");
									Flash::notice("valor mult = ".$mult);
									Flash::notice("valor = ".$valor);
								}
								
								$saldo = $customer["MINUTOS"] - $acumulado;
								$acumulado += $saldo; 
								$valor += $saldo * $this->TarifaHabitacionMinutos->findFirst("yyyy = ".date("Y")." and  fin <= '".$acumulado."' and tipo_habitacion_id = '$th' ","order: fin desc")->valor;
								
								//$valor = $this->TarifaHabitacion->findFirst("hora_inicio > '".$customer["hora"]."' and  '".$customer["hora"]."' <= hora_fin and tipo_habitacion_id = '$th' ")->valor;
								Flash::notice("Centro Produccion = ".$_REQUEST["centro_produccion"]);
								Flash::notice("Tipo habitacion = ".$th);
								Flash::notice("valor = ".$valor);
								//$horatmp = explode(":",$customer["hora"]);
								//$valor = $_REQUEST["valor"];
									//$hora = $horatmp[0];
									
									/*if($horatmp[1]>30){
										$hora = $hora + 1;
									}
									
									if($hora < 1 ){
										$hora = 1;
									}
									*/
									echo   "<script>
									jQuery('#tmp_cantidad2').val('1');
									jQuery('#tmp_valor2').val('".$valor."');
									jQuery('#tmp_total2').val('".$valor."');
									</script>";
							}
				
				}
				
						
				
		}
		
		
		public function liquidarAction(){
			$this->setResponse('ajax');
		}
		
		
	
		
		
		
		
		

	}
	
?>
