<?php

class graficosxmlController extends ApplicationController {



	public function initialize() {
		$temp=$this->Admin->findFirst(" id = '".Session::get("usuarios_id")."' ")->plantilla;
		$this->setTemplateAfter("$temp");
	}

	public function indexAction(){
		
		
	}
	
	
	
	public function grafico1Action(){
		
		
	}
	
	public function graficasAction(){
		
		
		$this->setResponse("view");
		
	}
	
	public function exportarAction(){
		
		$this->setResponse("view");
			
	}
	
	
	public function grafico1_xmlAction($id_encuesta,$id_pregunta,$id_tipo_pregunta){
		
		
		$this->setResponse("ajax");
		$response = ControllerResponse::getInstance();
		$response->setHeader("Content-Type: text/xml");
		//$response->setResponse(ControllerResponse::RESPONSE_OTHER);
		$response->setResponseAdapter("xml");
		$this->setParamToView("id_encuesta",$id_encuesta);
		$this->setParamToView("id_pregunta",$id_pregunta);
		$this->setParamToView("id_tipo_pregunta",$id_tipo_pregunta);
		
		
		
	}
	
	

}

?>