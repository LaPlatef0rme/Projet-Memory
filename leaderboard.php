<?php
session_start();

require_once ("database.php");

// Vérifier si la session contient bien un objet 'player' avec un score
if (!isset($_SESSION['player'])) {
    $_SESSION['player'] = new stdClass();  // Crée un objet vide si non défini
    $_SESSION['player']->score = 0;  // Valeur par défaut pour le score
}

// Logique d'affichage du classement
echo '<h1> Classement des Joueurs </h1>';

$ranking = [
    ['name' => 'Champion 1', 'score' => 100],
    ['name' => 'Champion 2', 'score' => 90],
    ['name' => 'Joueur 1', 'score' => $_SESSION['player']->score], // Utilisation de l'objet player
];

foreach ($ranking as $index => $player) {
    echo ($index + 1) . ". " . htmlspecialchars($player['name']) . " - " . $player['score'] . "<br>";
}
?>
