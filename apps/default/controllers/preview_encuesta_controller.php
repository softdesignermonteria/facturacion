<?php

	class preview_encuestaController extends ApplicationController {
		
		
		public function initialize() {

			$this->setTemplateAfter("adminiziolite");

		}
		
		
		public function add_resultados_encAction($id){
			
			$formulario_encuesta = $this->FormularioEncuesta->find(" id_encuesta = '$id' ");
			$encuesta = $this->Encuesta->findFirst(" id = '$id' ");
			
            $this->setParamToView("formulario_encuesta", $formulario_encuesta);
			$this->setParamToView("encuesta", $encuesta);
			//$this->setParamToView("preguntas", $preguntas);
					
        }
		
		public function resultadosAction(){
					
        }
		
		public function add_resultados_encuestasAction(){
			
			 $this->setResponse('view');
			 $lista=$_REQUEST['lista'];
			 $preguntas=$_REQUEST['preguntas'];
			 $tipo_pregunta=$_REQUEST['tipo_pregunta'];
			 $id_encuesta=$_REQUEST['id_encuesta'];
			 //$id_encuesta=$id;
			 
			 $enc= $this->Encuesta->findFirst("id='$id_encuesta'");
			 
			 $hoy = date("Y-m-d");
									 
			 if(($hoy<=$enc->fecha_cierre)&&($enc->estado=='0')){
			 /*Calculo el tamaño del array, para luego recorrerlos cuantas veces sea necesario en el for, y hacer el respectivo
			 insert en la tabla resultados_encuestas */
			 $tamano=sizeof($preguntas);
						  
			
			 $res_enc  = new ResultadosEncuestas();
			 
			 
			 for($i=0;$i<$tamano;$i++){
			 	
			 $sw=0;
			 $res_enc->id=  '0';	
			 $res_enc->id_encuesta      =  $id_encuesta[0] ;
			 $res_enc->id_pregunta      =  $preguntas[$i] ;
			 $res_enc->id_tipo_pregunta =  $tipo_pregunta[$i] ;
			 $res_enc->respuesta        =  $lista[$i];
			 
					
			 if($sw==0){		
						
				if($res_enc->save()){
					
					Flash::success(Router::getController()." Guardada Satisfactoriamente");
					echo "<script>redireccionar_action('graficosxml/grafico1/?id_encuesta=$enc->id');</script>";
	
				}else{
				
					Flash::error("Error: No se pudieron guardar los resultados...");	
					
						foreach($res_enc->getMessages() as $message){
								  Flash::error($message->getMessage());
						}
			        }
			     }
		      }
		    
			}else{
			       Flash::error("Lo sentimos: Pero la encuesta no está habilitada, comuniquese con el administrador del sistema.");
		  }
		}
		
		
		public function enviar_encuestaAction(){
			
				$emp = $this->Empresa->findFirst(" id  = '3' ");
				
				$db = DbBase::rawConnect();
				$sql = "SELECT 
						  clientes.id as clientes_id,
						  clientes.razon_social,
						  clientes.correo,
						  kardex.id as kardex_id,
						  kardex.nombre_producto,
						  kardex.valor_tiempo,
						  factura.id as factura_id,
						  factura.prefijo,
						  factura.consecutivo,
						  tg.tiempo,
						  factura.fecha,
						  tg.tiempo_garantia,
						  DATE_ADD(factura.fecha, interval(kardex.valor_tiempo * tg.tiempo) Day) AS vencimiento,
						  DATEDIFF( DATE_ADD(factura.fecha, interval(kardex.valor_tiempo * tg.tiempo) Day), '2014-03-05') AS diferencia
						FROM
						  factura
						  INNER JOIN detalle_factura ON (factura.id = detalle_factura.factura_id)
						  INNER JOIN clientes ON (factura.clientes_id = clientes.id)
						  INNER JOIN kardex ON (detalle_factura.kardex_id = kardex.id)
						  INNER JOIN tiempo_garantia tg ON (kardex.tiempo_garantia_id = tg.id)
						WHERE
						  1 = 1
						  and DATEDIFF(DATE_ADD(factura.fecha, interval(kardex.valor_tiempo * tg.tiempo) Day), '2014-03-05') <= 30
						  and DATEDIFF(DATE_ADD(factura.fecha, interval(kardex.valor_tiempo * tg.tiempo) Day), '2014-03-05') > 0
						  and (factura.prefijo + factura.consecutivo  + kardex.id) 
						  			not in ( select (prefijo + consecutivo + kardex_id) from correossend where enviado = '1' )
						  ";
				$result = $db->query($sql);	
				
				while($fila = $db->fetchArray($result)){	
				
					try{	
			
					/**/
					$mail = new PHPMailer;

					//$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->IsSendmail();
					$mail->MailerDebug = true;
					//$mail->SMTPDebug  = 1;
					$mail->SMTPAuth = true;                               // Enable SMTP authentication
					$mail->Host = "smtp.gmail.com";  // Specify main and backup server
					
					//$mail->Port = 587;                               // Enable SMTP authentication
					
					$mail->Username = "$emp->remitente";                            // SMTP username
					$mail->Password = "$emp->password";                           // SMTP password
					$mail->SMTPSecure = 'tsl';                            // Enable encryption, 'ssl' also accepted
					
					$mail->From = "$emp->remitente";
					$mail->FromName = "Software: Factutesis";
					
					$mail->addAddress("".$fila["correo"]."","".$fila["razon_social"]."");  // Add a recipient
					//$mail->addAddress('ellen@example.com');              // Name is optional
					
					$mail->addReplyTo("administrador@softdesignermonteria.net", "Alejadro Betancourt");
					
					//$mail->addCC('cc@example.com');
					//$mail->addBCC('bcc@example.com');
					
					$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
					//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
					//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
					$mail->isHTML(true);                                  // Set email format to HTML
					
					$mail->Subject = 'Encuesta';
					
						$cuerpoMensaje = "<h1>Encuesta de Satisfaccion del cliente</h1>";
						$cuerpoMensaje.= "<p>Por favor llene Nuestra encuesta de Satisfaccion sobre el servicio prestado por nosotros</p>";
						
						foreach($this->Encuesta->find(" correo = '1' ") as $encuesta):
							$cuerpoMensaje.= "<h2>$encuesta->encabezado</h2>";
							$cuerpoMensaje.= "<p><a href=\"http://107.170.28.129/factutesis/preview_encuesta/add_resultados_enc/$encuesta->id\" target='_blank'>Haga Click Aqui para ir a esta Encuesta</a></p>";
		
						endforeach;
						
					$mail->Body    = "$cuerpoMensaje";
					$mail->AltBody = "Email generado automaticamente por el software Factutesis ".date("Y-m-d");
					
					if(!$mail->send()) {
					   Flash::error('Message could not be sent.');
					   Flash::error('Mailer Error: ' . $mail->ErrorInfo);
					   $sw=0;
					   //Flash::error(print_r($mail));
					   //exit;
					}else{
						$sw=1;
						 Flash::success('Mensaje enviado satisfactoriamente');
					}
					
					$correo = new Correossend();
					$correo->remitente = $emp->remitente;
					$correo->nombre_remitente = "Software: Factutesis";
					$correo->factura_id = $fila["factura_id"];
					$correo->prefijo = $fila["prefijo"];
					$correo->consecutivo = $fila["consecutivo"];
					$correo->kardex_id = $fila["kardex_id"];
					$correo->nombre_producto = $fila["nombre_producto"];
					$correo->fecha = date("Y-m-d");
					$correo->clientes_id = $fila["clientes_id"];
					$correo->correo = $fila["correo"];
					$correo->razon_social = $fila["razon_social"];
					$correo->asunto = "Encuesta";
					$correo->mensaje = $cuerpoMensaje;
					$correo->enviado = $sw;
					
					
					if(!$correo->save()){
							Flash::error("error guardando registro");
							foreach($correo->getMessages() as $message){
							Flash::error($message->getMessage());
							}
						}
						
					} catch (phpmailerException $e) {
						Flash::error( $e->errorMessage() ); //Pretty error messages from PHPMailer
					} catch (Exception $e){
						Flash::error( $e->getMessage() );
					}	
				
				} /**fin while*/
				
					
        }
		
		
	}
	
?>