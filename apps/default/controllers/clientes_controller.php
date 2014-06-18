<?php

	class ClientesController extends ApplicationController {
	
		
		
		
	
		public function initialize() {
		   //$this->setTemplateAfter("a_bit_boxy");
		   // $this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}
		
		
		
		 



		public function indexAction(){
		
		}
				
				
		public function traer_municipiosAction($id){
		      
			$this->setResponse('ajax');  //ajax o view da los mismo
			$mun = $this->Municipios->find("departamentos_id='$id'");
			$this->setParamToView("municipios",$mun);
			  
		}
		
		
		/****
			agregarAction vista en la cual se mostrara el 
			formulario para agregar clientes
		***/
		public function agregarAction(){
					
        }
		
		public function find_buscarAction(){
			$this->setResponse("ajax");
		}
		
		public function find_detalle_buscarAction(){
			$this->setResponse("ajax");
		}
		
		
	
		
		public function validarAction($id,$opcion){

			$this->setResponse("view");
			
			$sw=0;
			$cli = new Clientes();
			if( $this->Clientes->count(" 1=1 and (id = '$id' or nit ='$id') ") > 0 ){
				$cli = $this->Clientes->findFirst(" 1=1 and (id = '$id' or nit ='$id')  ");
				echo "<script>jQuery(\"#$opcion\").val(\"\");jQuery(\"#".Router::getController()."_id\").val(\"$cli->id\");jQuery(\"#clientes\").val(\"$cli->razon_social\");</script>";
			}else{
				Flash::error("No se encontró cliente");
				echo "<script>jQuery(\"#$opcion\").val(\"\");</script>";
				echo "<script>jQuery(\"#".Router::getController()."_id\").val(\"\");</script>";
				}
			//public $scaffold = false;
		}
		
		/****
			addAction metodo en la cual se insertarán
			los datos del cliente
		***/
		public function addAction(){
			//primero instanciamos clase clientes
			$this->setResponse('view');
			$cli  = new Clientes();
			//cargamos el objeto mediantes los metodos setters
			
			$sw = 0;
			if($this->Clientes->count("nit = '".$this->getPostParam("nit")."'")>0){
				Flash::error("DOCUMENTO DE IDENTIFICACION (NIT/CEDULA) YA SE EXISTE EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
				$cli->id = '0';
				$cli->nit = $this->getPostParam("nit");
				$cli->nombre1 = $this->getPostParam("nombre1");
				$cli->nombre2 = $this->getPostParam("nombre2");
				$cli->apellido1 = $this->getPostParam("apellido1");
				$cli->apellido2 = $this->getPostParam("apellido2");
				$cli->razon_social = $this->getPostParam("razon_social");
				$cli->direccion_casa = $this->getPostParam("direccion_casa");
				$cli->direccion_oficina = $this->getPostParam("direccion_oficina");
				$cli->telefono1 = $this->getPostParam("telefono1");
				$cli->telefono2 = $this->getPostParam("telefono2");
				$cli->celular = $this->getPostParam("celular");
				//$cli->departamentos_id = $this->getPostParam("dptos");
				$cli->departamentos_id = $this->getPostParam("departamentos_id");
				$cli->municipios_id = $this->getPostParam("municipios_id");
				$cli->activo = $this->getPostParam("activo");
				$cli->correo = $this->getPostParam("email");
				$cli->web = $this->getPostParam("web");
				$cli->activo = 0;
						
				if($cli->save()){
					Flash::success("Se insertó correctamente el registro");
					print("<script>/*alert('Se insertó correctamente el registro');*/
								document.location.replace('".core::getInstancePath()."clientes/show/$cli->id');</script>");
				}else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($cli->getMessages() as $message){
							  Flash::error($message->getMessage());
						}
				}
			
			}//fin  si todo bien
			
	    }
		/****
			mostrarAction vista en la cual se mostrarán
			los datos del cliente
		***/
		public function showAction($id){
          
            $clientes = $this->Clientes->find("id='$id'");
            $this->setParamToView("clientes", $clientes);
	
		}
		/****
			modificarAction vista en la cual se mostrarán
			los datos del cliente
		***/
		public function modificarAction(){
			//$this->setResponse("ajax");
			$id= $_REQUEST['id'];
			
			if( isset($id) ){
					
				$id_clientes =$id;
						
				$cli = $this->Clientes->findFirst(" id = '$id_clientes' ");
				$dpto= $this->Departamentos->findFirst("id = '$cli->departamentos_id'");
				$mpio= $this->Municipios->findFirst("id = '$cli->municipios_id'");
							
				Tag::displayTo("id",$cli->id);
				Tag::displayTo("nit",$cli->nit);
				Tag::displayTo("nombre1",$cli->nombre1);
				Tag::displayTo("nombre2",$cli->nombre2);
				Tag::displayTo("apellido1",$cli->apellido1);
				Tag::displayTo("apellido2",$cli->apellido2);
				Tag::displayTo("razon_social",$cli->razon_social);
				Tag::displayTo("direccion_casa",$cli->direccion_casa);
				Tag::displayTo("direccion_oficina",$cli->direccion_oficina);
				Tag::displayTo("telefono1",$cli->telefono1);
				Tag::displayTo("telefono2",$cli->telefono2);
				Tag::displayTo("celular",$cli->celular);
				Tag::displayTo("departamentos",$cli->departamentos_id);
				Tag::displayTo("departamento",$dpto->departamento);
				Tag::displayTo("municipios",$cli->municipios_id);
				Tag::displayTo("municipio",$mpio->municipio);
				Tag::displayTo("email",$cli->correo);
				Tag::displayTo("web",$cli->web);
				
			}else{
					Flash::error("Parametro Incorrecto Volver a Buscar Ies para modificar.");
				}
		}
		/****
			actualizarAction metodo en el cual se actualizaran
			los datos del cliente
		***/
		public function actualizarAction($id){
			
			$this->setResponse('view');
			
			$clientes  = new Clientes();
			
			$cli = $clientes->findFirst("id = '$id'");
			//cargamos el objeto mediantes los metodos setters
			$cli->nit = $this->getPostParam("nit");
			//$cli->nombre1 = $this->getPostParam("pn");
			//$cli->nombre2 = $this->getPostParam("sn");
			//$cli->apellido1 = $this->getPostParam("pa");
			//$cli->apellido2 = $this->getPostParam("sa");
			$cli->nombre1 = '';
			$cli->nombre2 = '';
			$cli->apellido1 = '';
			$cli->apellido2 = '';
			$cli->razon_social = $this->getPostParam("rs");
			$cli->direccion_casa = $this->getPostParam("dc");
			$cli->direccion_oficina = $this->getPostParam("dof");
			$cli->telefono1 = $this->getPostParam("tf1");
			$cli->telefono2 = $this->getPostParam("tf2");
			$cli->celular = $this->getPostParam("celular");
			$cli->departamentos_id = $this->getPostParam("dptos");
			$cli->municipios_id = $this->getPostParam("municipios");
			$cli->activo = $this->getPostParam("activo");
			$cli->correo = $this->getPostParam("email");
			$cli->web = $this->getPostParam("web");
			$cli->activo = $this->getPostParam("activo");
					
			if($cli->save()){
				Flash::success("Se actualizo correctamente el registro");
				print("<script>document.location.replace('".core::getInstancePath()."clientes/show/$cli->id');</script>");
			}else{
				Flash::error("Error: No se pudo actualizar el registro");	
					foreach($cli->getMessages() as $message){
				          Flash::error($message->getMessage());
					}	  
			}
		}
		
		public function updateAction($id){
			
			$this->setResponse("ajax");
			
			$id_clientes=$_REQUEST['id'];

			$sw=0;
			
			$clientes = $this->Clientes->findFirst("id = '".$id_clientes."'");
			
			if($sw==1){
				
				Flash::error("Error: Cliente ya existe!!!");
			
			}else{
				
						
						$clientes = $this->Clientes->findFirst(" id = '".$id_clientes."' ");
						//$Usuario->setId($this->getPostParam("id"));
						$clientes->nit = $this->getPostParam("nit");
						$clientes->nombre1 = $this->getPostParam("nombre1");
						$clientes->nombre2 = $this->getPostParam("nombre2");
						$clientes->apellido1 = $this->getPostParam("apellido1");
						$clientes->apellido2 = $this->getPostParam("apellido2");
						$clientes->razon_social = $this->getPostParam("razon_social");
						$clientes->direccion_casa = $this->getPostParam("direccion_casa");
						$clientes->direccion_oficina = $this->getPostParam("direccion_oficina");
						$clientes->telefono1 = $this->getPostParam("telefono1");
						$clientes->telefono2 = $this->getPostParam("telefono2");
						$clientes->celular = $this->getPostParam("celular");
						$clientes->departamentos_id = $this->getPostParam("departamentos_id");
						$clientes->municipios_id = $this->getPostParam("municipios_id");
						$clientes->activo = 0;
						$clientes->correo = $this->getPostParam("email");
						$clientes->web = $this->getPostParam("web");
						
															
					if($clientes->save()){
							  Flash::success("Se Actualizo correctamente el registro");
							  /*echo "<script>redireccionar_action('menu');</script>";	*/
							   echo "<script>redireccionar_action('clientes/modificar/?id=$clientes->id');</script>";
							  
						}else{
							 
							 Flash::error("Error: No se pudo Actualizar el registro");	
							 /*echo "<script>redireccionar_action('menu');</script>";	*/
									
						}
				  }
			  		
		}
		
		
		public function clientes_con_equipoAction(){
		
			
			$sql = '';

			if( isset( $_REQUEST['empresa_id']   )==true ) { if( $_REQUEST['empresa_id'] != ''   ) { $sql .= " and {#SaldoRemisiones}.empresa_id like '%".$_REQUEST['empresa_id']."%'";                   } }	
			if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and {#Clientes}.nit like '".$_REQUEST['nit']."%'";                   } }
			if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and {#Clientes}.razon_social like '%".str_replace(' ',"%",utf8_decode($_REQUEST['razon_social']))."%'"; } }
			
			
			$query = new ActiveRecordJoin(array(
						"entities" => array("Clientes","SaldoRemisiones"),
						"groupFields" => array(
							"{#Clientes}.razon_social",
							"{#Clientes}.id",
							"{#Clientes}.nit",
							"{#SaldoRemisiones}.empresa_id"
						),
						"conditions" => "{#SaldoRemisiones}.anulado = 0 and {#Clientes}.activo = 0 and  ( {#SaldoRemisiones}.cantidad - {#SaldoRemisiones}.devueltos ) <> 0 $sql "
						
				));
				
				$this->setParamToView("detalles", $query->getResultSet());
				$this->setParamToView("sql", $query->getSqlQuery());
				//Flash::error($query->getSqlQuery());
		
		}
		
		
		public function clientes_sin_equipoAction(){
		
			$sql = '';

			if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and c1.nit like '".$_REQUEST['nit']."%'";                   } }
			if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and c1.razon_social like '%".str_replace(' ',"%",utf8_decode($_REQUEST['razon_social']))."%'"; } }
			
		
			$query = new ActiveRecordJoin(array(
				"entities"=> array("Clientes","SaldoRemisiones"),
				"groupFields"=> array(
							"{#Clientes}.id"
				),
				"conditions" => " {#SaldoRemisiones}.anulado = 0 and {#Clientes}.activo = 0 and ( {#SaldoRemisiones}.cantidad - {#SaldoRemisiones}.devueltos ) <> 0  "
			));
			
			$query2 = $query->getSQLQuery();
			$sql = "SELECT c1.* FROM clientes c1 WHERE c1.id not in ($query2) $sql order by c1.razon_social";
	
			$this->setParamToView("detalles", $this->Clientes->findAllBySql($sql) );
			//$this->setParamToView("sql", $query->getSqlQuery());
		
		}
		
		
		public function buscarAction(){
		
		}
		
		
		
		
		
		public function detalle_buscarAction(){
				
				$this->setResponse('view');
				
				//$nom1 = $_REQUEST['nom1'];
				//$nom2 = $_REQUEST['nom2'];
				//$ape1 = $_REQUEST['ape1'];
				//$ape2 = $_REQUEST['ape2'];
				
				
				$sql = ' 1=1 ';
				
				if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and nit like '%".$_REQUEST['nit']."%'";                   } }
				if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and razon_social like '%".str_replace(' ',"%",utf8_decode($_REQUEST['razon_social']))."%'"; } }
				$ordenar = $_REQUEST['ordenar'];
				
				//if($nom1!=''){ $sql .= " and nombre1 like '%$nom1%' "; }
				//if($nom2!=''){ $sql .= " and nombre2 like '%$nom2%' "; }
				//if($ape1!=''){ $sql .= " and apellido1 like '%$ape1%' "; }
				//if($ape2!=''){ $sql .= " and apellido2 like '%$ape2%' "; }
				
				
				$clientes = $this->Clientes->find(" $sql ","order: $ordenar");
          		$this->setParamToView("clientes", $clientes);
		
		}
		
		
		public function trae_clientesAction(){
			
			//$this->setResponse('ajax');
			$this->setTemplateAfter("default");

		}
		
		
		public function trae_clientes_detallesAction(){
			
			$this->setResponse('view');
			
			$sql = "select * from clientes where 1=1 and activo = 0 ";
			
			if( isset( $_REQUEST['codigo']       )==true ) { if( $_REQUEST['codigo'] != ''       ) { $sql .= " and id like '%".$_REQUEST['codigo']."%'";                 } }
			if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and nit like '%".$_REQUEST['nit']."%'";                   } }
			if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and razon_social like '%".str_replace(' ',"%",utf8_decode($_REQUEST['razon_social']))."%'"; } }
			//if( isset( $_REQUEST['nombre1']      )==true ) { if( $_REQUEST['nombre1'] != ''      ) { $sql .= " and nombre1 like '%".$_REQUEST['nombre1']."%'";           } }
			//if( isset( $_REQUEST['nombre2']      )==true ) { if( $_REQUEST['nombre2'] != ''      ) { $sql .= " and nombre2 like '%".$_REQUEST['nombre2']."%'";           } }
			//if( isset( $_REQUEST['apellido1']    )==true ) { if( $_REQUEST['apellido1'] != ''    ) { $sql .= " and apellido1 like '%".$_REQUEST['apellido1']."%'";       } }
			//if( isset( $_REQUEST['apellido2']    )==true ) { if( $_REQUEST['apellido2'] != ''    ) { $sql .= " and apellido2 like '%".$_REQUEST['apellido2']."%'";       } }
			if( isset( $_REQUEST['orderby']      )==true ) { if( $_REQUEST['orderby'] != ''      ) { $sql .= " order by ".$_REQUEST['orderby']." ";       } }
			
			$clientes = $this->Clientes->findAllBySql($sql);
			//Flash::error($sql);
			$this->setParamToView('clientes',$clientes);
		
		}
		
		
		
		public function agregar_clientesAction(){
		
			//$this->set_response('view');
			$this->setTemplateAfter("default");
		
		}
		
		/****
			addAction metodo en la cual se insertarán
			los datos del cliente
		***/
		public function add_clientesAction(){
			//primero instanciamos clase clientes
			$this->setResponse('view');
			$cli  = new Clientes();
			//cargamos el objeto mediantes los metodos setters
			
			$sw = 0;
			if($this->Clientes->count("nit = '".$this->getPostParam("nit")."'")>0){
				Flash::error("DOCUMENTO DE IDENTIFICACION (NIT/CEDULA) YA SE EXISTE EN NUESTRA BASE DE DATOS");
				$sw=1;
			}
			
			if($sw==0){
				$cli->id = '0';
				$cli->nit = $this->getPostParam("nit");
				//$cli->nombre1 = $this->getPostParam("pn");
				//$cli->nombre2 = $this->getPostParam("sn");
				//$cli->apellido1 = $this->getPostParam("pa");
				//$cli->apellido2 = $this->getPostParam("sa");
				$cli->nombre1 = '';
				$cli->nombre2 = '';
				$cli->apellido1 = '';
				$cli->apellido2 = '';
				$cli->razon_social = $this->getPostParam("rs");
				$cli->direccion_casa = $this->getPostParam("dc");
				$cli->direccion_oficina = $this->getPostParam("dof");
				$cli->telefono1 = $this->getPostParam("tf1");
				$cli->telefono2 = $this->getPostParam("tf2");
				$cli->celular = $this->getPostParam("celular");
				$cli->departamentos_id = $this->getPostParam("tmp_dptos");
				$cli->municipios_id = $this->getPostParam("tmp_municipios");
				$cli->activo = $this->getPostParam("activo");
				$cli->correo = $this->getPostParam("email");
				$cli->web = $this->getPostParam("web");
				$cli->activo = 0;
						
				if($cli->save()){
					Flash::success("Se insertó correctamente el registro");
					print("<script>escoger_cliente('$cli->id','$cli->razon_social','$cli->telefono1','$cli->telefono2');Dialog.okCallback();</script>");
				}else{
					Flash::error("Error: No se pudo insertar registro");	
					foreach($cli->getMessages() as $message){
							  Flash::error($message->getMessage());
						}
				}
			
			}//fin  si todo bien
			
	    }
		
		
		public function verificarAction($id){
			
			$this->setResponse('view');
			
			$cli = new Clientes();
			$cli->findFirst("nit = '$id'");
			echo "<script>";
			echo "$('clientes_id').value='$cli->id';";
			echo "$('nombre_cliente').value='$cli->razon_social';";
			if($cli->id==''){ echo "alert('cliente no encontrado o no existe');"; }
			echo "</script>";
			
			//$this->setTemplateAfter("default");

		}
		
		
		
		/*CONSULTA DE CLIENTES CON FACTURAS AGREGADAS*/
		
		public function trae_clientes_hfacturasAction(){
			
			//$this->setResponse('ajax');
			$this->setTemplateAfter("default");
			

		}
		
		
		public function trae_clientes_hfacturas_detallesAction(){
			
			$this->setResponse('view');
			
			//$sql = "select * from clientes where 1=1 ";
			$sql = '';
			if( isset( $_REQUEST['codigo']       )==true ) { if( $_REQUEST['codigo'] != ''       ) { $sql .= " and id like '%".$_REQUEST['codigo']."%'";                 } }
			if( isset( $_REQUEST['nit']          )==true ) { if( $_REQUEST['nit'] != ''          ) { $sql .= " and nit like '%".$_REQUEST['nit']."%'";                   } }
			if( isset( $_REQUEST['razon_social'] )==true ) { if( $_REQUEST['razon_social'] != '' ) { $sql .= " and razon_social like '%".str_replace(' ',"%",utf8_decode($_REQUEST['razon_social']))."%'"; } }
		//	if( isset( $_REQUEST['orderby']      )==true ) { if( $_REQUEST['orderby'] != ''      ) { $sql .= " order by ".$_REQUEST['orderby']." ";       } }
		
			
			$query = new ActiveRecordJoin(array(
						"entities" => array("Factura","Clientes"),
					 	"groupFields"=>array(
										"{#Clientes}.id",
										"{#Clientes}.nit",
										"{#Clientes}.razon_social"
										),
						"conditions"=>(" 1=1 and activo = 0 $sql ")
				));
				//Flash::error($query->getSqlQuery());
			 $this->setParamToView('clientes',$query->getResultSet());
			
			
			//$clientes = $this->Clientes->findAllBySql($sql);
			//Flash::error($sql);
			//$this->setParamToView('clientes',$clientes);
		
		}
		
		/* FIN CONSULTA DE CLIENTES CON FACTURAS AGREGADAS*/
		
		
		public function deleteAction($id){
		
 			//$this->setResponse('ajax');
			//$this->setTemplateAfter("default");
			
			$p = $this->Clientes->findFirst(" id = '$id' ");
			$sw=0;
			
			if( $this->Remisiones->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en remisiones");
			}
			
			if( $this->Devoluciones->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en devoluciones");
			}
			
			if( $this->Factura->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en facturacion");
			}
			
			if( $this->RecibosCaja->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en recibos de caja");
			}
			
			if( $this->Anticipos->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en anticipos de clientes");
			}
			
			if( $this->Anticipos->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en cuentas por cobrar");
			}
			
			if( $this->Cxc->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en cuentas por cobrar");
			}
			
			if( $this->SaldoRemisiones->count(" clientes_id = '$id' ") > 0 ){
				$sw=1; Flash::error("Clientes tiene movimiento en cuentas por cobrar");
			}
			
			
			
			if($sw==0){
				if(!$p->delete()){
					Flash::error("no se pudo borrar cliente");
				}else{
					Flash::success("Cliente borrado satisfactoriamente");
				}
			}
		
		
		}
		
	
	  
   }
?>