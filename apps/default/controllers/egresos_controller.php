<?php

	class egresosController extends ApplicationController {
	
		
		public $prefijo;
		public $tipo_documento;
		public $tipo_documento_nombre;
		public $id_consecutivo;
		
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		   // $this->setTemplateAfter("menu_azul");
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
					Router::routeTo("controller: ".Router::getController()."", "action: index");
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
					Router::routeTo("controller: ".Router::getController()."", "action: index");
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
				
		
		
		/****
			agregarAction vista en la cual se mostrara el 
			formulario para agregar clientes
		***/
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
						
								$encabezado = new Egresos();
								$encabezado->setTransaction($transaction);
	
								 $encabezado->id                  = $_REQUEST["id"];
								 $encabezado->prefijo             = $prefijo;
								 $encabezado->consecutivo         = $consecutivo;   
								 $encabezado->proveedores_id      = $_REQUEST["proveedores_id"];
								 $encabezado->bancos_id           = $_REQUEST["bancos_id"];
								 $encabezado->forma_pago_id       = $_REQUEST["forma_pago_id"];
								 $encabezado->empresa_id          = $_REQUEST["empresa_id"];
								 $encabezado->tipo_documento_id   = $tipo_documento_id;
								 $encabezado->fecha_act           = date("Y-m-d H:i:s");
								 $encabezado->fecha               = $_REQUEST["fecha"];
								 $encabezado->hora                =  $_REQUEST["hora"];
								 $encabezado->hora_act            =  date("H:m:s");
								 $encabezado->anulado             = $_REQUEST["anulado"];
								
								if($encabezado->save()==false){
									foreach($encabezado->getMessages() as $message){ 
										Flash::error("TABLA FACTURA: ".$message); 
									}
									$transaction->rollback();
								}
							
	
								$detalles = new DetalleEgresos();
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
										$detalles = new DetalleEgresos();
										$detalles->setTransaction($transaction);
										//Flash::error(substr($items->id,0,4));
									
										if( trim(substr($items->id,0,4)) == trim('temp') ){
											$detalles->id                     = '';
											//Flash::error(substr($items->id,0,4));
										}else{
											$detalles->id                     = $items->id;
											//Flash::notice(substr($items->id,0,4));
											}
										$detalles->cxp_id                 = $items->cxp_id;
										$detalles->egresos_id             = $encabezado->id;
										$detalles->conceptos_id           = 0;
										$detalles->concepto               = $items->concepto;
										$detalles->total               = $items->valor;
										if($items->multiplica == "SUMA") {$detalles->multiplica = '1';}
										if($items->multiplica == "RESTA") {$detalles->multiplica = '-1';}
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
					Flash::success("EGRESO GUARDADO SATISFACTORIAMENTE");	
					echo "<script>alert('EGRESO GUARDADO SATISFACTORIAMENTE');";
					echo "redireccionar_action('egresos/agregar/?id=$encabezado->id&msgconfirm=true');</script>";
					}catch(TransactionFailed $e){		
						Flash::error($e->getMessage());
						//cierre cacth try
					}
			  }//cierra if todo bien
		}
		
		
		public function agregar_detalles_egresosAction(){
			
			
			$this->setResponse('view');
			
			$dtegresos  = new DetalleEgresosTmp();
			
			//Flash::success(print_r($bancos));
			$dtegresos->id = '0';
			$dtegresos->id_unico = $_REQUEST['id_unico'];
			$dtegresos->egresos_id = 0;
			$dtegresos->anulado = 0;
			$dtegresos->total = $_REQUEST['valor'];
			$dtegresos->concepto = $_REQUEST['concepto'];
						
			//Flash::success(print_r($dtegresos));		
					
			if($dtegresos->save()){
				
				Flash::success("Detalles Agregados y Guardados Satisfactoriamente");
				

			}else{
			
				Flash::error("Error: No se pudo Guardar el registro...");	
				
				foreach($dtegresos->getMessages() as $message){
				Flash::error($message->getMessage());
				
				}	  
				
			}
			$this->setParamToView("dtegresos",$this->DetalleEgresosTmp->find(" id_unico = '".$_REQUEST['id_unico']."' "));
					
	    }
		
		
		
		public function deleteAction(){
			
			
			
			$this->setResponse('view');
			
			$dtegresos  = new DetalleEgresosTmp();
			
			if($dtegresos->delete(" id = '".$_REQUEST["id"]."' ")){
				
				Flash::success("Detalle egreso Eliminado Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Eliminar y Guardar el registro...");	
				
				foreach($dtegresos->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
			
			$this->setParamToView("dtegresos",$this->DetalleEgresosTmp->find("id_unico = '".$_REQUEST['id_unico']."' "));
			
					
	    }
		
		
		
		public function showAction($id){
		
			 $this->setParamToView("encabezado",$this->Egresos->findFirst("id = '$id' "));
			 $this->setParamToView("idt",$id);								
			 $this->setParamToView("detalles",$this->DetalleEgresos->find("egresos_id = '$id' and anulado = 0"));				
 			
		}
		
		
		
		
		public function buscarAction(){
			
		}
		
		
		public function detalle_buscarAction(){
				
				$this->setResponse('ajax');
				
				if( isset( $_REQUEST['razon_social']   )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and {#Proveedores}.razon_social like '%".str_replace(' ',"%",$_REQUEST['razon_social'])."%'"; } }
				//if( isset( $_REQUEST['direccion']      )==true ) { if( $_REQUEST['direccion'] != ''      ) { $sql .= " and {#Direccion}.direccion like '%".str_replace(' ',"%",$_REQUEST['direccion'])."%'";           } }
				if( isset( $_REQUEST['finicial']       )==true ) { if( $_REQUEST['finicial'] != ''       ) { $sql .= " and {#Egresos}.fecha >=  '".$_REQUEST['finicial']."'";           } }
				if( isset( $_REQUEST['ffinal']         )==true ) { if( $_REQUEST['ffinal'] != ''         ) { $sql .= " and {#Egresos}.fecha <=  '".$_REQUEST['ffinal']."'";           } }
				$empresa_id  = $_REQUEST["empresa_id"];
			
				$query = new ActiveRecordJoin(array(
						"entities" => array("Egresos","Proveedores","Empresa"),
					 	"fields"=>array(
										"{#Egresos}.id",
										"{#Egresos}.prefijo",
										"{#Egresos}.consecutivo",
										"{#Egresos}.fecha",
										"{#Egresos}.proveedores_id",
										"{#Proveedores}.razon_social",
										"{#Proveedores}.direccion_casa"
										),
						"conditions"=>(" {#Egresos}.empresa_id = '$empresa_id' $sql ")
				));
				
				//$clientes = $this->Clientes->findAllBySql($sql);
				//Flash::error($query->getSqlQuery());
				$this->setParamToView('detalles',$query->getResultSet());
				//$this->setParamToView('query',$query);
				//$this->setParamToView('query2',"hola");
				
				
				
		}
		
		
		public function modificarAction(){
					
        }
		
		
	
	  
   }
?>