<?php

use PHPUnit\Framework\TestCase;
require "src/pas.php";

final class PasTest extends TestCase
{

    function testCreerPas(){
        $pas = new Pas('Action1','ResultatAttendu1');
        $this->assertNotNull($pas);
        return $pas;
    }

    /**
     * @depends testCreerPas
     */
    function testInitialiserPas($pas){
        $pas->initialiserPas();
        $this->assertNotNull($pas);
        return $pas;
    }

    /**
     * @depends testInitialiserPas
     */
    function testInitialiserPasExec($pas){
        $this->assertNull($pas->getDateExecution());
        $this->assertNull($pas->getResultatObtenu());
        $this->assertNull($pas->getCommentaire());
        $this->assertEquals('Non passé', $pas->getStatut());
    }
    
    /**
     * @depends testCreerPas
     */
    function testExecuterPas($pas){
        $pas->executerPas('ResultatObtenu1','commentaire1','OK');
        $this->assertNotNull($pas);
        return $pas;
    }

    /**
     * @depends testExecuterPas
     */
    function testExecuterPasExec($pas){
        $this->assertEquals(date('Y-m-d'), $pas->getDateExecution());
        $this->assertEquals('ResultatObtenu1', $pas->getResultatObtenu());
        $this->assertEquals('commentaire1', $pas->getCommentaire());
        $this->assertEquals('OK', $pas->getStatut());
    }
}