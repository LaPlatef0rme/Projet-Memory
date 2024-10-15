<?php

session_start();
   
// Vérifier si la session contient bien un objet 'player' avec un score
if (!isset($_SESSION['player']) || !isset($_SESSION['player']->score)) {
    // Valeur par défaut en cas d'absence d'informations sur le joueur
    $_SESSION['player'] = (object)[ 'score' => 0 ];  // ou une autre valeur par défaut
}
    //logique affichage classement ici

    echo '<h1> Classement des Joueurs </h1>';

    $ranking = [
        ['name' => 'Champion 1', 'score' => 100],
        ['name' => 'Champion 2', 'score'=> 90],
        ['name' => 'Joueur 1', 'score' => $_SESSION['player']->score],
    ];
    foreach ($ranking as $index => $player){
        echo ($index + 1) . ". " . $player['name'] . " - " . $player['score'] . "<br>";
    }

?>

