<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];

        function emailExists($email)
        {
            $db = getBD();

            $query = "SELECT COUNT(*) FROM Client WHERE mail = :email";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        }

        $exists = emailExists($email);



        if ($exists) {
            $code_verification = bin2hex(random_bytes(4));

            $queryInsertCode = "UPDATE Client SET code = :code_verification WHERE mail = :email";
            $stmtInsertCode = getBD()->prepare($queryInsertCode);
            $stmtInsertCode->bindParam(':code_verification', $code_verification, PDO::PARAM_STR);
            $stmtInsertCode->bindParam(':email', $email, PDO::PARAM_STR);
            $stmtInsertCode->execute();


            $sujet = "Récupération de mot de passe";
            $message = "Votre code de vérification est : $code_verification";
            $headers = "From: PerformanceFootd@amdin.com";

            mail($email, $sujet, $message, $headers);

            echo json_encode(["exists" => true, "message" => "Un e-mail de récupération a été envoyé. Veuillez vérifier votre boîte de réception."]);
        } else {
            echo json_encode(["exists" => false, "message" => "L'adresse e-mail spécifiée n'existe pas dans notre système. Veuillez vérifier et réessayer."]);
        }
    }
}

?>
