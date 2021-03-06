<?php

namespace Eye3\ControlBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datos
 *
 * @ORM\Table(name="datos", indexes={@ORM\Index(name="fk_Datos_Camiones1_idx", columns={"id"})})
 * @ORM\Entity(repositoryClass="Eye3\ControlBundle\Entity\DatosRepository")
 */
class Datos
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Camiones
     *
     * @ORM\ManyToOne(targetEntity="Camiones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_tag_camiones", referencedColumnName="id")
     * })
     *
     */
    private $idcamion;	

    /**
     * @var string
     *
     * @ORM\Column(name="grua", type="string", length=50, nullable=false)
     */
    private $grua;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime", nullable=false)
     */
    private $inicio;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="final", type="datetime", nullable=false)
     */
    private $final;

    /**
     * @var float
     *
     * @ORM\Column(name="duracion", type="float", nullable=false)
     */
    private $duracion;

    /**
     * @var integer
     *
     * @ORM\Column(name="cuenta", type="integer", nullable=false)
     */
    private $hit;



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
     * Set camion
     *
     * @param string $camion
     * @return Datos
     */
    public function setCamion($camion)
    {
        $this->camion = $camion;

        return $this;
    }

    /**
     * Get camion
     *
     * @return string 
     */
    public function getCamion()
    {
        return $this->camion;
    }

    /**
     * Set grua
     *
     * @param string $grua
     * @return Datos
     */
    public function setGrua($grua)
    {
        $this->grua = $grua;

        return $this;
    }

    /**
     * Get grua
     *
     * @return string 
     */
    public function getGrua()
    {
        return $this->grua;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     * @return Datos
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime 
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set duracion
     *
     * @param \DateTime $duracion
     * @return Datos
     */
    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;

        return $this;
    }

    /**
     * Get duracion
     *
     * @return \DateTime 
     */
    public function getDuracion()
    {
        return $this->duracion;
    }

    /**
     * Set hit
     *
     * @param integer $hit
     * @return Datos
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return integer 
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * Set final
     *
     * @param \DateTime $final
     * @return Datos
     */
    public function setFinal($final)
    {
        $this->final = $final;

        return $this;
    }

    /**
     * Get final
     *
     * @return \DateTime 
     */
    public function getFinal()
    {
        return $this->final;
    }

    /**
     * Set idcamion
     *
     * @param \Eye3\ControlBundle\Entity\Camiones $idcamion
     * @return Datos
     */
    public function setIdcamion(\Eye3\ControlBundle\Entity\Camiones $idcamion = null)
    {
        $this->idcamion = $idcamion;

        return $this;
    }

    /**
     * Get idcamion
     *
     * @return \Eye3\ControlBundle\Entity\Camiones 
     */
    public function getIdcamion()
    {
        return $this->idcamion;
    }
}
