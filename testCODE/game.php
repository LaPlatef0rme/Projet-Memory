<?php
require_once("cards.php");
require_once("game.php");
require_once("leaderboard.php");

$images = [
    'jonathan.jpg',
    'giorno2.jpg',
    'jolyne.jpg',
    'josuke.jpg',
    'jotaro.jpg',
    'diavolo.jpg',
    'kira.png',
    '2.webp',
    'dio.jpg',
    'pucci.jpg',
    'wham.jpg',
    'kakyoin.jpg'
];

$cards = [];
foreach ($images as $index => $image) {
    $cards[] = new Card($index * 2 + 1, $image); // Première instance de la paire
    $cards[] = new Card($index * 2 + 2, $image); // Deuxième instance de la paire
}

// Mélanger les cartes une seule fois lors du démarrage de la session
if (!isset($_SESSION['shuffled_cards'])) {
    shuffle($cards); // Mélange les cartes
    $_SESSION['shuffled_cards'] = $cards; // Stocke l'ordre mélangé dans la session
} else {
    $cards = $_SESSION['shuffled_cards']; // Utilise l'ordre mélangé existant
}

// Gestion du retournement et du nombre de tours
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['flip'])) {
        $cardIdToFlip = intval($_POST['flip']);

        // Si deux cartes sont déjà retournées et qu'une troisième est retournée
        if (isset($_SESSION['flipped_cards']) && count($_SESSION['flipped_cards']) >= 2) {
            // Remettre les cartes qui ne correspondent pas à l'état non retourné
            foreach ($cards as $card) {
                if (in_array($card->getID(), $_SESSION['flipped_cards'])) {
                    $card->resetFlip();
                }
            }
            $_SESSION['flipped_cards'] = []; // Réinitialiser après 2 cartes retournées
        }

        // Retourner la carte qui a été cliquée
        foreach ($cards as $card) {
            $card->handleFlip($cardIdToFlip);
        }

        // Stocker les cartes retournées dans la session pour comparer
        if (!isset($_SESSION['flipped_cards'])) {
            $_SESSION['flipped_cards'] = [];
        }

        // Ajout de la carte retournée
        if (!in_array($cardIdToFlip, $_SESSION['flipped_cards'])) {
            $_SESSION['flipped_cards'][] = $cardIdToFlip;
        }

        // Vérifier si deux cartes sont retournées
        if (count($_SESSION['flipped_cards']) == 2) {
            $firstCardId = $_SESSION['flipped_cards'][0];
            $secondCardId = $_SESSION['flipped_cards'][1];

            // Comparer les deux cartes retournées
            $firstCard = null;
            $secondCard = null;

            foreach ($cards as $card) {
                if ($card->getID() == $firstCardId) {
                    $firstCard = $card;
                } elseif ($card->getID() == $secondCardId) {
                    $secondCard = $card;
                }
            }

            // Vérifie si les cartes correspondent
            if ($firstCard && $secondCard) {
                if ($firstCard->isMatched($secondCard)) {
                    $firstCard->match(); // Marquer comme assortie
                    $secondCard->match(); // Marquer comme assortie
                }
            }

            // Incrémentation du nombre de tours
            $_SESSION['turn_count'] = ($_SESSION['turn_count'] ?? 0) + 1;

            // Sauvegarder les modifications dans la session
            $_SESSION['shuffled_cards'] = $cards;
        } else {
            // Sauvegarder les modifications dans la session (retournement de la première carte)
            $_SESSION['shuffled_cards'] = $cards;
        }
    }

    // Réinitialiser le jeu
    if (isset($_POST['reset'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Vérifie si un nombre de paires a été soumis et stocké
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pairs_count'])) {
    $pairsCount = intval($_POST['pairs_count']);
    $_SESSION['pairs_count'] = $pairsCount; // Stocker dans la session
} else {
    $pairsCount = $_SESSION['pairs_count'] ?? 3; // Valeur par défaut : 3 paires
}

// Limite le nombre de paires entre 3 et 12
$pairsCount = max(3, min($pairsCount, 12));

// Génération des cartes en fonction du nombre de paires
$cards = [];
for ($i = 0; $i < $pairsCount; $i++) {
    $cards[] = new Card($i * 2 + 1, $images[$i]); // Première instance de la paire
    $cards[] = new Card($i * 2 + 2, $images[$i]); // Deuxième instance de la paire
}

// Mélanger les cartes une seule fois lors du démarrage de la session
if (!isset($_SESSION['shuffled_cards']) || count($_SESSION['shuffled_cards']) !== $pairsCount * 2) {
    shuffle($cards); // Mélange les cartes
    $_SESSION['shuffled_cards'] = $cards; // Stocke l'ordre mélangé dans la session
} else {
    $cards = $_SESSION['shuffled_cards']; // Utilise l'ordre mélangé existant
}

// Gestion du retournement des cartes, logique inchangée
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['flip'])) {
        $cardIdToFlip = intval($_POST['flip']);

        // Logique du retournement des cartes...
    }

    // Réinitialisation du jeu
    if (isset($_POST['reset'])) {
        session_destroy();
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Jeu de Memory</title>
</head>
<body>
    <div class="container">
        <p>Nombre de coups :</p>
        <p class="nbrtour"><?php echo $_SESSION['turn_count'] ?? 0; ?></p>
    </div>

    <!-- Formulaire de sélection du nombre de paires -->
    <div class="container2">
        <form method="POST">
            <label for="pairs_count">Nombre de paires (3 à 12) :</label>
            <input type="number" id="pairs_count" name="pairs_count" value="<?php echo $pairsCount; ?>" min="3" max="12">
            <button type="submit" class="texte2">Définir</button>
        </form>
    </div>

    <div class="container-cards">
        <?php
        // Afficher les cartes dans la grille
        foreach ($cards as $card) {
            echo "<div class='img-container'>";
            $card->displayCard();
            echo '</div>';
        }
        ?>
    </div>

    <div class="container2">
        <form method="POST">
            <button type="submit" name="reset" class="texte2">Redémarrer</button>
        </form>
    </div>
</body>
</html>
