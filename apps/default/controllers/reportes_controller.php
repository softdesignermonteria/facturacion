<?php

	class ReportesController extends ApplicationController {
		
		public function initialize() {

			$this->setTemplateAfter("adminiziolite");

		}


		public function indexAction(){
		
		}
		
		public function clientesAction(){
		    $this->setResponse("view");
		}
				
		public function clientesmesAction(){
					
		}
		
		public function print_jaspertclientesmesAction(){
			
				$this->setResponse("view");
		
		}
		
		public function print_jaspertlistadoproveedoresAction(){
			
				$this->setResponse("view");
		
		}
	
		
		
	  
   }
?>