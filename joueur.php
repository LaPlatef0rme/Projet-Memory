<?php



class joueur {
    private $name;
    private $scores;

    public function __construct($pseudo) {
        $this->name = $pseudo;
        $this->scores = [];
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function addScore($score) {
        $this->scores[] = $score;
    }

    public function getScores() {
        return $this->scores;
    }

    public function getBestScore() {
        return !empty($this->scores) ? min($this->scores) : null;
    }
}
?>