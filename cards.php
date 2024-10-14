<?php
class Cards {
    protected $id;
    protected $contenu;

    public function __construct($id, $contenu) {
        $this->id = $id;
        $this->contenu = $contenu;
    }

    public function getId() {
        return $this->id;
    }

    public function getContenu() {
        return $this->contenu;
}
}

// Définir la variable avant de l'utiliser
$image1 = "images/number_one.png";

// Utiliser correctement la variable dans l'echo
echo '<img src="' . $image1 . '" alt="Description de l\'image">';


$image1 = "images/number_one.png"; 
$image2 = "images/number_two.png"; 
$image3 = "images/number_three.png"; 
$image4 = "images/number_four.png"; 
$image5 = "images/number_five.png"; 
$image6 = "images/number_six.png"; 
$image7 = "images/number_seven.png"; 
$image8 = "images/number_eight.png"; 
$image9 = "images/number_nine.png"; 
$image10 = "images/number_ten.png"; 
$image11 = "images/number_eleven.png";
$image12 = "images/number_twelve.png"; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    
</body>
</html>