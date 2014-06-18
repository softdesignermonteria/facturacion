<?php

	class AdministradorController extends ApplicationController {
	
		
		
		
		public function initialize() {
		
		
			$temp=$this->Empresa->findFirst(" activo = 0 ")->plantilla;
			$this->setTemplateAfter("$temp");
	
		}


		/**
		 * Aqui sale el formulario de Iniciar Sesión
		 *
		 */
		 
		public function indexAction(){
		
		}
		
		
		 public function not_found($controller, $action){
				 $this->set_response('view');
				 Flash::error("No esta definida la accion $action, redireccionando");
				 return $this->redirect('menu');
				 
		 }

		/**
		 * Esta accion es ejecutada por application/before_filter en caso
		 * de que el usuario trate de entrar a una accion a la cual
		 * no tiene permiso
		 *
		 */
		public function no_accesoAction(){

		}
		
		
		
		public function cambioAction(){

		}
		
		
		
		
		public function permisosAction(){
			
			//verficando permisos
			$acl  = new AccessList();
			$menu = new Menu();
			$role = new Role();
			Flash::success("Verificando Permisos");
			
			foreach($role->find(" role != 'superadministrador' and role != 'administrador' ") as $role):
				Flash::success("verificando Permisos del Perfil $role->role");
				foreach($menu->find("posicion_y <> 0") as $menu):
					$tmp = explode("/",$menu->url);
					$resource = $tmp[0]; 
					$action   = $tmp[1]; 
					if($action==''){ $action = 'index'; }
					//Flash::notice(count($tmp));
					if($acl->count("resource = '$resource' and action = '$action' ") == 0 ){
						$acl->id        = '';
						$acl->role      = $role->role;
						$acl->resource  = $resource;
						$acl->action    = $action;
						$acl->allow     = 'N';
						//Flash::notice(count($tmp).$menu->url);
						if( !$acl->save() ){
							Flash::error("Error Guardando Registro");
							foreach($acl->getMessages() as $message){ 
								Flash::error("Error tabla access list : ".$message); 
							}
						}
						
					}
				endforeach;
			endforeach; //roles
			
			Flash::success("Permisos Actualizados");
			
				
				
		}
		
		
		public function ver_permisosAction(){
			$this->setResponse("ajax");
			
			if( isset($_REQUEST["role"])==true && isset($_REQUEST["controlador"])==true && isset($_REQUEST["accion"])==true && isset($_REQUEST["permiso"])==true ){
				$role = $_REQUEST["role"];
				$controlador = $_REQUEST["controlador"];
				$accion = $_REQUEST["accion"];
				$permiso = $_REQUEST["permiso"];
				$acl = new AccessList();
				if($permiso=='Y'){ $permiso = 'N'; }else{ $permiso = 'Y'; }
				$acl = $this->AccessList->findFirst(" role = '$role' and resource = '$controlador' and  action = '$accion' ");
				$acl->allow = $permiso;
				
				if(!$acl->save()){
					Flash::Error("Error Actualizando el permiso");
				}
				
			}
			
		}
		
		
		public function AgregarAction(){

		}
		
			
		public function modificarAction($id){
			//$this->setResponse("ajax");
			if( isset($id) ){
					
				$id_admin = $id;
						
				$admin = $this->Admin->findFirst(" id = '$id_admin' ");
		/*		$dpto= $this->Departamentos->findFirst("id = '$cli->departamentos_id'");
				$mpio= $this->Municipios->findFirst("id = '$cli->municipios_id'");*/
				Tag::displayTo("id",$admin->id);
				Tag::displayTo("username",$admin->username);
				Tag::displayTo("password",$admin->password);
				Tag::displayTo("confirmar",$admin->password);
				Tag::displayTo("empleados",$admin->empleado_id);
				Tag::displayTo("role",$admin->role);
							
							
			}else{
					Flash::error("Parametro incorrecto!, por favor vuelva a buscar el usuario para modificar.");
				}
		}
		
		public function ActualizarAction(){
			
			//$this->setResponse("ajax");
			$Usuario= new Admin();
			$sw=0;
			//$usuario = $Usuario->findFirst("username = '".$_REQUEST['username']."'");
			
			if($sw==1){
				Flash::error("Error: Nombre usuario ya existe!!!");
			
			}else{
				
				if($this->getPostParam("password")!=$this->getPostParam("confirmar")){$sw=1;Flash::error("Error Contraseñas no son iguales..");}
			
				if($sw==0){
					$Usuario                   = $this->Admin->findFirst("id = '".$_REQUEST["id"]."'");
					$Usuario->username         = $this->getPostParam("username");
					$Usuario->password         = $this->getPostParam("password");
					$Usuario->nombre_completo  = $this->getPostParam("nombre_completo");

					
					if($Usuario->save()){
						  Flash::addMessage("Se Actualizo correctamente el registro",FLASH::SUCCESS);
						  /*echo "<script>redireccionar_action('menu');</script>";	*/
						   return $this->redirect('menu');
					}else{
						 Flash::addMessage("Error: No se pudo Actualizar el registro",FLASH::ERROR);	
						 /*echo "<script>redireccionar_action('menu');</script>";	*/
						  return $this->redirect('menu');
						 foreach($Usuario->getMessages() as $message){ 
							Flash::error("Error tabla Usuarios : ".$message); 
						}
					}
				
				}
			}
			
		}
		
		
		public function buscarAction(){
			$condicion = "";
			if(Session::get("role")=="superadministrador" || Session::get("role")=="superadministrador" ) {
					$condicion = "";
				}else{
					$condicion = " and empleado_id = '".Session::get("id_empleado")."' ";	
					}
					
			$this->SetParamToView("admin",$this->Admin->find(" 1=1 $condicion"));
			
		}
		
		/**
		 * Validacion si el login y el password son correctos
		 */
		public function validaAction(){
				
				
				$Usuario = new Admin();
				$usuario = $Usuario->findFirst("username = '".$_REQUEST['login']."'
							and password = '".$_REQUEST['password']."'");
				
				
				if($usuario){
				
					$Empresa = new Empresa();
					$emp = $Empresa->findFirst("id = '".$_REQUEST['empresa']."'");
					
					$empleado = $this->Empleado->findFirst(" id = '$usuario->empleado_id' ");
					
					if(!$emp){
						Flash::error('Usuario/Clave incorrectos');
						Flash::error('POR FAVOR ESCOJA UNA EMPRESA/O CREE UNA EMPRESA PREVIAMENTE');
						return $this->routeTo('action: index');
					}
					
					/**
					 * Almaceno en la variable de session el id del usuario
					 * autenticado
					 */
					Session::set('admin_username'      , $usuario->username);
					Session::set('tipo_usuario'        , $usuario->tipo_usuario);
					Session::set('usuarios_id'         , $usuario->id);
					Session::set('nombre_completo'     , $usuario->nombre_completo);
					Session::set('usuario_autenticado' , true);
					Session::set('id_empresa'          , $emp->id);
					Session::set('nombre_empresa'      , $emp->nombre_empresa);
					Session::set('kardex_id_default'      , $emp->kardex_id_default);
					Session::set('id_empleado'         , $empleado->id);
					Session::set('nombre_empleado'     , $empleado->nombre_completo);
					Session::set('regimen'             , $emp->regimen_id);
					Session::set('role'                , $usuario->role);
					Session::set('cobro_tarifa'        , $emp->cobro_tarifa);
					
					/**
					 * Lo redireccionos al formulario de clientes
					 */
				return $this->redirect('menu');
			
				} else {
					Flash::error('Usuario/Clave incorrectos');
					return $this->routeTo('action: index');
					//return Router::routeTo("controller: administrador");
				}

		}
		
		
		public function salirAction(){
			
				Session::unsetData('admin_username');
				Session::unsetData('tipo_usuario');
				Session::unsetData('usuarios_id');
				Session::unsetData('nombre_completo');
				Session::unsetData('usuario_autenticado');
				Session::unsetData('id_empresa');
				Session::unsetData('nombre_empresa');
				Session::unsetData('id_empleado');
				Session::unsetData('nombre_empleado');
				Session::unsetData('regimen');
				Session::unsetData('cobro_tarifa');
			
			Flash::notice('Has salido');
			return $this->routeTo('action: index');
		}
		
		
		
		
		
		public function addAction(){
			
			$this->setResponse("view");
			$admin= new Admin();
			$sw=0;
			$admin = $admin->findFirst("username = '".$_REQUEST['username']."'");
			
			if($admin){
				Flash::error("Error: Nombre usuario ya existe!!!");
			
			}else{
				
				if($this->getPostParam("password")!=$this->getPostParam("confirmar")){
					
					$sw=1;Flash::error("Error Contraseñas no son iguales..");
				}
				
				$admin= new Admin();
				
				if($sw==0){
					
					
					$admin->id                 = '0';
					$admin->username           = $this->getPostParam("username");
					$admin->password           = $this->getPostParam("password");
					$admin->nombre_completo    = $this->getPostParam("empleados");
					$admin->tipo_usuario       = $this->getPostParam("tipo_usuario");
					$admin->empleado_id        = $this->getPostParam("empleados_id");
					$admin->role               = $this->getPostParam("role");
									
					
					if($admin->save()){
						
						 
						   echo "<script>redireccionar_action('administrador/show/$admin->id');</script>";
						  /*echo "<script>redireccionar_action('menu');</script>";	*/
						   //return $this->redirect('menu');
					}else{
							
							Flash::error("Error: No se pudo insertar registro");
								
							foreach($empleado->getMessages() as $message):
							
								Flash::error("Error: ".$message);
							
							endforeach;
							
				 }
			  }
			}
					
		}
	
		public function updateAction($id){
			//primero instanciamos clase clientes
			$this->setResponse("view");
			$sw=0;
			$id = $_REQUEST["id"];
			
			//cargamos el objeto mediantes los metodos setters
			$admin = $this->Admin->findFirst("id = '$id'");
			$admin->username           = $_REQUEST["username"];
			$admin->password           = $_REQUEST["password"];
			$admin->nombre_completo	= $_REQUEST["empleados"];
			$admin->empleado_id	    = $_REQUEST["empleados_id"];
			$admin->role   	           = $_REQUEST["role"];
			
			if($_REQUEST['password']!=$_REQUEST['confirmar']){
				
				$sw=1;
				Flash::error("Error: Claves no son iguales...");
			}
			
			if($sw==0){	
						
			 if($admin->save()){
					Flash::success("Se actualizo correctamente el registro");
					echo "<script>redireccionar_action('administrador/modificar/$admin->id');</script>";
					
				}else{
					
					Flash::error("Error: No se pudo insertar registro");
		
			}
		  }
		  					
	    }
		
		public function showAction($id){
          
            $admin = $this->Admin->find("id='$id'");
            $this->setParamToView("admin", $admin);
	
		}
		
		 
		public function ajuste_inventario_negativoAction(){
			
		}
		
		

	}

?>