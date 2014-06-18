<?php

	class DepartamentosController extends ApplicationController {

			
						
		public function indexAction(){

		}
		
		public function find_buscarAction(){
				$this->setResponse('ajax');		
		}
		
		public function find_detalle_buscarAction(){
				$this->setResponse('ajax');
		}
		
		
		public function validarAction($id,$opcion){

			$this->setResponse("view");
			
			$sw=0;
			$dpto = new Departamentos();
			if( $this->Departamentos->count(" id = '$id' ") > 0 ){
				$dpto = $this->Departamentos->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"$dpto->departamento\");</script>";
				
			}else{
				Flash::error("No se Encontro Departamento");
				echo "<script>jQuery(\"#$opcion\").val(\"\");</script>";
				}
			
			
			//public $scaffold = false;
		}
		
			
	}

?>