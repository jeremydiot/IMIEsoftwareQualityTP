<?php

require_once "src/exception/tooMuchFicheException.php";
require_once "src/exception/dateDebutNullException.php";
require_once "src/exception/noFicheInCampagneException.php";

class Campagne{

    private $nom;
    private $dateDebut;
    private $dateFin;
    private $statut;
    private $listeFiche;

    public function __construct($nom,$listeFiche){
        if(!is_array($listeFiche)) $listeFiche = array();
        $this->listeFiche = $listeFiche;
        $this->nom = $nom;
    }

    public function getNom(){
        return $this->nom;
    }

    public function setNom($nom){
        $this->nom = $nom;
    }

    public function getDateDebut(){
        return $this->dateDebut;
    }

    public function setDateDebut($dateDebut){
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(){
        return $this->dateFin;
    }

    public function setDateFin($dateFin){
        $this->dateFin = $dateFin;
    }

    public function getStatut(){
        return $this->statut;
    }

    public function setStatut($statut){
        $this->statut = $statut;
    }

    public function getListeFiche(){
        return $this->listeFiche;
    }

    public function setListeFiche($listeFiche){
        $this->listeFiche = $listeFiche;
    }


    public function ajouterFiche($fiche){
        if(count($this->listeFiche)==10){
            throw new TooMuchFicheException();
        }
        $this->listeFiche[] = $fiche;
    }

    public function initialiserCampagne(){
        if(count($this->listeFiche)==0){
            throw new NoFicheInCampagneException();
        }

        $this->dateDebut = null;
        $this->dateFin = null;
        $this->statut = null;

        foreach ($this->listeFiche as $fiche) {
            $fiche->initialiserFiche();
        }
    }

    public function executerCampagne(){
        if(count($this->listeFiche)==0){
            throw new NoFicheInCampagneException();
        }
        foreach ($this->listeFiche as $fiche) {
            $fiche->initialiserFiche();
        }
        $this->dateDebut = new DateTime();
        $this->statut = "En cours";
    }

    public function calculerStatut(){
        $statuts = array();

        foreach ($this->listeFiche as $fiche){
            $statuts[] = $fiche->getStatut();
        }

        foreach(array_count_values($statuts) as $k => $v){
            if($k == 'KO'){
                $this->statut = 'KO';
                break;
            }
            if($k == 'OK' && $v == count($statuts)){
                $this->statut = 'OK';
                break;
            }
        }

        if($this->statut != 'KO' && $this->statut != 'OK'){
            $this->statut = 'En cours';
        }
    }

    public function terminerCampagne(){
        if($this->dateDebut == null){
            throw new DateDebutNullException();
        }
        $this->dateFin = new DateTime();
    }



}