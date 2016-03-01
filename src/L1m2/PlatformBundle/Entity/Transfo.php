<?php

namespace L1m2\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Transfo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="L1m2\PlatformBundle\Entity\TransfoRepository")
 * @UniqueEntity(fields="mots", message="Une liste similaire a déjà été proposée.")
 */
class Transfo extends Proposition
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
     * @ORM\ManyToOne(targetEntity="L1m2\PlatformBundle\Entity\Extrait")
     * @ORM\JoinColumn(nullable=false)
     */
    private $extrait;

    /**
     * @var string
     *
     * @ORM\Column(name="mots", type="text")
     */
    private $mots;

    /**
     * @var string
     *
     * @ORM\Column(name="anagrammes", type="text")
     */
    private $anagrammes;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=255)
     */
    private $resume;


    const ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';     
    const PATTERN = '/[^A-Z]{1,}/';

    public function __construct()
    {
        parent::__construct();
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
     * Set extrait
     *
     * @param Extrait $extrait
     *
     * @return Transfo
     */
    public function setExtrait(Extrait $extrait)
    {
        $this->extrait = $extrait;

        return $this;
    }

    /**
     * Get extrait
     *
     * @return Extrait
     */
    public function getExtrait()
    {
        return $this->extrait;
    }

    /**
     * Set mots
     *
     * @param string $mots
     *
     * @return Transfo
     */
    public function setMots($mots)
    {
        $this->mots = $mots;

        return $this;
    }

    /**
     * Get mots
     *
     * @return string
     */
    public function getMots()
    {
        return $this->mots;
    }
    /**
     * Set anagrammes
     *
     * @param string $str
     *
     * @return Transfo
     */
    public function setAnagrammes($str)
    {
        $this->anagrammes = $str;

        return $this;
    }

    /**
     * Get anagrammes
     *
     * @return string
     */
    public function getAnagrammes()
    {
        return $this->anagrammes;
    }
 
    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return Transfo
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {        
        return $this->resume;
    }

    /**
     *  compare liste of words with other lists of words
     *
     * @return integer
     */
    public function comparerMots($aT, $m_max)
    {
        $ind = 0;
        $indmax = 0;
        $metmax = 0; 
        $liste1 = explode(',', $this->mots);
        foreach ($aT as $transfo1)
        {
            $metric = $this->metricListeListe($liste1, explode(',', $transfo1->getMots()));
            if ($metric > $metmax)
            {
                $metmax = $metric;
                $indmax = $ind;
            }
            $ind++;    
        }
        if ($metmax > $m_max)
        {
            return $aT[$indmax];        
        }
        return null;
    }  
    /**
     * check letters of word list versus letters of quoted text: length, content 
     * compute the letters mapping, the anagrams and set the anagrammes field
     *
     *    @param string $extr  raw extract
     *
     * @return bool
     */
    public function verifierMots($extrait)
    {
        // filtering lists 
        $liste2 = preg_replace(self::PATTERN, '', $extrait);
        $liste1 = preg_replace(self::PATTERN, '', $this->mots);
        // checking length
        if (strlen($liste1) != strlen($liste2))
        {
            return false;
        }
        // checking letter positions
        $situe = self::positionLettres($liste1, $liste2);
        if ($situe == null)
        {
            return false;
        }
        // create anagrams
        $arrayM = self::genererAnagrammes($this->mots);
        // setting anagrammes
        $str1 = implode(',', $situe).'_'.implode(',', $arrayM);
        $str2 = self::genererLongueurs(explode(',', $extrait)); 
        $this->setAnagrammes($str1.'_'.$str2);
        // setting resume
        $this->setResume($arrayM[0].' X '.count($arrayM));
        return true;
    }
    /**
     * mapping letters between two lists of letters
     *            
     * @return Array
     */
    public static function positionLettres($liste1, $liste2)
    {
        // computing indexes
        $situe = array_fill(0, strlen($liste1), 0); 
        // assumption : number of occurences is identical for liste1 and liste2
        $flag = true; 
        $alphabet = self::ALPHABET;
        for ($i = 0; (($i < strlen(self::ALPHABET)) and ($flag == true)); $i++)
        {        
            $letter = $alphabet[$i];
            $idx1 = strpos($liste1, $letter);
            $idx2 = strpos($liste2, $letter);
            while (($idx1 !== false) and ($idx2 !== false) and ($flag == true))
            {
                $situe[$idx1] = $idx2;
                $idx1 = strpos($liste1, $letter, $idx1 + 1);
                $idx2 = strpos($liste2, $letter, $idx2 + 1);
                if ((($idx1 === false) and ($idx2 !== false)) or
                    (($idx2 === false) and ($idx1 !== false)))
                {
                     $flag = false;
                }
            }
        }
        if ($flag)
        {
            return $situe;
        }
        return null;
    } 
    /**
     * creating anagrams from a list of words
     *            
     * @return Array
     */
    public static function genererAnagrammes($listeM)
    {
        $ind = 0;
        $arrayM = explode(',', $listeM);
        $alphabet = self::ALPHABET;
        foreach ($arrayM as $value)
        {
            $mot = preg_replace(self::PATTERN, '', $value);
            $str1 = '';
            for ($i = 0; $i < strlen(self::ALPHABET); $i++)
            {  
                $letter = $alphabet[$i];           
                $idx1 = strpos($mot, $letter);
                while ($idx1 !== false)
                {
                    $str1 = $str1.$letter;
                    $idx1 = strpos($mot, $letter, $idx1 + 1); 
                }
            }
            $arrayM[$ind] = $str1;
            $ind++;
        }
        return $arrayM;
    }
    /**
     * joining in a string, the word lengths of a word array
     *            
     * @return String
     */
    public static function genererLongueurs($arrayM)
    {
        // joining length of anagrams
        $str2 ='';
        $ind = 0;
        $cnt = count($arrayM);
        foreach ($arrayM as $value)
        {
            $str2 = $str2.strlen($value);
            if ($ind != $cnt - 1)
            {
                $str2 = $str2.',';
            }
            $ind++;
        }
        return $str2;
    }
    /**
     *  metric between words
     *
     * @return integer
     */
    public static function metricMots($mot1, $mot2)
    {
        $metric = 0;
        for ($i = 0; $i < min(strlen($mot1), strlen($mot2)); $i++)
        {  
            if ( $mot1[$i] == $mot2[$i])
            {
                $metric += 1;
            }
        }
        return $metric;
    }
    /**
     *  metric between list of words and word
     *
     * @return integer
     */
    public static function metricListeMots($liste, $mot)
    {
        $metric = 0;
        foreach ($liste as $mot2)
        {
            $metric = max($metric, self::metricMots($mot, $mot2));
        }
        return $metric;
    }
    /**
     *  metric between 2 lists of words
     *
     * @return integer
     */
    public static function metricListeListe2($liste1, $liste2)
    {
        $metric = 0;
        foreach ($liste2 as $mot)
        {
            $metric += self::metricListeMots($liste1, $mot);
        }
        return $metric;
    }
    /**
     *  metric between 2 lists of words
     *
     * @return integer
     */
    public static function metricListeListe($liste1, $liste2)
    {
        $metric = 0;
        for ($i = 0; $i < min(count($liste1), count($liste2)); $i++)
        {
            $metric += self::metricMots($liste1[$i], $liste2[$i]);
        }
        return $metric;
    }

}

