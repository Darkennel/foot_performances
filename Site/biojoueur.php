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

        .player-info {
            width: 50%;
            margin-top: 8%;
            border-radius: 15px;
            /* Bordures arrondies */
            background-color: #fff;
            /* Fond blanc pour contraste */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px;
            margin-left: 25%;
            display: flex;
            /* Utilisation de flexbox pour organiser les éléments */
            align-items: center;
            /* Aligner les éléments sur la ligne verticalement */
        }

        .player-info img {
            margin-right: 10px;
            /* Marge à droite pour séparer l'image du texte */
        }

        .player-info a {
            margin-left: 4%;
        }

        .player-info h1 {
            flex-grow: 1;
            /* Permettre au nom/prénom de s'étendre pour occuper l'espace disponible */
            text-align: center;
        }

        .remove-button {
            margin-left: auto;
            /* Place le bouton à droite */
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            /* Marge en haut du tableau */
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;

            background-color: #fff;
            /* Fond blanc pour contraste */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th {
            background-color: #f2f2f2;
        }

        .row-divider {
            border-top: 1px solid #ccc;
        }

        /* Style pour le conteneur du tableau */
        .table-container {

            float: left;
            /* Aligner le conteneur à gauche */
            margin-top: 5%;
            margin-left: 2%;
            /* Ajouter une marge à gauche pour séparer du conteneur précédent */
            width: 30%;
            /* Largeur du conteneur */
        }

        .chart-container {
            margin-top: -35%;
            width: 58%;
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

        .alertco {
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
                <a href="Acceuil.php">
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
    $query = "SELECT j.*, GROUP_CONCAT(Distinct c.Club) AS clubs, GROUP_CONCAT(Distinct l.Nom) AS nom_ligue
    FROM table_joueurv2 j
    INNER JOIN table_appartenirv2 a ON a.id_nom = j.id_nom
    INNER JOIN table_clubv2 c ON c.id_club = a.id_club
    INNER JOIN table_championnatv2 l ON c.id_championnat = l.id_championnat
    WHERE j.id_nom = $id_jou";
    $rep = $bdd->query($query);




    // Vérification si des données ont été récupérées
    if ($rep && $joueur = $rep->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="player-info">';
        echo '<img src="' . $joueur['LienImage'] . '" alt="Photo" height="200">';
        echo "<h1>" . $joueur['Nom'] . "</h1>";

        // Récupérer l'ID du client de la session
        $id_client = isset($_SESSION['Clients']['id_client']) ? $_SESSION['Clients']['id_client'] : null;

        // Vérifier si l'ID du client de la session est défini
        if ($id_client) {
            // Récupérer l'ID du joueur de l'URL
            $id_joueur_url = $_GET["id"];

            // Préparer la requête SQL pour vérifier si le joueur est dans la liste des favoris du client actuel
            $query_favoris = "SELECT * FROM Favoris WHERE id_client = :id_client AND id_joueur = :id_joueur";
            $stmt = $bdd->prepare($query_favoris);
            $stmt->bindParam(':id_client', $id_client);
            $stmt->bindParam(':id_joueur', $id_joueur_url);
            $stmt->execute();
            $favoris = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si le joueur est dans la liste des favoris du client
            if ($favoris) {
                echo '<button class="remove-button" data-joueur-id="' . $joueur['id_nom'] . '">Retirer</button>';
            } else {
                echo '<button class="add-button" data-joueur-id="' . $joueur['id_nom'] . '">Ajouter</button>';
            }
        } else {
            // Si l'ID du client de la session n'est pas défini, affichez un message d'erreur ou redirigez l'utilisateur vers la page de connexion
            echo "";
        }
        if (isset($_SESSION['Clients']['id_client'])) {
            echo "<br>";
            echo "<a href='Prediction.php?id=" . $joueur['id_nom'] . "'>Prediction</a>";
        } else {
            echo "<br>";
            echo "<a href='nouveau.php'>Créer un compte</a>";
            echo "<a href='index.php'>Connectez-vous</a>";
        }
        echo '</div>';

        // Affichage du tableau avec les informations du joueur
        echo '<div class="table-container">';
        echo '<table>';
        echo '<tr>';
        echo '<th>Nationalité</th>';
        echo '<td>' . $joueur['nationalité'] . '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<th>Clubs</th>';
        echo '<td>' . $joueur['clubs'] . '</td>'; // Utilisation de la colonne clubs qui contient tous les clubs pour lesquels le joueur a joué
        echo '</tr>';
        echo '<tr>';
        echo '<th>Ligue</th>';
        echo '<td>' . $joueur['nom_ligue'] . '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p>Aucune donnée trouvée pour cet ID.</p>';
    }


    $but = "SELECT s.Annee, s.Buts 
    FROM table_statsv2 s
    INNER JOIN table_joueurv2 j ON s.id_nom = j.id_nom
    WHERE j.id_nom = $id_jou 
    ORDER BY s.Annee ASC";

    $liste = $bdd->query($but);

    // Tableau pour stocker les données de buts pour chaque saison
    $data = array();
    while ($row = $liste->fetch(PDO::FETCH_ASSOC)) {
        $data[$row['Annee']] = $row['Buts'];
        //var_dump($data);
    }
    ?>

    <div class="chart-container">
        <canvas id="barChart"></canvas>
    </div>


    <script>
        // Données des buts par saison
        var data = <?php echo json_encode(array_values($data)); ?>;
        var labels = <?php echo json_encode(array_keys($data)); ?>;

        // Configuration du graphique en radar
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar', // Utilisez le type de graphique 'bar' pour un diagramme en barres
            data: {
                labels: <?php echo json_encode(array_keys($data)); ?>, // Les années comme étiquettes sur l'axe des x
                datasets: [{
                    label: 'Nombre de buts',
                    data: <?php echo json_encode(array_values($data)); ?>, // Le nombre de buts sur l'axe des y
                    backgroundColor: 'rgba(54, 162, 235, 1)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    fill: true
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
        $(document).ready(function() {
            $('.remove-button').on('click', function() {
                var joueurID = $(this).data('joueur-id');

                if (confirm("Êtes-vous sûr de vouloir retirer ce joueur de vos favoris ?")) {
                    $.ajax({
                        url: 'supprimerfav.php',
                        method: 'POST',
                        data: {
                            ID: joueurID
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);

                            if (response.exists === true) {
                                location.reload();
                            } else {
                                alert('La suppression a échoué. Veuillez réessayer.');
                            }
                        },
                        error: function() {
                            console.log('Une erreur s\'est produite lors de la vérification de la suppression.');
                            alert('Une erreur s\'est produite lors de la suppression. Veuillez réessayer.');
                        }
                    });
                }
            });
            $('.add-button').on('click', function() {
                var joueurID = $(this).data('joueur-id');

                if (confirm("Êtes-vous sûr de vouloir ajouter ce joueur à vos favoris ?")) {
                    $.ajax({
                        url: 'ajouterfav.php',
                        method: 'POST',
                        data: {
                            ID: joueurID
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log('Response:', response);

                            if (response.exists === true) {
                                location.reload();
                            } else {
                                alert('La suppression a échoué. Veuillez réessayer.');
                            }
                        },
                        error: function() {
                            console.log('Une erreur s\'est produite lors de la vérification de la suppression.');
                            alert('Une erreur s\'est produite lors de la suppression. Veuillez réessayer.');
                        }
                    });
                }
            });
        });
    </script>



</body>

</html>