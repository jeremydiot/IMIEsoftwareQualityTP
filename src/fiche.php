<?php

require_once "src/exception/tooMuchPasException.php";

class Fiche{

    private $Nom;
    private $dateDebut;
    private $dateFin;
    private $statut;
    private $listePas;

    public function __construct($nom, $listePas){
        if(!is_array($listePas)) $listePas = array();
        $this->listePas = $listePas;
        $this->nom = $nom;
    }

    public function getListePas(){
        return $this->listePas;
    }

    public function getDateDebut(){
        return $this->dateDebut;
    }

    public function getDateFin(){
        return $this->dateFin;
    }

    public function getStatut(){
        return $this->statut;
    }


    public function executerFiche(){
        $this->dateDebut = new DateTime();
        $this->dateFin = null;
        $this->statut = 'En cours';

        foreach ($this->listePas as $pas){
            $pas->initialiserPas();
        }
    }


    public function initialiserFiche(){
        $this->dateDebut = null;
        $this->dateFin = null;
        $this->statut = 'En cours';

        foreach ($this->listePas as $pas){
            $pas->initialiserPas();
        }
    }


    public function calculerStatut(){
        
        $statuts = array();

        foreach ($this->listePas as $pas){
            $statuts[] = $pas->getStatut();
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


    public function ajouterPas($pas){

        if(count($this->listePas)==3){
            throw new TooMuchPasException();
        }
        $this->listePas[] = $pas; 
    }


    public function terminerFiche(){
        $this->dateFin = new DateTime();
    }
}