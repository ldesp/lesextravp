<?php
// src/L1m2/PlatformBundle/Entity/PropoRepository.php

namespace L1m2\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

abstract class PropoRepository extends \Doctrine\ORM\EntityRepository
{
    abstract protected function getStatusQuery($status, $order);

    public function getToutes($order = Proposition::DATE_DESC)
    {
        return $this->getStatusQuery(Proposition::STATUS_TOUT, $order)->getResult();
    }

    public function getRecues($order = Proposition::DATE_DESC)
    {
        return $this->getStatusQuery(Proposition::STATUS_RECU, $order)->getResult();
    }

    public function getRejetees($order = Proposition::DATE_DESC)
    {
        return $this->getStatusQuery(Proposition::STATUS_REJETE, $order)->getResult();
    }

    public function getAcceptees($order = Proposition::DATE_DESC)
    {
        return $this->getStatusQuery(Proposition::STATUS_ACCEPTE, $order)->getResult();
    }

    protected function pagedQuery($page, $nbPerPage, $status, $order)
    {
        $query = $this->getStatusQuery($status, $order);
        $query
            // On définit la valeur à partir de laquelle commencer la liste
            ->setFirstResult(($page-1) * $nbPerPage)
            // Ainsi que le nombre de valeurs à afficher sur une page
            ->setMaxResults($nbPerPage)
        ;
        return  $query;
    }

    public function getPagedToutes($page, $nbPerPage, $order = Proposition::DATE_DESC)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_TOUT, $order );
        return  new Paginator($query, true);
    }

    public function getPagedRecues($page, $nbPerPage, $order = Proposition::DATE_DESC)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_RECU, $order);
        return  new Paginator($query, true);
    }

    public function getPagedRejetees($page, $nbPerPage, $order = Proposition::DATE_DESC)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_REJETE, $order);
        return  new Paginator($query, true);
    }

    public function getPagedAcceptees($page, $nbPerPage, $order = Proposition::DATE_DESC)
    {
        $query = $this->pagedQuery($page, $nbPerPage, Proposition::STATUS_ACCEPTE, $order);
        return  new Paginator($query, true);
    }
 
}
