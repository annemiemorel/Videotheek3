<?php

namespace Data;

require_once 'DBConfig.php';
require_once 'Entities/Video.php';
require_once 'Entities/Titel.php';
require_once 'Entities/Tabellijn.php';
////require_once 'Entities/Login.php';
//require_once 'Exceptions/GebruikerBestaatException.php';
require_once 'Exceptions/VideoNrBestaatException.php';
require_once 'Exceptions/TitelBestaatException.php';
require_once 'Exceptions/NummerBestaatNietException.php';
require_once 'Exceptions/NummerIsAlUitgeleendException.php';
require_once 'Exceptions/NummerIsNietUitgeleendException.php';

use Data\DBConfig;
use Entities\Video;
use Entities\Titel;
use Entities\Tabellijn;
//use Entities\Bestelling;
//use Exceptions\GebruikerBestaatException;
use Exceptions\VideoNrBestaatException;
use Exceptions\TitelBestaatException;
use Exceptions\NummerBestaatNietException;
use Exceptions\NummerIsAlUitgeleendException;
use Exceptions\NummerIsNietUitgeleendException;
use PDO;

session_start();

class VideoDAO {

    public function getTabellijnen() {
        $tabellijnen = array(); //hier komt voor  elke titel een object van klasse Tabellijn
        $titellijst = $this->getTitels();
         echo "<br> titellijst=".var_dump($titellijst)."<br>";
        foreach ($titellijst as $titelenkel) {  //doe dit voor elke titel: 
            $titel = $titelenkel['titel'];  //selecteer titel uit array met ['id'] en ['titel']
           // echo "titel=".$titel."<br>";
            $tabellijn = new Tabellijn($titel); //titel = eerste element van tabellijn
            $sql = "select videonr from titels inner join videos on titels.id=titelnr where aanwezig=1 and titel= :titel";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            //print_r($stmt);
            $stmt->execute(array(':titel' => $titel));
            $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
            //echo "aanwezig ".var_dump($resultSet)."<br>";
            $totaal=count($resultSet);
            $tabellijn->setAanwezigeVideonrs($resultSet); //aanwezige videonrs = tweede element van tabellijn
            $sql = "select videonr from titels inner join videos on titels.id=titelnr where aanwezig=0 and titel= :titel";
            // $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
            $stmt = $dbh->prepare($sql);
            //print_r($stmt);
            $stmt->execute(array(':titel' => $titel));
            $resultSet2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
           // echo "niet aanwezig ".var_dump($resultSet2)."<br>";
            $totaal+=count(resultSet2);
            $tabellijn->setNietAanwezigeVideonrs($resultSet2); //niet aanwezige videonrs = derde element van tabellijn
            $dbh = null;
           // echo "aantal=" .$totaal."<br>";
            if ($totaal!=0) {  //voeg $tabellijn toe aan array $tabellijnen als er minstens 1 video (aanwezig of niet aanwezig) bestaat voor een titel
                array_push($tabellijnen, $tabellijn); 
            }
        }
        //echo "<br> in videoDAO: <br>";
        //echo var_dump($tabellijnen);
        return $tabellijnen;
    }

    public function getTitels() {
        $sql = "select distinct titel from titels order by titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        //echo "getTitels=".var_dump($resultSet)."<br>";
        $dbh = null;

        if (!$resultSet) {  //niets gevonden
            throw new TitelBestaatNietException();
        } else {
            //echo var_dump($resultSet)."<br>";
            return $resultSet;
        }
    }

    public function getVideoGegevens($titelnr) {
        $sql = "select videonr, aanwezig from videos where titelnr= :titelnr";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute(array(':titelnr' => $titelnr));
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dbh = null;

//       if (!$resultSet) {  //niets gevonden
//        throw new TitelBestaatNietException();
//       } else {
        return $resultSet;

//}
    }

    public function haalcombiLijst() {
        $sql = "SELECT titel, videonr, titelnr, aanwezig FROM videos inner join titels on titels.id=titelnr order by titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $dbh = null;

//       if (!$resultSet) {  //niets gevonden
//        throw new TitelBestaatNietException();
//       } else {
        return $resultSet;
    }
    public function getVideonummers() {
      
        $sql = "SELECT videonr FROM videos";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "videonrs=".var_dump($resultSet);
        $dbh = null;

//       if (!$resultSet) {  //niets gevonden
//        throw new TitelBestaatNietException();
//       } else {
        return $resultSet;
    }
    public function getAll() {  //refresh: haal terug alle gegevens op
        $titels = $this->getTitels();
        foreach ($titels as $rij) {
            $gegevens = $this->getVideoGegevens($rij['id']);
            print($gegevens);
        }
        echo $titels;
//      $sql = "select * from videos order by titel";
//    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
//     $stmt = $dbh->prepare($sql);
//     //print_r($stmt);
//        $stmt->execute();
//        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
//
//        $dbh = null;
//        
//       if (!$resultSet) {  //niets gevonden
//        throw new DatumBestaatNietException();
//       } else {
        return $titels;
//       }
    }

    public function getNummersTitel() {
        $sql = "select id from videos where titel= :titel order by titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute();
        $resultSet = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // $resultSet = $dbh->query($sql);
        $lijst = array();
        foreach ($resultSet as $rij) {
            $video = new Video($rij["id"], $rij["titel"], $rij["aanwezig"]);
            array_push($lijst, $video);
        }
        $dbh = null;
        //$_SESSION["velden"]=sizeof($lijst); //aantal items
        //echo "lijst=".print_r($lijst);
        $_SESSION['lijst'] = $lijst;
        return; // $lijst;
    }

    public function getByOneTitel($titel) {
        $sql = "select id from titels where titel= :titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute(array(':titel' => $titel));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);  //geen enkele titel kan dubbel voorkomen in lijst  titels

        $dbh = null;
        return $rij['id'];
//       if (!$rij) {  //niets gevonden
//            return $rij;
//       } else {
//        throw new TitelBestaatException();}
    }
  
//public function create($titel) {  //nieuwe functie om boek te kunnen toevoegen
//    //**foutafhandeling**//
//
//    //$bestaandeVideo = $this->getByOneTitel($titel); //null indien nog niet bestaat, anders ?
//
//    //**foutafhandeling**//
//    $sql = "insert into titels (titel) values (:titel)";
//    //echo $sql;
//    $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//    $stmt = $dbh->prepare($sql); 
//
//    $stmt->execute(array(':titel' => $titel));
//
//    $gastId = $dbh->lastInsertId();
//    $dbh = null; 
//
//    $titel = Titel::create($gastId, $titel);
//    return $titel;
//} 
    public function voegtiteltoe($titel) {
        $sql = "select id from titels where titel= :titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute(array(':titel' => $titel));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);  //geen enkele titel kan dubbel voorkomen in lijst  titels

        if (!$rij) {  //niets gevonden
            //echo "false";
            $sql = "insert into titels (titel) values (:titel)";
            //echo $sql;

            $stmt = $dbh->prepare($sql);

            $stmt->execute(array(':titel' => $titel));
            $gastId = $dbh->lastInsertId();
            $dbh = null;

            $titel = Titel::create($gastId, $titel);
            return $titel;
        } else {
            throw new TitelBestaatException();
        }
    }

    public function voegvideotoe($titel, $videonr) {
        $sql = "select id from videos where videonr= :videonr";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        //print_r($stmt);
        $stmt->execute(array(':videonr' => $videonr));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);  //geen enkele titel kan dubbel voorkomen in lijst  titels

        if (!$rij) {  //niets gevonden
            //echo "false";
            $titelnr = $this->getByOneTitel($titel);
            echo($titelnr);
            $sql = "insert into videos (videonr, titelnr, aanwezig) values ( :videonr, :titelnr, :aanwezig)";
            //echo $sql;

            $stmt = $dbh->prepare($sql);

            $stmt->execute(array(':videonr' => $videonr, ':titelnr' => $titelnr, ':aanwezig' => 1));
            $gastId = $dbh->lastInsertId();
            $dbh = null;

            $video = Video::create($gastId, $videonr, $titelnr, 1);
            return $video;
        } else {
            throw new VideoNrBestaatException();
        }
    }

    public function getVideoNummer($nr) {
        $sql = "select aanwezig from videos where videonr= :videonr";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':videonr' => $nr));
        $rij = $stmt->fetch(PDO::FETCH_ASSOC);  //geen enkele titel kan dubbel voorkomen in lijst  titels

        if (!$rij) {
            throw new NummerBestaatNietException();
        } else {
            return $rij['aanwezig'];
        }
    }

    public function ontleennummer($nr) {
        if ($this->getVideoNummer($nr) == true) {

            $sql = "update videos set aanwezig= :aanwezig where videonr= :videonr";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':aanwezig' => 0, ':videonr' => $nr));
            $dbh = null;

            return;
        } else {
            throw new NummerIsAlUitgeleendException();
            //echo "fout emailadres";
        }
    }

    public function brengnummer($nr) {
        if ($this->getVideoNummer($nr) == false) {
            $sql = "update videos set aanwezig= :aanwezig where videonr= :videonr";
            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
            $stmt = $dbh->prepare($sql);
            $stmt->execute(array(':aanwezig' => 1, ':videonr' => $nr));
            $dbh = null;

            return;
        } else {
            throw new NummerIsNietUitgeleendException();
            //echo "fout emailadres";
        }
    }

    public function verwijdertitel($titel) {   //nieuwe functie om boek te verwijderen
        $titelid = $this->getByOneTitel($titel);
        echo $titelid;
        $sql = "delete from titels where titel = :titel";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//    $dbh = new PDO("mysql:host=localhost;dbname=cursusphp;charset=utf8;port=3307","cursusgebruiker","cursuspwd");//;DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':titel' => $titel));
//    $dbh = null;
        $sql = "delete from videos where titelnr = :titelnr";
        //$dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//    $dbh = new PDO("mysql:host=localhost;dbname=cursusphp;charset=utf8;port=3307","cursusgebruiker","cursuspwd");//;DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':titelnr' => $titelid));
        $dbh = null;
    }

    public function verwijdervideo($nr) {   //nieuwe functie om boek te verwijderen
//        $titelid=$this->getByOneTitel($titel);
//        echo $titelid;
        $sql = "delete from videos where videonr = :videonr";
        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//    $dbh = new PDO("mysql:host=localhost;dbname=cursusphp;charset=utf8;port=3307","cursusgebruiker","cursuspwd");//;DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
        $stmt = $dbh->prepare($sql);
        $stmt->execute(array(':videonr' => $nr));
//  
        $dbh = null;
    }

//   public function checklogin($email,$paswoord){ //functie die controleert of paswoord past bij voornaam gast
//        $sql="SELECT id FROM cursisten WHERE email= :email and paswoord= :paswoord";
//        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD); 
//        $stmt = $dbh->prepare($sql);
//        $paswoord= sha1("Annemie".$paswoord);
//       $stmt->execute(array(':email' => $email, ':paswoord'=> $paswoord));
//       $rij = $stmt->fetch(PDO::FETCH_ASSOC);
//
//       if (!$rij) {  //niets gevonden
//        //echo "false";
//        throw new FoutPaswoordException();
//        
//       } else {
////        $genre = Genre::create($rij["genre_id"], $rij["genre"]);
////        $boek = Boek::create($rij["boek_id"], $rij["titel"], $genre);
//       
//       // $_SESSION["gast"]=$rij["id"];
//       // echo $_SESSION["gast"]; 
//        $dbh = null;
//        return true; //wel boek gevonden met titel $titel
//       }
//   }
//    public function getByEmail($email){  //functie om na te gaan of er reeds een boek met deze titel bestaat (foutafhandeling)
//        $sql = "select id from cursisten where email = :email" ;
//     $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//     $stmt = $dbh->prepare($sql);
//       $stmt->execute(array(':email' => $email));
//       $rij = $stmt->fetch(PDO::FETCH_ASSOC);
//        $dbh = null;
//       if (!$rij) {  //niets gevonden
//        return null;
//       } else {
//        $cursist = "bestaat"; //Cursist::create($rij["id"], $rij["email"], $paswoord);
//        
//        
//        return $rij["id"]; //wel boek gevonden met titel $titel
//       }
//
//    }  
//    
//    public function plaatsbestelling($email,$paswoord){
//        if(!$this->getByEmail($email)==null){
//        if($this->checklogin($email,$paswoord)){
//            $bestaandeGebruiker=$this->getByEmail($email);
//            //echo $bestaandeGebruiker;
//            for($x=0;$x<$_SESSION["aantalbroodjes"];$x++){
//                
//                $bestelling= $_SESSION["bestellingcursist"][$x][0];
//                $prijs= $_SESSION["bestellingcursist"][$x][1];
//       
//             $sql = "insert into bestellingen (datum,cursist,bestelling,prijs) values (:datum, :cursist, :bestelling, :prijs)";
//            //echo $sql;
//            $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//            $stmt = $dbh->prepare($sql); 
//            $datum = date("Y-m-d");
//
//            $stmt->execute(array(':datum' => $datum, ':cursist' => $bestaandeGebruiker, ':bestelling' => $bestelling, ':prijs' => $prijs));
//        }}
//         $dbh = null;
//        return;}
//        else{
//            throw new EmailBestaatNietException();
//        }
//    }  
//    
//    public function haalvoorraad($type){
//        //console.log("haalvoorraad");
//        $sql = "select voorraad from drank where type= :type";
//        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//      
//        //$lijst = array();
//        $stmt = $dbh->prepare($sql);
//       $stmt->execute(array(':type' => $type));
//       $rij = $stmt->fetch(PDO::FETCH_ASSOC);
//       $voorraad=$rij['voorraad'];
//       $voorraad=$voorraad-1;
//       $sql = "update drank set voorraad= :voorraad where type= :type";
//       $stmt = $dbh->prepare($sql);
//       $stmt->execute(array(':voorraad'=> $voorraad, ':type' => $type));
//       //echo print_r($resultSet);
//       //echo "totaal = ".count($resultSet);
//        $dbh = null;
//        
//       if (!$rij) {  //niets gevonden
//           //console.log("fout type");
//        throw new FrisDrankException();
//       } else {
////       
//           
//           
//         return $voorraad;
//        }
//}
//public function haalprijs($type){
//        //console.log("haalvoorraad");
//        $sql = "select prijs from drank where type= :type";
//        $dbh = new PDO(DBConfig::$DB_CONNSTRING, DBConfig::$DB_USERNAME, DBConfig::$DB_PASSWORD);
//      
//        //$lijst = array();
//        $stmt = $dbh->prepare($sql);
//       $stmt->execute(array(':type' => $type));
//       $rij = $stmt->fetch(PDO::FETCH_ASSOC);
//       
//       //echo print_r($resultSet);
//       //echo "totaal = ".count($resultSet);
//        $dbh = null;
//        
//       if (!$rij) {  //niets gevonden
//           //console.log("fout type");
//        throw new FrisDrankException();
//       } else {
////       
//           $prijs=$rij['prijs'];
//           
//         return $prijs;
//        }
//}
}
