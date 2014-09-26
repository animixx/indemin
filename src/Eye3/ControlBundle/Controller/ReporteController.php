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
     * @Route("/", name="reporte_semanal")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function semanalAction($fecha='4-09-2013')
    {
		$date= date_create($fecha);
		$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		$semana= $date->modify('this Monday')->format('j')." al ".$date->modify('next Sunday')->format('j');
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana($fecha);
        $detalleG1 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana2($fecha);
        $detalleG2 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_semana2($fecha,"Grúa-2");
		
		$mpdfService = $this->get('tfox.mpdfport');
		$mPDF = $mpdfService->getMpdf( array( '','', 0, '', 45, 15, 16, 16, 9, 39, 'L' ));
	
		$stylesheet = file_get_contents('bundles/eye3control/css/uncompressed/bootstrap.css');
		$stylesheet2 = file_get_contents('bundles/eye3control/css/uncompressed/ace.css');
						
		$mPDF->WriteHTML($stylesheet,1);
		$mPDF->WriteHTML($stylesheet2,1);
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte.html.twig', array(
								'titulo' => 'semanal - '.$semana.' '.$meses[($date->format('n'))-1]." ".$date->format('Y'),
								'graficos' => 'semana '.$semana,
								'datos' => $datos,
													) ), 2);
		$mPDF->AddPage();
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte2s.html.twig', array(
								'datos1' => $detalleG1,
								'datos2' => $detalleG2,
								'graficos' => 'semana '.$semana,
								'primer' => $date,
													) ), 2);

		$mPDF->Output();


    }

    /**
     * 
     * @Route("/mensual", name="reporte_mensual")
     * @Template("Eye3ControlBundle:Reporte:index.html.twig")
	 *
	 * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function mensualAction($fecha='01-06-2014')
    {
		
		$date= date_create($fecha);
		$meses = ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];
		
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes($fecha);
        $detalleG1 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes2($fecha);
        $detalleG2 = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes2($fecha,"Grúa-2");
		
		$mpdfService = $this->get('tfox.mpdfport');
		$mPDF = $mpdfService->getMpdf( array( '','', 0, '', 15, 45, 16, 16, 9, 39, 'L' ));
	
		$stylesheet = file_get_contents('bundles/eye3control/css/uncompressed/bootstrap.css');
		$stylesheet2 = file_get_contents('bundles/eye3control/css/uncompressed/ace.css');
						
		$mPDF->WriteHTML($stylesheet,1);
		$mPDF->WriteHTML($stylesheet2,1);
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte.html.twig', array(
								'titulo' => 'mensual - '.$meses[($date->format('n'))-1]." ".$date->format('Y'),
								'graficos' => 'mes '.$meses[($date->format('n'))-1],
								'datos' => $datos,
													) ), 2);
	
		$mPDF->AddPage();
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte2.html.twig', array(
								'datos1' => $detalleG1,
								'datos2' => $detalleG2,
								'graficos' => 'mes '.$meses[($date->format('n'))-1],
								'primer' => $date->modify('first day of this month')->modify('next Monday'),
													) ), 2);

		$mPDF->Output();
		
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
			$aux++;
			$data[]=round($value);
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
			

			// Create the bar plot
			$bplot = new \BarPlot($data);
			
			$bplot->SetValuePos('center');
			
			$graph->yaxis->scale->SetAutoMin($min);
			
			// Add the plots to the graph
			$graph->Add($sline);
			$graph->Add($bplot);
			$bplot->value->SetAngle(90);
			$bplot->value->SetFormat('i:s');
			$bplot->value->SetFormatCallback('gmdate');
			$bplot->value->SetColor('red');
			$bplot->value->Show();
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
				$plot->value->Show();
				$bplot[] = $plot;
				
			}
			
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
						$etiqueta_full[]=date("d",$fecha->modify('+1 day')->getTimestamp())-1;
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
			// $bplot->value->Show();
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

}
