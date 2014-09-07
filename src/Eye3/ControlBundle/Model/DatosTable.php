<?php
namespace Eye3\ControlBundle\Model;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class DatosTable
 *
 * @package Eye3\ControlBundle\Model
 *
 * @DataTable\Table(id="datosTable")
 */
class DatosTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
    /**
     * @var int
     * @DataTable\Column(source="datos.id", name="Id")
     */
    public $id;

    /**
     * @var string
     * @DataTable\Column(source="datos.camion", name="Camion")
     * @DataTable\DefaultSort()
     */
    public $camion;

    /**
     * @var string
     * @DataTable\Column(source="datos.grua", name="Grua")
     * @DataTable\DefaultSort()
     */
    public $grua;



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