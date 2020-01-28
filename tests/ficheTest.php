<?php

use PHPUnit\Framework\TestCase;
require "src/fiche.php";
require "src/pas.php";
require "src/tooMuchPasException.php";

final class ficheTest extends TestCase
{

    public function testAjouterPas(){
        $fiche = new Fiche('fiche1',null);
        $pas = $this->createStub(Pas::class);
        $fiche->ajouterPas($pas);
        $this->assertEquals(1, count($fiche->getListePas()));
    }

    public function testExceptionAjouterPas(){
        $pas = array($this->createStub(Pas::class),$this->createStub(Pas::class),$this->createStub(Pas::class));
        $fiche = new Fiche('fiche1',$pas);
        $this->expectException(TooMuchPasException::class);
        $fiche->ajouterPas($this->createStub(Pas::class));
     
    }


    public function testInitialiserFiche(){

        $mockPas1 = $this->createMock(Pas::class);
        $mockPas1->expects($this->once())
                ->method('initialiserPas');

        $mockPas2 = $this->createMock(Pas::class);
        $mockPas2->expects($this->once())
                ->method('initialiserPas');

        $fiche = new Fiche('fiche1',array($mockPas1,$mockPas2));

        $fiche->initialiserFiche();

        $this->assertNull($fiche->getDateDebut());
        $this->assertNull($fiche->getDateFin());
        $this->assertEquals('En cours', $fiche->getStatut());

    }

    public function testExecuterFiche(){

        $mockPas1 = $this->createMock(Pas::class);
        $mockPas1->expects($this->once())
                ->method('initialiserPas');

        $mockPas2 = $this->createMock(Pas::class);
        $mockPas2->expects($this->once())
                ->method('initialiserPas');

        $fiche = new Fiche('fiche1',array($mockPas1,$mockPas2));

        $fiche->executerFiche();

        $this->assertEquals(date('Y-m-d'), $fiche->getDateDebut());
        $this->assertNull($fiche->getDateFin());
        $this->assertEquals('En cours', $fiche->getStatut());

    }

    public function testCalculerStatut(){

        $stubPas1 = $this->createStub(Pas::class);
        $stubPas1->method('getStatut')
                    ->willReturn('KO');

        $stubPas2 = $this->createStub(Pas::class);
        $stubPas2->method('getStatut')
                    ->willReturn('OK');

        $fiche = new Fiche('fiche1',array($stubPas1,$stubPas2));

        $fiche->calculerStatut();

        $this->assertEquals('KO', $fiche->getStatut());
    }

    public function testTerminerFiche(){
        $fiche = new Fiche('fiche1',null);

        $fiche->terminerFiche();

        $this->assertEquals(date('Y-m-d'), $fiche->getDateFin());
    }
}