<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Favoris</title>
    <link rel="stylesheet" href="/Site/Sites.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        * {
            text-align: center;
        }

        table {
            border-collapse: collapse;
            width: 70%;
            margin-left: 13%;
            border-radius: 15px;
            margin-top: 12%;
            /* Bordures arrondies */
            background-color: #fff;
            /* Fond blanc pour contraste */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 2px solid #ddd;
            padding: 8px;
            text-align: left;
            text-align: center;
        }

        img {
            display: block;
            margin: 0 auto;
        }

        .button-cell {
            text-align: center;
        }

        .bio-link {
            text-decoration: none;
            color: #007bff;
        }

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
    </style>
</head>

<body>
    <header class="header-block">
        <div class="header-content">
            <div class="logo">
                <img src="/Site/logo.png" alt="Logo du site" class="logo-left">
            </div>
            <a href="#" class="logo-link">
                <img src="/Site/connexion.jpg" alt="Logo de connexion" class="logo-right">
            </a>
            <div class="title-and-search">
                <h1>FOOTBALL STAT</h1>
                <input type="text" id="search" list="players" placeholder="Rechercher un joueur...">
                <button id="searchButton">Rechercher</button>

                <datalist id="players">
                </datalist>
            </div>
            <nav class="navi">
            <ul>
                    <li><a href="/Site/Acceuil.php">Home</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=3">Bundesliga</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=2">Liga</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=5">Ligue 1</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=1">Première ligue</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=4">Série A</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div id="myDropdown" class="dropdown-menu">
        <a href="Acceuil.php">Acceuil</a>
        <a href="information.php">Profil</a>
        <a href="Comparer.php">Comparer des joueurs</a>
        <a href="messagerie.php">Forum</a>
        <a href="Deconnexion.php">Déconnexion</a>
    </div>

    <?php

    session_start();

    if (isset($_SESSION['Clients']['id_client'])) {
        $id_utilisateur = $_SESSION['Clients']['id_client'];

        function getBD()
        {
            $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
            return $bdd;
        }

        function getFavoris($id_utilisateur)
        {
            $bdd = getBD();
            $query = $bdd->prepare('
                SELECT tju.LienImage, tju.Nom, tju.nationalité, tju.id_nom
                FROM Favoris fav
                JOIN Client c ON fav.id_client = c.id
                JOIN table_joueurv2 tju ON fav.id_joueur = tju.id_nom
                WHERE c.id = :id_utilisateur
            ');

            $query->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $query->execute();

            $favoris = $query->fetchAll(PDO::FETCH_ASSOC);
            return $favoris;
        }

        $favoris = getFavoris($id_utilisateur);
        //var_dump($favoris);
        //var_dump($id_utilisateur);
    } else {
        header('Location: index.php');
        exit();
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>Joueur</th>
                <th>Nom, Prénom</th>
                <th>Nationalité</th>
                <th>Action</th>
                <th>Plus d'informations</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($favoris as $favori) : ?>
                <tr>
                    <td><img src="<?php echo $favori['LienImage']; ?>" alt="Image du joueur"></td>
                    <td><?php echo $favori['Nom']; ?></td>
                    <td><?php echo $favori['nationalité']; ?></td>
                    <td class="button-cell"><button class="remove-button" data-joueur-id="<?php echo $favori['id_nom']; ?>">Retirer</button></td>
                    <td class="button-cell"><a href="biojoueur.php?id=<?php echo $favori['id_nom']; ?>" class="bio-link" target="_blank">En savoir plus</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var logoLink = document.querySelector(".logo-link");
            var dropdownMenu = document.getElementById("myDropdown");

            logoLink.addEventListener("click", function(event) {
                event.stopPropagation();
                dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
            });

            document.addEventListener("click", function() {
                dropdownMenu.style.display = "none";
            });

            dropdownMenu.addEventListener("click", function(event) {
                event.stopPropagation();
            });
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
        });

        function updatePlayersList(players) {
            const datalist = document.getElementById("players");
            datalist.innerHTML = ""; // Effacer les options existantes

            players.forEach(player => {
                const option = document.createElement("option");
                option.value = player.nom;
                option.setAttribute("data-id", player.id); // Ajouter l'ID du joueur comme attribut data
                datalist.appendChild(option);
            });
        }

        function fetchPlayers() {
            fetch('get_players.php')
                .then(response => response.json())
                .then(data => {
                    updatePlayersList(data);
                })
                .catch(error => console.error('Erreur lors de la récupération des joueurs:', error));
        }

        fetchPlayers();

        document.getElementById("searchButton").addEventListener("click", function() {
            let searchText = document.getElementById("search").value.trim();

            if (searchText.includes(" ")) {
                searchText = searchText.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            } else {
                searchText = searchText.charAt(0).toUpperCase() + searchText.slice(1).toLowerCase();
            }

            const selectedOption = document.querySelector("option[value='" + searchText + "']");

            if (selectedOption) {
                const playerId = selectedOption.getAttribute("data-id");
                window.location.href = "biojoueur.php?id=" + playerId;
            } else {
                alert("Joueur non trouvé.");
            }
        });
    </script>


</body>

</html>