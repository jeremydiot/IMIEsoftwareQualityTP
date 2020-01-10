<?php

class Pas {
    private $action;
    private $resultatAttendu;
    private $resultatObtenu;
    private $commentaire;
    private $dateExecution;
    private $statut;

    public function __construct($action,$resultatAttendu){
        $this->action = $action;
        $this->resultatAttendu = $resultatAttendu;
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction($action){
        $this->action = $action;
    }

    public function getResultatAttendu(){
        return $this->resultatAttendu;
    }

    public function setResultatAttendu($resultatAttendu){
        $this->resultatAttendu = $resultatAttendu;
    }

    public function getResultatObtenu(){
        return $this->resultatObtenu;
    }

    public function setResultatObtenu($resultatObtenu){
        $this->resultatObtenu = $resultatObtenu;
    }

    public function getCommentaire(){
        return $this->commentaire;
    }

    public function setCommentaire($commentaire){
        $this->commentaire = $commentaire;
    }

    public function getDateExecution(){
        return $this->dateExecution;
    }

    public function setDateExecution($dateExecution){
        $this->dateExecution = $dateExecution;
    }

    public function getStatut(){
        return $this->statut;
    }

    public function setStatus($statut){
        $this->statut = $statut;
    }

    public function initialiserPas(){
        $this->dateExecution = null;
        $this->resultatObtenu = null;
        $this->commentaire = null;
        $this->statut = 'Non passé';
    }

    public function executerPas($resultatObtenu, $commentaire, $statut){
        $this->dateExecution = date('Y-m-d');
        $this->resultatObtenu = $resultatObtenu;
        $this->commentaire = $commentaire;
        $this->statut = $statut;
    }
}