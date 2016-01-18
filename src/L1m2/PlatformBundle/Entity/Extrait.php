<?php

namespace L1m2\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Extrait
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="L1m2\PlatformBundle\Entity\ExtraitRepository")
 * @UniqueEntity(fields="entrees", message="Un extrait similaire a déjà été proposé.")
 */
class Extrait extends Proposition
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
     * @ORM\Column(name="category", type="string", length=255)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="extrait", type="text")
     */
    private $extrait;
    /**
     * @var integer
     *
     * @ORM\Column(name="ntransfo", type="integer")
     */
    private $ntransfo;

    /**
     * @var string
     *
     * @ORM\Column(name="indice", type="string", length=255)
     */
    private $indice;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="text")
     */
    private $reference;

    /**
     * @var string
     *
     * @ORM\Column(name="entrees", type="text")
     */
    private $entrees;

    const CAT_TBD = "tbd"; // valeur initiale de la categorie

    public function __construct()
    {
        parent::__construct();
        $this->setCategory(self::CAT_TBD);
        $this->setStatus(Proposition::STATUS_ACCEPTE); 
        $this->setNtransfo(0);         
    }
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
     * Set category
     *
     * @param string $category
     *
     * @return Extrait
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set extrait
     *
     * @param string $extrait
     *
     * @return Extrait
     */
    public function setExtrait($extrait)
    {
        $this->extrait = $extrait;

        return $this;
    }

    /**
     * Get extrait
     *
     * @return string
     */
    public function getExtrait()
    {
        return $this->extrait;
    }
   /**
     * Set ntransfo
     *
     * @param integer $ntransfo
     *
     * @return Extrait
     */
    public function setNtransfo($ntransfo)
    {
        $this->ntransfo = $ntransfo;

        return $this;
    }
   /**
     * increase ntransfo
     * 
     */
    public function increaseNtransfo()
    {
        $this->ntransfo += 1;
    }
    /**
     * Get ntransfo
     *
     * @return integer
     */
    public function getNtransfo()
    {
        return $this->ntransfo;
    }
    /**
     * Set indice
     *
     * @param string $indice
     *
     * @return Extrait
     */
    public function setIndice($indice)
    {
        $this->indice = $indice;

        return $this;
    }

    /**
     * Get indice
     *
     * @return string
     */
    public function getIndice()
    {
        return $this->indice;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Extrait
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Extrait
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set inputs
     *
     * @param string $entrees
     *
     * @return Extrait
     */
    public function setEntrees($entrees)
    {
        $this->entrees = $entrees;

        return $this;
    }
    /**
     * Get inputs
     *
     * @return string
     */
    public function getEntrees()
    {
        return $this->entrees;
    }

    /**
     * Get filtered extract
     *
     * @return string
     */
    public function getExtraitFiltre()
    {
        return explode('_', $this->entrees)[2];
    }
    /**
     * Get firt letters
     *
     * @return string
     */
    public function getInitiales()
    {
        return explode('_', $this->entrees)[0];
    }
    /**
     * Get letters used to build words
     *
     * @return string
     */
    public function getPioche()
    {
        return explode('_', $this->entrees)[1];
    }



}

