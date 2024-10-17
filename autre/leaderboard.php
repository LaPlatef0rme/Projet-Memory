<?php
// classes/Leaderboard.php
class Leaderboard {
    protected $players = [];

    public function __construct() {
        if (!isset($_SESSION['leaderboard'])) {
            $_SESSION['leaderboard'] = [];
        }
        $this->players = $_SESSION['leaderboard'];
    }

    public function addPlayer($player) {
        $this->players[] = [
            'name' => $player->getName(),
            'score' => $player->getScore(),
            'bestScore' => $player->getBestScore()
        ];
        
        // Trier les joueurs par score décroissant
        usort($this->players, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        // Garder seulement les 10 meilleurs
        $this->players = array_slice($this->players, 0, 10);
        $_SESSION['leaderboard'] = $this->players;
    }

    public function getTopPlayers() {
        return $this->players;
    }
}
?>
