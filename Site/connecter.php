<?php
session_start();

function verifierUtilisateur($email, $motdepasse)
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');

    $query = "SELECT * FROM `Client` WHERE `mail`=:mail";
    $requete = $bdd->prepare($query);
    $requete->bindParam(':mail', $email, PDO::PARAM_STR);
    $requete->execute();

    $resultat = $requete->fetch(PDO::FETCH_ASSOC);

    if ($resultat && password_verify($motdepasse, $resultat['mdp'])) {
        $_SESSION['Clients'] = array(
            'id_client' => $resultat['id'],
            'nom' => $resultat['nom'],
            'prenom' => $resultat['prenom'],
            'adresse' => $resultat['adresse'],
            'numero' => $resultat['numero'],
            'mail' => $resultat['mail'],
        );
        return true;
    } else {
        return false;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['adrmail']) && isset($_POST['mdpcon'])) {
        $emailAVerifier = $_POST['adrmail'];
        $motdepasseAVerifier = $_POST['mdpcon'];

        $utilisateurValide = verifierUtilisateur($emailAVerifier, $motdepasseAVerifier);
        echo json_encode(array('valide' => $utilisateurValide));
        exit;
    }
}

echo json_encode(array('valide' => false));
?>