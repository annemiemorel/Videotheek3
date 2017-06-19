<?php
session_start();
?>
<html>
    <head><title>Videotheek - Verwijder video</title>
        <link href="../styles/main.css" rel="stylesheet" type="text/css"> </head>
    <body>
        <h2>::: Video verwijderen </h2>
        <?php
        if (isset($_GET["error"]) && $_GET["error"] == "titelbestaat") {
            ?>
            <p style="color: red">Deze titel bestaat al!</p>
            <?php
        }

        if (isset($_GET["nummer"]) && $_GET["nummer"] == "verwijderd") {
            ?>
            <font color="#ff0000">De video "<?php print($_GET['nr']); ?>" werd verwijderd uit de database. </font>

        <?php } ?>

        <form method="post" action="../verwijdervideo.php?action=verwijder" style="font-size:1.2em ">
            <p>Kies nummer video:</p>
            <?php
            $videonummers = $_SESSION['videonummers'];
//            echo "we zijn in verwijdervideoForm :" . "<br> ";
//            echo var_dump($videonummers);
            ?>
            <select style="font-size:1em " name="nr">
                <?php
                foreach ($videonummers as $nummer) {
                    ?>

                    <option value="<?php print($nummer['videonr']); ?>"> <?php print($nummer['videonr']); ?></option>
<?php } ?>

            </select>   <br>
            <input type="submit" style="font-size:1em" value="Verwijder" class="kaderknop">    

        </form>
    <tr>
        <td><a href="../hoofdmenu.php" class="kaderknop">Terug naar Hoofdmenu</a></td>

    </tr>
<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] == "geennummer") {
        ?>
            <font color="#ff0000">U hebt geen naam ingegeven!</font>
        <?php } else if ($_GET['error'] == "nummerbestaatal") {
            ?>
            <font color="#ff0000">Deze gast bestaat reeds!</font>
        <?php
        }
    }
    exit(0);
    ?>
</body>
</html>