<?php

function getBD(){
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}


function emailExists($email) {
    $db = getBD();

    $query = "SELECT COUNT(*) FROM Client WHERE mail = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->fetchColumn(); 

    return $count > 0; 
}

header('Content-Type: application/json');

if ($_POST['mail']) {
    $email = $_POST['mail'];
    $exists = emailExists($email);
    if ($exists) {
        echo json_encode(array('exists' => true));
    } else {
        echo json_encode(array('exists' => false));
    }
} else {
    echo json_encode(array('error' => 'Invalid input'));
}
?>
