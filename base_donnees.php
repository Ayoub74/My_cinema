<?php

try
{
    // On se connecte à MySQL
    $bdd = new PDO('mysql:host=localhost;dbname=my_cinema;charset=utf8', 'ayoub', 'toor');
		
}
catch(Exception $e)
{
    // En cas d'erreur, on affiche un message et on arrête tout
        die('Erreur : '.$e->getMessage());
}

?>