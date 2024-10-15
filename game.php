<?php
class Game {
    private $deck;
    private $pairsFound;

    public function __construct($numPairs){
        $this->deck = $this->generateDeck($numPairs);
        $this->pairsFound = 0;
    }

    private function generateDeck($numPairs){
        $values = range(1, $numPairs);
        $images = array_map(function($i){
            return "image_cart/card{$i}.webp";
        }, $values);

        $deck = [];

        foreach($values as $value){
            $randomImage = $images[array_rand($images)];
            $deck[] = new Card($value, $randomImage);  // Ajoute l'image à la carte
            $deck[] = new Card($value, $randomImage);
        }

        shuffle($deck);
        return $deck;
    }

    public function revealCard($index){
        if (!$this->deck[$index]->revealed){
            $this->deck[$index]->reveal();
            return $this->deck[$index];
        }
        return null;
    }

    public function hideCards($card1, $card2){
        if ($card1->value !== $card2->value){
            $card1->hide();
            $card2->hide();
        } else {
            $this->pairsFound++;  // Corrige l'incrémentation de pairsFound
        }
    }
}
?>
