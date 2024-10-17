<?php
// classes/Player.php
class Player {
    protected $id;
    protected $name;
    protected $score;
    protected $bestScore;

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
        $this->score = 0;
        $this->bestScore = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getScore() {
        return $this->score;
    }

    public function updateScore($points) {
        $this->score += $points;
        if ($this->score > $this->bestScore) {
            $this->bestScore = $this->score;
        }
    }

    public function getBestScore() {
        return $this->bestScore;
    }
}
?>
