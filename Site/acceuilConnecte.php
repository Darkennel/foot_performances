<!DOCTYPE html>
<html class="acceuil">

<head>
    <link rel="stylesheet" href="Sites.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Performances football</title>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        .dropdown-menu {
            display: none;
            position: fixed;
            top: 50px;
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
    <header>
        <img src="logo.png" alt="Logo du site" class="logo-left">
        <a href="#" class="logo-link">
            <img src="connexion.jpg" alt="Logo de connexion" class="logo-right">
        </a>
        <div class="title-and-search">
            <h1>LES PERFORMANCES DES ATTAQUANTS DE FOOTBALL</h1>
            <form action="suggestions.php" method="get" class="formulaire" id="searchForm">
                <label for="searchInput" hidden>Recherche</label>
                <input type="text" name="search" id="searchInput" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <nav>
            <ul>
                <li><a href="Accueils.php">Home</a></li>
                <li><a href="Bundesliga.php">Bundesliga</a></li>
                <li><a href="Liga.php">Liga</a></li>
                <li><a href="Ligue1.php">Ligue 1</a></li>
                <li><a href="PremièreLique.php">Première ligue </a></li>
                <li><a href="SérieA.php">série A</a></li>
            </ul>
        </nav>
    </header>

    <div id="myDropdown" class="dropdown-menu">
        <a href="information.php">Profil</a>
        <a href="Prediction.php">Prediction</a>
        <a href="Favoris.php">Favoris</a>
        <a href="Comparer.php">Comparer des joueurs</a>
        <a href="messagerie.php">Forum</a>
        <a href="Deconnexion.php">Déconnexion</a>
    </div>

    <main>
    </main>

    <footer>
        <img src="logo.png" alt="Logo du footer" class="logo-footer-left">
        <div class="social-icons">
            <a href="#"><img src="facebook.png" alt="Facebook"></a>
            <a href="#"><img src="instagram.png" alt="Instagram"></a>
            <a href="#"><img src="twitter.png" alt="Twitter"></a>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var logoLink = document.querySelector(".logo-link");
            var dropdownMenu = document.getElementById("myDropdown");
            var searchInput = document.getElementById("searchInput");

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

            searchInput.addEventListener("input", function() {
                var searchTerm = searchInput.value;

                $.ajax({
                    url: 'suggestions.php',
                    method: 'GET',
                    data: {
                        search: searchTerm
                    },
                    dataType: 'json',
                    success: function(data) {
                        displaySuggestions(data);
                    },
                    error: function(error) {
                        console.error('Erreur AJAX:', error);
                    }
                });
            });

            function displaySuggestions(suggestions) {
                dropdownMenu.innerHTML = '';

                suggestions.forEach(function(suggestion) {
                    var listItem = document.createElement('li');
                    listItem.textContent = suggestion.nom + ' ' + suggestion.prenom;

                    listItem.addEventListener('click', function() {
                        console.log('Suggestion sélectionnée:', suggestion.nom, suggestion.prenom);

                        dropdownMenu.style.display = 'none';
                    });

                    dropdownMenu.appendChild(listItem);
                });

                dropdownMenu.style.display = 'block';
            }
        });
    </script>


</body>

</html>