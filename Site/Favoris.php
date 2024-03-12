<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Profil</title>
</head>

<body>
    <h1>Favoris</h1>
    <?php

    function getBD()
    {
        $bdd = new PDO('mysql:host=localhost;dbname=FootProjet;charset=utf8', 'root', 'root');
        return $bdd;
    }



    function getFavoris($id_utilisateur)
    {
        $bdd = getBD();

        $query = $bdd->prepare('SELECT image, nom, prenom FROM Foot WHERE id_utilisateur = :id_utilisateur');
        $query->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
        $query->execute();

        $favoris = $query->fetchAll(PDO::FETCH_ASSOC);

        return $favoris;
    }

    $id_utilisateur = $_SESSION['Clients']['id_client'];
    $favoris = getFavoris($id_utilisateur);
    ?>

    <h2>Vos joueurs favoris</h2>
    <ul>
        <?php foreach ($favoris as $favori) : ?>
            <li>
                <img src="<?php echo $favori['image']; ?>" alt="Image du joueur">
                <span>Nom : <?php echo $favori['nom']; ?></span>
                <span>Prénom : <?php echo $favori['prenom']; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>

</body>

</html>