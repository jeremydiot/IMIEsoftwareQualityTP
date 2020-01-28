<?php

use PHPUnit\Framework\TestCase;
require_once "src/fiche.php";
require_once "src/campagne.php";
require_once "src/exception/tooMuchFicheException.php";

final class CampagneTest extends TestCase
{
    protected $campagne;
    protected static $dateDuJour;

    public static function setUpBeforeClass(): void
    {
        self::$dateDuJour = new DateTime();
    }

    public static function tearDownAfterClass(): void
    {
        self::$dateDuJour = null;
    }

    protected function setUp():void{
        $this->campagne = new Campagne('campagne1',null);
    }

    protected function tearDown():void{
        unset($this->campagne);
    }

    /**
     * @covers Campagne::ajouterFiche
     * @covers Campagne::getListeFiche
     */
    public function testAjouterFiche(){
        $fiche = $this->createStub(Fiche::class);
        $this->campagne->ajouterFiche($fiche);
        $this->assertEquals(1, count($this->campagne->getListeFiche()));
    }

    /**
     * @covers Campagne::ajouterFiche
     */
    public function testExceptionAjouterFiche(){

        for ($i=0; $i < 10; $i++) { 
            $this->campagne->ajouterFiche($this->createStub(Fiche::class));
        }
        $this->expectException(TooMuchFicheException::class);
        $this->campagne->ajouterFiche($this->createStub(Fiche::class));
    }

    /**
     * @covers Campagne::initialiserCampagne
     */
    public function testExceptionInitialiserCampagne(){
        $this->expectException(NoFicheInCampagneException::class);
        $this->campagne->initialiserCampagne();
    }

    /**
     * @covers Campagne::initialiserCampagne
     * 
     */
    public function testExecInitialiserCampagne(){
        $mockFiche1 = $this->createMock(Fiche::class);
        $mockFiche1->expects($this->once())
                ->method('initialiserFiche');

        $mockFiche2 = $this->createMock(Fiche::class);
        $mockFiche2->expects($this->once())
                ->method('initialiserFiche');

        $this->campagne->ajouterFiche($mockFiche1);
        $this->campagne->ajouterFiche($mockFiche2);

        $this->campagne->initialiserCampagne();

        $this->assertNotNull($this->campagne);

        return $this->campagne;
    }

    /**
     * @covers Campagne::getDateDebut
     * @depends testExecInitialiserCampagne
     */
    public function testDateDebutInitialiserCampagne($campagne){
        $this->assertNull($campagne->getDateDebut());
    }

    /**
     * @covers Campagne::getDateFin
     * @depends testExecInitialiserCampagne
     */
    public function testDateFinInitialiserCampagne($campagne){
        $this->assertNull($campagne->getDateFin());
    }

    /**
     * @covers Campagne::getStatut
     * @depends testExecInitialiserCampagne
     */
    public function testStatutInitialiserCampagne($campagne){
        $this->assertNull($campagne->getStatut());
    }

    
    /**
     * @covers Campagne::executerCampagne
     */
    public function testExceptionExecuterCampagne(){
        $this->expectException(NoFicheInCampagneException::class);
        $this->campagne->executerCampagne();
    }


    /**
     * @covers Campagne::executerCampagne
     */
    public function testExecExecuterCampagne(){
        $mockFiche1 = $this->createMock(Fiche::class);
        $mockFiche1->expects($this->once())
                ->method('initialiserFiche');

        $mockFiche2 = $this->createMock(Fiche::class);
        $mockFiche2->expects($this->once())
                ->method('initialiserFiche');

        $this->campagne->ajouterFiche($mockFiche1);
        $this->campagne->ajouterFiche($mockFiche2);

        $this->campagne->executerCampagne();

        $this->assertNotNull($this->campagne);

        return $this->campagne;
    }

    /**
     * @covers Campagne::getDateDebut
     * @depends testExecExecuterCampagne
     */
    public function testDateDebutExecuterCampagne($campagne){
        $this->assertEqualsWithDelta(self::$dateDuJour->getTimestamp(),$campagne->getDateDebut()->getTimestamp(),3);
    }

    /**
     * @covers Campagne::getStatut
     * @depends testExecExecuterCampagne
     */
    public function testStatutExecuterCampagne($campagne){
        $this->assertEquals('En cours', $campagne->getStatut());
    }

    /**
     * @covers Campagne::calculerStatut
     */
    public function testStatutKOCalculerStatut(){

        $stubFiche1 = $this->createStub(Fiche::class);
        $stubFiche1->method('getStatut')
                    ->willReturn('KO');

        $stubFiche2 = $this->createStub(Fiche::class);
        $stubFiche2->method('getStatut')
                    ->willReturn('OK');

        $this->campagne->ajouterFiche($stubFiche1);
        $this->campagne->ajouterFiche($stubFiche2);

        $this->campagne->calculerStatut();

        $this->assertEquals('KO', $this->campagne->getStatut());

    }

    /**
     * @covers Campagne::calculerStatut
     */
    public function testStatutOKCalculerStatut(){

        $stubFiche1 = $this->createStub(Fiche::class);
        $stubFiche1->method('getStatut')
                    ->willReturn('OK');

        $stubFiche2 = $this->createStub(Fiche::class);
        $stubFiche2->method('getStatut')
                    ->willReturn('OK');

        $this->campagne->ajouterFiche($stubFiche1);
        $this->campagne->ajouterFiche($stubFiche2);

        $this->campagne->calculerStatut();

        $this->assertEquals('OK', $this->campagne->getStatut());

    }

    /**
     * @covers Campagne::calculerStatut
     */
    public function testStatutEnCoursCalculerStatut(){

        $stubFiche1 = $this->createStub(Fiche::class);
        $stubFiche1->method('getStatut')
                    ->willReturn('En cours');

        $stubFiche2 = $this->createStub(Fiche::class);
        $stubFiche2->method('getStatut')
                    ->willReturn('En cours');

        $this->campagne->ajouterFiche($stubFiche1);
        $this->campagne->ajouterFiche($stubFiche2);

        $this->campagne->calculerStatut();

        $this->assertEquals('En cours', $this->campagne->getStatut());
    }

    /**
     * @covers Campagne::terminerCampagne
     */
    public function testExceptionTerminerCampagne(){
        $this->expectException(DateDebutNullException::class);
        $this->campagne->terminerCampagne();
    }

    /**
     * @covers Campagne::terminerCampagne
     * @covers Campagne::getDateFin
     * @depends testExecExecuterCampagne
     */
    public function testTerminerCampagne($campagne){
        $campagne->terminerCampagne();
        $this->assertEqualsWithDelta(self::$dateDuJour->getTimestamp(),$campagne->getDateFin()->getTimestamp(),3);
    }
}