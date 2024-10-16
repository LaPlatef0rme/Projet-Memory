<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Inclure les classes
require_once ("database.php");
require "card.php";
require "game.php";
require "player.php";

// Initialisation des sessions
if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [];
}

if (!isset($_SESSION['player'])) {
    $_SESSION['player'] = new Player("Joueur 1");
}

if (!isset($_SESSION['game']) || !$_SESSION['game'] instanceof Game) {
    $_SESSION['game'] = new Game(6); // Nombre de paires par défaut
}

// Traitement des requêtes POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['card_index'])) {
        $index = intval($_POST['card_index']);
        $card = $_SESSION['game']->revealCard($index); // Révèle la carte

        // Vérifie si deux cartes sont révélées
        if (isset($_SESSION['game']->firstCardIndex)) {
            $firstCardIndex = $_SESSION['game']->firstCardIndex;
            $firstCard = $_SESSION['game']->deck[$firstCardIndex];

            // Compare les deux cartes
            if ($firstCard->value === $card->value) {
                // Paires trouvées
                unset($_SESSION['game']->deck[$firstCardIndex]);
                unset($_SESSION['game']->deck[$index]);
                $_SESSION['game']->pairsFound++;
            } else {
                // Cache les cartes après un court délai (il faudra un moyen pour le faire, par exemple avec JavaScript)
                $_SESSION['game']->hideCards($firstCard, $card);
            }
            unset($_SESSION['game']->firstCardIndex); // Réinitialise
        } else {
            // Première carte révélée
            $_SESSION['game']->firstCardIndex = $index;
        }
    }

    if (isset($_POST['num_pairs'])) {
        $num_pairs = intval($_POST['num_pairs']);
        $_SESSION['game'] = new Game($num_pairs); // Recréer le jeu avec le nouveau nombre de paires
        $_SESSION['player_name'] = $_POST['player_name'];
        $_SESSION['score'] = 0; // Initialiser le score
    }

    if (isset($_POST['score'])) {
        $score = intval($_POST['score']);
        updateScores($_SESSION['player_name'], $score);
    }
}

// Fonction pour mettre à jour les scores
function updateScores($player_name, $score) {
    $_SESSION['scores'][$player_name] = $score;
    arsort($_SESSION['scores']); // Trier les scores
}

// Fonction pour obtenir les meilleurs joueurs
function getTopPlayers() {
    return array_slice($_SESSION['scores'], 0, 10, true);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memory Game</title>
    <link rel="stylesheet" href="memory.css">
</head>
<body>
    <h1>Memory Game</h1>
    <div class="board">
        <form method="POST">
            <?php // Affichage des cartes
            if (isset($_SESSION['game']->deck)) {
                foreach ($_SESSION['game']->deck as $index => $card) {
                    echo '<div class="card">';
                    if ($card->isRevealed) {
                        echo '<img src="' . $card->getImage() . '" alt="Card" />'; // Modifiez ici
                    } else {
                        echo '<button type="submit" name="card_index" value="' . $index . '" class="hidden-card">?</button>';
                    }
                    echo '</div>';
                }
            }
            
            ?>
        </form>
    </div>

    <h2>Score: <?php echo $_SESSION['score']; ?></h2>
    <h2>Classement des meilleurs joueurs</h2>
    <ul>
        <?php foreach (getTopPlayers() as $name => $score): ?>
            <li><?php echo htmlspecialchars($name) . ": " . $score; ?></li>
        <?php endforeach; ?>
    </ul>

    <button onclick="location.href='leaderboard.php'">Voir le tableau des scores</button>
    <button onclick="location.href='player.php'">Rejouer</button>
</body>
</html>
