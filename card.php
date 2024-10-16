<?php
class Card {
    protected $value;
    protected $image;
    public $isRevealed;

    public function __construct($value, $image) {
        $this->value = $value;
        $this->image = $image;
        $this->isRevealed = false;
    }

    public function reveal() {
        $this->isRevealed = true;
    }

    public function hide() {
        $this->isRevealed = false;
    }

    public function getImage() { // Méthode pour accéder à l'image
        return $this->image;
    }
}

?>
