<?php
// src/L1m2/PlatformBundle/Entity/Proposition.php

namespace L1m2\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/** @ORM\MappedSuperclass */
abstract class Proposition
{
    /** 
     * @ORM\Column(type="integer") 
     */
    protected $status;
    /**
     * @ORM\Column(name="auteur", type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 4,
     *      max = 40,
     *      minMessage = "Votre pseudonyme est trop court, moins de {{ limit }} lettres",
     *      maxMessage = "Votre pseudonyme est trop long, plus de {{ limit }} lettres"
     * )
     */
    protected $auteur;
    /**
     * @ORM\Column(name="datepropo", type="datetime")
     */
    protected $datePropo;
    /**
     * @ORM\Column(name="dateparu", type="datetime")
     */
    protected $dateParu;

    const STATUS_RECU = 1;    // valeur initiale du status, filtre les propositions recues 
    const STATUS_REJETE = 2;  // filtre les propositions rejetees
    const STATUS_ACCEPTE = 4; // filtre les propositions acceptees 
    const STATUS_TOUT = 7;     // pas de filtre sur les propositions 


    public function __construct()
    {
        $this->setDatePropo(new \Datetime());
        $this->setDateParu($this->datePropo);
        $this->setStatus(self::STATUS_RECU);
    }
 
    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
    * @param int $id
    * @return Proposition
    */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
    /**
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
    /**
     * @param string $auteur
     * @return Proposition
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
        return $this;
    }
    /**
     * @param \DateTime $date
     * @return Proposition
     */
    public function setDatePropo($date)
    {
        $this->datePropo = $date;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getDatePropo()
    {
        return $this->datePropo;
    }
    /**
     * @param \DateTime $date
     * @return Proposition
     */
    public function setDateParu($date)
    {
        $this->dateParu = $date;
        return $this;
    }
    /**
     * @return \DateTime
     */
    public function getDateParu()
    {
        return $this->dateParu;
    }

    /**
     * @return bool
     */
    public function isAccepted()
    {
        return ($this->status == self::STATUS_ACCEPTE);
    }
    /**
     * @return bool
     */
    public function isRejected()
    {
        return ($this->status == self::STATUS_REJETE);
    }

}
