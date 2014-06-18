<?php

	class KardexController extends ApplicationController {
		
		public function initialize() {
		  // $this->setTemplateAfter("a_bit_boxy");
		   //$this->setTemplateAfter("menu_azul");
		   	$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
			$this->setTemplateAfter("$temp");
		}
		
		public function indexAction(){
		
		}
		
		public function autocompletejsonAction(){
			$this->setResponse("view");
		}
		
		public function agregarAction(){
					
        }
		
		public function modificarAction(){
			
				$id = $_REQUEST["id"];
				$kar = $this->Kardex->findFirst(" id = '$id' ");
				$emp = $this->Empresa->findFirst(" id = '$kar->empresa_id' ");
				
				Tag::displayTo("id",$kar->id);
				Tag::displayTo("empresa_id",$kar->empresa_id);
				Tag::displayTo("nombre_empresa",$emp->nombre_empresa);
				Tag::displayTo("codigo",$kar->codigo);
				Tag::displayTo("nombre_producto",$kar->nombre_producto);
				Tag::displayTo("nombre_corto",$kar->nombre_corto);
				Tag::displayTo("grupos_id",$kar->grupos_id);
				Tag::displayTo("valor",$kar->valor);
				Tag::displayTo("stock_minimo",$kar->stock_minimo);
				Tag::displayTo("stock_maximo",$kar->stock_maximo);
				Tag::displayTo("tipo_kardex_id",$kar->tipo_kardex_id);
				Tag::displayTo("aviso",$kar->aviso);
				Tag::displayTo("visible_pos",$kar->visible_pos);
				Tag::displayTo("iva_compra",$kar->iva_compra);
				Tag::displayTo("iva_venta",$kar->iva_venta);
				Tag::displayTo("tiempo_garantia_id",$kar->tiempo_garantia_id);
				Tag::displayTo("valor_tiempo",$kar->valor_tiempo);
				
				
					
        }
		
		public function buscarAction(){
					
        }
		
		public function find_detalle_buscarAction(){
				$this->setResponse("view");		
        }
		
		
		
		/****
			addAction metodo en la cual se insertarán
			los datos del cliente
		***/
		public function addAction(){
			
			$this->setResponse("view");
			//primero instanciamos clase clientes
			
			$sw=0;
			
			if($this->getPostParam("aviso")=="@"){ $sw=1; Flash::error("aviso es requerido"); }
			if($this->getPostParam("visible_pos")=="@"){  $sw=1;  Flash::error("visible pos es requerido"); }
			//if($this->getPostParam("aviso")=="@"){ Flash::error("aviso es requerido"); }
			
			if($sw==0){			
			
				$kar  = new Kardex();
				//cargamos el objeto mediantes los metodos setters
				$kar->id               = $this->getPostParam("id");
				$kar->empresa_id       = $this->getPostParam("empresa_id");
				$kar->codigo           = $this->getPostParam("codigo");
				$kar->nombre_producto  = $this->getPostParam("nombre_producto");
				$kar->nombre_corto     = $this->getPostParam("nombre_corto");
				$kar->grupos_id        = $this->getPostParam("grupos_id");
				$kar->valor            = $this->getPostParam("valor");
				$kar->stock_minimo     = $this->getPostParam("stock_minimo");
				$kar->stock_maximo     = $this->getPostParam("stock_maximo");
				$kar->tipo_kardex_id   = $this->getPostParam("tipo_kardex_id");
				$kar->aviso            = $this->getPostParam("aviso");
				$kar->visible_pos      = $this->getPostParam("visible_pos");
				$kar->iva_compra       = $this->getPostParam("iva_compra");
				$kar->iva_venta        = $this->getPostParam("iva_venta");
				
				$kar->tiempo_garantia_id  = $this->getPostParam("tiempo_garantia_id");
				$kar->valor_tiempo        = $this->getPostParam("valor_tiempo");
										
				if($kar->save()){
					Flash::success("Se insertó o Modifico  correctamente el registro");
					Flash::addMessage("Se insertó o Modifico correctamente el registro",Flash::SUCCESS);
					echo "<script>redireccionar_action('kardex/show/?id=$kar->id');</script>";	
					
				}else{
					Flash::error("Error: No se pudo insertar el registro");	
					foreach($kar->getMessages()  as $msg):
						Flash::error("Error: ".$msg->getMessage());	
					endforeach;
				}
			}
					
	    }
		
		/****
			addAction metodo en la cual se insertarán
			los datos del cliente
		***/
		public function updateAction(){
			
			$this->setResponse("view");
			//primero instanciamos clase clientes
			$sw=0;
			
			if($this->getPostParam("aviso")=="@"){ $sw=1; Flash::error("aviso es requerido"); }
			if($this->getPostParam("visible_pos")=="@"){  $sw=1;  Flash::error("visible pos es requerido"); }
			//if($this->getPostParam("aviso")=="@"){ Flash::error("aviso es requerido"); }
			
			if($sw==0){		
					$kar  = new Kardex();
					//cargamos el objeto mediantes los metodos setters
					$kar->id               = $this->getPostParam("id");
					$kar->empresa_id       = $this->getPostParam("empresa_id");
					$kar->codigo           = $this->getPostParam("codigo");
					$kar->nombre_producto  = $this->getPostParam("nombre_producto");
					$kar->nombre_corto     = $this->getPostParam("nombre_corto");
					$kar->grupos_id        = $this->getPostParam("grupos_id");
					$kar->valor            = $this->getPostParam("valor");
					$kar->stock_minimo     = $this->getPostParam("stock_minimo");
					$kar->stock_maximo     = $this->getPostParam("stock_maximo");
					$kar->tipo_kardex_id   = $this->getPostParam("tipo_kardex_id");
					$kar->aviso            = $this->getPostParam("aviso");
					$kar->visible_pos      = $this->getPostParam("visible_pos");
					$kar->iva_compra       = $this->getPostParam("iva_compra");
					$kar->iva_venta        = $this->getPostParam("iva_venta");
					
					$kar->tiempo_garantia_id  = $this->getPostParam("tiempo_garantia_id");
					$kar->valor_tiempo        = $this->getPostParam("valor_tiempo");
											
					if($kar->save()){
						Flash::success("Se Modifico  correctamente el registro");
						Flash::addMessage("Se Modifico correctamente el registro",Flash::SUCCESS);
						echo "<script>redireccionar_action('kardex/show/?id=$kar->id');</script>";	
						
					}else{
						Flash::error("Error: No se pudo insertar el registro");	
						foreach($kar->getMessages()  as $msg):
							Flash::error("Error: ".$msg->getMessage());	
						endforeach;
					}
			}
					
	    }
		/****
			mostrarAction vista en la cual se mostrarán
			los datos del producto
		***/
		public function showAction(){
          
           		$id = $_REQUEST["id"];
				$kar = $this->Kardex->findFirst(" id = '$id' ");
				$emp = $this->Empresa->findFirst(" id = '$kar->empresa_id' ");
				
				Tag::displayTo("id",$kar->id);
				Tag::displayTo("empresa_id",$kar->empresa_id);
				Tag::displayTo("nombre_empresa",$emp->nombre_empresa);
				Tag::displayTo("codigo",$kar->codigo);
				Tag::displayTo("nombre_producto",$kar->nombre_producto);
				Tag::displayTo("nombre_corto",$kar->nombre_corto);
				Tag::displayTo("grupos_id",$kar->grupos_id);
				Tag::displayTo("valor",$kar->valor);
				Tag::displayTo("stock_minimo",$kar->stock_minimo);
				Tag::displayTo("stock_maximo",$kar->stock_maximo);
				Tag::displayTo("tipo_kardex_id",$kar->tipo_kardex_id);
				Tag::displayTo("aviso",$kar->aviso);
				Tag::displayTo("visible_pos",$kar->visible_pos);
				Tag::displayTo("iva_compra",$kar->iva_compra);
				Tag::displayTo("iva_venta",$kar->iva_venta);
				Tag::displayTo("tiempo_garantia_id",$kar->tiempo_garantia_id);
				Tag::displayTo("valor_tiempo",$kar->valor_tiempo);
				
	
		}
		
	
		
	}
		
?>