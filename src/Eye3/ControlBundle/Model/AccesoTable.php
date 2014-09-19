<?php
namespace Eye3\ControlBundle\Model;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class AccesoTable
 *
 * @package Eye3\ControlBundle\Model
 *
 * @DataTable\Table(id="accesoTable",displayLength=10, serverSideProcessing = false)
 */
class AccesoTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
	
    /**
     * @var date
     * @DataTable\Column(source="registro.fecha", name="Fecha", stype="date-euro")
	 * @DataTable\Format(dataFields={"dato":"registro.fecha"}, template="Eye3ControlBundle:Registro:fecha.html.twig")
     * @DataTable\DefaultSort()
     */
    public $cuando; 
	
	
    /**
     * @var string
     * @DataTable\Column(source="registro.accion", name="Accion")
     */
    public $accion;

   /**
     * @var string
     * @DataTable\Column(source="registro.usuario.nombre", name="Usuario")
     */
    public $usuario;



    /**
     * @var bool hydrate results to doctrine objects
     */
    public $hydrateObjects = true;

    /**
     * getQueryBuilder
     *
     * @param Request $request
     *
     * @return null
     */
    public function getQueryBuilder(Request $request = null)
    {
        $userRepository = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('Eye3\ControlBundle\Entity\Registro');
        $qb = $userRepository->createQueryBuilder('registro');

        return $qb;
    }
}