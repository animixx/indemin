<?php

namespace Eye3\ControlBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Eye3\ControlBundle\Entity\Datos;

$JPGraphSrc = '/home2/animixco/public_html/indemin/src/JPGraph/src';
require_once ($JPGraphSrc.'/jpgraph.php');
require_once ($JPGraphSrc.'/jpgraph_line.php');
require_once ($JPGraphSrc.'/jpgraph_bar.php');
require_once ($JPGraphSrc.'/jpgraph_pie.php');
require_once ($JPGraphSrc.'/jpgraph_plotline.php');

/**
 * Class RegistroController
 *
 * @Route("/reportes")
 *
 * @package Eye3\ControlBundle\Controller
 */
class ReporteController extends Controller
{

    /**
     * 
     * @Route("/{fecha}/{descarga}", requirements={"fecha" = "[0-9]{4}W0*([1-9]|[1-4][0-9]|5[0-2])","descarga"="[ID]"}, name="reporte_semanal")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function semanalAction($fecha='2013W36',$descarga="I")
    {
		$date= date_create($fecha);
		// print_r($date);exit;
		// $meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		$semana= $date->modify('this Monday')->format('j/m')." al ".$date->modify('next Sunday')->format('j/m');
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana($fecha);
        $detalleG1 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana2($fecha);
        $detalleG2 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana2($fecha,"Grúa-2");
		
		$mpdfService = $this->get('tfox.mpdfport');
		$mPDF = $mpdfService->getMpdf( array( '','Letter-L'));
	
		$stylesheet = file_get_contents('bundles/eye3control/css/uncompressed/bootstrap.css');
		$stylesheet2 = file_get_contents('bundles/eye3control/css/uncompressed/ace.css');
						
		$mPDF->WriteHTML($stylesheet,1);
		$mPDF->WriteHTML($stylesheet2,1);
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte.html.twig', array(
								'titulo' => 'semanal - '.$semana.' '." del ".$date->format('Y'),
								'graficos' => 'semana '.$semana,
								'datos' => $datos,
													) ), 2);
		if (count($datos)>0)
		{
			$mPDF->AddPage();
			$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte2s.html.twig', array(
								'datos1' => $detalleG1,
								'datos2' => $detalleG2,
								'graficos' => 'semana '.$semana,
								'primer' => $date,
													) ), 2);
		}
		$mPDF->Output(preg_replace('/\s+/', '','Reporte'.$semana."'".$date->format('y').'-indemin.pdf'),'I');


    }

    /**
     * 
     * @Route("/mensual/{fecha}/{descarga}", requirements={"fecha" = "[0-9]{4}/[0-9]{1,2}","descarga"="[ID]"}, name="reporte_mensual")
     * @Template("Eye3ControlBundle:Reporte:index.html.twig")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function mensualAction($fecha='2014/6',$descarga="I")
    {
		$date= date_create($fecha."/1");
		
		$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes($fecha);
        $detalleG1 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes2($fecha);
        $detalleG2 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes2($fecha,"Grúa-2");
		
		$mpdfService = $this->get('tfox.mpdfport');
		$mPDF = $mpdfService->getMpdf( array( '','Letter-L' ));
	
		$stylesheet = file_get_contents('bundles/eye3control/css/uncompressed/bootstrap.css');
		$stylesheet2 = file_get_contents('bundles/eye3control/css/uncompressed/ace.css');
		
		$mPDF->WriteHTML($stylesheet,1);
		$mPDF->WriteHTML($stylesheet2,1);
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte.html.twig', array(
								'titulo' => 'mensual - '.$meses[($date->format('n'))-1]." ".$date->format('Y'),
								'graficos' => 'mes '.$meses[($date->format('n'))-1],
								'datos' => $datos,
													) ), 2);
	
		if (count($datos)>0)
		{
			$mPDF->AddPage();
			
			$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte2.html.twig', array(
									'datos1' => $detalleG1,
									'datos2' => $detalleG2,
									'graficos' => 'mes '.$meses[($date->format('n'))-1],
									'primer' => $date->modify('first Monday of this month'),
														) ), 2);
		}
		$mPDF->Output('Reporte'.$meses[($date->format('n'))-1].$date->format('Y').'-indemin.pdf',$descarga);
		
    }
	
   /**
     * 
     * @Route("/imagen", name="grafico")
	 *
     * @return response
     */
    public function graficaAction( $tipo, array $valores, $fecha = null, $titulo = null ) {
	
		
		if ($tipo == "pie" )
		{
		     
			foreach ($valores as $key => $value)
			{
			$data[]=$value;
			$labels[]=$key[0].$key[7]."(%.1f%%)";
			$leyenda[]=$key;
			}
			
			// Create the Pie Graph.
			$graph = new \PieGraph(250,250);
			$graph->SetShadow();

			// Set A title for the plot
			$graph->title->Set('Tiempo Total Operación');
			// $graph->title->SetFont(FF_VERDANA,FS_BOLD,12);
			$graph->title->SetColor('black');
			$graph->legend->SetShadow('gray@0.4',5);
			$graph->legend->SetColumns(2);
			$graph->legend->SetPos(0,0.99,'right','bottom');
			

			// Create pie plot
			$p1 = new \PiePlot($data);
			$p1->SetLegends($leyenda);
			$p1->SetCenter(0.5,1);
			$p1->SetSize(0.25);

			// Setup the labels to be displayed
			$p1->SetLabels($labels);

			// This method adjust the position of the labels. This is given as fractions
			// of the radius of the Pie. A value < 1 will put the center of the label
			// inside the Pie and a value >= 1 will pout the center of the label outside the
			// Pie. By default the label is positioned at 0.5, in the middle of each slice.
			$p1->SetLabelPos(0.6);
			
			// Enable and set policy for guide-lines. Make labels line up vertically
			$p1->SetGuideLines(true,false);
			$p1->SetGuideLinesAdjust(1.1);

			// Setup the label formats and what value we want to be shown (The absolute)
			// or the percentage.
			$p1->SetLabelType(PIE_VALUE_PER);
			$p1->value->Show();
			// $p1->value->SetFont(FF_ARIAL,FS_NORMAL,9);
			$p1->value->SetColor('darkgray');

			// Add and stroke
			$graph->Add($p1);
			// // $graph->Stroke();

		}
		elseif ($tipo == "columnas")
		{	
			foreach ($valores as $key => $value)
			{
			$dirije="data".++$aux;
			$$dirije=array(($aux-1)=>round($value));
			$etiquetas[]=$key[0].$key[7];
			$temp+=$value;
			$min=($value<$min || is_null($min))?$value:$min;
			}
			$prom= $temp/$aux;

			// Create the graph. 
			$graph = new \Graph(300,250);	
			$graph->SetScale('textlin');

			$graph->img->SetMargin(70,20,20,20);
			$graph->SetShadow();

			
			$sline = new \PlotLine(HORIZONTAL,$prom,"red",2); 
			$sline->SetLegend("Promedio Global"); 
			$sline->SetLineStyle("longdashed"); 
			$sline->SetColor("orange");
			// $sline->value->Show();
			// $sline->value->SetFormat('%d');
			// $sline->value->SetColor("black");
			

			$graph->Add($sline);
			for ($cuenta=1;$cuenta<=$aux;$cuenta++ )
			{
				$barras="bplot".$cuenta;
				$datografico="data".$cuenta;
				$$datografico=array_replace( array_fill(0,$aux,null),$$datografico);
				
				// Create the bar plots
				$$barras = new \BarPlot($$datografico);
				$$barras->SetValuePos('center');
				$graph->Add($$barras);
				$$barras->value->SetAngle(90);
				$$barras->value->SetFormatCallback('gmdate');
				$$barras->value->SetColor('red');
				$$barras->value->SetFormat('i:s');
				$$barras->value->Show();
			}	
			$graph->yaxis->scale->SetAutoMin($min-5);
			$graph->legend->SetShadow('gray@0.4',5);
			$graph->legend->SetPos(0,0.99,'right','bottom');

			$graph->title->Set('Tiempo de ciclo promedio');
			$graph->xaxis->SetTitle('Camiones','center');
			$graph->yaxis->SetTitle('Tiempo','center');
			$graph->yaxis->SetTitleMargin(45);

			// $graph->title->SetFont(FF_FONT1,FS_BOLD);
			// $graph->yaxis->title->SetFont(FF_FONT1,FS_BOLD);
			// $graph->xaxis->title->SetFont(FF_FONT1,FS_BOLD);

			$graph->xaxis->SetTickLabels($etiquetas);
			$graph->yaxis->SetLabelFormatString("i:s",true);
			//$graph->xaxis->SetTextTickInterval(2);

		
		} 
		elseif ($tipo == "multiple")
		{	
			
			
			foreach ($valores as $camiones=>$datos)
			{
				$data=array();
				$etiquetas=array();
				foreach ($datos as $semana=>$valores)
					{
						
						$data[]=$valores;
						$etiquetas[]=$semana;
						
					}
				$plot = new \BarPlot($data);
				$plot->SetLegend($camiones);
				$bplot[] = $plot;
				
			}
	// print_r($data);exit;		
			//verifica si el grafico a mostrar es mensual o semanal
				//mensual, partiendo de un arreglo vacio en la semana coloca "1 al 'domingo de esa semana'", ó(si) es el ultimo item del arreglo coloca el "'lunes de la semana' al 'ultimo dia del mes'" , ó coloca el "'lunes de la semana' al 'domingo de la semana'"
				//semanal, genera un arreglo con los 7 dias
			if ($titulo[0]=="m")
				foreach ($etiquetas as $semanas)
				{
					if ($etiqueta_full == null )
						$etiqueta_full[]= "1 al ".date( "j", strtotime((date("Y",$fecha->getTimestamp()))."W".($semanas-1)."7") );
					elseif ($semanas == end($etiquetas))
						$etiqueta_full[]= date( "j", strtotime((date("Y",$fecha->getTimestamp()))."W".($semanas-1)."1") )." al ".date("d", strtotime("last day of this month",$fecha->getTimestamp()));
					else
						$etiqueta_full[]= date( "j", strtotime((date("Y",$fecha->getTimestamp()))."W".($semanas-1)."1") )." al ".date( "j", strtotime((date("Y",$fecha->getTimestamp()))."W".($semanas-1)."7") );
				}
			elseif ($titulo[0]=="s")
				for($counter=0;$counter<7;$counter++)
					{
						$etiqueta_full[]=date("d/m",$fecha->getTimestamp());
						$fecha->modify('+1 day');
					}

			// Create the graph. 
			$graph = new \Graph(600,450);	
			$graph->SetScale("textlin");
			$graph->SetShadow();

			$graph->img->SetMargin(70,20,-40,-40);
			$graph->SetShadow();

			// Create the grouped bar plot
			$gbplot = new \GroupBarPlot($bplot);
			$gbplot->SetWidth(0.9);

			// ...and add it to the graPH

			$graph->Add($gbplot);

			$graph->title->Set('Operacion camiones / '.$titulo);
			$graph->xaxis->SetTitle((($titulo[0]=="m")?'Semanas':'Dias'),'center');
			$graph->yaxis->SetTitle('Promedio Operacion','center');
			$graph->yaxis->SetTitleMargin(45);

			$graph->xgrid->Show(); 
						
			// $bplot->value->SetAngle(90);
			// $bplot->value->SetFormat('i:s');
			// $bplot->value->SetFormatCallback('gmdate');
			// $bplot->value->SetColor('red','darkred');
			$bplot[0]->value->Show();
	// print_r($gbplot);exit;
			$graph->legend->SetShadow('gray@0.4',5);
			$graph->legend->SetPos(0,0.99,'right','bottom');

			


			$graph->xaxis->SetTickLabels($etiqueta_full);
			$graph->yaxis->SetLabelFormatString("i:s",true);

		
		} 
		  
		 // Display the graph
			$gdImgHandler = $graph->Stroke(_IMG_HANDLER);
			//Start buffering
			ob_start();      
			//Print the data stream to the buffer
			$graph->img->Stream(); 
			//Get the conents of the buffer
			$image_data = ob_get_contents();
			//Stop the buffer/clear it.
			ob_end_clean();
			//Set the variable equal to the base 64 encoded value of the stream.
			//This gets passed to the browser and displayed.
			$image = base64_encode($image_data);
		
			 $response = new Response($image, 200);
	
			return $response;
			
		
			
			
    }
	
   /**
     * 
     * @Route("/excel/{fecha}",requirements={"fecha" = "[0-9]{4}W0*([1-9]|[1-4][0-9]|5[0-2])"}, name="genera_excel")
	 *
     * @return response
     */
    public function excelAction($fecha = "2013W36"  ) {
	
		$date= date_create($fecha);
		$hasta = $date->modify('next Monday')->format('Y-m-d');
		$domingo = $date->modify('last Sunday')->format('j/m');
		$desde = $date->modify('last Monday')->format('Y-m-d');
		
		$semana= $date->format('j/m')." al ".$domingo;
		
		
		$em = $this->getDoctrine()->getManager();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->historial_excel($desde,$hasta);
		


		 // ask the service for a Excel5
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

		$phpExcelObject->getProperties()->setCreator("Eye3")
			->setLastModifiedBy("Eye3 Online Monitor")
			->setTitle("Office 2005 XLSX Test Document")
			->setSubject("Office 2005 XLSX Test Document")
			->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
			->setKeywords("office 2005 openxml php")
			->setCategory("Test result file");
		$phpExcelObject->setActiveSheetIndex(0)
			->setCellValue('A1', 'ID')
			->setCellValue('B1', 'Camión')
			->setCellValue('C1', 'Grúa')
			->setCellValue('D1', 'Inicio')
			->setCellValue('E1', 'Duración');
		$phpExcelObject->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
				$aux=2;
				foreach ($datos as $llenado)
						{	
							
							 $phpExcelObject->getActiveSheet()->setCellValue('A'.$aux, $llenado['id'])
							->setCellValue('B'.$aux, $llenado['camion'])
							->setCellValue('C'.$aux, $llenado['grua'])
							->setCellValue('D'.$aux, $llenado['inicio'])
							->setCellValue('E'.$aux++, $llenado['duracion']);
						}
		   
		$phpExcelObject->getActiveSheet()->setTitle('Historial');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$phpExcelObject->setActiveSheetIndex(0);

		// create the writer
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		// create the response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		// adding headers
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment;filename='.preg_replace('/\s+/', '','Historial'.$semana."'".$date->format('y').'-indemin.xlsx'));
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');

		return $response;
			
    }
	
   /**
     * 
     * @Route("/excel/mensual/{fecha}", requirements={"fecha" = "[0-9]{4}/[0-9]{1,2}"}, name="genera_excel_mes")
	 *
     * @return response
     */
    public function excelMensualAction($fecha = "2014/6"  ) {
		
		$date= date_create($fecha."/1");
		$hasta = $date->modify('first day of next month')->format('Y-m-d');
		$desde = $date->modify('first day of last month')->format('Y-m-d');
		
		$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		
		$em = $this->getDoctrine()->getManager();
        $datos = $em->getRepository('Eye3ControlBundle:Datos')->historial_excel($desde,$hasta);
		


		 // ask the service for a Excel5
		$phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

		$phpExcelObject->getProperties()->setCreator("Eye3")
			->setLastModifiedBy("Eye3 Online Monitor")
			->setTitle("Office 2005 XLSX Test Document")
			->setSubject("Office 2005 XLSX Test Document")
			->setDescription("Test document for Office 2005 XLSX, generated using PHP classes.")
			->setKeywords("office 2005 openxml php")
			->setCategory("Test result file");
		$phpExcelObject->setActiveSheetIndex(0)
			->setCellValue('A1', 'ID')
			->setCellValue('B1', 'Camión')
			->setCellValue('C1', 'Grúa')
			->setCellValue('D1', 'Inicio')
			->setCellValue('E1', 'Duración');
		$phpExcelObject->getActiveSheet()->getStyle("A1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("B1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("C1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("D1")->getFont()->setBold(true);
		$phpExcelObject->getActiveSheet()->getStyle("E1")->getFont()->setBold(true);
				$aux=2;
				foreach ($datos as $llenado)
						{	
							
							 $phpExcelObject->getActiveSheet()->setCellValue('A'.$aux, $llenado['id'])
							->setCellValue('B'.$aux, $llenado['camion'])
							->setCellValue('C'.$aux, $llenado['grua'])
							->setCellValue('D'.$aux, $llenado['inicio'])
							->setCellValue('E'.$aux++, $llenado['duracion']);
						}
		   
		$phpExcelObject->getActiveSheet()->setTitle('Historial');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$phpExcelObject->setActiveSheetIndex(0);

		// create the writer
		$writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007');
		// create the response
		$response = $this->get('phpexcel')->createStreamedResponse($writer);
		// adding headers
		$response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
		$response->headers->set('Content-Disposition', 'attachment;filename=Historial'.$meses[($date->format('n'))-1].$date->format('Y').'-indemin.xlsx');
		$response->headers->set('Pragma', 'public');
		$response->headers->set('Cache-Control', 'maxage=1');

		return $response;
			
    }

}
