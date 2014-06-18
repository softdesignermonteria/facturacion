<?php
	class facturacionController extends ApplicationController {
		//declaramos variables publicas para todas las vistas asociadas

		public $prefijo;
		public $tipo_documento;
		public $tipo_documento_nombre;
		public $id_consecutivo;

		public function initialize() {
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
		
		
		
		public function BuscarAction(){

		}

	
		public function agregarAction(){
			Tag::DisplayTo("fecha",date("Y-m-d"));
			
			$vence = Date::addInterval(date("Y-m-d"), 1, Date::INTERVAL_MONTH);
			//Flash::notice(Session::get("id_empresa"));
			Tag::DisplayTo("vencimiento","$vence");
			Tag::DisplayTo("tipo_documento_id","$this->tipo_documento");
			Tag::DisplayTo("prefijo","$this->prefijo");
			Tag::DisplayTo("detalle_consecutivos_id","$this->id_consecutivo");
			
			Tag::DisplayTo("empresa_id",Session::get("id_empresa"));
			Tag::DisplayTo("admin_id",Session::get("usuarios_id"));
			Tag::DisplayTo("nombre_empresa",Session::get("nombre_empresa"));
			
		}


	
		public function agregar_itemAction(){

			$this->setResponse("ajax");
			$id = "";
			$tiva = "0";
			
			$subtotal = 0;
			$descuento = 0;
			$iva = 0;
			
			$id   = $_REQUEST["id"];
			$tiva = $_REQUEST["tmp_tarifa_iva_id"];
			$des  = $_REQUEST["tmp_descuento"];
			 
			if( $id==''   ){ $id = "temp".rand(); }
			if( $des==''  ){ $des = 0; }
			if( $tiva=='' || $tiva == '0' || $tiva == '@' ){ $tiva = "1"; }
			
			$ti = new TarifaIva();
			$ti = $ti->findFirst(" id = '$tiva' ");
			
			
			
			$subtotal  = $_REQUEST["tmp_valor"] / ( 1 + ($ti->valor/100) );  //subtotal iva incluido antes de descuento
			$descuento = $_REQUEST["tmp_valor"] / ( 1 + ($ti->valor/100) ) * ( $des/100 );
			
			$responce["id"]             = $id;
			$responce["kardex_id"]      = $_REQUEST["tmp_kardex_id"];
			$responce["referencia"]     = $_REQUEST["tmp_referencia"];	
			$responce["nombre"]         = $_REQUEST["tmp_nombre"];	
			$responce["cantidad"]       = $_REQUEST["tmp_cantidad"];	
			//$responce["valor"]          = $subtotal  * $_REQUEST["tmp_cantidad"] ;	
			$responce["valor"]          = $subtotal   ;	
			$responce["descuento"]      = $des;	
			//$responce["total_descuento"] = $descuento * $_REQUEST["tmp_cantidad"];	
			$responce["total_descuento"] = $descuento ;	
			$responce["tarifa_iva_id"]  = $ti->id;	
			//$responce["iva"]            = ( $subtotal - $descuento ) * ($ti->valor/100) * $_REQUEST["tmp_cantidad"];	
			$responce["iva"]            = ( $subtotal - $descuento ) * ($ti->valor/100) ;	
			$responce["valor_iva"]      = $ti->valor;
			$responce["total"]          =  ($responce["valor"] - $responce["total_descuento"]) + $responce["iva"] ;	
			$responce["anulado"]        = "NO";
			
			echo json_encode($responce);
			
		}
		
		
		public function agregar_item_updateAction(){

			$this->setResponse("ajax");
			
			$responce["id"]              = $_REQUEST["id"];
			$responce["kardex_id"]       = $_REQUEST["kardex_id"];
			$responce["referencia"]      = $_REQUEST["referencia"];	
			$responce["nombre"]          = $_REQUEST["nombre"];	
			$responce["cantidad"]        = $_REQUEST["cantidad"];	
			$responce["valor"]           = $_REQUEST["valor"]  ;	
			$responce["descuento"]       = $_REQUEST["descuento"];	
			$responce["total_descuento"] = $_REQUEST["total_descuento"];	
			$responce["tarifa_iva_id"]   = $_REQUEST["tarifa_iva_id"];	
			$responce["iva"]             = $_REQUEST["iva"] ;	
			$responce["valor_iva"]       = $_REQUEST["valor_iva"];
			$responce["total"]           = $_REQUEST["total"] ;	
			$responce["anulado"]         = $_REQUEST["anulado"];
			
			echo json_encode($responce);
			

		}

		
		public function detalle_facturaAction($id){
			$this->setResponse("ajax");
		}
		
		
			
		public function find_detalle_buscarAction(){
			$this->setResponse("ajax");
		}


		

		public function addAction(){
			$this->setResponse('view');
			$sw=0;
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
								$id_consecutivo = $cons->id;
								//Flash::error("$id");
								//Flash::error("$prefijo");
								//Flash::error("$consecutivo");
								//Flash::error("$tipo_documento_id");
								//Flash::error($_REQUEST['tipo_documento_id']);
								$dtc = new DetalleConsecutivos();
								$dtc = $this->DetalleConsecutivos->findFirst("id = '$id'");
								$dtc->setTransaction($transaction);
								$dtc->desde = $dtc->desde+1;
									if($dtc->save()==false){
											echo $deshabilitar;
											
											foreach($dtc->getMessages() as $message){ 
												$msg_error.=Flash::error("TABLA DETALLE CONSECUTIVOS: ".$message);  
												Flash::error("TABLA DETALLE CONSECUTIVOS: ".$message); 
										}
										$transaction->rollback();
									}
									
							}else{
								$prefijo = $_REQUEST["prefijo"];
								$consecutivo = $_REQUEST["consecutivo"];
								$tipo_documento_id = $_REQUEST["tipo_documento_id"];
								$id_consecutivo = $_REQUEST["detalle_consecutivos_id"];
							}
						//Flash::success("VERIFICANDO LOS CONSECUTIVOS PARA ESTE DOCUMENTO");		
							$encabezado = new Factura();
							$encabezado->setTransaction($transaction);
							//para traer el mismo modelo ya instanciado
							 $encabezado->id                    = $_REQUEST["id"];
							 $encabezado->empresa_id            = $_REQUEST["empresa_id"];
							 $encabezado->tipo_documento_id     = $tipo_documento_id; 
							 $encabezado->detalle_consecutivos_id   = $id_consecutivo;
							 $encabezado->prefijo               = $prefijo;
							 $encabezado->consecutivo           = $consecutivo;
							
							 $encabezado->centro_produccion_id  = $_REQUEST["centro_produccion_id"];
							 $encabezado->bodegas_id            = $_REQUEST["bodegas_id"];
							 $encabezado->clientes_id           = $_REQUEST["clientes_id"];
							 $encabezado->fecha                 = $_REQUEST["fecha"];
							 $encabezado->vencimiento           = $_REQUEST["vencimiento"];
							 
							
							 $encabezado->fecha_registro        = date("Y-m-d H:i:s");
							 $encabezado->admin_id              = $_REQUEST["admin_id"];
							 $encabezado->fecha_act             = date("Y-m-d H:i:s");
							 $encabezado->ip                    =  $_SERVER['REMOTE_ADDR'];
							 
							 $encabezado->subtotal              = $_REQUEST["subtotal"];
							 $encabezado->descuento             = $_REQUEST["descuento"];
							 $encabezado->iva                   = $_REQUEST["iva"];
							 $encabezado->total                 = $_REQUEST["total"];
							 
							 $encabezado->anulado               = '0';

							if($encabezado->save()==false){
								echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
								foreach($encabezado->getMessages() as $message){ 
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

								$dttmp = new DetalleFactura();
								$dttmp->setTransaction($transaction);
								$dttmp->delete("factura_id = '$encabezado->id'");
							
								foreach( $detalles_item as $items):
									$detalles = new DetalleFactura();
									$detalles->setTransaction($transaction);
									//$detalles->id                     = $items->id;
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$detalles->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$detalles->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$detalles->kardex_id              = $items->kardex_id;
									$detalles->factura_id     		  = $encabezado->id;
									$detalles->tarifa_iva_id     	  = $items->tarifa_iva_id;
									$detalles->valor                  = $items->valor;
									$detalles->cantidad               = $items->cantidad;
									$detalles->descuento              = $items->descuento;
									$detalles->total_descuento        = $items->total_descuento;
									$detalles->iva                    = $items->iva;
									$detalles->total                  = $items->total;
									//$detalles->anulado                = '0';
									if($items->anulado == "SI") {$detalles->anulado = '1';}
									if($items->anulado == "NO") {  $detalles->anulado = '0';  $total += $detalles->total; }

										if($detalles->save()==false){
											echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
											foreach($detalles->getMessages() as $message){ 
												Flash::error(" TABLA DETALLE PEDIDO CLIENTES : ".$message); 
											}
											$transaction->rollback();
										}	
								endforeach;			
					}

					$transaction->commit();
					Flash::success("FACTURA GUARDADO SATISFACTORIAMENTE");	
					}catch(TransactionFailed $e){		
						echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
						Flash::error($e->getMessage());
					//cierre cacth try
					}

		  }//cierra if todo bien

		}


		public function modificarAction(){
			
			$id = $_REQUEST["id"];
			
			$encabezado = $this->Factura->findFirst("  id = '$id' ");
			$detalles   = $this->DetalleFactura->find(" factura_id = '$id' ");
			$empresa    = $this->Empresa->findFirst("  id = '$encabezado->empresa_id'  ");
			$admn       = $this->Admin->findFirst("    id = '$encabezado->admin_id'    ");
			$empleado   = $this->Empleado->findFirst(" id = '$admn->empleado_id' ");
			
			Tag::DisplayTo("id",$encabezado->id);
 			Tag::DisplayTo("tipo_documento_id",$encabezado->tipo_documento_id);
			Tag::DisplayTo("detalle_consecutivos_id",$encabezado->detalle_consecutivos_id);
			Tag::DisplayTo("empresa_id",$encabezado->empresa_id);
			Tag::DisplayTo("prefijo2",$encabezado->prefijo);
			Tag::DisplayTo("consecutivo",$encabezado->consecutivo);
			Tag::DisplayTo("nombre_empresa",$empresa->nombre_empresa);
			
			Tag::DisplayTo("admin_id",$encabezado->admin_id);
			Tag::DisplayTo("nombre_empleado",$empleado->nombre_completo);
			
			Tag::DisplayTo("centro_produccion_id",$encabezado->centro_produccion_id);
			Tag::DisplayTo("bodegas_id",$encabezado->bodegas_id);
			Tag::DisplayTo("clientes_id",$encabezado->clientes_id);
			Tag::DisplayTo("fecha",$encabezado->fecha);
			Tag::DisplayTo("vencimiento",$encabezado->vencimiento);
			
			Tag::DisplayTo("subtotal",$encabezado->subtotal);
			Tag::DisplayTo("descuento",$encabezado->descuento);
			Tag::DisplayTo("iva",$encabezado->iva);
			Tag::DisplayTo("total",$encabezado->total);
			
			$this->setParamToView("detalles",$detalles);
			
			$this->setParamToView("centro_produccion_id",$encabezado->centro_produccion_id);
			$this->setParamToView("bodegas_id",$encabezado->bodegas_id);
			$this->setParamToView("clientes_id",$encabezado->clientes_id);
			
			
 
		}


		public function updateAction(){
			$this->setResponse('view');
			$sw=0;
					//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
					//abriando transacciones
				Flash::success("EMPEZANDO A GUARDAR LOS REGISTOS...");	
				$transaction = new ActiveRecordTransaction(true);   
				try{
					$transaction = TransactionManager::getUserTransaction(); 
					
							$prefijo = $_REQUEST["prefijo"];
							$consecutivo = $_REQUEST["consecutivo"];
							$tipo_documento_id = $_REQUEST["tipo_documento_id"];
							$id_consecutivo = $_REQUEST["detalle_consecutivos_id"];
						
							//Flash::success("VERIFICANDO LOS CONSECUTIVOS PARA ESTE DOCUMENTO");		
							$encabezado = new Factura();
							$encabezado->setTransaction($transaction);
							//para traer el mismo modelo ya instanciado
							 $encabezado->id                    = $_REQUEST["id"];
							 $encabezado->empresa_id            = $_REQUEST["empresa_id"];
							 $encabezado->tipo_documento_id     = $tipo_documento_id; 
							 $encabezado->detalle_consecutivos_id  = $id_consecutivo;
							 $encabezado->prefijo               = $prefijo;
							 $encabezado->consecutivo           = $consecutivo;
							
							 $encabezado->centro_produccion_id  = $_REQUEST["centro_produccion_id"];
							 $encabezado->bodegas_id            = $_REQUEST["bodegas_id"];
							 $encabezado->clientes_id           = $_REQUEST["clientes_id"];
							 $encabezado->fecha                 = $_REQUEST["fecha"];
							 $encabezado->vencimiento           = $_REQUEST["vencimiento"];
							 
							
							 $encabezado->fecha_registro        = date("Y-m-d H:i:s");
							 $encabezado->admin_id              = Session::get("usuarios_id");
							 $encabezado->fecha_act             = date("Y-m-d H:i:s");
							 $encabezado->ip                    =  $_SERVER['REMOTE_ADDR'];
							 
							 $encabezado->subtotal              = $_REQUEST["subtotal"];
							 $encabezado->descuento             = $_REQUEST["descuento"];
							 $encabezado->iva                   = $_REQUEST["iva"];
							 $encabezado->total                 = $_REQUEST["total"];
							 
							 $encabezado->anulado               = '0';

							if($encabezado->save()==false){
								echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
								foreach($encabezado->getMessages() as $message){ 
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

								/*$dttmp = new DetalleFactura();
								$dttmp->setTransaction($transaction);
								$dttmp->delete("factura_id = '$encabezado->id'");*/
							
								foreach( $detalles_item as $items):
									$detalles = new DetalleFactura();
									$detalles->setTransaction($transaction);
									//$detalles->id                     = $items->id;
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$detalles->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$detalles->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$detalles->kardex_id              = $items->kardex_id;
									$detalles->factura_id     		  = $encabezado->id;
									$detalles->tarifa_iva_id     	  = $items->tarifa_iva_id;
									$detalles->valor                  = $items->valor;
									$detalles->cantidad               = $items->cantidad;
									$detalles->descuento              = $items->descuento;
									$detalles->total_descuento        = $items->total_descuento;
									$detalles->iva                    = $items->iva;
									$detalles->total                  = $items->total;
									//$detalles->anulado                = '0';
									if($items->anulado == "SI") {$detalles->anulado = '1';}
									if($items->anulado == "NO") {  $detalles->anulado = '0';  $total += $detalles->total; }

										if($detalles->save()==false){
											echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
											foreach($detalles->getMessages() as $message){ 
												Flash::error(" TABLA DETALLE PEDIDO CLIENTES : ".$message); 
											}
											$transaction->rollback();
										}	
								endforeach;			
					}

					$transaction->commit();
					Flash::success("FACTURA MODIFICADA SATISFACTORIAMENTE");	
					echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
					echo "<script>redireccionar_action('facturacion/show/?id=$encabezado->id');</script>";
					}catch(TransactionFailed $e){		
						echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
						Flash::error($e->getMessage());
					//cierre cacth try
					}

		  }//cierra if todo bien

		}

	

		public function showAction(){
			
			$id = $_REQUEST["id"];
			
			$encabezado = $this->Factura->findFirst("  id = '$id' ");
			$detalles   = $this->DetalleFactura->find(" factura_id = '$id' ");
			$empresa    = $this->Empresa->findFirst("  id = '$encabezado->empresa_id'  ");
			$admn       = $this->Admin->findFirst("    id = '$encabezado->admin_id'    ");
			$empleado   = $this->Empleado->findFirst(" id = '$admn->empleado_id' ");
			
			Tag::DisplayTo("id",$encabezado->id);
 			Tag::DisplayTo("tipo_documento_id",$encabezado->tipo_documento_id);
			Tag::DisplayTo("detalle_consecutivos_id",$encabezado->detalle_consecutivos_id);
			Tag::DisplayTo("empresa_id",$encabezado->empresa_id);
			Tag::DisplayTo("prefijo2",$encabezado->prefijo);
			Tag::DisplayTo("consecutivo",$encabezado->consecutivo);
			Tag::DisplayTo("nombre_empresa",$empresa->nombre_empresa);
			
			Tag::DisplayTo("admin_id",$encabezado->admin_id);
			Tag::DisplayTo("nombre_empleado",$empleado->nombre_completo);
			
			Tag::DisplayTo("centro_produccion_id",$encabezado->centro_produccion_id);
			Tag::DisplayTo("bodegas_id",$encabezado->bodegas_id);
			Tag::DisplayTo("clientes_id",$encabezado->clientes_id);
			Tag::DisplayTo("fecha",$encabezado->fecha);
			Tag::DisplayTo("vencimiento",$encabezado->vencimiento);
			
			Tag::DisplayTo("subtotal",$encabezado->subtotal);
			Tag::DisplayTo("descuento",$encabezado->descuento);
			Tag::DisplayTo("iva",$encabezado->iva);
			Tag::DisplayTo("total",$encabezado->total);
			
			$this->setParamToView("detalles",$detalles);
			
			$this->setParamToView("centro_produccion_id",$encabezado->centro_produccion_id);
			$this->setParamToView("bodegas_id",$encabezado->bodegas_id);
			$this->setParamToView("clientes_id",$encabezado->clientes_id);
			
			
 
		}


			
	}

	

?>

