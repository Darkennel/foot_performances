<?php
function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

// Connexion à la base de données
$conn = getBD();

// Requête pour récupérer tous les joueurs avec leur nom et leur ID
$sql = "SELECT * FROM table_joueurv2";
$result = $conn->query($sql);

$players = array();

if ($result !== false && $result->rowCount() > 0) {
    // Stocker les joueurs dans un tableau
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $players[] = array(
            'id' => $row['id_nom'],
            'nom' => $row['Nom']
        );
    }
}

// Envoyer les joueurs au format JSON
echo json_encode($players);

// Fermer la connexion à la base de données
$conn = null;
?>

