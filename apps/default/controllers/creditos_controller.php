<?php
			
	class CreditosController extends ApplicationController {
		
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
					Router::routeTo("controller: remisiones", "action: index");
					return false;
				}else{
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
					Router::routeTo("controller: remisiones", "action: index");
					return false;
				}else{
					//cargando las variables globales para este controlador
					$this->prefijo = $cons->prefijo;
					$this->id_consecutivo = $cons->id;
				}//fin si o no existe docuemnto
			} //fin si comprobacion action = agregar
		 }
	 
	 
		public function indexAction(){

		}
		
		
			
		public function trae_clientesAction(){
			
			//$this->setResponse('ajax');
			$this->setTemplateAfter("default");

		}
		
		public function agregarAction(){
			
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
					//Flash::success("tipo documento".$_REQUEST['tipo_documento']);	
					foreach($this->DetalleConsecutivos->find("activo = '0' and tipo_documento_id = '".$_REQUEST['tipo_documento']."'") as $cons){
						//Flash::success("VERIFICANDO LOS CONSECUTIVOS PARA ESTE DOCUMENTO");		
							$fact = new Factura();
							$fact->setTransaction($transaction);
							//para traer el mismo modelo ya instanciado
							 $fact->id = '';
							 $fact->prefijo             = $cons->prefijo;
							 $fact->consecutivo         = $cons->desde;   
							 $fact->clientes_id         = $_REQUEST["clientes_id"];
							 $fact->direccion_id        = $_REQUEST["direccion_id"];
							 $fact->empresa_id          = $_REQUEST["empresa_id"];
							 $fact->tipo_documento_id   = $cons->tipo_documento_id;
							 $fact->fecha_act           = date("Y-m-d H:i:s");
							 $fact->fecha               = $_REQUEST["fecha"];
							
							 $db = DbBase::rawConnect();
							 $db->query("SELECT ADDDATE('".$fact->fecha."', INTERVAL 30 DAY)  as vecimiento ");
							 //Flash::error("SELECT ( DATEDIFF('".$r->findFirst(" id = '$sal->remisiones_id' ")->fecha."', '".$fecha_devol."') + 1 ) as dias ");
							 while($fila = $db->fetchArray() ){
								$vecimiento = $fila['vecimiento'];
							 }
							 
							 $fact->vencimiento         = $vecimiento;
							 $fact->anulado             = '0';
							 $fact->id_unico            = $_REQUEST["id_unico"];
							 $fact->anombre             = $_REQUEST["anombre"];
							 $fact->anit                = $_REQUEST["anit"];
							 $fact->observaciones       = $_REQUEST["observaciones"];
							//Flash::error(print_r($rem));
							//Flash::error($rem->id);
							Flash::error($cons->desde);
							//abre si
							if($fact->save()==false){
								//abre for each
								foreach($fact->getMessages() as $message){ 
									Flash::error("TABLA FACTURA: ".$message); 
								}
								//cierra foreach
								$transaction->rollback();
							//cierra if
							}
							
														
				} //cierra for each consecutivos	
											
				$transaction->commit();
				Flash::success("FACTURA GUARDADA SATISFACTORIAMENTE");	
				echo "<script>/*alert('FACTURA GUARDADA SATISFACTORIAMENTE');*/
						document.location.replace('".core::getInstancePath()."credito/show/$fact->id');</script>";
					
				}catch(TransactionFailed $e){		
				
					Flash::error($e->getMessage());
						
				
				//cierre cacth try
				}
					
				
		  }//cierra if todo bien
		  

		}
		
		
		
		public function agregar2Action(){
			
		}
		
		
		
		/* trae productos de cxc a la factura */
		public function trae_productosAction(){
		
 			$this->setResponse('ajax');

			$clientes_id  = $_REQUEST['clientes_id'];
			$direccion_id = $_REQUEST['direccion_id'];
			$empresa_id   = $_REQUEST['empresa_id'];
			$id_unico     = $_REQUEST['id_unico'];
			$orderby      =  'id';
			
			$det = new Cxc();
			
			$detalles = $det->find("clientes_id = '$clientes_id' and direccion_id = '$direccion_id' and empresa_id = '$empresa_id' and facturado =  '0'","order: $orderby");
			
			 $this->setParamToView("detalles", $detalles);	
		
		}
		
		
		public function delete_detalle_productoAction(){
		
 			$this->setResponse('view');
			//$this->setTemplateAfter("default");
				
				
				$idt = $_REQUEST['idt'];
				$id_unico = $_REQUEST['id_unico'];
				
				
				$rtmp = new DetalleFacturaTmp();
							  				  
				  if($rtmp->delete(" id = '$idt' ")==true){
				  	
						Flash::success("ARTICULO BORRADO SATISFACTORIAMENTE....");
					     
				  }else{
				  	foreach($rtmp->getMessages() as $message){
				          Flash::error($message->getMessage());
					}	  
				  }
				  
				  
				  $this->setParamToView("detalles",$this->DetalleFacturaTmp->find(" id_unico = '$id_unico' "));				
				
				
		
		}
		
	
		
		
		public function showAction($id){
		
			 $this->setParamToView("encabezado",$this->Factura->find(" id = '$id' "));
			 $this->setParamToView("idt",$id);								
			 $this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id = '$id' and anulado = 0 "));				
 			
		}
	
		public function show2Action($id){
		
			 $this->setParamToView("encabezado",$this->Factura->find(" id = '$id' "));
			 $this->setParamToView("idt",$id);								
			// $this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id = '$id' "));				
 			
		}
				
		
		/*trae todos los productos que esten pendientes por fdacturar*/
		
		public function trae_todos_productosAction(){
		
 			$this->setResponse('ajax');

			$clientes_id  = $_REQUEST['clientes_id'];
			$direccion_id = $_REQUEST['direccion_id'];
			$empresa_id   = $_REQUEST['empresa_id'];
			$id_unico     = $_REQUEST['id_unico'];
			$orderby      =  'id';
			
			$det = new Cxc();
			
			$detalles = $det->find("clientes_id = '$clientes_id' and direccion_id = '$direccion_id' and empresa_id = '$empresa_id' and facturado =  '0'","order: $orderby");
				
				$dtf = new DetalleFacturaTmp();
				$dtf2 = new DetalleFacturaTmp();
			
				foreach($detalles as $det):
					 
					  $dtf->id           = '';
					  $dtf->kardex_id    = $det->kardex_id;
					  $dtf->factura_id   = '0';
					  $dtf->cxc_id       = $det->id;
					  $dtf->desde        = $det->desde;
					  $dtf->hasta        = $det->hasta;
					  $dtf->costo        = $det->costo;
					  $dtf->dias         = $det->dias;
					  $dtf->cantidad     = $det->cantidad;
					  $dtf->valor_diario = $det->valor_diario;
					  $dtf->valor_hora   = $det->valor_hora;
					  $dtf->iva          = $det->iva;
					  $dtf->total        = $det->total;
					  $dtf->id_unico     = $id_unico;
					  $dtf->observacion  = $det->observacion;
					  $dtf->otros_cobros = $det->otros_cobros;
					  
					  
					  if($this->DetalleFacturaTmp->count("id_unico = '$id_unico' and cxc_id = '$det->id'")==0){
					  	$dtf->save();
					  }else{
					  	Flash::error("PRODUCTO YA AGREADO A ESTA FACTURA...");
					  }
					  
				endforeach;	
			
			 $dtf3 = $this->DetalleFacturaTmp->find("id_unico = '$id_unico'","order: $orderby");
			 $this->setParamToView("detalles", $dtf3);
		
		}
		
		
	
		
				
		public function traer_municipiosAction($id){
		      
			$this->setResponse('ajax');  //ajax o view da los mismo
			$mun = $this->Municipios->find("departamentos_id='$id'");
			$this->setParamToView("municipios",$mun);
			  
		}
		
		
		
		public function buscarAction(){
			
		}
		
		
		public function detalle_buscarAction(){
				
				$this->setResponse('ajax');
				
				if( isset( $_REQUEST['razon_social']   )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and {#Clientes}.razon_social like '%".str_replace(' ',"%",$_REQUEST['razon_social'])."%'"; } }
				if( isset( $_REQUEST['direccion']      )==true ) { if( $_REQUEST['direccion'] != ''      ) { $sql .= " and {#Direccion}.direccion like '".$_REQUEST['direccion']."%'";           } }
				if( isset( $_REQUEST['finicial']       )==true ) { if( $_REQUEST['finicial'] != ''       ) { $sql .= " and {#Factura}.fecha >=  '".$_REQUEST['finicial']."'";           } }
				if( isset( $_REQUEST['ffinal']         )==true ) { if( $_REQUEST['ffinal'] != ''         ) { $sql .= " and {#Factura}.fecha <=  '".$_REQUEST['ffinal']."'";           } }
				$empresa_id  = $_REQUEST["empresa_id"];
			
				$query = new ActiveRecordJoin(array(
						"entities" => array("Factura","Clientes","Direccion","Empresa","TipoDocumento"),
						"fields"=>array(
										"{#Factura}.id",
										"{#Factura}.prefijo",
										"{#Factura}.consecutivo",
										"{#Factura}.fecha",
										"{#Clientes}.razon_social",
										"{#Direccion}.direccion"
										),
						"conditions"=>(" {#Factura}.empresa_id = '$empresa_id' $sql ")
				));
				
				
				
				
				//$clientes = $this->Clientes->findAllBySql($sql);
				//Flash::error($query->getSqlQuery());
				$this->setParamToView('detalles',$query->getResultSet());
				//$this->setParamToView('query',$query);
				//$this->setParamToView('query2',"hola");
				
		}
		
		
		public function modificarAction($id){
		
				
				$query = new ActiveRecordJoin(array(
						"entities" => array("Factura", "Clientes", "Direccion","Empresa","TipoDocumento"),
						"fields"=>array(
										"{#Factura}.*",
										"{#Clientes}.razon_social",
										"{#Direccion}.direccion",
										"{#Empresa}.nombre_empresa",
										"{#TipoDocumento}.nombre"
										),
						"conditions"=>("{#Factura}.id = '$id'")
				));
				
				
				if($this->Factura->findFirst(" id = '".$id."' ")->empresa_id != Session::get("id_empresa") ){
					Flash::error("POR FAVOR CAMBIE DE EMPRESA PARA HACER ESTA OPERACION");	
					Flash::addMessage("POR FAVOR CAMBIE DE EMPRESA PARA HACER ESTA OPERACION", Flash::ERROR);
					$this->redirect("menu/");
				}
				
				$this->setParamToView("factura",$query->getResultSet());
				$this->setParamToView("idt",$id);
				$this->setParamToView("detalles",$this->DetalleFactura->find("factura_id = '$id' and anulado = 0"));
			
		}
		
		
		public function add_detalle_producto_modAction(){
		
 			$this->setResponse('ajax');
			$dtf = new DetalleFacturaTmp();
			$dtf2 = new DetalleFacturaTmp();
			$cxc_id     = $_REQUEST['idt'];
			$id_unico     = $_REQUEST['id_unico'];
			$id     = $_REQUEST['id'];
			$det =  $this->Cxc->findFirst(" id = '$cxc_id' and anulado = 0 ");
			$sw=0;
			//Flash::error($this->Cxc->findFirst("id = '$cxc_id' ")->id);
			Flash::error("$cxc_id");
			Flash::error("$id");
			//Flash::error("$id_unico");
			
			if($this->DetalleRecibosCaja->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO RECIBOS DE CAJA A LA FACTURA"); $sw=1;
			}
			
			if($this->AnticiposFactura->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO ANTICIPOS A LA FACTURA"); $sw=1;
			}
			
			if($sw==0){  
				
					try{		
					
							 $transaction = TransactionManager::getUserTransaction(); 
							
							  $dtf = new DetalleFactura();
							  $dtf->setTransaction($transaction);
							  $dtf->id           = '';
							  $dtf->kardex_id    = $det->kardex_id;
							  $dtf->factura_id   = $id;
							  $dtf->cxc_id       = $det->id;
							  $dtf->desde        = $det->desde;
							  $dtf->hasta        = $det->hasta;
							  $dtf->costo        = $det->costo;
							  $dtf->dias         = $det->dias;
							  $dtf->cantidad     = $det->cantidad;
							  $dtf->valor_diario = $det->valor_diario;
							  $dtf->valor_hora   = $det->valor_hora;
							  $dtf->iva          = $det->iva;
							  $dtf->total        = $det->total;
							  $dtf->id_unico     = $id_unico;
							  $dtf->observacion  = $det->observacion;
							  $dtf->otros_cobros = $det->otros_cobros;
							  $dtf->anulado      = $det->anulado;
							  
							  
							  if($this->DetalleFactura->count("id_unico = '$id_unico' and cxc_id = '$det->id' and anulado = 0")==0){
									
									if( !$dtf->save() ){
										//abre for each
											foreach($dtf->getMessages() as $message){ 
												Flash::error("Error guardando iten de factura: ".$message); 
											}
										//cierra foreach	
									}
									
							  }else{
								Flash::error("PRODUCTO YA AGREADO A ESTA FACTURA...");
							  }
							  
							  //$est = new EstadoCuenta();
							  $est = $this->EstadoCuenta->findFirst(" factura_id = '$id' and anulado = 0 ");
							  $est->setTransaction($transaction);
							  $sum = 0;
							  if( $this->DetalleFactura->count("anulado = 0 and factura_id = '$id' ") > 0 ){ $sum = $this->DetalleFactura->sum("total","conditions: anulado = 0 and factura_id = '$id' "); }
							  $est->total               = $sum;
							  
							   if($est->save()==false){
									//abre for each
									foreach($est->getMessages() as $message){ 
										Flash::error("ERROR ACTUALIZANDO ESTADO DE CUENTA: ".$message); 
									}
									//cierra foreach
									
								$transaction->rollback();
							}
							
							  //$cxc = new Cxc();
							  $cxc = $this->Cxc->findFirst("  id = '$cxc_id' and anulado = 0 ");
							  $cxc->setTransaction($transaction);
							  $cxc->facturado = 1;
							  
							   if($cxc->save()==false){
									//abre for each
									foreach($cxc->getMessages() as $message){ 
										Flash::error("ACTUALIZANDO CUENTAS POR COBRAR: ".$message); 
									}
									//cierra foreach
									
								$transaction->rollback();
							}
									
							  
						$transaction->commit();
						Flash::success("FACTURA MODIFICADA SATISFACTORIAMENTE");	
						
							
						}catch(TransactionFailed $e){		
						
							Flash::error($e->getMessage());
								
						
						//cierre cacth try
						}
						
					
						  $est = $this->EstadoCuenta->findFirst(" factura_id = '$id' and anulado = 0 ");
						  //$est->setTransaction($transaction);
						  $sum = 0;
						  if( $this->DetalleFactura->count("anulado = 0 and factura_id = '$id' ") > 0 ){ $sum = $this->DetalleFactura->sum("total","conditions: anulado = 0 and factura_id = '$id' "); }
						  $est->total               = $sum;
						  
						   if($est->save()==false){
								//abre for each
								foreach($est->getMessages() as $message){ 
									Flash::error("ERROR ACTUALIZANDO ESTADO DE CUENTA: ".$message); 
								}
								//cierra foreach
								
							//$transaction->rollback();
						}else{
								Flash::success("Estado Cuenta Actualizado Satisfactoriamente");
						}
						
				}
			
			 $dtf2 = $this->DetalleFactura->find("id_unico = '$id_unico'","order: $orderby");
			 $this->setParamToView("detalles", $dtf2);
		
		}
		
		
		
		public function delete_detalle_producto_modAction(){
		
 			$this->setResponse('ajax');
			$dtf = new DetalleFacturaTmp();
			$dtf2 = new DetalleFacturaTmp();
			$id_unico     = $_REQUEST['id_unico'];
			$id     = $_REQUEST['id'];
			$idt     = $_REQUEST['idt'];
			$cxc_id     = $_REQUEST['cxc_id'];
			$det =  $this->Cxc->findFirst(" id = '$cxc_id' and anulado = 0 ");
			$sw=0;
			//Flash::error($this->Cxc->findFirst("id = '$cxc_id' ")->id);
			//Flash::error("$cxc_id");
			//Flash::error("$id_unico");
			
			if($this->DetalleRecibosCaja->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO RECIBOS DE CAJA A LA FACTURA"); $sw=1;
			}
			
			if($this->AnticiposFactura->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO ANTICIPOS A LA FACTURA"); $sw=1;
			}
			
			if($sw==0){  
				
					try{		
					
							 $transaction = TransactionManager::getUserTransaction(); 
							
							 // $dtf = new DetalleFactura();
							  $dtf = $this->DetalleFactura->findFirst("id = '$idt'");
							  $dtf->setTransaction($transaction);
							  $dtf->anulado      = 1;
							  if( !$dtf->save() ){
									//abre for each
										foreach($dtf->getMessages() as $message){ 
											Flash::error("Error ANULANDO item de factura: ".$message); 
										}
									//cierra foreach	
								}
							  
							 /* //$est = new EstadoCuenta();
							  $est = $this->EstadoCuenta->findFirst(" factura_id = '$id' and anulado = 0 ");
							  $est->setTransaction($transaction);
							  $sum = 0;
							  if( $this->DetalleFactura->count("anulado = 0 and factura_id = '$id' ") > 0 ){ $sum = $this->DetalleFactura->sum("total","conditions: anulado = 0 and factura_id = '$id' "); }
							  $est->total               = $sum; 
							  
							   if($est->save()==false){
									//abre for each
									foreach($est->getMessages() as $message){ 
										Flash::error("ERROR ACTUALIZANDO ESTADO DE CUENTA: ".$message); 
									}
									//cierra foreach
									
								$transaction->rollback();
							}*/
							
							  //$cxc = new Cxc();
							  $cxc = $this->Cxc->findFirst("  id = '$cxc_id' and anulado = 0 ");
							  $cxc->setTransaction($transaction);
							  $cxc->facturado = 0;
							  
							   if($cxc->save()==false){
									//abre for each
									foreach($cxc->getMessages() as $message){ 
										Flash::error("ACTUALIZANDO CUENTAS POR COBRAR: ".$message); 
									}
									//cierra foreach
									
								$transaction->rollback();
							}
									
							  
						$transaction->commit();
						Flash::success("Factura actualizada correctamente");
							
						}catch(TransactionFailed $e){		
						
							Flash::error($e->getMessage());
								
						
						//cierre cacth try
						}
						
						
						$est = $this->EstadoCuenta->findFirst(" factura_id = '$id' and anulado = 0 ");
						  //$est->setTransaction($transaction);
						  $sum = 0;
						  if( $this->DetalleFactura->count("anulado = 0 and factura_id = '$id' ") > 0 ){ $sum = $this->DetalleFactura->sum("total","conditions: anulado = 0 and factura_id = '$id' "); }
						  $est->total               = $sum;

						  
						   if($est->save()==false){
								//abre for each
								foreach($est->getMessages() as $message){ 
									Flash::error("ERROR ACTUALIZANDO ESTADO DE CUENTA: ".$message); 
								}
								//cierra foreach
								
							//$transaction->rollback();
						}else{
								Flash::success("Estado Cuenta Actualizado Satisfactoriamente");
						}
						
				}
			
			 $dtf2 = $this->DetalleFactura->find("id_unico = '$id_unico'","order: $orderby");
			 $this->setParamToView("detalles", $dtf2);
		
		}
		
		
		/* trae productos de cxc a la factura */
		public function trae_productos_modAction(){
		
 			$this->setResponse('ajax');

			$clientes_id  = $_REQUEST['clientes_id'];
			$direccion_id = $_REQUEST['direccion_id'];
			$empresa_id   = $_REQUEST['empresa_id'];
			$id_unico     = $_REQUEST['id_unico'];
			$orderby      =  'id';
			
			$det = new Cxc();
			
			$detalles = $det->find("clientes_id = '$clientes_id' and direccion_id = '$direccion_id' and empresa_id = '$empresa_id' and facturado =  0 and anulado = 0","order: $orderby");
			
			 $this->setParamToView("detalles", $detalles);	
		
		}
		
		
		public function updateAction(){
		
			$this->setResponse('view');
			
			$id          = $_REQUEST["id"];
			$fecha       = $_REQUEST["fecha"];
			$hora        = $_REQUEST["hora"];
			$anombre     = $_REQUEST["anombre"];
			$anit        = $_REQUEST["anit"];
			$observacion = $_REQUEST["observacion"];
			
			$sw=0;
			$fact = $this->Factura->findFirst("id = '$id'");
			
			if($this->DetalleRecibosCaja->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO RECIBOS DE CAJA A LA FACTURA"); $sw=1;
			}
			
			if($this->AnticiposFactura->count(" anulado = 0 and factura_id = '$id' ") > 0){
				Flash::error("NO SE PUEDE MODIFICAR ESTA FACTURA POR QUE YA SE HAN HECHO ANTICIPOS A LA FACTURA"); $sw=1;
			}
			
			//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
				
				$vencimiento = new Date($fecha);
				$vencimiento->addDays(30);
				
				$fact->anit = $_REQUEST['anit'];
				$fact->anombre = $_REQUEST['anombre'];
				$fact->fecha = $fecha;
				$fact->vencimiento = $vencimiento;
				$fact->fecha_act = date("Y-m-d H:i:s");
				//$fact->hora_act = date("H:i:s");
				//$fact->activo=0;
				$fact->anulado='0';
				$fact->observaciones=$observacion;
				//Flash::error(print_r($fact));
				//Flash::error($fact->id);
				//abre si
				if($fact->save()==false){
					
					//abre for each
					foreach($fact->getMessages() as $message){ 
						
						Flash::error($message); 
						
					}
					//cierra foreach
				//cierra if
				}else{
					Flash::success("FACTURA ACTUALIZADA SATISFACTORIAMENTE");
					echo "<script>/*alert('REMISION GUARDADA SATISFACTORIAMENTE');*/
						document.location.replace('".core::getInstancePath()."facturacion/show/$fact->id');</script>";
					
				}
				
				
			} //fin todo bien
			
		}	
		
		public function eliminarAction(){
		
			$this->setResponse('view');
			
			$id          = $_REQUEST["id"];
			
			//$sr = new Cxc();
			$dr = new DetalleFactura();
			$drt = new DetalleFacturaTmp();
			$sw=0;
			
			$drt->deleteAll();
			
			//Flash::error(" SALDO REMISIONES ".$sr->count(" remisiones_id = '$id' and anulado = '0'"));	 
			/*if( $sr->count(" devoluciones_id = '$id' and anulado = '0'") > 0 ){
				$sw=1; 
				Flash::error("TODAVIA HAY SALDOS DE ESTA DEVOLUCION ACTIVOS SIN ANULAR POR FAVOR ANULARLOS TODOS...");
			}*/
			//Flash::error(" DETALLE REMISIONES ".$dr->count(" remisiones_id = '$id' and anulado = '0'"));	
			if( $dr->count(" factura_id = '$id' and anulado = '0' ") > 0 ){
				$sw=1; 
				Flash::error("TODAVIA HAY SALDOS DE ESTA DEVOLUCION ACTIVOS SIN ANULAR POR FAVOR ANULARLOS TODOS...");
			}
			
		
			if($this->DetalleFactura->count(" factura_id = '$id' and anulado = '0' ") > 0 ){
					Flash::error("NO SE PUEDE ELIMINAR LA DEVOLUCION PORQUE HAY ARTICULOS DEVUELTOS ANTERIORMENTE...");
					$sw=1;
			}
							
			
			//Flash::error("sw ".$sw);
			
			//si no hay error de valiaciones o cualquier otra novedad
			if($sw==0){
				
				$transaction = new ActiveRecordTransaction(true);   
		
						try{
						
							  $sald = new EstadoCuenta();
							  $sald->setTransaction($transaction);
							
							   if($sald->deleteAll(" factura_id = $id ")==false){
								//abre for each
								foreach($sald->getMessages() as $message){ 
									Flash::error($message); 
								}
								//cierra foreach
								$transaction->rollback();
								}//cierra actualizacion de tabla de Saldos de Remisiones
							
															
							  $rtmp = new DetalleFactura();
							  $rtmp->setTransaction($transaction);
							  
							  if($rtmp->deleteAll(" factura_id = $id ")==false){
									//abre for each
									foreach($rtmp->getMessages() as $message){ 
										Flash::error($message); 
									}
									//cierra foreach
								$transaction->rollback();
								}//cierra actualizacion de tabla de Saldos de Remisiones
								
								
							  
								
								$rem = new Factura();
							    $rem->setTransaction($transaction);
							
							   if($rem->deleteAll(" id = $id ")==false){
								//abre for each
								foreach($rem->getMessages() as $message){ 
									Flash::error($message); 
								}
								//cierra foreach
								$transaction->rollback();
								}//cierra actualizacion de tabla de Saldos de Remisiones
								
									
							$transaction->commit();
							Flash::success("FACTURA BORRADA SATISFACTORIAMENTE");	
							print("<script>document.location.replace('".core::getInstancePath()."menu/');</script>");
							}catch(TransactionFailed $e){		
					
								Flash::error($e->getMessage());
									
						//cierre cacth try
						}	
				
			} //fin todo bien
			
		}	
		

	}
	
?>
