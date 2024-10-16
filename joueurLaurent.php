<?php
session_start();
require_once("cardss.php");
require_once("player.php");



class joueur {
    protected $pseudo;
    protected $meilleurScore;

    public function __construct($pseudo) {
        $this->pseudo = $pseudo;
        $this->meilleurScore = [];
    }

    public function getPseudo() {
        return $this->pseudo;
    }

    public function addMeilleurScore($score) {
        $this->meilleurScore[] = $score;
    }

    public function getMeilleurScores() {
        return $this->meilleurScore;
    }

    public function getBestMeilleurScore() {
        return !empty($this->scores) ? min($this->meilleurScore) : null;
    }
}
?>