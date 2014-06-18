<?php

	class MunicipiosController extends ApplicationController {

						
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
			$dpto = new Departamentos();
			if( $this->Municipios->count(" id = '$id' ") > 0 ){
				$dpto = $this->Municipios->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"$dpto->municipio\");</script>";
				
			}else{
				Flash::error("No se Encontro Municipio");
				echo "<script>jQuery(\"#$opcion\").val(\"\");</script>";
				}
			
			
			//public $scaffold = false;
		}
		
					
	}

?>