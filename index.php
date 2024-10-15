<?php
session_start();
require "card.php";
require "game.php";
require "player.php";

if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [];
}

if (!isset($_SESSION['player'])){//Remplacer par un formulaire pour le nom
    $_SESSION['player'] = new Player("Joueur 1");
}
if (!isset($_SESSION['Game'])){
    $_SESSION['game'] = new Game(6);//L'utilisateur peux le modifier
}



function generateCards($num_pairs) {
    $cards = range(1, $num_pairs);
    $cards = array_merge($cards, $cards); // Doubler les cartes pour les paires
    shuffle($cards); // Mélanger les cartes
    return $cards;
}

function updateScores($player_name, $score) {
    $_SESSION['scores'][$player_name] = $score;
    arsort($_SESSION['scores']); // Trier les scores par ordre décroissant
}

function getTopPlayers() {
    return array_slice($_SESSION['scores'], 0, 10, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['num_pairs'])) {
        $num_pairs = intval($_POST['num_pairs']);
        $cards = generateCards($num_pairs);
        $_SESSION['cards'] = $cards;
        $_SESSION['player_name'] = $_POST['player_name'];
        $_SESSION['score'] = 0; // Initialiser le score
    }

    if (isset($_POST['score'])) {
        $score = intval($_POST['score']);
        updateScores($_SESSION['player_name'], $score);
    }
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
    <h1> Memory Game </h1>
    <form method="POST">
            <label for="num_pairs">Choisissez le nombre de paires (3-12) :</label>
            <input type="number" id="num_pairs" name="num_pairs" min="3" max="12" required>
            <input type="text" name="player_name" placeholder="Votre nom" required>
            <button type="submit">Démarrer le jeu</button>
        </form>
    <div class="board">
        <?php//Affiche les cartes
        foreach ($_SESSION['game']->deck as $index => $card){
            echo '<div class="card" data-index="' . $index . '">'; 

            if ($card->isRevealed){
            echo ' <img src="' .$card->image . '"alt="Card" />';
               }else{
            echo '<div class="hidden-card">?</div>';
               }

               echo '</div>';
    ?>
    </div>
    <?php if (!isset($_SESSION['cards'])): ?>
        
        <?php else: ?>
            <h2>Cartes tirées</h2>
            <div class="cards">
                <?php foreach ($_SESSION['cards'] as $card): ?>
                    <div class="card"><?php echo $card; ?></div>
                <?php endforeach; ?>
            </div>
               <?php endif; ?>

    <h2>Classement des meilleurs joueurs</h2>
    <ul>
        <?php foreach (getTopPlayers() as $name => $score): ?>
            <li><?php echo htmlspecialchars($name) . ": " . $score; ?></li>
        <?php endforeach; ?>
    </ul>
    <button onclick="location.href='leaderboard.php'">Voir le tableau des scores</button>
    <button onclick="location.href='player.php'"> Rejouer </button>

</body>
</html>



