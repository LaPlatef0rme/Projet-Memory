<?php
session_start();
require_once 'Card.php';
require_once 'index.php';

// Récupérer les cartes utilisée
$cards = $_SESSION['cards'];
$flipCards = $_SESSION['flipCards'];
$trouverPairs = $_SESSION['trouverPairs'];
$compteur = $_SESSION['compteur'];

// Vérifier quelle carte a été retournée
if (isset($_POST['retourn'])) {
    $cardIndex = intval($_POST['retourn']);

    // Ajouter la carte retournée à la liste des cartes retournées
    if (!in_array($cardIndex, $flipCards)) {
        $flipCards[] = $cardIndex;
    }

    // Si deux cartes sont retournées, on les compare
    if (count($flipCards) === 2) {
        $card1 = $cards[$flipCards[0]];
        $card2 = $cards[$flipCards[1]];

        // Incrémenter le compteur de tentatives
        $compteur++;

        // Comparer les deux cartes
        if ($card1->getId() === $card2->getId()) {
            // Les cartes correspondent, les ajouter à la liste des paires trouvées
            $trouverPairs[] = $flipCards[0];
            $trouverPairs[] = $flipCards[1];
        }

        // Réinitialiser les cartes retournées après comparaison
        $flipCards = [];
    }

    // Sauvegarder l'état du jeu dans la session
    $_SESSION['cards'] = $cards;
    $_SESSION['flipCards'] = $flipCards;
    $_SESSION['trouverPairs'] = $trouverPairs;
    $_SESSION['compteur'] = $compteur;
}

// Retourner à la page de jeu
// header('Location: index.php');
exit;
?>