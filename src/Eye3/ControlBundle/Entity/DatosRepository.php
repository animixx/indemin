<?php

namespace Eye3\ControlBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Eye3\ControlBundle\Entity\Datos;

/**
 * DatosRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DatosRepository extends EntityRepository
{

		public function findMax()
		{
			$query = $this->getEntityManager()
				->createQuery(
					'SELECT p FROM Eye3ControlBundle:Datos p'
				)->setMaxResults(10);

			try {
				return $query->getResult();
			} catch (\Doctrine\ORM\NoResultException $e) {
				return null;
			}
		}
		
		public function max_tiempo_dia($fecha = '2013-08-24%')
		{
		//grupo de pruebas solo grua 1 ->2013-08-15  , las 2 gruas -> (2014-06-12 ,2014-07-10) , solo grua 2 -> 2014-06-07
			$query = $this->getEntityManager()
				->createQuery(
					'SELECT COUNT(DISTINCT p.camion ) as datos , p.grua FROM Eye3ControlBundle:Datos p where p.inicio LIKE :fecha GROUP BY p.grua ORDER BY p.grua,p.camion'
				)->setParameter('fecha', $fecha);

			try {
				return $query->getResult();
			} catch (\Doctrine\ORM\NoResultException $e) {
				return null;
			}
		}
		
		
		public function camiones()
		{
		//grupo de pruebas solo grua 1 ->2013-08-15  , las 2 gruas -> (2014-06-12 ,2014-07-10) , solo grua 2 -> 2014-06-07
			$query = $this->getEntityManager()
				->createQuery(
					'SELECT DISTINCT p.camion FROM Eye3ControlBundle:Datos p ORDER BY p.camion'
				);

			try {
				return $query->getResult();
			} catch (\Doctrine\ORM\NoResultException $e) {
				return null;
			}
		}
		
		public function tiempo_dia($fecha = '2013-08-24')
		{
		// SELECT `camion`, SEC_TO_TIME(SUM( TIME_TO_SEC(`duracion`))) as tiempo_total, count(*) as veces, grua FROM `datos` where DATE(inicio) = '2014-06-12'  group by camion,grua
			$query = $this->getEntityManager()
				->getConnection()
				->prepare(
					'SELECT d.camion, SEC_TO_TIME(SUM( TIME_TO_SEC(d.duracion))) as tiempo_total, count(*) as veces, d.grua FROM datos d where DATE(d.inicio) = :fecha  group by d.camion, d.grua order by d.grua , d.camion'
				);
				$query->bindValue('fecha', $fecha );

			 $query->execute();
			 
			 return $query->fetchAll();
			 
		}
		
		public function camion_dia($fecha = '2013-09-24%')
		{
		// (SELECT camion,grua,count(*) as inicio,min(duracion) as tiempo,max(duracion) as max,SEC_TO_TIME(avg(TIME_TO_SEC(duracion))) as prom,SEC_TO_TIME(sum(TIME_TO_SEC(duracion))) as suma FROM datos WHERE inicio like '2013-09-24%'  GROUP BY camion,grua )
		// union
		// (SELECT camion,grua,inicio,DATE_FORMAT(duracion,"%i,%s"),null,null,null FROM datos WHERE  inicio like '2013-09-24%'  )
		//  ORDER BY grua, camion,max desc  , inicio
//grupo de pruebas solo grua 1 ->2013-09-24  , las 2 gruas -> (2014-06-16) , solo grua 2 -> 2014-06-07
			$query = $this->getEntityManager()
				->getConnection()
				->prepare(
					'(SELECT camion,grua,count(*) as inicio,min(duracion) as tiempo,max(duracion) as max,SEC_TO_TIME(avg(TIME_TO_SEC(duracion))) as prom,SEC_TO_TIME(sum(TIME_TO_SEC(duracion))) as suma FROM datos WHERE inicio like :fecha GROUP BY camion,grua )
				union
					 (SELECT camion,grua,inicio,TIME_TO_SEC(duracion),null,null,null FROM datos WHERE inicio like :fecha )
					 ORDER BY grua, camion, max desc , inicio'

			);
				$query->bindValue('fecha', $fecha );

			 $query->execute();
			 
			 return $query->fetchAll();
		}

}
