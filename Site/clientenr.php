<?php
session_start();

function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

function enregistrer($nom, $prenom, $adresse, $telephone, $email, $motDePasse)
{
    $bdd = getBD();
    $hashedPassword = password_hash($motDePasse, PASSWORD_BCRYPT);

    $sql = "INSERT INTO Client (nom, prenom, adresse, numero, mail, mdp) 
            VALUES (:nom, :prenom, :adresse, :telephone, :email, :mdp)";

    $requete = $bdd->prepare($sql);
    $requete->bindParam(':nom', $nom, PDO::PARAM_STR);
    $requete->bindParam(':prenom', $prenom, PDO::PARAM_STR);
    $requete->bindParam(':adresse', $adresse, PDO::PARAM_STR);
    $requete->bindParam(':telephone', $telephone, PDO::PARAM_STR);
    $requete->bindParam(':email', $email, PDO::PARAM_STR);
    $requete->bindParam(':mdp', $hashedPassword);

    return $requete->execute();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = $_POST['n'];
    $prenom = $_POST['p'];
    $adresse = $_POST['adr'];
    $telephone = $_POST['num'];
    $email = $_POST['mail'];
    $motDePasse = $_POST['mdp1'];

    $enregistrementReussi = enregistrer($nom, $prenom, $adresse, $telephone, $email, $motDePasse);

    header('Content-Type: application/json');
    if ($enregistrementReussi) {
        echo json_encode(array('enregistrementReussi' => true));
    } else {
        echo json_encode(array('enregistrementReussi' => false));
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Echec'));
}
