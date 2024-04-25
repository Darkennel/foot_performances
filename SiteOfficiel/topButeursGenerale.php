<?php

if (isset($_GET['year'])) {
    $year = intval($_GET['year']);

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $initQuery = $bdd->prepare("SET @rank = 0");
        $initQuery->execute();

        $query = $bdd->prepare("
        SELECT DISTINCT
    j.id_nom as id_nom,
    j.nom AS Nom,
    s.Buts AS Buts,
    s.Annee AS Annee
    FROM 
    table_statsv2 s
JOIN 
    table_joueurv2 j ON s.id_nom = j.id_nom
JOIN 
    table_appartenirv2 a ON j.id_nom = a.id_nom AND a.Annee = s.Annee
JOIN 
    table_clubv2 c ON a.id_club = c.id_club
JOIN 
    table_championnatv2 l ON c.id_championnat = l.id_championnat
WHERE
    s.Annee = $year
ORDER BY
    s.Buts DESC
LIMIT 5;");

        $query->execute([$year]);

        $topScorers = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($topScorers);
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Erreur de base de données : ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('error' => 'Paramètres manquants.'));
}
