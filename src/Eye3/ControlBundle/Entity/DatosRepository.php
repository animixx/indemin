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
		
		public function camiones()
		{
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
		
		public function tiempo_dia($fecha = '2014-06-16%')
		{
		// (SELECT camion, SUM( TIME_TO_SEC(duracion)) as tiempo_total, SEC_TO_TIME(avg(TIME_TO_SEC(duracion))) as prom, count(*) as veces, grua FROM datos where DATE(inicio) = '2014-06-16'  group by camion, grua ) 
		// union
		// (SELECT null, SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))) , null, COUNT(DISTINCT (camion) )  , grua   FROM datos  where inicio LIKE '2014-06-16%' GROUP BY grua) 
		// ORDER BY grua, camion
//grupo de pruebas solo grua 1 ->2013-08-15  , las 2 gruas -> (2014-06-16, 2014-06-12 ,2014-07-10) , solo grua 2 -> 2014-06-07

			$query = $this->getEntityManager()
				->getConnection()
				->prepare(
					'(SELECT camion, SUM( TIME_TO_SEC(duracion)) as tiempo_total, SEC_TO_TIME(avg(TIME_TO_SEC(duracion))) as prom, count(*) as veces, grua FROM datos where inicio LIKE :fecha  group by camion, grua ) 
				 union
					(SELECT null, SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))) , null, COUNT(DISTINCT(camion)) , grua FROM datos  where inicio LIKE :fecha GROUP BY grua) 
					ORDER BY grua, camion'
				);
				$query->bindValue('fecha', $fecha );

			 $query->execute();
			 
			 return $query->fetchAll();
			 
		}
		
		public function camion_dia($fecha = '2014-06-16%')
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
		
		public function grua_dia($fecha = '2013-09-24%')
		{
			//SELECT * FROM datos p WHERE p.inicio like '2013-09-24%' ORDER BY p.grua, p.inicio
			//SELECT  SUM(TIME_TO_SEC(duracion))  as uso, TIME_TO_SEC(SUBTIME('24:00:00', SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))))) as muerto,  count(*) as ciclos, SEC_TO_TIME(AVG(TIME_TO_SEC(duracion)))  as prom, grua   FROM datos  where inicio LIKE  '2013-09-24%' GROUP BY grua
			//SELECT t1.camion as camion, t1.inicio as inicio, t1.duracion as duracion, SUBTIME(time(t2.inicio),time(t1.inicio)) as proximo  from datos as t1 , datos as t2 where t1.id+1 = t2.id and t1.inicio like '2013-09-24%'
			$query = $this->getEntityManager()
				->getConnection()
				->prepare(
					'SELECT SUM(TIME_TO_SEC(duracion)) as uso, TIME_TO_SEC(SUBTIME("24:00:00", SEC_TO_TIME(SUM(TIME_TO_SEC(duracion))))) as muerto,  count(*) as ciclos, SEC_TO_TIME(AVG(TIME_TO_SEC(duracion))) as prom, grua FROM datos where inicio LIKE :fecha GROUP BY grua'
				

			);
				$query->bindValue('fecha', $fecha );

			 $query->execute();
			 
			 return $query->fetchAll();
		}

}
