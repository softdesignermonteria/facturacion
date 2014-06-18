<?php 
		 
	
	 $sql="select count(*) as total_respuesta,pr.texto_pregunta,lm.valor,lm.texto 
	 from resultados_encuestas re,encuesta en,preguntas pr,lista_menu lm 
	 where re.id_encuesta=en.id and re.id_pregunta=pr.id 
	 and lm.id_encuesta+lm.id_pregunta+lm.valor=re.id_encuesta+re.id_pregunta+re.respuesta
	 and lm.id_encuesta=en.id and lm.id_pregunta=pr.id 
	 group by pr.texto_pregunta,lm.valor,lm.texto";
	 $r=mysql_query($sql." limit 1 ");
	 $fila=mysql_fetch_array($r);
	 $resultado=mysql_query($sql);
	 $resultado2=mysql_query($sql);
	 $resultado3=mysql_query($sql);
	 	
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/dclk100/table.css" type="text/css"/>
<title>.: MENÚ PRINCIPAL :.</title>
</head>

<body>
<table width="586" height="595" border="0">
  <tr>
    <td height="377"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="550" height="413" align="right" id="Column3D" >
      <param name="movie" value="FCF_MSColumn3D.swf" />
      <param name="FlashVars" value="&dataURL=grafico_barras.xml&chartWidth=250&chartHeight=309" />
      <param name="quality" value="high" />
      <embed src="FCF_MSColumn3D.swf" width="550" height="413" align="right" flashvars="&dataURL=grafico_barras1.xml&chartWidth=550&chartHeight=390" quality="high" name="FCF_MSColumn3D.swf" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />    
</object></td>
  </tr>
</table>
<?php 
 	include('FusionCharts_Gen.php');
	# Add category names
   	$FC = new FusionCharts("FCF_MSColumn3D.swf","100","50");
    # Set the relative path of the swf file
    $FC->setSWFPath("tablero/");
    # Store chart attributes in a variable
	$strParam="caption=Detalle de Tecnicos;subcaption=Comparacion;xAxisName=Examen;YAxisName=Resultados;formatNumberScale=0";
	$FC->setChartParams($strParam);
    
	   
	/*$FC = new FusionCharts("FCF_Pie3D.swf","250","300");
     # Set the relative path of the swf file
     $FC->setSWFPath("tablero/");*/
	 
	 //Creamos un archivo xml en tiempo de ejecucion y luego es leido para visualizar los datos.
	 $f = fopen("grafico_barras1.xml","w+"); 
	 
	 fwrite($f,"<graph xaxisname='Encuesta' caption='Resultados Encuesta' xAxisName='' yAxisName='' formatNumberScale='0' \n hovercapborder='889E6D' rotateNames='0' numdivlines='9' divLineColor='CCCCCC' \n divLineAlpha='80' showAlternateHGridColor='1' AlternateHGridAlpha='30' AlternateHGridColor='CCCCCC' subcaption=''>");
	 fwrite($f,"<categories font='Arial' fontSize='11' fontColor='000000'>");
	 while($fila1=mysql_fetch_array($resultado)){ 
	  fwrite($f,"<category name='$fila1[texto]'/>");
	 }
	 fwrite($f,"</categories>");
	 fwrite($f,"<dataset seriesname='$fila[texto_pregunta]' color='FDC12E'>");
	 while($fila2=mysql_fetch_array($resultado2)){ 
	 //$i=$i+1*rand(5, 1000);
	 fwrite($f,"<set value='$fila2[total_respuesta]'/>");
	 }
	 fwrite($f,"</dataset>");
	
	 fwrite($f,"</graph>");
	   
	  	
    ?>


    </td>
</tr>
</table>
</body>
</div>
</html>