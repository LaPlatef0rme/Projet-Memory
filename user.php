<?php
    class user{
        protected $id="";
        protected $login= "";
        protected $password="";
        protected $firstname= "";
        protected $lastname= "";





    public function create() {
        
    }
    }
    
    
    
    private $connexion;

    public function __construct($connexion) {
        $this->connexion = $connexion;
    }

    public function create() {
        $query = "INSERT INTO users (login, email, firstname, lastname) VALUES (:login, :email, :firstname, :lastname)";
        $register = $this->connexion->prepare($query);

        $register->bindParam(':login', $this->login);
        $register->bindParam(':email', $this->email);
        $register->bindParam(':firstname', $this->firstname);
        $register->bindParam(':lastname', $this->lastname);

        
        if($register->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // CRUD (Read)
    public function read($id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $register = $this->connexion->prepare($query);
        $register->bindParam(':id', $id);
        $register->execute();

        // Retourner les résultats sous forme de tableau associatif
        if ($register->rowCount() > 0) {
            return $register->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    // CRUD (Update)
    public function update($id) {
        $query = "UPDATE users SET login = :login, email = :email, firstname = :firstname, lastname = :lastname WHERE id = :id";
        $register = $this->connexion->prepare($query);

        // Liaison des paramètres
        $register->bindParam(':login', $this->login);
        $register->bindParam(':email', $this->email);
        $register->bindParam(':firstname', $this->firstname);
        $register->bindParam(':lastname', $this->lastname);
        $register->bindParam(':id', $id);

        // Exécution de la requête
        if($register->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // CRUD (Delete)
    public function delete($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $register = $this->$connextion->prepare($query);
        $register->bindParam(':id', $id);

    
        if($register->execute()) {
            return true;
        } else {
            return false;
        }
    
}







// $username = "root";
//     $password = "";
//     $database = new PDO("mysql:host=localhost;dbname=classes", $username, $password);

//     $query = $database->query("SELECT * FROM Utilisateurs");

//     while($line = $query->fetch()) {
//         echo "<br/> <br/> ";

//         echo "L'utilisateur n°" . $line["id"];
//         echo $line["name"];
//         echo " avec l'adresse email " . $line["email"];

    // }