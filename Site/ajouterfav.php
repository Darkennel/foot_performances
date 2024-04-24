<?php

session_start();

function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

function ajouterJoueurFavoris($id_joueur, $id_client) {
    $db = getBD();

    // Vérifier d'abord si le joueur n'existe pas déjà dans les favoris du client
    $query_check = "SELECT COUNT(*) as count FROM Favoris WHERE id_joueur = :joueurID AND id_client = :clientID";
    $stmt_check = $db->prepare($query_check);
    $stmt_check->bindParam(':joueurID', $id_joueur, PDO::PARAM_STR);
    $stmt_check->bindParam(':clientID', $id_client, PDO::PARAM_STR);
    $stmt_check->execute();
    $result_check = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result_check['count'] > 0) {
        // Le joueur existe déjà dans les favoris du client
        return false;
    }

    // Si le joueur n'existe pas déjà dans les favoris, l'ajouter
    $query = "INSERT INTO Favoris (id_joueur, id_client) VALUES (:joueurID, :clientID)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':joueurID', $id_joueur, PDO::PARAM_STR);
    $stmt->bindParam(':clientID', $id_client, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0; 
}

header('Content-Type: application/json');

if ($_POST['ID'] && isset($_SESSION['Clients']['id_client'])) {
    $id_joueur = $_POST['ID'];
    $id_client = $_SESSION['Clients']['id_client'];
    $ajout = ajouterJoueurFavoris($id_joueur, $id_client);

    if ($ajout) {
        echo json_encode(array('exists' => true));
    } else {
        echo json_encode(array('exists' => false, 'message' => 'Le joueur est déjà dans vos favoris.'));
    }
} else {
    echo json_encode(array('exists' => false, 'message' => 'ID du joueur ou ID du client manquant dans la demande.'));
}
?>
