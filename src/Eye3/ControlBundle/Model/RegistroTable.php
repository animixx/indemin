<?php
namespace Eye3\ControlBundle\Model;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class RegistroTable
 *
 * @package Eye3\ControlBundle\Model
 *
 * @DataTable\Table(id="registroTable",displayLength=10,serverSideProcessing=true)
 */
class RegistroTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
    /**
     * @var int
     * @DataTable\Column(source="datos.id", name="Id")
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="datos.camion", name="Camion")
     * @DataTable\Format(dataFields={"dato":"datos.camion"}, template="Eye3ControlBundle:Registro:show.html.twig")
	*/
    public $camion;

    /**
     * @var string
     * @DataTable\Column(source="datos.grua", name="Grua")
	 * @DataTable\Format(dataFields={"dato":"datos.grua"}, template="Eye3ControlBundle:Registro:show.html.twig")
     */
    public $grua;

	/**
     * @var datetime
     * @DataTable\Column(source="datos.inicio", name="Inicio")
	 * @DataTable\Format(dataFields={"dato":"datos.inicio"}, template="Eye3ControlBundle:Registro:fecha.html.twig")
     */
    public $inicio;
	
	/**
     * @var time
     * @DataTable\Column(source="datos.duracion", name="Duracion")
	 * @DataTable\Format(dataFields={"dato":"datos.duracion"}, template="Eye3ControlBundle:Registro:hora.html.twig")
     */
    public $duracion;



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
            ->getRepository('Eye3\ControlBundle\Entity\Datos');
        $qb = $userRepository->createQueryBuilder('datos');

        return $qb;
    }
}