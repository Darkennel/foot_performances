<?php

function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=FootProjet;charset=utf8', 'root', 'root');
    return $bdd;
}

if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];

    $bdd = getBD();
    
    $query = $bdd->prepare('SELECT nom, prenom FROM foot WHERE nom LIKE :searchTerm LIMIT 5');
    $query->bindParam(':searchTerm', $searchTerm . '%', PDO::PARAM_STR);
    $query->execute();
    
    $results = $query->fetchAll(PDO::FETCH_ASSOC);

    $suggestions = [];

    foreach ($results as $result) {
        $suggestions[] = [
            'nom' => $result['nom'],
            'prenom' => $result['prenom'],
        ];
    }

    echo json_encode($suggestions);
}
?>
