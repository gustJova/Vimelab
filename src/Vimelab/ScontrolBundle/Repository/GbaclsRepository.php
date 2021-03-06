<?php

namespace Vimelab\ScontrolBundle\Repository;
use Doctrine\ORM\EntityRepository;

class GbaclsRepository extends EntityRepository
{
    public function getCount()
    {
        $em = $this->getEntityManager();
        $querry = $em->createQuery("SELECT COUNT(o) FROM ScontrolBundle:Gbacls o");
        $resul = $querry->getSingleScalarResult();
        return $resul;
    }

    public function getCountPages($limite = 20)
    {
        $cantidad = $this->getCount();
        $numeropag = intval($cantidad / $limite);
        $numeropag = ($cantidad % $limite) > 0 ? $numeropag + 1 : $numeropag;
        return $numeropag;
    }

    public function getPage($limite = 20, $pagina = 1)
    {
        $pagina = $pagina < 1 ? 1 : $pagina;

        $em = $this->getEntityManager();
        $querry = $em->createQuery("SELECT o FROM ScontrolBundle:Gbacls o JOIN o.gbusua u ORDER BY u.nombre ASC");
        $querry->setFirstResult(($pagina-1)*$limite);
        $querry->setMaxResults($limite);
        return $querry->getResult();
    }

    public function getFilter($parametro)
    {
        $em = $this->getEntityManager();
        $querry = $em->createQuery("SELECT m, l FROM ScontrolBundle:Gbacls m JOIN m.gbusua l WHERE l.nombre LIKE '%$parametro%' OR m.modulo LIKE '%$parametro%' ORDER BY l.id ASC");
        return $querry->getResult();
    }
	
	public function getExist($usu, $mod, $act)
    {
        $em = $this->getEntityManager();
        $querry = $em->createQuery("SELECT COUNT(o) FROM ScontrolBundle:Gbacls o JOIN o.gbusua u WHERE u.id = $usu AND o.modulo = '$mod' AND o.accion = '$act'");
        $resul = $querry->getSingleScalarResult();
        return $resul;
    }
	
	public function getInUser($usu)
    {
        $em = $this->getEntityManager();
        $querry = $em->createQuery("SELECT o FROM ScontrolBundle:Gbacls o JOIN o.gbusua u WHERE u.id = $usu ORDER BY o.modulo ASC");
     	return $querry->getResult();
    }
}
