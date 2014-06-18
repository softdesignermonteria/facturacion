<?php
			
	class ComprasController extends ApplicationController {
		
		
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
						$det= new DetalleConsecutivos();
						$cons = $det->findFirst("tipo_documento_id = '$this->tipo_documento' and empresa_id = '".Session::get('id_empresa')."' and activo = '0' ");
						if(!$cons){
							//Flash::error("tipo de documento no existe en la bd - Consecutivos del Sistema o no se la ha Asignado un Consecutivo valido");
							Flash::error("NO SE ENCONTRO UN CONSECUTIVO VALIDO O NO EXISTE CONSECUTIVO PARA ESTE MODULO");
							Flash::error("esta accion es solo la pueden hacer los administradores del sistema");
							//$this->redirect("administrador/index");
							Router::routeTo("controller: menu", "action: index");
							return false;
						}else{
							//cargando las variables globales para este controlador
							$this->prefijo = $cons->prefijo;
							$this->id_consecutivo = $cons->id;
						}//fin si consecutivo no existe
				}//fin si o no existe docuemnto
			} //fin si comprobacion action = agregar
		} //FIN BEFORFILTER
		
		
			
		public function indexAction(){

		}
		
		
			
		
		public function find_detalle_buscarAction(){
			$this->setResponse("ajax");
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
			
			
			
			$subtotal  = $_REQUEST["tmp_valor"] ;  //subtotal iva incluido antes de descuento
			$descuento = $_REQUEST["tmp_valor"] * ( $des/100 );
			
			$responce["id"]             = $id;
			$responce["kardex_id"]      = $_REQUEST["tmp_kardex_id"];
			$responce["referencia"]     = $_REQUEST["tmp_referencia"];	
			$responce["nombre"]         = $_REQUEST["tmp_nombre"];	
			$responce["cantidad"]       = $_REQUEST["tmp_cantidad"];	
			//$responce["valor"]          = $subtotal  * $_REQUEST["tmp_cantidad"] ;	
			$responce["valor"]          = $subtotal;	
			$responce["descuento"]      = $des;	
			//$responce["total_descuento"] = $descuento * $_REQUEST["tmp_cantidad"];	
			$responce["total_descuento"] = $descuento;	
			$responce["tarifa_iva_id"]  = $ti->id;	
			//$responce["iva"]            = ( $subtotal - $descuento ) * ($ti->valor/100) * $_REQUEST["tmp_cantidad"];	
			$responce["iva"]            = ( $subtotal - $descuento ) * ($ti->valor/100);	
			$responce["valor_iva"]      = $ti->valor;
			$responce["total"]          = ($responce["valor"] - $responce["total_descuento"]) + $responce["iva"] ;	
			$responce["anulado"]        = "NO";
			
			echo json_encode($responce);
				
				//detalles.push({id:1,referencia:123,nombre:"prueba",costo:10000,tarifaiva:16,iva:16000,descuento:20000,porc_desc:20,total:80000});
				
			//}

			
		}
		
		public function agregar_item_updateAction(){
			$this->setResponse("ajax");
			$kardex = $this->Kardex->findFirst("id = '".$_REQUEST["kardex_id"]."' ");
			$responce["id"]              = $_REQUEST["id"];
			$responce["kardex_id"]       = $_REQUEST["kardex_id"];
			$responce["referencia"]      = $_REQUEST["referencia"];	
			$responce["nombre"]          = $_REQUEST["nombre"];	
			$responce["cantidad"]        = $_REQUEST["cantidad"];	
			$responce["valor"]           = $_REQUEST["valor"];	
			$responce["descuento"]       = $_REQUEST["descuento"];	
			$responce["total_descuento"] = $_REQUEST["total_descuento"];	
			$responce["tarifa_iva_id"]   = $_REQUEST["tarifa_iva_id"];	
			$responce["iva"]             = $_REQUEST["iva"];	
			$responce["valor_iva"]       = $_REQUEST["valor_iva"];
			$responce["total"]           = $_REQUEST["total"] ;	
			$responce["anulado"]         = $_REQUEST["anulado"];
			
			echo json_encode($responce);
				
				//detalles.push({id:1,referencia:123,nombre:"prueba",costo:10000,tarifaiva:16,iva:16000,descuento:20000,porc_desc:20,total:80000});
				
			//}

			
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
					
							$compras = new Compras();
							$compras->setTransaction($transaction);

							 $compras->id                  = $_REQUEST["id"];
							 $compras->prefijo             = $prefijo;
							 $compras->consecutivo         = $consecutivo;   
							 $compras->proveedores_id      = $_REQUEST["proveedores_id"];
							 $compras->bodegas_id          = $_REQUEST["bodegas_id"];
							 $compras->empresa_id          = $_REQUEST["empresa_id"];
							 $compras->tipo_documento_id   = $tipo_documento_id;
							 $compras->fecha_act           = date("Y-m-d H:i:s");
							 $compras->fecha               = $_REQUEST["fecha"];
							 $compras->vencimiento         =  $_REQUEST["vencimiento"];
							 $compras->incluye_iva         =  0;
							 $compras->factura             =  $_REQUEST["factura"];
							 
							 $compras->fecha_registro        = date("Y-m-d H:i:s");
							 $compras->admin_id              = $_REQUEST["admin_id"];
							 $compras->fecha_act             = date("Y-m-d H:i:s");
							 $compras->ip                    =  $_SERVER['REMOTE_ADDR'];
							 
							 $compras->subtotal              = $_REQUEST["subtotal"];
							 $compras->descuento             = $_REQUEST["descuento"];
							 $compras->iva                   = $_REQUEST["iva"];
							 $compras->total                 = $_REQUEST["total"];
							 
							 $compras->anulado             = '0';
							
							if($compras->save()==false){
								foreach($compras->getMessages() as $message){ 
									Flash::error("TABLA FACTURA: ".$message); 
								}
								$transaction->rollback();
							}
						

							$detalles = new DetalleCompras();
							$detalles->setTransaction($transaction);
							$total = 0;
							$detalles_item = str_replace("]\"","]",str_replace("\"[","[",str_replace("\\","",$_POST["detalles"])));
							if(json_decode($detalles_item)){
								Flash::success("Json Correcto");
								$detalles_item = json_decode($detalles_item);
								//foreach($detalles_item as $dt):
									//print($dt->id);
									//echo "<br />";
								//endforeach;
							}else{
								Flash::error("Error json");
								$transaction->rollback();
							}
							//print_r($detalles_item);
							foreach( $detalles_item as $items):
									$detalles = new DetalleCompras();
									$detalles->setTransaction($transaction);
									//Flash::error(substr($items->id,0,4));
								
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$detalles->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$detalles->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$detalles->kardex_id              = $items->kardex_id;
									$detalles->compras_id     		  = $compras->id;
									$detalles->tarifa_iva_id     	  = $items->tarifa_iva_id;
									$detalles->valor                  = $items->valor;
									$detalles->cantidad               = $items->cantidad;
									$detalles->descuento              = $items->descuento;
									$detalles->total_descuento        = $items->total_descuento;
									$detalles->iva                    = $items->iva;
									$detalles->total                  = $items->total;
									if($items->anulado == "SI") {$detalles->anulado = '1';}
									if($items->anulado == "NO") {$detalles->anulado = '0';}
									//$detalles->anulado                = $items->anulado;
									$total += $items->total;
									//Flash::error(print_r($detalles));
									if($detalles->save()==false){
										foreach($detalles->getMessages() as $message){ 
											Flash::error("TABLA: DETALLE COMPRAS ".$message); 
										}
										$transaction->rollback();
									}	
							endforeach; //cierre for each del detalles temporales de factura
								
				$transaction->commit();
				Flash::success("FACTURA DE COMPRA GUARDADA SATISFACTORIAMENTE");	
				echo "<script>alert('FACTURA DE COMPRA GUARDADA SATISFACTORIAMENTE');";
				echo "redireccionar_action('compras/show/?id=$compras->id');</script>";
				}catch(TransactionFailed $e){		
					Flash::error($e->getMessage());
					echo "<script>jQuery(\"#guardar\").removeAttr(\"disabled\");</script>";
					//cierre cacth try
				}
		  }//cierra if todo bien

		}
		
		
		
				
		public function modificarAction(){
			
			$id = $_REQUEST["id"];
			
			$encabezado = $this->Compras->findFirst("  id = '$id' ");
			$detalles   = $this->DetalleCompras->find(" compras_id = '$id' ");
			$empresa    = $this->Empresa->findFirst("  id = '$encabezado->empresa_id'  ");
			$admn       = $this->Admin->findFirst("    id = '$encabezado->admin_id'    ");
			$empleado   = $this->Empleado->findFirst(" id = '$admn->empleado_id' ");
			
			Tag::DisplayTo("id",$encabezado->id);
 			Tag::DisplayTo("tipo_documento_id",$encabezado->tipo_documento_id);
			//Tag::DisplayTo("detalle_consecutivos_id",$encabezado->detalle_consecutivos_id);
			
			Tag::DisplayTo("empresa_id",$encabezado->empresa_id);
			Tag::DisplayTo("prefijo2",$encabezado->prefijo);
			Tag::DisplayTo("consecutivo",$encabezado->consecutivo);
			Tag::DisplayTo("nombre_empresa",$empresa->nombre_empresa);
			
			Tag::DisplayTo("admin_id",$encabezado->admin_id);
			Tag::DisplayTo("nombre_empleado",$empleado->nombre_completo);
			
			//Tag::DisplayTo("centro_produccion_id",$encabezado->centro_produccion_id);
			Tag::DisplayTo("bodegas_id",$encabezado->bodegas_id);
			Tag::DisplayTo("proveedores_id",$encabezado->proveedores_id);
			Tag::DisplayTo("fecha",$encabezado->fecha);
			Tag::DisplayTo("vencimiento",$encabezado->vencimiento);
			
			Tag::DisplayTo("factura",$encabezado->factura);
			
			Tag::DisplayTo("subtotal",$encabezado->subtotal);
			Tag::DisplayTo("descuento",$encabezado->descuento);
			Tag::DisplayTo("iva",$encabezado->iva);
			Tag::DisplayTo("total",$encabezado->total);
			
			$this->setParamToView("detalles",$detalles);
			
			//$this->setParamToView("centro_produccion_id",$encabezado->centro_produccion_id);
			$this->setParamToView("bodegas_id",$encabezado->bodegas_id);
			$this->setParamToView("proveedores_id",$encabezado->proveedores_id);
			
			
 
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
						
							 $compras = new Compras();
							 $compras->setTransaction($transaction);

							 $compras->id                  = $_REQUEST["id"];
							 $compras->prefijo             = $prefijo;
							 $compras->consecutivo         = $consecutivo;   
							 $compras->proveedores_id      = $_REQUEST["proveedores_id"];
							 $compras->bodegas_id          = $_REQUEST["bodegas_id"];
							 $compras->empresa_id          = $_REQUEST["empresa_id"];
							 $compras->tipo_documento_id   = $tipo_documento_id;
							 $compras->fecha_act           = date("Y-m-d H:i:s");
							 $compras->fecha               = $_REQUEST["fecha"];
							 $compras->vencimiento         =  $_REQUEST["vencimiento"];
							 $compras->incluye_iva         =  0;
							 $compras->factura             =  $_REQUEST["factura"];
							 
							 $compras->fecha_registro        = date("Y-m-d H:i:s");
							 $compras->admin_id              = $_REQUEST["admin_id"];
							 $compras->fecha_act             = date("Y-m-d H:i:s");
							 $compras->ip                    =  $_SERVER['REMOTE_ADDR'];
							 
							 $compras->subtotal              = $_REQUEST["subtotal"];
							 $compras->descuento             = $_REQUEST["descuento"];
							 $compras->iva                   = $_REQUEST["iva"];
							 $compras->total                 = $_REQUEST["total"];
							 
							 $compras->anulado             = '0';
							
							if($compras->save()==false){
								foreach($compras->getMessages() as $message){ 
									Flash::error("TABLA FACTURA: ".$message); 
								}
								$transaction->rollback();
							}
						

							$detalles = new DetalleCompras();
							$detalles->setTransaction($transaction);
							$total = 0;
							$detalles_item = str_replace("]\"","]",str_replace("\"[","[",str_replace("\\","",$_POST["detalles"])));
							if(json_decode($detalles_item)){
								Flash::success("Json Correcto");
								$detalles_item = json_decode($detalles_item);
								//foreach($detalles_item as $dt):
									//print($dt->id);
									//echo "<br />";
								//endforeach;
							}else{
								Flash::error("Error json");
								$transaction->rollback();
							}
							//print_r($detalles_item);
							foreach( $detalles_item as $items):
									$detalles = new DetalleCompras();
									$detalles->setTransaction($transaction);
									//Flash::error(substr($items->id,0,4));
								
									if( trim(substr($items->id,0,4)) == trim('temp') ){
										$detalles->id                     = '';
										//Flash::error(substr($items->id,0,4));
									}else{
										$detalles->id                     = $items->id;
										//Flash::notice(substr($items->id,0,4));
										}
									$detalles->kardex_id              = $items->kardex_id;
									$detalles->compras_id     		  = $compras->id;
									$detalles->tarifa_iva_id     	  = $items->tarifa_iva_id;
									$detalles->valor                  = $items->valor;
									$detalles->cantidad               = $items->cantidad;
									$detalles->descuento              = $items->descuento;
									$detalles->total_descuento        = $items->total_descuento;
									$detalles->iva                    = $items->iva;
									$detalles->total                  = $items->total;
									if($items->anulado == "SI") {$detalles->anulado = '1';}
									if($items->anulado == "NO") {$detalles->anulado = '0';}
									//$detalles->anulado                = $items->anulado;
									$total += $items->total;
									//Flash::error(print_r($detalles));
									if($detalles->save()==false){
										foreach($detalles->getMessages() as $message){ 
											Flash::error("TABLA: DETALLE COMPRAS ".$message); 
										}
										$transaction->rollback();
									}	
							endforeach; //cierre for each del detalles temporales de factura
								
				$transaction->commit();
				Flash::success("FACTURA DE COMPRA MODIFICADA SATISFACTORIAMENTE");	
				echo "<script>alert('FACTURA DE COMPRA MODIFICADA SATISFACTORIAMENTE');";
				echo "redireccionar_action('compras/show/?id=$compras->id');</script>";
				}catch(TransactionFailed $e){		
					Flash::error($e->getMessage());
					//cierre cacth try
				}
		  }//cierra if todo bien

		}
		
		
		public function showAction($id){
		
			 
			$id = $_REQUEST["id"];
			
			$encabezado = $this->Compras->findFirst("  id = '$id' ");
			$detalles   = $this->DetalleCompras->find(" compras_id = '$id' ");
			$empresa    = $this->Empresa->findFirst("  id = '$encabezado->empresa_id'  ");
			$admn       = $this->Admin->findFirst("    id = '$encabezado->admin_id'    ");
			$empleado   = $this->Empleado->findFirst(" id = '$admn->empleado_id' ");
			
			Tag::DisplayTo("id",$encabezado->id);
 			Tag::DisplayTo("tipo_documento_id",$encabezado->tipo_documento_id);
			//Tag::DisplayTo("detalle_consecutivos_id",$encabezado->detalle_consecutivos_id);
			
			Tag::DisplayTo("empresa_id",$encabezado->empresa_id);
			Tag::DisplayTo("prefijo2",$encabezado->prefijo);
			Tag::DisplayTo("consecutivo",$encabezado->consecutivo);
			Tag::DisplayTo("nombre_empresa",$empresa->nombre_empresa);
			
			Tag::DisplayTo("admin_id",$encabezado->admin_id);
			Tag::DisplayTo("nombre_empleado",$empleado->nombre_completo);
			
			//Tag::DisplayTo("centro_produccion_id",$encabezado->centro_produccion_id);
			Tag::DisplayTo("bodegas_id",$encabezado->bodegas_id);
			Tag::DisplayTo("proveedores_id",$encabezado->proveedores_id);
			Tag::DisplayTo("fecha",$encabezado->fecha);
			Tag::DisplayTo("vencimiento",$encabezado->vencimiento);
			
			Tag::DisplayTo("factura",$encabezado->factura);
			
			Tag::DisplayTo("subtotal",$encabezado->subtotal);
			Tag::DisplayTo("descuento",$encabezado->descuento);
			Tag::DisplayTo("iva",$encabezado->iva);
			Tag::DisplayTo("total",$encabezado->total);
			
			$this->setParamToView("detalles",$detalles);
			
			//$this->setParamToView("centro_produccion_id",$encabezado->centro_produccion_id);
			$this->setParamToView("bodegas_id",$encabezado->bodegas_id);
			$this->setParamToView("proveedores_id",$encabezado->proveedores_id);			
 			
		}
	
				
		
		
		
		
		public function buscarAction(){
			
		}
		
			
		public function detalle_buscarAction(){
			
			$this->setResponse("ajax");
			
			$condicion1 = ' 1 = 1 ';
			
			if( $_REQUEST["fecha_inicio"]!=''   ){ $condicion1 .= " and fecha >= '".$_REQUEST["fecha_inicio"]."' "; }
			if( $_REQUEST["fecha_fin"]!=''      ){ $condicion1 .= " and fecha <= '".$_REQUEST["fecha_fin"]."' "; }
			if( $_REQUEST["factura"]!=''        ){ $condicion1 .= " and factura like '".$_REQUEST["factura"]."%' "; }
			if( $_REQUEST["proveedores_id"]!='' ){ $condicion1 .= " and proveedor_id = '".$_REQUEST["proveedor_id"]."' "; }
			
				$this->setParamToView( "detalles" , $this->Compras->find( $condicion1 ) );
			
			
		}
		
		

		
		

	}
	
?>
