<?php

	class centro_produccionController extends ApplicationController {

						
		public function indexAction(){

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
			$bod = new CentroProduccion();
			if( $this->CentroProduccion->count(" id = '$id' ") > 0 ){
				$bod = $this->Bodegas->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#".$opcion."_id\").val(\"$bod->id\");jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"".$bod->bodega."\");</script>";
				
			}else{
				Flash::error("No se Encontro Centro Produccion");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#$opcion\").val(\"\");</script>";
			}
			
			
			//public $scaffold = false;
			
			
			
		}
		
			
	}

?>