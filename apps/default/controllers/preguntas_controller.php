<?php

	class PreguntasController extends ApplicationController {



		/**

		 * Inicializa el Controlador Login

		 *

		 */



		public function initialize() {

			$this->setTemplateAfter("adminiziolite");

		}



		/**

		 * Index por defecto del Controlador

		 *

		 */

		 

		public function indexAction(){

		

		}

		

		/**

		 * Aqui sale el formulario de Iniciar Sesión

		 *

		 */

		 

		public function LoginAction(){


		}
		
		public function agregarAction(){
					
        }

		
		public function addAction(){

			$this->setResponse('view');
			$sw=0;
			$preguntas  = new Preguntas();
			$fecha_creacion=date("Y-m-d H:i:s");
			
			$preguntas->id		 		  = '0';
			$preguntas->texto_pregunta      = $_REQUEST["texto_pregunta"];
			$preguntas->fecha_creacion      = $fecha_creacion;
			$preguntas->usuarios_id         = $_REQUEST["usuarios_id"];
			
			
			
			if($sw==0){		
						
				if($preguntas->save()){
					
					Flash::success(Router::getController()." Guardada Satisfactoriamente");
					echo "<script>quitar_mensajes();</script>";
					/*echo "<script>redireccionar_action('preguntas/agregar/?id_encuesta=$encuesta->id');</script>";*/
	
				}else{
				
					Flash::error("Error: No se pudo Guardar el registro...");	
					
					foreach($preguntas->getMessages() as $message){
							  Flash::error($message->getMessage());
					}	  
					
				}
			}
					

		 }

		 public function modificarAction($id){
			//$this->setResponse("ajax");
			
			if( isset($id) ){
					
				$id = $id;
						
				$preg = $this->Preguntas->findFirst(" id = '$id'");
			
						
				Tag::displayTo("id",$preg->id);
				Tag::displayTo("texto_pregunta",$preg->texto_pregunta);
				Tag::displayTo("fecha_creacion",$preg->fecha_creacion);
				Tag::displayTo("usuarios_id",$preg->usuarios_id);
	
				
			}else{
					Flash::error("Parametro Incorrecto Volver a Buscar Solicitud para modificar.");
				}
		}
		
		public function updateAction(){
			//primero instanciamos clase clientes
			
			$this->setResponse("view");
			$id = $_REQUEST["id"];
			//cargamos el objeto mediantes los metodos setters
			$pregunta = $this->Preguntas->findFirst("id = '$id'");
			
			$pregunta->texto_pregunta  = $_REQUEST["texto_pregunta"];
			//$pregunta->usuarios_id     = $_REQUEST["usuarios_id"];
													
			if($pregunta->save()){
				Flash::success("Se actualizo correctamente el registro");
				echo "<script>redireccionar_action('preguntas/modificar/$pregunta->id');</script>";
				
			}else{
				Flash::error("Error: No se pudo actualizar registro");	
			}
					
					
	    }
		
		public function eliminarAction($id){
			//$this->setResponse("ajax");
			if( isset($id_pregunta) ){
				
				$preg = $this->Preguntas->findFirst(" id = '$id' ");
			
						
				Tag::displayTo("id",$preg->id);
				Tag::displayTo("texto_pregunta",$preg->texto_pregunta);
				Tag::displayTo("fecha_creacion",$preg->fecha_creacion);
				Tag::displayTo("usuarios_id",$preg->usuarios_id);
				
				
			}else{
					Flash::error("Parametro Incorrecto Volver a Buscar ".strtoupper(Router::getController())." para modificar.");
				}
		}
		
		public function deleteAction(){
			//primero instanciamos clase clientes
			$this->setResponse("view");
			
			$id_pregunta=$_REQUEST['id_pregunta'];
			$id_usuario=$_REQUEST['usuarios_id'];
			
			if($this->Preguntas->findFirst(" id_pregunta = '$id_pregunta' and usuarios_id='$id_usuario'")==0){
				
				$sw=1;
				Flash::error("Usted no está autorizado para eliminar la pregunta....");
				
			}else{			
			
			
			if($sw==0){
				
				$preg = $this->Preguntas->findFirst(" id_pregunta = '$id_pregunta' and usuarios_id='$id_usuario'");
				
				if($preg->delete()){
					
					Flash::success("Se elimino correctamente el registro");
					
				}else{
					Flash::error("Error: No se pudo borrar registro");	
				}
			  }//fin todo bien sw==0;
		    }
		}
		
		
		public function asociar_preguntasAction(){
					
        }
		
		public function add_valor_listaAction(){
			
			$this->setResponse("ajax");
			$sw=0;
			$lista  = new ListaMenu();
			
					
			$lista->id			  	  = '0';
			
			if(($_REQUEST["id"]=="")&&($_REQUEST["valorlista"]=="")&&($_REQUEST["texto"]=="")){
			
			Flash::error("Verifique la encuesta y el valor que va a guardar en la lista");
			
			}else{
			
			$lista->id			  	  = '0';
			$lista->id_encuesta		 = $_REQUEST['id'];
			$lista->id_pregunta		 = $_REQUEST['id_pregunta'];
			$lista->texto		       = $_REQUEST['texto'];
			$lista->valor		       = $_REQUEST['valorlista'];
						
			if($sw==0){		
						
				if($lista->save()){
					
					Flash::success("Valor asociado al lista/menú Correctamente");
					/*echo "<script>quitar_mensajes();</script>";*/
					/*echo "<script>redireccionar_action('preguntas/agregar/?id_encuesta=$encuesta->id');</script>";*/
	
				}else{
				
					Flash::error("Error: No se pudo Guardar el registro...");	
					
					foreach($lista->getMessages() as $message){
							  Flash::error($message->getMessage());
					}	  
					
				}
			 }
		   }
        }
		
		public function asociar_preguntas_encAction(){
			

			$this->setResponse('view');
			$sw=0;
			$form_enc  = new FormularioEncuesta();
			
		
			$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
			$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			$form_enc->valor              = $_REQUEST["valor"];
			
			if($_REQUEST["id_tipo_pregunta"]==1){
			
			$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
			$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			$form_enc->etiqueta_html      = "<input type='text' id='t".rand(5,15)."' name='lista[]'/>";
			$form_enc->valor              = $_REQUEST["valor"];
				
			}
			
			if($_REQUEST["id_tipo_pregunta"]==2){
			
			$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
			$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			$form_enc->etiqueta_html      = "<textarea id='ta".rand(5,15)."' cols='20' rows='3' name='lista[]'></textarea>";
			$form_enc->valor              = $_REQUEST["valor"];
				
			}
			
			if($_REQUEST["id_tipo_pregunta"]==3){
				
				/*Verifico que exista una pregunta!!, si la respuesta es verdadera le asocio el otro componente de 
				  tipo casilla de verificacion, sino creo la otra pregunta con el mismo componente en mención*/
				  
				$form_enc = $this->FormularioEncuesta->findFirst("id_encuesta = '".$_REQUEST["encuesta_id"]."' and id_pregunta = '".$_REQUEST["preguntas_id"]."' and id_tipo_pregunta = '".$_REQUEST["id_tipo_pregunta"]."'");
					
				if($form_enc!=false){
				
			    $form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			    $form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			    $form_enc->etiqueta_html      = $form_enc->etiqueta_html."<input type='radio' id='ra".rand(5,15)."' value='".$_REQUEST["valor"]."' name='lista[]'/><label for='ra".rand(5,15)."'>$_REQUEST[valor]</label>";
			    $form_enc->valor              = $_REQUEST["valor"];
					
				}else{
					
				$form_enc  = new FormularioEncuesta();
									
				$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
				$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
				$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
				$form_enc->etiqueta_html      = "<input type='radio' id='ra".rand(5,15)."' value='".$_REQUEST["valor"]."' name='lista[]'/><label for='ra".rand(5,15)."'>$_REQUEST[valor]</label>";
				$form_enc->valor              = $_REQUEST["valor"];
								
				}
				 
			}
			
						
			if($_REQUEST["id_tipo_pregunta"]==4){
				
				/*Verifico que exista una pregunta!!, si la respuesta es verdadera le asocio el otro componente de 
				  tipo casilla de verificacion, sino creo la otra pregunta con el mismo componente en mención*/
				  
				$form_enc = $this->FormularioEncuesta->findFirst("id_encuesta = '".$_REQUEST["encuesta_id"]."' and id_pregunta = '".$_REQUEST["preguntas_id"]."' and id_tipo_pregunta = '".$_REQUEST["id_tipo_pregunta"]."'");
					
				if($form_enc!=false){
				
			    $form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			    $form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
				$temp=rand(5,15);
			    $form_enc->etiqueta_html      = $form_enc->etiqueta_html."<input type='checkbox' id='chbox".$temp."' value='".$_REQUEST["valor"]."' name='lista[]'/><label for='chbox".$temp."'>$_REQUEST[valor]</label>";
			    $form_enc->valor              = $_REQUEST["valor"];
					
				}else{
					
				$form_enc  = new FormularioEncuesta();
									
				$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
				$form_enc->id_pregunta        = $_REQUEST["id_pregunta"];
				$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
				$temp=rand(5,15);
				$form_enc->etiqueta_html      = "<input type='checkbox' id='chbox".$temp."' value='".$_REQUEST["valor"]."' name='lista[]'/><label for='chbox".$temp."'>$_REQUEST[valor]</label>";
				$form_enc->valor              = $_REQUEST["valor"];
								
				}
				 
			}
			
			if($_REQUEST["id_tipo_pregunta"]==5){
			
			$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
			$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			$temp=rand(5,15);
			/*onclick='javascript:validar();*/
			$form_enc->etiqueta_html      = "<select id='select".$temp."' name='lista[]'>";
			$form_enc->valor              = $_REQUEST["valor"];
				
			}
			
			if($_REQUEST["id_tipo_pregunta"]==6){
				
			$temp=rand(5,10);
			
			$form_enc->id_encuesta		= $_REQUEST["encuesta_id"];
			$form_enc->id_pregunta        = $_REQUEST["preguntas_id"];
			$form_enc->id_tipo_pregunta   = $_REQUEST["id_tipo_pregunta"];
			$temp=rand(5,15);
			$form_enc->etiqueta_html      = "<input type='text' id='f".$temp."' name='lista[]'/><script>jQuery(document).ready(function(){if( jQuery( '#f".$temp."')){ jQuery( '#f".$temp."').datepicker({showButtonPanel: true, dateFormat: 'yy-mm-dd'});}});</script>";
			$form_enc->valor              = $_REQUEST["valor"];
				
			}
			//Tag::select('id_tipo_pregunta',$tipo_pregunta->find('order: id'), 'using: id,descripcion')
						
			if($sw==0){		
						
				if($form_enc->save()){
					
					Flash::success(Router::getController()." Guardada Satisfactoriamente");
					echo "<script>quitar_mensajes();</script>";
					/*echo "<script>vista_preliminar('encuesta/show/$form_enc->id_encuesta');</script>";*/
	
				}else{
				
					Flash::error("Error: No se pudo Guardar el registro...");	
					
					foreach($form_enc->getMessages() as $message){
							  Flash::error($message->getMessage());
					}	  
					
				}
			}
			
			
						
		}
		

		 public function not_found($controller, $action){

				 $this->set_response('view');

				 Flash::error("No esta definida la accion $action, redireccionando");

				 return $this->redirect('administrador');

				 

		 }


		public function buscarAction(){
		
		}
		
		
		
		public function find_buscarAction(){
				$this->setResponse('view');		
		}
		
		public function find_detalle_buscarAction(){
				$this->setResponse('view');
		}
		
		public function validarAction($id,$opcion){
			
			$this->setResponse("view");
			
			$sw=0;
			$preg = new Preguntas();
			if( $this->Preguntas->count(" id = '$id' ") > 0 ){
				$preg = $this->Preguntas->findFirst(" id = '$id' ");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#".$opcion."_id\").val(\"$preg->id\");jQuery(\"#$opcion\").val(\"\");jQuery(\"#$opcion\").val(\"".$preg->texto_pregunta."\");</script>";
				
			}else{
				Flash::error("No se Encontro Solicitud");
				echo "<script>jQuery(\"#".$opcion."_id\").val(\"\");jQuery(\"#$opcion\").val(\"\");</script>";
			}
			
			
			//public $scaffold = false;
			
			
			
		}
		

		public function no_accesoAction(){

				$this->set_response('view');

				 Flash::error("No esta definida la accion $action, redireccionando");

				 return $this->redirect('administrador');

		}

			

		/**

		 * Validacion si el login y el password son correctos

		 */

		public function validaAction(){
			
				$this->setResponse('view');

				$Usuario = new Admin();
				$usuario = $Usuario->findFirst("username = '".$_REQUEST['login']."'
							and password = '".md5($_REQUEST['password'])."'");
				
				
				if($usuario){
				
					$Empresa = new Empresa();
					$emp = $Empresa->findFirst("id = '".$_REQUEST['empresa']."'");
					
					$empleado = $this->Empleado->findFirst(" id = '$usuario->empleado_id' ");
					
					if(!$emp){
						Flash::error('Usuario/Clave incorrectos');
						Flash::error('POR FAVOR ESCOJA UNA EMPRESA/O CREE UNA EMPRESA PREVIAMENTE');
						return false;
					}
					
					 $db = DbBase::rawConnect();
			  		 $db->query("select * from access_list where role = '$usuario->role' ");
					 
					 if($db->numRows()==0 ){
						   Flash::notice("otra cosa");
						     Flash::notice("Rol no existe en la tabla de permisos '$usuario->role'");
						   $authLog = new Logger("File", "auth_failed.txt");
						   $authLog->log("Rol no existe en la tabla de permisos '$usuario->role'");
						   return false;
					  } 
					
					/**
					 * Almaceno en la variable de session el id del usuario
					 * autenticado
					 */
					Session::set('admin_username'      , $usuario->username);
					Session::set('tipo_usuario'        , $usuario->tipo_usuario);
					Session::set('usuarios_id'         , $usuario->id);
					Session::set('nombre_completo'     , $empleado->nombre_completo);
					Session::set('usuario_autenticado' , true);
					Session::set('id_empresa'          , $emp->id);
					Session::set('nombre_empresa'      , $emp->nombre_empresa);
					Session::set('kardex_id_default'   , $emp->kardex_id_default);
					Session::set('id_empleado'         , $empleado->id);
					Session::set('nombre_empleado'     , $empleado->nombre_completo);
					Session::set('regimen'             , $emp->regimen_id);
					Session::set('role'                , $usuario->role);
					Session::set('cobro_tarifa'        , $emp->cobro_tarifa);
					
					/**

					 * Lo redireccionos al formulario de clientes

					 */
						Flash::success('Logueado');
						//$this->setResponse("ajax");
						echo "<script>redireccionar_action('home/aplicaciones');</script>";

				} else {

					Flash::error('Usuario/Clave incorrectos');

					//return $this->routeTo('action: login');

				}



		}
		
		public function eliminar_item_encuestaAction(){
			
		$this->setResponse('view');
			
			$formulario_encuesta  = new FormularioEncuesta();
			
			$id_encuesta=$_REQUEST['id_encuesta'];
			
			if($formulario_encuesta->delete(" id = '".$_REQUEST["id"]."' ")){
				
				Flash::success("Detalle egreso Eliminado Satisfactoriamente");

			}else{
			
				Flash::error("Error: No se pudo Eliminar y Guardar el registro...");	
				
				foreach($formulario_encuesta->getMessages() as $message){
				          Flash::error($message->getMessage());
				}	  
				
			}
			
			//$this->setParamToView("formulario_encuesta",$this->FormularioEncuesta->find("id_encuesta = '".$_REQUEST["id_encuesta"]."' and id_pregunta='".$_REQUEST['id_pregunta']."' and id_tipo_pregunta='".$_REQUEST['id_tipo_pregunta']."'"));
			
		}

		/*

		* Salir Sale del sistema y cierra todas las variables 

		* de las sessiones activas para este hilo de conexion

		*/

		

		public function salirAction(){

			

				Session::unsetData('admin_username');

				Session::unsetData('role');

				Session::unsetData('usuarios_id');

				Session::unsetData('nombre_completo');

				Session::unsetData('usuario_autenticado');

		

			Flash::notice('Has salido Gracias');

			return $this->routeTo('action: login');

		}
		
		public function showAction($id){
          
            $formulario_encuesta = $this->FormularioEncuesta->find(" id_encuesta = '$id' ");
			$encuesta = $this->Encuesta->findFirst(" id = '$id' ");
			
            $this->setParamToView("formulario_encuesta", $formulario_encuesta);
			$this->setParamToView("encuesta", $encuesta);
			//$this->setParamToView("preguntas", $preguntas);
		
	
		}
		
	
		/*public function showAction($id){

          

            $admin = $this->Admin->find("id='$id'");

            $this->setParamToView("admin", $admin);

	

		}*/
	

	}



?>