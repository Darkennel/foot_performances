<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Comparer</title>
    <link rel="stylesheet" href="Sites.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <style>
        .player-info img {
            margin-top: 16px;
            margin-left: -70px;
        }

        #secondPlayerInfo table {
            width: 60%;
            margin-left: -80px;
        }

        #firstPlayerInfo table {
            width: 90%;
            margin-left: -70px;
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

        .grid-container {
            display: grid;
            grid-template-columns: 45% auto 45%;
            /* Divise la page en trois colonnes, laissant 10% d'espace de chaque côté */
            column-gap: 10%;
            /* Espace entre les colonnes */
            margin: 70px;
            /* Marge pour espacer du bord de la page */
            margin-top: 12%;
        }

        /* Style pour le div de gauche */
        .jougauche {
            grid-column: 1 / 2;
            margin-left: 31%;
            /* Place le div à la première colonne (à gauche) */
        }

        /* Style pour le div de droite */
        .joudroite {
            grid-column: 3 / 4;
            margin-left: -8%;
            /* Place le div à la troisième colonne (à droite) */
        }

        /* Style pour l'image "vs.png" */
        .vs-image {
    display: block;
    /* L'image est affichée comme un bloc */
    width: 150px;
    /* Largeur fixe de l'image */
    height: auto;
    /* Hauteur automatique pour conserver les proportions */
    margin-left: -170px; /* Retirer la marge à gauche pour empêcher le centrage */
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
        <a href="Prediction.php">Prediction</a>
        <a href="Favoris.php">Favoris</a>
        <a href="messagerie.php">Forum</a>
        <a href="Deconnexion.php">Déconnexion</a>
    </div>

    <main class="grid-container">
        <div class="jougauche">
            <!-- Zone pour le premier joueur sélectionné -->
            <input type="text" id="search1" list="players" placeholder="Rechercher un joueur...">
            <button id="searchButton1">Comparer</button>
            <datalist id="players">
                <!-- Liste des joueurs pour le premier div -->
            </datalist>
            <div id="firstPlayerInfo" class="player-info"></div> <!-- Ajout d'un conteneur pour afficher les informations du premier joueur -->
        </div>

        <!-- Image "vs.png" -->
        <img src="/Site/Image/vs.jpg" class="vs-image" alt="Versus">

        <div class="joudroite">
            <!-- Zone pour le deuxième joueur sélectionné -->
            <input type="text" id="search2" list="players" placeholder="Rechercher un joueur...">
            <button id="searchButton2">Comparer</button>
            <datalist id="players">
                <!-- Liste des joueurs pour le deuxième div -->
            </datalist>
            <div id="secondPlayerInfo" class="player-info"></div> <!-- Ajout d'un conteneur pour afficher les informations du deuxième joueur -->
        </div>
    </main>

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



        document.getElementById("searchButton1").addEventListener("click", function() {
            let searchText = document.getElementById("search1").value.trim();

            if (searchText.includes(" ")) {
                searchText = searchText.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            } else {
                searchText = searchText.charAt(0).toUpperCase() + searchText.slice(1).toLowerCase();
            }

            const selectedOption = document.querySelector("#players option[value='" + searchText + "']");

            if (selectedOption) {
                const playerId = selectedOption.getAttribute("data-id");
                // Faire une requête AJAX pour obtenir les informations du joueur à afficher dans le div secondPlayerInfo
                fetch('get_player_info.php?id=' + playerId)
                    .then(response => response.json()) // Utilisez response.json() pour convertir la réponse en objet JSON
                    .then(data => {
                        // Créez des éléments HTML pour afficher le nom et l'image du joueur
                        const playerInfoContainer = document.getElementById("firstPlayerInfo");
                        playerInfoContainer.innerHTML = ""; // Effacez tout contenu précédent

                        // Créer une image pour afficher la photo du joueur
                        const playerImage = document.createElement("img");
                        playerImage.src = data.LienImage;
                        playerImage.alt = data.Nom + " - Photo";
                        playerImage.height = 200;
                        playerInfoContainer.appendChild(playerImage);

                        // Créer un tableau pour afficher les statistiques du joueur
                        // Créer un tableau pour afficher les statistiques du joueur
                        const statsTable = document.createElement("table");
                        const statsTableBody = statsTable.createTBody();

                        // Ajouter une ligne pour chaque statistique avec une cellule pour le nom de la statistique et une cellule pour sa valeur
                        Object.keys(data).forEach(key => {
                            // Exclure les statistiques spécifiques de la boucle
                            if (key !== "id_nom" && key !== "nationalité" && key !== "nom_club" && key !== "nom_ligue" && key !== "LienImage" && key !== "Nom") {
                                const row = statsTableBody.insertRow();
                                const cell1 = row.insertCell(0);
                                const cell2 = row.insertCell(1);
                                cell1.textContent = key.replace(/_/g, ' '); // Remplacer les underscores par des espaces dans le nom de la statistique
                                cell2.textContent = data[key];
                            }
                        });


                        playerInfoContainer.appendChild(statsTable);
                    })
                    .catch(error => console.error('Erreur lors de la récupération des informations du joueur:', error));
            } else {
                alert("Joueur non trouvé.");
            }
        });


        document.getElementById("searchButton2").addEventListener("click", function() {
            let searchText = document.getElementById("search2").value.trim();

            if (searchText.includes(" ")) {
                searchText = searchText.toLowerCase().split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
            } else {
                searchText = searchText.charAt(0).toUpperCase() + searchText.slice(1).toLowerCase();
            }

            const selectedOption = document.querySelector("#players option[value='" + searchText + "']");

            if (selectedOption) {
                const playerId = selectedOption.getAttribute("data-id");
                // Faire une requête AJAX pour obtenir les informations du joueur à afficher dans le div secondPlayerInfo
                fetch('get_player_info.php?id=' + playerId)
                    .then(response => response.json()) // Utilisez response.json() pour convertir la réponse en objet JSON
                    .then(data => {
                        // Créez des éléments HTML pour afficher le nom et l'image du joueur
                        const playerInfoContainer = document.getElementById("secondPlayerInfo");
                        playerInfoContainer.innerHTML = ""; // Effacez tout contenu précédent

                        // Créer une image pour afficher la photo du joueur
                        const playerImage = document.createElement("img");
                        playerImage.src = data.LienImage;
                        playerImage.alt = data.Nom + " - Photo";
                        playerImage.height = 200;
                        playerInfoContainer.appendChild(playerImage);

                        // Créer un tableau pour afficher les statistiques du joueur
                        const statsTable = document.createElement("table");
                        const statsTableBody = statsTable.createTBody();


                        // Ajouter une ligne pour chaque statistique avec une cellule pour le nom de la statistique et une cellule pour sa valeur
                        Object.keys(data).forEach(key => {
                            // Exclure les statistiques spécifiques de la boucle
                            if (key !== "id_nom" && key !== "nationalité" && key !== "nom_club" && key !== "nom_ligue" && key !== "LienImage" && key !== "Nom") {
                                const row = statsTableBody.insertRow();
                                const cell1 = row.insertCell(0);
                                const cell2 = row.insertCell(1);
                                cell1.textContent = key.replace(/_/g, ' '); // Remplacer les underscores par des espaces dans le nom de la statistique
                                cell2.textContent = data[key];
                            }
                        });

                        playerInfoContainer.appendChild(statsTable);
                    })
                    .catch(error => console.error('Erreur lors de la récupération des informations du joueur:', error));
            } else {
                alert("Joueur non trouvé.");
            }
        });


    </script>


</body>

</html>