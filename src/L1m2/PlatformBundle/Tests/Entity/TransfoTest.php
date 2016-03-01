<?php

namespace L1m2\PlatformBundle\Tests\Entity;

use L1m2\PlatformBundle\Entity\Transfo;

class TransfoTest extends \PHPUnit_Framework_TestCase
{
    public function testMetricMots()
    {
        $mot1 = 'POMME';
        $mot2 = 'POIRES';
        $result = Transfo::metricMots('POMME','POIRES');
        // assert that 
        $this->assertEquals(3, $result);
    }

    public function testMetricListeMots()
    {
        $mot1 = 'POMME';
        $a1 = array('POIRES', 'POMPE', 'P', '');
        $result = Transfo::metricListeMots($a1,$mot1);
        // assert that 
        $this->assertEquals(4, $result);
    } 

    public function testMetricListeListe()
    {
        $a2 = array('POMME', 'FRAISE', 'ANANAS');
        $a1 = array('POIRE', 'CERISE', 'BANANE', '');
        $result = Transfo::metricListeListe($a1, $a2);
        // assert that 
        $this->assertEquals(6, $result);
    } 

    public function testGenererAnagrammes()
    {
        $l1 = 'POMME,ANANAS,POIRES,AZALEE';
        $aresult = Transfo::genererAnagrammes($l1);
        // assert that 
        $this->assertEquals('EMMOP', $aresult[0]);
        $this->assertEquals('AAANNS', $aresult[1]);
        $this->assertEquals('EIOPRS', $aresult[2]);
        $this->assertEquals('AAEELZ', $aresult[3]);
    }

    public function testGenererLongueurs()
    {
        $a1 = array('POIRES', 'POMPE', 'P', '');
        $result = Transfo::genererLongueurs($a1);
        // assert that 
        $this->assertEquals('6,5,1,0', $result);
    }        
   
}

