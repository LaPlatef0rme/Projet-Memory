<?php
$host = "localhost";
$dbname = "scores";
$username = "root";
$password="";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);

    // Configuration des options PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    echo "Bv la connexion t'es trop forte <br>";

} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

?>