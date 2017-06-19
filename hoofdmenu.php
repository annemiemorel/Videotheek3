<?php
session_start();
unset($_SESSION['tabellijnen']);
//unset($_SESSION['videogeg']);
header("location: doeactie.php?action=init");  //doeactie.php?action=stuurmail");
exit(0);
?> 

