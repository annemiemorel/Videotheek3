<?php

require_once "Business/VideoService.php";
require_once "Exceptions/TitelBestaatException.php";
use Business\VideoService;
use Exceptions\TitelBestaatException;
session_start();
function haaltitels(){
    $pl = new VideoService(); 
     $lijst = $pl->getTitels(); 
     echo print_r($lijst);
        //echo $lijst[0]->getPrijs();
       
    $_SESSION['lijst']=$lijst; 
    return $lijst;

}


function haalcombilijst(){
    $pl = new VideoService(); 
     $lijst = $pl->haalCombiLijst(); 
    // echo print_r($lijst);
        //echo $lijst[0]->getPrijs();
       
//    $_SESSION['combilijst']=$lijst; 
    return $lijst;
}
function haaltabellijnen(){
    $pl = new VideoService(); 
     $lijst = $pl->getTabellijnen(); 
    return $lijst;
}
function haalvideonummers(){
    $pl = new VideoService(); 
     $lijst = $pl->getVideonummers(); 
    return $lijst;
}
if (isset($_GET["action"]) && $_GET["action"] == "init"){
    try{
    $lijst=haaltitels();
    $tabellijnen= haaltabellijnen();
   // echo "tabellijnen=".var_dump($tabellijnen);
    $_SESSION['tabellijnen']=$tabellijnen;
    //echo var_dump($combilijst);
    $_SESSION['lijst']=$lijst;   
    $combilijst=haalcombilijst();
    $_SESSION['combilijst']=$combilijst;
     $aantaltitels=count($lijst);
         //   echo "aantal = ". $aantaltitels;
//        for($x=0;$x<$aantaltitels;$x++) { 
//            $Svc= new VideoService();
//           $_SESSION['videogeg'][$x]=$Svc->getVideoGegevens($lijst[$x]['id']); //videogegevens van titel in $x
//        }
//        echo var_dump($_SESSION['videogeg']);
     $videonummers=haalvideonummers();
     $_SESSION['videonummers']=$videonummers;
    header("location: Presentation/hoofdmenuView.php");
    exit(0);
    }
    catch(TitelBestaatNietException $ex){
        header("location: Presentation/hoofdmenuView.php?titel=bestaatniet");
        exit(0);
    }
}
if (isset($_GET["action"]) && $_GET["action"] == "verwijder"){
    try{
         $lijst=haaltitels();
       // $_SESSION['lijst']=$lijst;   
           //echo print_r($lijst);

     $aantaltitels=count($lijst);
        header("location: Presentation/verwijdertitelForm.php?titel=bestaatniet");
        exit(0);
    } catch (Exception $ex) {

    }
    
}
//if (isset($_GET["action"]) && $_GET["action"] == "process") {
//    header("location: Presentation/voegtiteltoeForm.php"); //hoofdmenu.php");  //
//        exit(0);
//}
if (isset($_GET["action"]) && $_GET["action"] == "haalnummers"){
     $pl = new VideoService(); 
     $lijst=$_SESSION['lijst'];
     $aantaltitels=count($lijst);
       //echo "aantal = ". $aantaltitels;
        for($x=0;$x<$aantaltitels;$x++) { 
            $_SESSION['nummers'][$x]=$pl->haalNummers($lijst[$x]['titel']); 
        }
}