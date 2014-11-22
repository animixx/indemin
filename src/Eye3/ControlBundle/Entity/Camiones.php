<?php

namespace Eye3\ControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Camiones
 *
 * @ORM\Table(name="camiones")
 * @ORM\Entity
 */
class Camiones
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=45)
     */
    private $tag;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_camion", type="string", length=50)
     */
    private $camion;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tag
     *
     * @param string $tag
     * @return Camiones
     */
    public function setTag($tag)
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get tag
     *
     * @return string 
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Set Camion
     *
     * @param string $Camion
     * @return Camiones
     */
    public function setCamion($camion)
    {
        $this->camion = $camion;

        return $this;
    }

    /**
     * Get Camion
     *
     * @return string 
     */
    public function getCamion()
    {
        return $this->camion;
    }
}
