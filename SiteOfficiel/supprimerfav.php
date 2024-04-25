<?php

session_start();

function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

function supprimerjoueur($id, $id_client) {
    $db = getBD();

    $query = "DELETE FROM Favoris WHERE id_joueur = :joueurID AND id_client = :clientID";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':joueurID', $id, PDO::PARAM_STR);
    $stmt->bindParam(':clientID', $id_client, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->rowCount() > 0; 
}

header('Content-Type: application/json');

if ($_POST['ID'] && isset($_SESSION['Clients']['id_client'])) {
    $id = $_POST['ID'];
    $id_client = $_SESSION['Clients']['id_client'];
    $supp = supprimerjoueur($id, $id_client);

    if ($supp) {
        echo json_encode(array('exists' => true));
    } else {
        echo json_encode(array('exists' => false));
    }
} else {
    echo json_encode(array('exists' => false, 'message' => 'ID du joueur ou ID du client manquant dans la demande.'));
}
?>
