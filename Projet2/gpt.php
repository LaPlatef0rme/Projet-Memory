<?php
require_once("cards.php");

$images = [
    'number_one.webp',
    'number_two.webp',
    'number_three.webp',
    'number_four.jpg',
    'number_five.webp',
    'number_six.webp',
    'number_seven.webp',
    'number_eight.webp',
    'number_nine.webp',
    'number_ten.jpg',
    'number_eleven.jpg',
    'number_twelve.jpg'
];

$cards = [];
foreach ($images as $index => $image) {
    $cards[] = new Card($index * 2 + 1, $image);
    $cards[] = new Card($index * 2 + 2, $image);
}

// Mélanger les cartes lors du premier démarrage
if (!isset($_SESSION['shuffled_cards'])) {
    shuffle($cards);
    $_SESSION['shuffled_cards'] = $cards;
} else {
    $cards = $_SESSION['shuffled_cards'];
}

// Gérer le retournement des cartes et les tours
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['flip'])) {
        $cardIdToFlip = intval($_POST['flip']);

        // Si deux cartes sont déjà retournées et qu'une troisième est retournée
        if (isset($_SESSION['flipped_cards']) && count($_SESSION['flipped_cards']) >= 2) {
            foreach ($cards as $card) {
                if (in_array($card->getID(), $_SESSION['flipped_cards'])) {
                    $card->resetFlip();
                }
            }
            $_SESSION['flipped_cards'] = [];
        }

        foreach ($cards as $card) {
            $card->handleFlip($cardIdToFlip);
        }

        if (!isset($_SESSION['flipped_cards'])) {
            $_SESSION['flipped_cards'] = [];
        }

        if (!in_array($cardIdToFlip, $_SESSION['flipped_cards'])) {
            $_SESSION['flipped_cards'][] = $cardIdToFlip;
        }

        // Comparer les deux cartes si deux sont retournées
        if (count($_SESSION['flipped_cards']) == 2) {
            $firstCardId = $_SESSION['flipped_cards'][0];
            $secondCardId = $_SESSION['flipped_cards'][1];

            $firstCard = null;
            $secondCard = null;

            foreach ($cards as $card) {
                if ($card->getID() == $firstCardId) {
                    $firstCard = $card;
                } elseif ($card->getID() == $secondCardId) {
                    $secondCard = $card;
                }
            }

            if ($firstCard && $secondCard) {
                if ($firstCard->isMatched($secondCard)) {
                    $firstCard->match();
                    $secondCard->match();
                }
            }

            $_SESSION['turn_count'] = ($_SESSION['turn_count'] ?? 0) + 1;

            $_SESSION['shuffled_cards'] = $cards;
        } else {
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

// Calculer le score final
$totalMatched = 0;
foreach ($cards as $card) {
    if ($card->isAssorted()) {
        $totalMatched++;
    }
}

// Affichage du score final si toutes les paires sont trouvées
if ($totalMatched == count($cards)) {
    echo "<h1>Bravo ! Vous avez terminé le jeu en {$_SESSION['turn_count']} coups !</h1>";
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

    <div class="container-cards">
        <?php
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
