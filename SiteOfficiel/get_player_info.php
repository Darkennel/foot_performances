<?php
function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

// Vérifier si l'ID du joueur est fourni dans la requête
if (isset($_GET["id"])) {
    $id_joueur = $_GET["id"];

    // Connexion à la base de données
    $conn = getBD();

    // Requête pour récupérer les informations du joueur spécifique en fonction de son ID
    $sql = "SELECT j.*, c.Club AS nom_club, l.Nom AS nom_ligue,
            stats.max_buts, stats.moyenne_buts, stats.moyenne_grosse_occasion,
            stats.moyenne_tirs_total, stats.moyenne_tirs_cadres, stats.moyenne_minutes_jouees
            FROM table_joueurv2 j
            INNER JOIN table_appartenirv2 a ON a.id_nom = j.id_nom
            INNER JOIN table_clubv2 c ON c.id_club = a.id_club
            INNER JOIN table_championnatv2 l ON c.id_championnat = l.id_championnat
            LEFT JOIN (
                SELECT 
                    id_nom,
                    MAX(Buts) AS max_buts,
                    AVG(Buts) AS moyenne_buts,
                    AVG(`Gross ocas`) AS moyenne_grosse_occasion,
                    AVG(`Tirs Total`) AS moyenne_tirs_total,
                    AVG(`tirs cadrées`) AS moyenne_tirs_cadres,
                    AVG(`minutes jouées`) AS moyenne_minutes_jouees
                FROM 
                    table_statsv2
                WHERE 
                    id_nom = :id_joueur
                GROUP BY 
                    id_nom
            ) AS stats ON j.id_nom = stats.id_nom
            WHERE j.id_nom = :id_joueur";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_joueur', $id_joueur);
    $stmt->execute();

    $player = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si des données ont été récupérées
    if ($player) {
        // Envoyer les informations du joueur au format JSON
        echo json_encode($player);
    } else {
        // Si aucun joueur n'est trouvé avec l'ID spécifié, renvoyer une réponse vide
        echo json_encode(null);
    }

    // Fermer la connexion à la base de données
    $conn = null;
} else {
    // Si aucun ID de joueur n'est fourni dans la requête, renvoyer une réponse vide
    echo json_encode(null);
}
?>
