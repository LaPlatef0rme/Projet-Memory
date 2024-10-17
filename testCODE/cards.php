<?php
session_start(); 

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
                    <img src='" . ($this->isFlipped ? $this->image : 'dos_image.jpg') . "' class='images' />
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