<?php
include_once 'database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $pseudo = $_POST['pseudo'] ?? '';
    $score = $_POST['score'] ?? '';

    try {
        $requete = $pdo->prepare("INSERT INTO partie (pseudo, score) 
                                  VALUES (:pseudo, :score)");
                                  
        $requete->execute([
            ':pseudo' => $pseudo,
            ':score' => $score,
        ]);

        echo "Inscription réussie !";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire</title>
</head>
<body>
    <form action="form.php" method="POST">
        <label for="pseudo">Pseudo :</label><br>
        <input type="text" name="pseudo" placeholder="Choisissez votre pseudo" required><br> 

        <label for="score">Score :</label><br>
        <input type="text" name="score" placeholder="Entrez votre score" required><br>

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>
