<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Informations du Joueur</title>
    <style>
        .dropdown-menu {
            display: none;
            position: fixed;
            top: 130px;
            right: 0;
            z-index: 1;
            background-color: #f9f9f9;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu a {
            display: block;
            padding: 12px;
            text-decoration: none;
            color: black;
            text-align: center;
        }
        .chart-container {
    margin-top: -15%;
    width: 70%;
    height: 500px; /* Réglez la hauteur souhaitée en pixels */
    /* Réglez flex-grow, flex-shrink et flex-basis respectivement */
    border-radius: 15px;
    /* Bordures arrondies */
    background-color: #fff;
    /* Fond blanc pour contraste */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    /* Ombre pour donner un effet de profondeur /* Marge en bas pour séparer des autres sections */
    margin-left: auto;
    margin-right: 30px;
}


        .joupred{
            margin-left: 30px;
            width: 20%;
            margin-top: 10%;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/Site/Sites.css">
</head>

<body>
    <header class="header-block">
        <div class="header-content">
            <div class="logo">
                <a href="biojoueur.php?id=<?php echo $_GET["id"]; ?>">
                    <img src="/Site/Image/retour.jpg" alt="Logo de connexion" class="logo-retour">
                </a>
            </div>
            <div class="title-and-search">
                <h1>FOOTBALL STAT</h1>
            </div>

        </div>
    </header>


    <?php
    session_start(); // Démarrer la session si ce n'est pas déjà fait

    function getBD()
    {
        $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
        return $bdd;
    }

    $bdd = getBD();
    $id_jou = $_GET["id"];
    $query = "SELECT DISTINCT
    j.LienImage
FROM 
    table_joueurv2 j
WHERE
j.id_nom = $id_jou";
    $rep = $bdd->query($query);

    var_dump($rep);


    if ($rep && $joueur = $rep->fetch(PDO::FETCH_ASSOC)) {
        echo '<img class = "joupred" src="' . $joueur['LienImage'] . '" alt="Photo" height="200">';
    }
    $but_query = "SELECT 
        j.id_nom,
        j.nom AS Nom,
        s.Buts AS but,
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
    WHERE j.id_nom = $id_jou 
    ORDER BY s.Annee ASC";

    $liste = $bdd->query($but_query);

    // Tableau pour stocker les données de buts réels pour chaque saison
    $data = array();
    while ($row = $liste->fetch(PDO::FETCH_ASSOC)) {
        $data['real'][$row['Annee']] = $row['but'];
    }

    // Récupération des données de la requête pour les buts prédits
    $butpredi_query = "SELECT 
        j.id_nom,
        j.nom AS Nom,
        p.Annee AS Annee,
        p.Prediction AS butpredi
    FROM 
        prediction p
    JOIN 
        table_joueurv2 j ON p.id_nom = j.id_nom
    WHERE j.id_nom = $id_jou";

    $listepred = $bdd->query($butpredi_query);

    // Tableau pour stocker les données de buts prédits pour chaque saison
    while ($row = $listepred->fetch(PDO::FETCH_ASSOC)) {
        $data['predicted'][$row['Annee']] = $row['butpredi'];
    }

    // Combler les années manquantes entre les données réelles et prédites
    $start_year = min(array_keys($data['real']));
    $end_year = max(max(array_keys($data['real'])), max(array_keys($data['predicted'])));
    for ($year = $start_year; $year <= $end_year; $year++) {
        if (!isset($data['predicted'][$year])) {
            // Utiliser la valeur prédite précédente si l'année manque
            $data['predicted'][$year] = end($data['predicted']);
        }
    }
    ksort($data['predicted']);
?>

<div class="chart-container">
    <canvas id="lineChart"></canvas>
</div>

<footer>
        <div class="logo-footer">
            <img src="/Site/logo.png" alt="Logo du footer" class="logo-footer-left">
        </div>
        <div class="social-icons">

            <div class="menu-footer">
                <ul>
                    <li>
                        <a href="/Site/Legalnotices.php">Infos légales</a>
                    </li>
                    <li>
                        <a href="/Site/Contact.php">Contact</a>
                    </li>
                    <li>
                        <a href="/Site/Conditions.php">Conditions d'utilisation</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

<script>
// Données des buts par saison
var data = <?php echo json_encode($data); ?>;

// Récupérer les années réelles
var realLabels = Object.keys(data['real']);

// Récupérer les buts réels
var realData = Object.values(data['real']);

// Récupérer les années prédites à partir de la dernière année réelle + 1
var lastRealYear = Math.max(...realLabels);
var predictedLabels = Object.keys(data['predicted']).map(Number).filter(year => year > lastRealYear);

// Combler les années manquantes dans les données réelles pour correspondre aux données prédites
for (var year = lastRealYear + 1; year < predictedLabels[0]; year++) {
    realLabels.push(year);
    realData.push(null); // Utilisez null pour les années manquantes
}

// Récupérer les buts prédits
var predictedData = Object.values(data['predicted']);

// Ajouter les données prédites
var combinedLabels = realLabels.concat(predictedLabels);
var combinedData = realData.concat(predictedData);

// Configuration du graphique en courbe
var ctx = document.getElementById('lineChart').getContext('2d');
var lineChart = new Chart(ctx, {
    type: 'line', // Utilisez le type de graphique 'line' pour une courbe
    data: {
        labels: combinedLabels, // Les années comme étiquettes sur l'axe des x
        datasets: [{
            label: 'Prédiction',
            data: combinedData.map((value, index) => index >= realLabels.length - 1 ? value : null),
            backgroundColor: 'rgba(255, 99, 132, 1)', // Remplissage rouge pour les données prédites
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1,
            fill: true, // Remplir en dessous de la ligne
            spanGaps: true // Pour connecter les lignes entre les données manquantes
        },
        {
            label: 'Nombre de buts',
            data: combinedData, // Les données réelles et prédites sur l'axe des y
            backgroundColor: 'rgba(54, 162, 235, 1)', // Bleu pour les données réelles
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1,
            fill: true,
            spanGaps: true // Pour connecter les lignes entre les données manquantes
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
</script>








</body>

</html>






<!-- SELECT DISTINCT
    j.id_nom as id_nom,
    J.LienImage as LienImage
    j.nom AS Nom,
    s.Buts as but,
    s.Annee as Annee,
    p.Annee AS Anneepred,
    p.Prediction as butpredi
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
join
    prediction p on p.id_nom = a.id_nom
WHERE
    j.id_nom ='4'-->