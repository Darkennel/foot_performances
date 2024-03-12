<!DOCTYPE html>
<html class="fondgen">

<head>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>perf_mbappe</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <body>
        <header>
            <h1>Ajouter des joueurs favoris</h1>
            <form action="" methode="post">
                <label for="joueur_id">Choisir un joeur :</label>
                <select name="joueur_id" id="joueur_id">
                    <?php foreach ($joueurs as $joueur) : ?>
                        <option value="<?php echo $joueur['id']; ?>"><?php echo $joueur['nom']; ?></option>
                    <?php endforeach; ?>
                    </select>
                    <button type="submit">Ajouter aux favoris</button>
            </form>
            <div class="logo">
                <img src="logo site.png" alt="Logo de votre site">
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </div>
    
        </header>
    </body>
</head>

<?php
$bdd = new PDO('mysql:host=localhost;dbname=performances;charset=utf8', 'root', 'root');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start(); // Démarrage de la session

    // Vérification si l'utilisateur est connecté
    if (isset($_SESSION['utilisateur_id'])) {
        // Vérification si le formulaire a été soumis correctement
        if (isset($_POST['joueur_id'])) {
            $joueur_id = $_POST['joueur_id'];
            $utilisateur_id = $_SESSION['utilisateur_id'];

            // Vérification si le joueur n'est pas déjà dans les favoris de l'utilisateur
            $query = $bdd->prepare("SELECT * FROM favoris WHERE utilisateur_id = :utilisateur_id AND joueur_id = :joueur_id");
            $query->bindParam(':utilisateur_id', $utilisateur_id);
            $query->bindParam(':joueur_id', $joueur_id);
            $query->execute();

            if ($query->rowCount() == 0) {
                // Ajout du joueur aux favoris de l'utilisateur
                $insert = $bdd->prepare("INSERT INTO favoris (utilisateur_id, joueur_id) VALUES (:utilisateur_id, :joueur_id)");
                $insert->bindParam(':utilisateur_id', $utilisateur_id);
                $insert->bindParam(':joueur_id', $joueur_id);
                $insert->execute();
                echo "Le joueur a été ajouté aux favoris avec succès.";
            } else {
                echo "Ce joueur est déjà dans vos favoris.";
            }
        }
    } else {
        echo "Veuillez vous connecter pour ajouter des joueurs favoris.";
    }
}



<body>
    <?php
    // Afficher la liste des joueurs disponibles
    $result = $bdd->query("SELECT * FROM joueurs");
    echo "<ul>";
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . $row['nom'] . " <a href='ajouter_favori.php?ajouter=1&joueur_id=" . $row['id'] . "'>Ajouter aux favoris</a></li>";
    }
    echo "</ul>";
    ?>
</body>


// Récupération de tous les joueurs depuis la base de données
$requete_joueurs = $bdd->query("SELECT * FROM joueurs");
$joueurs = $requete_joueurs->fetchAll(PDO::FETCH_ASSOC);
?>
</html>