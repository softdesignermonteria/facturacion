<?php

	class InventarioController extends ApplicationController {
	
		public function initialize() {
		  // $this->setTemplateAfter("a_bit_boxy");
		   //$this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}



		public function indexAction(){
		
		}
		
		
		
		public function inventarioAction(){
		
		}
		
		
	
		
		
		public function inventario_kardexAction($id){
		//$this->setResponse('view');
		$inventario  = new Kardex();
		//tipo kardex
		// 1 - producto
		// 2 - servicios					
		$inventario = $this->Kardex->findFirst("id = '$id'");
		$this->setParamToView("inventario", $inventario);
		$this->setParamToView("empresas",$this->Empresa->find("activo = '0'"));
		$this->setParamToView("idt", $id);
	
		}
		
		public function inventario_clienteAction($id){
		//$this->setResponse('view');
		$this->setParamToView("cliente", $this->Clientes->findFirst(" id = '$id' "));
		$this->setParamToView("empresas",$this->Empresa->find("activo = '0'"));
		$this->setParamToView("idt", $id);
	
		}
		
		public function inventario_cliente_detallesAction(){
		
			$empresa_id = $_REQUEST['empresa_id'];
			$clientes_id = $_REQUEST['clientes_id'];
			$direccion_id = $_REQUEST['direccion_id'];
			$kardex_id = $_REQUEST['kardex_id'];

		
			$this->setResponse('view');
			$this->setParamToView("cliente", $this->Clientes->findFirst(" id = '$clientes_id' "));
			$this->setParamToView("empresa",$this->Empresa->findFirst(" id = '$empresa_id' and activo = '0' "));
			$this->setParamToView("direccion",$this->Direccion->findFirst(" id = '$direccion_id'  "));
			$this->setParamToView("kardex",$this->Kardex->findFirst(" id = '$kardex_id' "));
			
	
		}
		
		public function inventario_cliente_historicoAction(){
		
			$empresa_id = $_REQUEST['empresa_id'];
			$clientes_id = $_REQUEST['clientes_id'];
			$direccion_id = $_REQUEST['direccion_id'];
			$kardex_id = $_REQUEST['kardex_id'];

		
			$this->setResponse('view');
			$this->setParamToView("cliente", $this->Clientes->findFirst(" id = '$clientes_id' "));
			$this->setParamToView("empresa",$this->Empresa->findFirst(" id = '$empresa_id' and activo = '0' "));
			$this->setParamToView("direccion",$this->Direccion->findFirst(" id = '$direccion_id'  "));
			$this->setParamToView("kardex",$this->Kardex->findFirst(" id = '$kardex_id' "));
			
	
		}
		
		
		
		public function listar_inventarioAction(){
				
				$this->setResponse('view');
				
				$kardex  = new Kardex();
		
				$grupo = $_REQUEST['grupos'];
				
				if($grupo!=4){
				
				$sql="select * from kardex where grupos_id='$grupo'";
				$inventario = $this->Kardex->findAllBySql("$sql");
          		$this->setParamToView("inventarios", $inventario);
				
				}else{
				
				$sql="select * from kardex order by grupos_id";
				$inventario = $this->Kardex->findAllBySql("$sql");
          		$this->setParamToView("inventarios", $inventario);
				
				}
		
		}
		
		public function traer_gruposAction(){
			$this->setResponse('view');
			
		}
		
		public function traer_proveedoresAction(){
			$this->setResponse('view');
			
		}
			
   }
   
?>