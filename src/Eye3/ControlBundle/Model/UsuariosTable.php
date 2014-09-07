<?php
namespace Eye3\ControlBundle\Model;

use Brown298\DataTablesBundle\MetaData as DataTable;
use Brown298\DataTablesBundle\Model\DataTable\QueryBuilderDataTableInterface;
use Brown298\DataTablesBundle\Test\DataTable\QueryBuilderDataTable;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\EngineInterface;

/**
 * Class UsuariosTable
 *
 * @package Eye3\ControlBundle\Model
 *
 * @DataTable\Table(id="usuariosTable",sortable=true,searchable=false)
 */
class UsuariosTable extends QueryBuilderDataTable implements QueryBuilderDataTableInterface
{
 
    /**
     * @var datetime
     * @DataTable\Column(source="usuario.last_login", name="Ultimo Acceso")
     * @DataTable\DefaultSort()
	 * @DataTable\Format(dataFields={"dato":"usuario.last_login"}, template="Eye3ControlBundle:Registro:fecha.html.twig")
     */
    public $fecha; 
	
    /**
     * @var string
     * @DataTable\Column(source="usuario.username",name="Usuario",sortable=false)
	 * @DataTable\DefaultSort()
     */
    public $nombre;

   /**
     * @var string
     * @DataTable\Column(source="usuario.nombre",name="Nombre",sortable=true)
     * @DataTable\DefaultSort()
     */
    public $login;
	
   /**
     * @var string
     * @DataTable\Column(searchable=false,source="usuario.tipo",name="Permisos")
     * @DataTable\DefaultSort()
     */
    public $rol;

	/**
     * @var int
     * @DataTable\Column(source="usuario.id", name="",sortable=false,searchable=false)
     * @DataTable\DefaultSort()
	 * @DataTable\Format(dataFields={"id":"usuario.id"}, template="Eye3ControlBundle:Uso:modifica.html.twig")
     */
    public $id;



    /**
     * @var bool hydrate results to doctrine objects
     */
    public $hydrateObjects = false;

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
            ->getRepository('Eye3\ControlBundle\Entity\Usuario');
        $qb = $userRepository->createQueryBuilder('usuario');

        return $qb;
    }
}