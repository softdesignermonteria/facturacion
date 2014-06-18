
<?php

	class LibrosController extends ApplicationController {
	
	
	
		public function initialize() {
		  // $this->setTemplateAfter("a_bit_boxy");
		   //$this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}

		
	
		
		/*INGRESOS DIARIOS*/
		
		public function ingresos_diariosAction(){
			//$this->setResponse("view");
		}
		
		public function ingresos_diarios_jsonAction(){
			
			$this->setResponse("view");
		}
		
		public function ingresos_diarios_pdfAction(){
			$this->setResponse("view");
		}
		
		
		public function generalAction(){
			$inventario  = new Kardex();
			//tipo kardex
			// 1 - producto
			// 2 - servicios					
			$inventario = $this->Kardex->find("tipo_kardex_id = '1' and visible_pos = 0 ","order: nombre_producto ASC");
			$this->setParamToView("inventario", $inventario);
		}
		
		
		public function general_pdfAction(){
			$this->setResponse("view");
		}
		
		public function general_excelAction(){
			$this->setResponse("view");
		}
		
		
		public function inventario_bodegasAction(){
			//$this->setResponse("view");
		}
		
		public function inventario_bodegas_jsonAction(){
			$this->setResponse("ajax");
		}
		
		public function inventario_bodegas_pdfAction(){
			$this->setResponse("view");
		}
		

		public function indexAction(){
		
		}
	
		public function facturacionAction(){
			
			
		}
		
		public function detalle_facturasAction(){
				
				$this->setResponse('view');
				$facturas  = new Factura();
				$empresa_id = $_REQUEST['empresa_id'];
				$desde= $_REQUEST['desde'];
				$hasta = $_REQUEST['hasta'];
				$clientes = $_REQUEST['clientes_id'];
				$sql='';
				$sql = "";
				$sql1 = "";
				$sql2 = "";
				$sql2 = "";
				$sql =  "and {#Factura}.anulado = '0' ";
				$sql3 = "and {#Clientes}.id = '$clientes' ";
				$sql1 = "and {#Factura}.fecha >= '$desde' "; 
				$sql2 = "and {#Factura}.fecha <= '$hasta' "; 
				
				//$sql2 = "and YEAR( {#Factura}.fecha ) = $yyyy and MOnTH( {#Factura}.fecha ) = $mes ";
				$query = new ActiveRecordJoin(array(
						"entities" => array("Factura","Clientes","Empresa"),
					 	"fields"=>array(
										"{#Factura}.id",
										"{#Factura}.prefijo",
										"{#Factura}.consecutivo",
										"{#Factura}.fecha",
										"{#Factura}.empresa_id",
										"{#Factura}.vencimiento",
										"{#Clientes}.razon_social",
										"{#Factura}.subtotal",
										"{#Factura}.descuento",
										"{#Factura}.iva",
										"{#Factura}.total",										
										),
						"conditions"=>(" {#Factura}.empresa_id = '$empresa_id' $sql $sql1 $sql2 $sql3")
				));
				//Flash::error($query->getSqlQuery());
			 $this->setParamToView('facturas',$query->getResultSet());
			 $this->setParamToView('sql',$query->getResultSet());
		
		}
		
		public function facturacionpdfAction(){
			
			$this->setResponse('view');
		}
		
		
		public function comprasAction(){
			
			
		}
		
		public function detalle_comprasAction(){
				
				$this->setResponse('view');
				$facturas  = new Compras();
				$empresa_id = $_REQUEST['empresa_id'];
				$desde= $_REQUEST['desde'];
				$hasta = $_REQUEST['hasta'];
				$proveedores = $_REQUEST['proveedores_id'];
				$sql='';
				$sql = "";
				$sql1 = "";
				$sql2 = "";
				$sql2 = "";
				$sql =  "and {#Compras}.anulado = '0' ";
				$sql3 = "and {#Proveedores}.id = '$proveedores' ";
				$sql1 = "and {#Compras}.fecha >= '$desde' "; 
				$sql2 = "and {#Compras}.fecha <= '$hasta' "; 
				
				//$sql2 = "and YEAR( {#Compras}.fecha ) = $yyyy and MOnTH( {#Compras}.fecha ) = $mes ";
				$query = new ActiveRecordJoin(array(
						"entities" => array("Compras","Proveedores","Empresa"),
					 	"fields"=>array(
										"{#Compras}.id",
										"{#Compras}.prefijo",
										"{#Compras}.consecutivo",
										"{#Compras}.fecha",
										"{#Compras}.empresa_id",
										"{#Compras}.vencimiento",
										"{#Proveedores}.razon_social",
										"{#Compras}.subtotal",
										"{#Compras}.descuento",
										"{#Compras}.iva",
										"{#Compras}.total",										
										),
						"conditions"=>(" {#Compras}.empresa_id = '$empresa_id' $sql $sql1 $sql2 $sql3")
				));
				//Flash::error($query->getSqlQuery());
			 $this->setParamToView('facturas',$query->getResultSet());
			 $this->setParamToView('sql',$query->getResultSet());
		
		}
		
		public function compraspdfAction(){
			
			$this->setResponse('view');
		}
		
			
		
		public function ingresosAction(){
		
		}
		
			
   }
   
?>