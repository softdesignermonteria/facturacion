<?php
	class PrintController extends ApplicationController {
	
		
		
		public function initialize() {
		   //$this->setTemplateAfter("default2");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}
		
		
		public function indexAction(){
			//$this->setTemplateAfter("a_bit_boxy");
			 //$this->setTemplateAfter("menu_azul");
		}
		
		public function creditosAction(){
			//$this->setTemplateAfter("a_bit_boxy");
			 //$this->setTemplateAfter("menu_azul");
		}
		
		public function licenciaAction(){
			//$this->setTemplateAfter("a_bit_boxy");
		}
		
		public function terminosAction(){
			//$this->setTemplateAfter("a_bit_boxy");
		}
		
		
		public function remisionesAction($id){
			
			$this->setResponse('view');
			
			$r = $this->Remisiones->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'REMISIONES' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Remisiones->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleRemisiones->find(" remisiones_id ='$id' and anulado = 0"));
			
			
			
		}
		
		public function devolucionesAction($id){
			
			$this->setResponse('view');
			$r = $this->Devoluciones->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'DEVOLUCIONES' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Devoluciones->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleDevoluciones->find(" devoluciones_id ='$id' "));
			
			
			
		}
		
		public function facturacionAction($id){
			$this->setResponse('view');
		}
		
		public function comprasAction($id){
			$this->setResponse('view');
		}
		
		
		public function cuenta_cobroAction($id){
			
			$this->setResponse('view');
			$r = $this->Factura->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'CUENTA_COBRO' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Factura->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id ='$id' "));
			
			
		}
		
		
		public function recibos_cajaAction($id){
			
			$this->setResponse('view');
			$r = $this->RecibosCaja->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'RECIBOS_CAJA' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->RecibosCaja->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleRecibosCaja->find(" recibos_caja_id ='$id' "));
			
			
		}
		
		
		public function anticiposAction($id){
			
			$this->setResponse('view');
			$r = $this->Anticipos->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'ANTICIPOS' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			//$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Anticipos->findFirst(" id ='$id' "));
			//$this->setParamToView("detalles",$this->DetalleRecibosCaja->find(" recibos_caja_id ='$id' "));
			
			
		}
		
		public function htmlpdfAction() {
			//$this->setTemplateAfter("default");
			$response = ControllerResponse::getInstance();
			$response->setHeader("Content-Type: application/xml");
			//$this->setResponse(ControllerResponse::RESPONSE_OTHER);
			$this->setResponse(ControllerResponse::RESPONSE_NO_LAYOUT);
			//$this->setResponseAdapter("xml");
//			$this->setResponse("xml");
			
			//Flash::error($xml);
			/*try{*/
			
				/*$response = ControllerResponse::getInstance();
				$response->setHeader("Content-Type: application/pdf");
				$response->setHeader("Expires: 0");
				$response->setResponse(ControllerResponse::RESPONSE_OTHER);
				$response->setResponseAdapter("pdf");*/
			
				
				
			/*}catch(Exepction $e){ 
				Flash::error("error".$e); 
			}*/
			
		}
		
		public function remisiones_htmlAction($id){
			//$this->setTemplateAfter("print_html");
			$this->setResponse('view');
		    if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->Remisiones->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'REMISIONES' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Remisiones->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleRemisiones->find(" remisiones_id ='$id' and anulado = 0"));
			
			$this->setParamToView("paginate",Tag::paginate($this->DetalleRemisiones->find(" remisiones_id ='$id' and anulado = 0"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
		}		
		
		
		
		public function pedidos_clientes_htmlAction($id){
			//$this->setTemplateAfter("print_html");
			$this->setResponse('view');
		    //if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			//if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			//$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->PedidoClientes->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'PEDIDOS_CLIENTES' and empresa_id = '$r->empresa_id' "));
			//$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			//$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->PedidoClientes->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetallePedidoClientes->find(" pedido_clientes_id ='$id' and anulado = 0"));
			
			//$this->setParamToView("paginate",Tag::paginate($this->DetallePedidoClientes->find(" pedido_clientes_id ='$id' and anulado = 0"),$pagina,10)); 
			//$this->setParamToView("totalizar",$totalizar);
		}		
		
		
			public function cuadre_caja_htmlAction($id){
			//$this->setTemplateAfter("print_html");
			$this->setResponse('view');
		    //if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			//if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			//$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->CuadreCaja->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'CUADRE_CAJA' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("empleado",$this->Empleado->findFirst(" id ='$r->empleado_id' "));
			$this->setParamToView("turnos",$this->Turnos->findFirst(" id ='$r->turnos_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->CuadreCaja->findFirst(" id ='$id' "));
			$query = new ActiveRecordJoin(array(
					"entities" => array("Cxc","CxcCuadreCaja"),
					"fields" => array( "{#Cxc}.id"
										,"{#Cxc}.prefijo"
										,"{#Cxc}.consecutivo"
										,"{#Cxc}.total"
										),
					"conditions"=>(" {#CxcCuadreCaja}.cxc_id = {#Cxc}.id 
										and cuadre_caja_id = '$id' 
										and {#Cxc}.anulado = 0
										and {#CxcCuadreCaja}.anulado = 0 ")
			));
			//$this->setParamToView("detalles",$this->CxcCuadreCaja->find(" cuadre_caja_id = '$id' and anulado = 0 "));
			$this->setParamToView("detalles",$query->getResultSet());
			
		}		
		
		
		public function devoluciones_htmlAction($id){
			
			$this->setResponse('view');
			
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->Devoluciones->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'DEVOLUCIONES' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Devoluciones->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleDevoluciones->find(" devoluciones_id ='$id' and anulado = 0 "));
			
			$this->setParamToView("paginate",Tag::paginate($this->DetalleDevoluciones->find(" devoluciones_id ='$id' and anulado = 0"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
			
			
		}
		
		public function facturacion_htmlAction($id){
			
			$this->setResponse('view');
			$titulo = '';
			
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);		
			$r = $this->Factura->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'FACTURACION' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Factura->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id ='$id' and anulado = 0 "));
			
			$this->setParamToView("paginate",Tag::paginate($this->DetalleFactura->find(" factura_id ='$id' and anulado = 0","order: desde"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
			
		}
		
		
		public function cuenta_cobro_htmlAction($id){
			
			$this->setResponse('view');
		    
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			
			$r = $this->Factura->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'CUENTA_COBRO' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Factura->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleFactura->find(" factura_id ='$id' and anulado = 0 "));
			
			$this->setParamToView("paginate",Tag::paginate($this->DetalleFactura->find(" factura_id ='$id' and anulado = 0","order: desde"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
			
		}
		
		
		public function recibos_caja_htmlAction($id){
			
			$this->setResponse('view');
			
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->RecibosCaja->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'RECIBOS_CAJA' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->RecibosCaja->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleRecibosCaja->find(" recibos_caja_id ='$id' "));
			
			$this->setParamToView("paginate",Tag::paginate($this->DetalleRecibosCaja->find(" recibos_caja_id ='$id' and anulado = 0"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
			
			
		}
		
		
		public function recibos_caja_blanco_htmlAction(){
			
			$this->setResponse('view');
			
			
		}
		
		
		public function anticipos_htmlAction($id){
			
			$this->setResponse('view');
			
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 10;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->Anticipos->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'ANTICIPOS' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Clientes->findFirst(" id ='$r->clientes_id' "));
			//$this->setParamToView("dir",$this->Direccion->findFirst(" id ='$r->direccion_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Anticipos->findFirst(" id ='$id' "));
			//$this->setParamToView("detalles",$this->DetalleRecibosCaja->find(" recibos_caja_id ='$id' "));
			
			$this->setParamToView("paginate",Tag::paginate($this->Anticipos->find(" id ='$id' and anulado = 0"),$pagina,10)); 
			$this->setParamToView("totalizar",$totalizar);
		}
			
			
			
		public function egresos_htmlAction($id){
			
			$this->setResponse('view');
			
			if(isset($_REQUEST["pagina"])==false){ $pagina = 1; }else{ $pagina = $_REQUEST["pagina"]; }
			if(isset($_REQUEST["totalizar"])==false){ $totalizar = ''; }else{ $totalizar = $_REQUEST["totalizar"]; }
			$total_page = 8;		   
	        $this->setParamToView("total_page",$total_page);
			$r = $this->Egresos->findFirst(" id = '$id'");
			$this->setParamToView("tipo_documento",$this->TipoDocumento->findFirst(" nombre = 'EGRESOS' and empresa_id = '$r->empresa_id' "));
			$this->setParamToView("cli",$this->Proveedores->findFirst(" id ='$r->proveedores_id' "));
			$this->setParamToView("config_consecutivos",$this->DetalleConsecutivos->findFirst(" id ='$r->tipo_documento_id' "));
			$this->setParamToView("encabezado",$this->Egresos->findFirst(" id ='$id' "));
			$this->setParamToView("detalles",$this->DetalleEgresos->find(" egresos_id ='$id' and anulado = '0' "));
			$this->setParamToView("paginate",Tag::paginate($this->DetalleEgresos->find(" egresos_id ='$id' and anulado = '0' "),$pagina,$total_page)); 
			$this->setParamToView("totalizar",$totalizar);
			
			
		}		
		
		public function remisiones_html2Action(){
			$this->setResponse('view');
				try{
				ReportComponent::load(array("Text", "Style"));
				
				$report2 = new Report('Text');
				
				$report2->setDisplayMode(Report::DISPLAY_PRINT_PREVIEW);
				$report2->setDocumentName("..::IMPRESION DE REMISIONES::..");
				
				$kardex = new Kardex();
				foreach($kardex->find() as $tipoReserva){
					$report2->addRow(array($tipoReserva->id, $tipoReserva->codigo));
				}
				
					$report2->finish();	
					$report2->outputToBrowser();
	
			}catch(CoreExepction $e){ 
				Flash::error("error".$e->getMessage()); 
			}
			
			
		}	
		
					
  
    }
?>