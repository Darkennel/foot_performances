<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Championnat</title>
    <link rel="stylesheet" href="/Site/Sites.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
<header class="header-block">
    <div class="header-content">
        <div class="logo">
            <img src="/Site/Image/logo.png" alt="Logo du site" class="logo-left">
            <a href="#">
                <img src="/Site/Image/connexion.jpg" alt="Logo de connexion" class="logo-right">
            </a>
        </div>
        <div class="title-and-search">
            <h1>FOOTBALL STAT</h1>
            <form action="#" method="get" class="search-form" id="searchForm">
                <label for="searchInput" hidden>Recherche</label>
                <input type="text" name="search" id="searchInput" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form> 
        </div>
        <nav class="navi">
            <ul>
                <li><a href="/Site/Accueils.php">Home</a></li>
                <li><a href="/Site/Championnats/Championnat.php?ID_ligue=2">Bundesliga</a></li>
                <li><a href="/Site/Championnats/Championnat.php?ID_ligue=4">Liga</a></li>
                <li><a href="/Site/Championnats/Championnat.php?ID_ligue=5">Ligue 1</a></li>
                <li><a href="/Site/Championnats/Championnat.php?ID_ligue=1">Première ligue</a></li>
                <li><a href="/Site/Championnats/Championnat.php?ID_ligue=3">Série A</a></li>
            </ul>
        </nav>
    </div>
</header>

    <main>
        <div class="content">
            
            <div class="data-container">
                <?php
                // Récupération des détails du championnat
                if (isset($_GET['ID_ligue'])) {
                    $ID_ligue = $_GET['ID_ligue'];
                    try {
                        $bdd = new PDO('mysql:host=localhost;dbname=Performances_Football;charset=utf8', 'root', 'root');
                        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $query = $bdd->prepare("SELECT * FROM table_ligue WHERE ID_ligue = :ID_ligue");
                        $query->execute(array(':ID_ligue' => $ID_ligue));

                        $championnat = $query->fetch(PDO::FETCH_ASSOC);
                        

                        if ($championnat) {
                            echo "<table>";
                            echo "<th>Nom</th><th>Logo</th><th>Pays</th></tr>";
                            echo "<tr>";
                            echo "<td>" . $championnat['Ligue'] . "</td>";
                            echo '<td><img src="' . $championnat['url_Logo'] . '" alt="Logo" id="LogoCh"></td>';
                            echo '<td><img src="' . $championnat['url_Pays'] . '" alt="Image Pays", id="LogoCh"></td>';
                            echo "</tr>";
                            echo "</table>";
                        } else {
                            echo "Aucun championnat trouvé.";
                        }
                    } catch (PDOException $e) {
                        echo "Erreur de connexion à la base de données : " . $e->getMessage();
                    }
                } else {
                    echo "Paramètre ID_ligue non défini dans l'URL.";
                }
                ?>
            
            </div>

            <div class="container">
               <div class="data-container">
                  <div class="year-selector">
                     <label for="year">Année :</label>
                      <select id="year">
                          <option value="2020">2020</option>
                          <option value="2019">2019</option>
                          <option value="2018">2018</option>
                          <option value="2017">2017</option>
                          <option value="2016">2016</option>
                       </select>
                   </div>
                
                 <div class="table-container">
                      <h2>Tableau des buteurs</h2>
                      <table id="topScorersTable">
                          <thead>
                              <tr>
                                  <th>Numéro</th>
                                  <th>Nom</th>
                                  <th>Buts</th>
                              </tr>
                          </thead>
                          <tbody id="topScorersBody">
                            <!-- Les données seront ajoutées ici par JavaScript -->
                          </tbody>
                     </table>
                  </div>
             </div>    

                <div class="image-container" id="graphContainer">
                    <h2>Visualisation des buts</h2>
                    <canvas id="goalsChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <footer> 
    <div class="logo-footer">
            <img src="/Site/Image/logo.png" alt="Logo du footer" class="logo-footer-left">
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Attacher l'événement de changement de sélection à l'élément de sélection de l'année
        document.getElementById('year').addEventListener('change', getTopScorers);

        $(document).ready(function() {
            // Appeler getTopScorers au chargement de la page avec l'année 2020 par défaut
            getTopScorers(2020);
        });

        // Fonction pour obtenir les meilleurs buteurs en fonction de l'année sélectionnée
        function getTopScorers(selectedYear) {
            var selectedYear = $("#year").val(); // Utiliser jQuery pour récupérer la valeur de l'année

            if(selectedYear) {
                $.ajax({
                    url: "topButeurs.php",
                    type: "GET",
                    data: {
                        year: selectedYear,
                        ID_ligue: <?php echo isset($_GET['ID_ligue']) ? $_GET['ID_ligue'] : 'null'; ?>
                    },
                    dataType: "json",
                    success: function(data) {
                        displayTopScorers(data);
                        displayGoalsChart(data);
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                    }
                });
                console.log("Chargement des données...");
            }
        }

        // Fonction pour afficher les meilleurs buteurs dans le tableau
        function displayTopScorers(data) {
            var tableBody = $("#topScorersBody");
            tableBody.empty(); // Efface le contenu actuel du tableau

            if(data.length > 0) {
                $.each(data, function(index, player) {
                    var row = "<tr><td>" + (index + 1) + "</td><td>" + player.Nom + "</td><td>" + player.Buts + "</td></tr>";
                    tableBody.append(row);
                });
            } else {
                tableBody.html("<tr><td colspan='3'>Aucun buteur trouvé pour cette année et ce championnat.</td></tr>");
            }
        }

        // Fonction pour afficher le graphique des buts par année
        // Stocker une référence au graphique précédent
var myChart = null;

// Fonction pour afficher le graphique des buts par année
function displayGoalsChart(data) {
    var years = [];
    var goals = [];

    // Convertir les données des buteurs en un format compatible avec Chart.js
    $.each(data, function(index, item) {
        years.push(item.Numéro); // Ajouter le numéro au tableau des années
        goals.push(item.Buts); // Ajouter le nombre de buts au tableau des buts
    });

    // Récupérer le canvas existant ou créer un nouveau canvas s'il n'existe pas
    var ctx = document.getElementById('goalsChart').getContext('2d');

    // Détruire le graphique précédent s'il existe
    if (myChart) {
        myChart.destroy();
    }

 // Configuration du graphique des buts par année
myChart = new Chart(ctx, {
    type: 'scatter',
    data: {
        datasets: [{
            label: '',
            data: goals.map((value, index) => ({ x: years[index], y: value })), // Associer chaque but à son année correspondante
            backgroundColor: 'rgba(0, 0, 255, 0.2)', // Bleu pour le fond
            borderColor: 'rgba(0, 0, 255, 1)', // Bleu pour les bordures
            borderWidth: 2 // Épaisseur de la bordure
        }]
    },
    options: {
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 10
            }
        },
        maintainAspectRatio: true, // Désactiver le maintien du ratio d'aspect pour permettre le redimensionnement
        scales: {
            x: {
                type: 'category',
                position: 'bottom',
                title: {
                    display: true,
                    text: 'Numéro',
                    color: '#10110d',
                    font: {
                        weight: 'bold' // Mise en gras de la police du titre de l'axe x
                    }
                },
                ticks: {
                    color: '#10110d', // Couleur blanche pour les valeurs de l'axe x
                    font: {
                        weight: 'bold'  // Mise en gras de la police des valeurs de l'axe x
                    }
                },
                grid: {
                    color: 'rgba(16,17,13,255)', // Couleur blanche des lignes de la grille
                    borderWidth: 2 // Épaisseur des lignes de la grille
                }
            },
            y: {
                title: {
                    display: true,
                    text: 'Buts',
                    color: '#10110d',
                    font: {
                        weight: 'bold' // Mise en gras de la police du titre de l'axe y
                    }
                },
                ticks: {
                    color: '#10110d', // Couleur noire pour les valeurs de l'axe y
                    font: {
                        weight: 'bold' // Mise en gras de la police des valeurs de l'axe y
                    }
                },
                grid: {
                    color: 'rgba(16,17,13,255)', // Couleur blanche des lignes de la grille
                    borderWidth: 2 // Épaisseur des lignes de la grille
                }
            }
        },
        plugins: {
            legend: {
                display: false // Désactiver complètement l'affichage de la légende
            }
        }
    }
});
}

    </script>
</body>
</html>
