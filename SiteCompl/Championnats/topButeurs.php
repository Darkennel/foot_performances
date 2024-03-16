<?php

if(isset($_GET['year']) && isset($_GET['ID_ligue'])) {
    $year = intval($_GET['year']); 
    $ID_ligue = intval($_GET['ID_ligue']); 
    error_log("Year: " . $year . " ID_ligue: " . $ID_ligue);
    
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=Performances_Football;charset=utf8', 'root', 'root');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $initQuery = $bdd->prepare("SET @rank = 0");
        $initQuery->execute();

        $query = $bdd->prepare("
        SELECT
        (@rank := @rank + 1) AS Numéro,
        Nom,
        Buts
    FROM (
        SELECT
            j.NomPrenom AS Nom,
            s.GOALS AS Buts
        FROM
            table_stats s
        JOIN
            table_joueur j ON s.id_stats_joueurs = j.ID
        JOIN
            table_club c ON j.id_joueur_club = c.id_club
        JOIN
            table_ligue l ON c.id_ligue = l.ID_ligue
        WHERE
            s.year = ? AND l.ID_ligue = ?
        ORDER BY
            s.GOALS DESC
    ) AS ranked_stats,
        (SELECT @rank := 0) r;
        ");

        $query->execute([$year, $ID_ligue]);

        $topScorers = $query->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($topScorers);
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Erreur de base de données : ' . $e->getMessage()));
    }
} else {
    echo json_encode(array('error' => 'Paramètres manquants.'));
}
?>
