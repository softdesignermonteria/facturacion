<?php
class ControllerBase {
  	
	public function onStartApplication(){
		
	}
  
	public function init(){
		Router::routeTo("controller: login","action: login");
		
	}
	
	public function beforeFilter(){
	 	
		if(Router::getController()!='login' && Router::getController()!='preview_encuesta'){
						 if(Session::get("usuario_autenticado") == false || Session::get("usuario_autenticado") == '' ){
							$authLog = new Logger("File", "auth_failed.txt");
							$authLog->log("El usuario debe estar autenticado para usar este modulo recurso: '". $this->getControllerName()."/".$this->getActionName()."'");
							 Flash::error("El usuario debe estar autenticado para usar este modulo");
							 echo "<h4><a href=".core::getInstancePath()."login/login>Haga Click aqui para inicial session</a></h4>";
							 Router::routeTo("controller: login", "action: login");
							 return false;
						 }//fin autenticado
							
		
			 
						 /*CODIGO DE PERIMISOS DE USUARIO*/
						  $role = Session::get('role');
						 
						  if($role==""){  $role = 'public';   }
							 
							  $acl = new Acl('Model', 'className: AccessList');
							  $resourceName = $this->getControllerName();
							  $operationName = $this->getActionName();
								
						  
						  if($acl->isAllowed($role, $resourceName, $operationName)==false ){
			
							   $authLog = new Logger("File", "auth_failed.txt");
							   $authLog->log("Autenticación fallo para el rol '$role' en el recurso '". $this->getControllerName()."/".$this->getActionName()."'");
							   Flash::addMessage("No tiene permiso para usar esta aplicacion '". $this->getControllerName()."/".$this->getActionName()."' ",Flash::ERROR);
							   Router::routeTo("controller: home", "action: error");
							   return false;
						  }
							 /*}*/
						/*FIN*/ /*CODIGO DE PERIMISOS DE USUARIO*/
			
			} // fin si esta logueado y es diferente a administrador
			
			
		}

}
