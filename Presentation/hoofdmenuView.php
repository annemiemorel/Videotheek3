<?php
$nummers = array();
$aanwezig = array();
require_once "../Entities/Tabellijn.php";
//use Entities\Tabellijn;
//use Tabellijn;
session_start();

function printrij($huidigetitel, $nummers, $aanwezig) {
    ?> <tr> 
        <td> 
            <?php
            print($huidigetitel);
            ?>
        </td>
        <td>
            <?php
            print($nummers);
            ?>
        </td>
        <td>
            <?php
            print($aanwezig);
            ?>
        </td> 
    </tr> 
<?php } ?> 
<!DOCTYPE html>
<html>
    <head>
        <link href="../styles/main.css" rel="stylesheet" type="text/css">

        <title>Videotheek - Hoofdmenu</title>
    </head>
    <body>

        <h1>Videotheek</h1>
        <div style="border: 2px #3399CC solid; padding-left: 1em">
            <p>Raadpleeg de catalogus van onze videotheek </p></div>

        <br><br>
        <article id="main">

            <table><tbody class="video">

                <th>Titel</th><th>Nummer(s)</th><th>Exemplaren aanwezig</th>

                <?php
                $tabellijnen = $_SESSION['tabellijnen'];
//                echo "we zijn in hoofdmenuView :" . "<br> ";
//                echo var_dump($tabellijnen);  //$tabellijnen bestaat uit array van objecten met elkens 3 objecten: titel, aanwezigevideonrs en nietaanwezigevideonrs
                foreach ($tabellijnen as $tabellijn) {
//                        echo "cut";
//                        echo var_dump($tabellijn->MooieVideos());
                    printrij($tabellijn->getTitel(), $tabellijn->MooieVideos(), $tabellijn->AantalAanwezigeVideos());
                }
                ?>



                </tbody></table>
            <br><br>
            <a href="../voegtiteltoe.php?action=process" class="kaderknop">Titel toevoegen</a>
            ***<a href="../voegvideotoe.php?action=process" class="kaderknop">Video toevoegen</a><br><br>
            <a href="../verwijdertitel.php?action=process" class="kaderknop">Titel verwijderen</a>
            ***<a href="../verwijdervideo.php?action=process" class="kaderknop">Video verwijderen</a><br><br>

            <!--</form>-->
<?php
if (isset($_GET["nummer"]) && $_GET["nummer"] == "bestaatniet") {
    ?>
                <p style="color: red">Het gekozen nummer bestaat niet!</p>
                <?php
            }

            if (isset($_GET["nummer"]) && $_GET["nummer"] == "noghier") {
                ?>
                <p style="color: red">Dit nummer is nog in de videotheek. Je kan het niet inleveren.</p>
                <?php
            }
            if (isset($_GET["nummer"]) && $_GET["nummer"] == "uitgeleend") {
                ?>
                <font color="#ff0000">Dit nummer is reeds uitgeleend. </font>

            <?php } ?>

            <form method="post" action="../filmhuren.php?action=process">
                <table >
                    <tbody class="login" >
                        <tr> <td>Nummer video: </td><td><input type ="text" name="nr" size="20" required></td></tr>
                        <tr><td></td><td><input type="submit" name="ontleen" value="Ontleen Nr" class="knop but1" id="knopfit"><input type="submit" name="brengterug" value="Breng Terug" class="knop but1" id="knopfit"></td></tr>
                    </tbody>
                </table>    <br>


            </form>
        </article>
        <aside style="color:red" id="sidebar">  

        </aside>
        <br>





    </body>
</html>