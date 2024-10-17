<?php
session_start(); 
// require_once("joueurLaurent.php");

class Card {
    private $id; 
    private $isFlipped; 
    private $isMatched;        
    private $image;

    public function __construct($id, $image) {
        $this->id = $id;
        $this->image = $image;
        $this->isFlipped = false;
        $this->isMatched = false;
    }

    public function match() {
        $this->isMatched = true;
        $this->isFlipped = true;
    }

    public function flip() {
        $this->isFlipped = true;
    }

    public function isMatched(Card $otherCard) {
        return $this->image === $otherCard->getImage();
    }

    public function getID() {
        return $this->id;
    }

    public function getImage() {
        return $this->image;
    }

    public function isAssorted() {
        return $this->isMatched;
    }

    public function isFlipped() {
        return $this->isFlipped;
    }

    // Remettre la carte à l'état non retourné si elle n'est pas assortie
    public function resetFlip() {
        if (!$this->isMatched) {
            $this->isFlipped = false;
        }
    }

    // Méthode pour afficher l'image
    public function displayCard() {
        echo "<form method='POST' style='display: inline;'>
                <input type='hidden' name='flip' value='{$this->id}'>
                <button type='submit' style='background: none; border: none; padding: 0; cursor: pointer;'>
                    <img src='" . ($this->isFlipped ? $this->image : 'jojobeaugosse.jpg') . "' class='images' />
                </button>
              </form>";
    }

    // Méthode pour retourner la carte si l'ID correspond
    public function handleFlip($id) {
        if ($this->id == $id && !$this->isMatched) { // Empêcher de retourner une carte déjà assortie
            $this->flip();
        }
    }
}

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
        <p class="nbrtour"><?php echo $_SESSION['turn_count'] ??  0; ?></p>
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
