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
    public function semanalAction(Request $request)
    {
	
		$fecha='16-06-2014';
		
		$em = $this->getDoctrine()->getManager();

        $datos = $em->getRepository('Eye3ControlBundle:Datos')->reporte_mes($fecha);
		
		$mpdfService = $this->get('tfox.mpdfport');
		$mPDF = $mpdfService->getMpdf();
	
		$stylesheet = file_get_contents('bundles/eye3control/css/uncompressed/bootstrap.css');
		$stylesheet2 = file_get_contents('bundles/eye3control/css/uncompressed/ace.css');
						
		$mPDF->WriteHTML($stylesheet,1);
		$mPDF->WriteHTML($stylesheet2,1);
		$mPDF->WriteHTML($this->renderView('Eye3ControlBundle:Reporte:reporte.html.twig', array(
								'titulo' => 'semanal',
								'datos' => $datos,
								'fecha' => $fecha,
													) ), 2);
		$mPDF->WriteHTML("hello world",2);

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
    public function mensualAction(Request $request)
    {
		$mpdfService = $this->get('tfox.mpdfport');
		$html = "<h1>Eye3 Monitor Online</h1>";
		$html = "<h1>Eye3 Monitor Online</h1>";
		$content = $mpdfService->generatePdf($html);
    }
	
   /**
     * 
     * @Route("/imagen", name="grafico")
	 *
     * @return response
     */
    public function graficaAction( $tipo, array $valores,$fijo = 100) {
	
		
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
			$data[]=$value;
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
			
			// $bplot->SetColor("white");
			$bplot->SetFillGradient("#4B0082","white",GRAD_LEFT_REFLECTION);
			$bplot->SetValuePos('center');
			
			
			// $bplot->SetWidth(45);
			// $bplot->SetLegend('Result');

			$graph->yaxis->scale->SetAutoMin($min);
			// Add the plots to the graph
			
			$graph->Add($sline);
			$graph->Add($bplot);
			$bplot->value->SetAngle(90);
			$bplot->value->SetFormat('i:s');
			$bplot->value->SetFormatCallback('gmdate');
			$bplot->value->SetColor('red','darkred');
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
