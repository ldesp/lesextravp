<?php
// src/L1m2/PlatformBundle/Entity/PropoRepository.php

namespace L1m2\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PropoRepository extends \Doctrine\ORM\EntityRepository
{
    abstract protected function getStatusQuery($status);

    public function getToutes()
    {
        return $this->getStatusQuery(Proposition::STATUS_TOUT)->getResult();
    }

    public function getRecues()
    {
        return $this->getStatusQuery(Proposition::STATUS_RECU)->getResult();
    }

    public function getRejetees()
    {
        return $this->getStatusQuery(Proposition::STATUS_REJETE)->getResult();
    }

    public function getAcceptees()
    {
        return $this->getStatusQuery(Proposition::STATUS_ACCEPTE)->getResult();
    }

    protected function pagedQuery($page, $nbPerPage, $status)
    {
        $query = $this->getStatusQuery($status);
        $query
            // On définit la valeur à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbPerPage)
            // Ainsi que le nombre de valeurs à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;
        return  $query;
    }

    public function getPagedToutes($page, $nbPerPage)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_TOUT);
        return  new Paginator($query, true);
    }

    public function getPagedRecues($page, $nbPerPage)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_RECU);
        return  new Paginator($query, true);
    }

    public function getPagedRejetees($page, $nbPerPage)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_REJETE);
        return  new Paginator($query, true);
    }

    public function getPagedAcceptees($page, $nbPerPage)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_ACCEPTE);
        return  new Paginator($query, true);
    }
 
}
