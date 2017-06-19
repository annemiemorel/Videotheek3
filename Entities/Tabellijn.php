<?php

namespace Entities;

class Tabellijn {

//    private static $idMap = array();  //bevat alle reeds aangemaakte objecten van klasse Voornaam; static: slechts 1 lijst voor alle Voornaam-objecten   
   // private $id;
    private $titel;
   // private $videonr;
   // private $titelnr;
    private $aanwezigevideonrs; //array
    private $nietaanwezigevideonrs; //array

    public function __construct($titel) {
   
        $this->titel = $titel;
        $this->aanwezigevideonrs=array();
        $this->nietaanwezigevideonrs=array();
    }

//    public static function create($id, $titel, $aanwezigevideonrs, $nietaanwezigevideonrs) {
//        if (!isset(self::$idMap[$id])) {  //geindexeerd met id van Boek-object: snel controleren of Boek-object met bepaalde id werd aangemaakt zonder hele array te overlopen
//            self::$idMap[$id] = new Tabellijn($id, $titel, $aanwezigevideonrs, $nietaanwezigevideonrs);  //indien er nog geen Boek-object met dit id bestaat, dan nieuw Boek-object aanmaken via constructor en aan lijst toevoegen
//        }
//        return self::$idMap[$id];  //indien er wel Boek-object met dit id bestaat, dan wordt het bestaande object teruggegeven
//    }

//    public function getId() {
//        return $this->id;
//    }


    public function getTitel() {
        return $this->titel;
    }

    public function getAanwezigeVideonrs() {
        return $this->aanwezigevideonrs;
    }
    
    public function getNietAanwezigeVideonrs() {
        return $this->nietaanwezigevideonrs;
    }

    public function setTitel($titel) {
        $this->titel = $titel;
    }

    public function setAanwezigeVideonrs($aanwezigevideonrs) {
        $this->aanwezigevideonrs = $aanwezigevideonrs;
    }
    public function setNietAanwezigeVideonrs($nietaanwezigevideonrs) {
        $this->nietaanwezigevideonrs = $nietaanwezigevideonrs;
    }
    public function MooieVideos(){
        $mooiestring='';
        foreach($this->aanwezigevideonrs as $nr){
            $mooiestring.= "<b>".$nr['videonr']."</b> ";
        }
        foreach($this->nietaanwezigevideonrs as $nr){
            $mooiestring .= $nr['videonr']." ";
        }
        //echo var_dump($mooiestring);
        return $mooiestring;
    }
    public function AantalAanwezigeVideos(){
        return count($this->getAanwezigeVideonrs());
    }
    public function totaalAantal(){
        $totaal=count($this->aanwezigevideonrs)+count($this->nietaanwezigevideonrs);
        return $totaal;
    }
}
