<?php

use PHPUnit\Framework\TestCase;
require_once "src/fiche.php";
require_once "src/pas.php";
require_once "src/exception/tooMuchPasException.php";

final class FicheTest extends TestCase
{
    protected $fiche;
    protected static $dateDuJour;

    public static function setUpBeforeClass(): void
    {
        self::$dateDuJour = new DateTime();
        // sleep(4);
        // fwrite(STDOUT,__METHOD__."\n");

    }

    public static function tearDownAfterClass(): void
    {
        self::$dateDuJour = null;
        // fwrite(STDOUT,__METHOD__."\n");
    }
    
    

    protected function setUp():void{
        $this->fiche = new Fiche('fiche1',null);
        // fwrite(STDOUT,__METHOD__."\n");
    }

    protected function tearDown():void{
        unset($this->fiche);
        // fwrite(STDOUT,__METHOD__."\n");
    }

    public function testAjouterPas(){
        // $fiche = new Fiche('fiche1',null);
        $pas = $this->createStub(Pas::class);
        $this->fiche->ajouterPas($pas);
        $this->assertEquals(1, count($this->fiche->getListePas()));
    }

    public function testExceptionAjouterPas(){
        // $pas = array($this->createStub(Pas::class),$this->createStub(Pas::class),$this->createStub(Pas::class));
        // $fiche = new Fiche('fiche1',$pas);
        $this->fiche->ajouterPas($this->createStub(Pas::class));
        $this->fiche->ajouterPas($this->createStub(Pas::class));
        $this->fiche->ajouterPas($this->createStub(Pas::class));

        $this->expectException(TooMuchPasException::class);

        $this->fiche->ajouterPas($this->createStub(Pas::class));
        // $fiche->ajouterPas($this->createStub(Pas::class));
     
    }


    public function testInitialiserFiche(){

        $mockPas1 = $this->createMock(Pas::class);
        $mockPas1->expects($this->once())
                ->method('initialiserPas');

        $mockPas2 = $this->createMock(Pas::class);
        $mockPas2->expects($this->once())
                ->method('initialiserPas');

        // $fiche = new Fiche('fiche1',array($mockPas1,$mockPas2));

        $this->fiche->ajouterPas($mockPas1);
        $this->fiche->ajouterPas($mockPas2);

        $this->fiche->initialiserFiche();

        $this->assertNull($this->fiche->getDateDebut());
        $this->assertNull($this->fiche->getDateFin());
        $this->assertEquals('En cours', $this->fiche->getStatut());

    }

    public function testExecuterFiche(){

        $mockPas1 = $this->createMock(Pas::class);
        $mockPas1->expects($this->once())
                ->method('initialiserPas');

        $mockPas2 = $this->createMock(Pas::class);
        $mockPas2->expects($this->once())
                ->method('initialiserPas');

        // $fiche = new Fiche('fiche1',array($mockPas1,$mockPas2));

        $this->fiche->ajouterPas($mockPas1);
        $this->fiche->ajouterPas($mockPas2);

        $this->fiche->executerFiche();

        // $this->assertEquals(date('Y-m-d'), $this->fiche->getDateDebut());
        $this->assertEqualsWithDelta(self::$dateDuJour->getTimestamp(),$this->fiche->getDateDebut()->getTimestamp(),3);
        $this->assertNull($this->fiche->getDateFin());
        $this->assertEquals('En cours', $this->fiche->getStatut());

    }

    public function testCalculerStatut(){

        $stubPas1 = $this->createStub(Pas::class);
        $stubPas1->method('getStatut')
                    ->willReturn('KO');

        $stubPas2 = $this->createStub(Pas::class);
        $stubPas2->method('getStatut')
                    ->willReturn('OK');

        // $fiche = new Fiche('fiche1',array($stubPas1,$stubPas2));

        $this->fiche->ajouterPas($stubPas1);
        $this->fiche->ajouterPas($stubPas2);

        $this->fiche->calculerStatut();

        $this->assertEquals('KO', $this->fiche->getStatut());
    }

    public function testTerminerFiche(){
        // $fiche = new Fiche('fiche1',null);

        $this->fiche->terminerFiche();

        $this->assertEqualsWithDelta(self::$dateDuJour->getTimestamp(),$this->fiche->getDateFin()->getTimestamp(),3);

        // $this->assertEquals(date('Y-m-d'), $this->fiche->getDateFin());
    }
}