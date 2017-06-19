<?php

namespace Business;
require_once 'Data/VideoDAO.php';
use Data\VideoDAO;


Class VideoService{

    public function getTabellijnen(){
        $dDAO = new VideoDAO();
        $lijst = $dDAO->getTabellijnen();
        return $lijst;
    }
    public function getVideonummers(){
        $dDAO = new VideoDAO();
        $lijst = $dDAO->getVideonummers();
        return $lijst;
    }
    public function getTitels() {
        $dDAO = new VideoDAO();
        $lijst = $dDAO->getTitels();
        return $lijst;
   }
   public function haalCombiLijst(){
       $dDAO = new VideoDAO();
        $lijst = $dDAO->haalcombiLijst();
        return $lijst;
   }
   public function getVideoGegevens($id) {
        $dDAO = new VideoDAO();
        $lijst = $dDAO->getVideoGegevens($id);
        return $lijst;
   }
    public function checkVoorraad($type){
        //console.log("checkvoorraad");
        $gDAO = new VideoDAO();
        $voorraad=$gDAO->haalvoorraad($type);
        return; // $voorraad;
    }
    public function haalNummers($titel){
        //console.log("checkvoorraad");
        $gDAO = new VideoDAO();
        $nummers=$gDAO->getNummersTitel($titel);
        return $nummers;
    }
     public function voegTitelToe($titel) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->voegtiteltoe($titel);
        return;
    } 
    public function voegVideoToe($titel,$nr) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->voegvideotoe($titel,$nr);
        return;
    } 
      public function verwijderTitel($titel) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->verwijdertitel($titel);
        return;
    } 
     public function verwijderVideo($nr) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->verwijdervideo($nr);
        return;
    } 
     public function ontleenNummer($nr) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->ontleennummer($nr);
        return;
    } 
     public function brengNummer($nr) { //functie nodig om boek toe te voegen
        $gDAO = new VideoDAO();
        $gDAO->brengnummer($nr);
        return;
    } 
}
