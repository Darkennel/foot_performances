<!DOCTYPE html>
<html class="acceuil">

<head>
    <link rel="stylesheet" href="Sites.css">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Performances football</title>
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
    </style>
</head>

<body style="text-align: center;">
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
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=2">Bundesliga</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=4">Liga</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=5">Ligue 1</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=1">Première ligue</a></li>
                    <li><a href="/Site/Championnats/Championnat.php?ID_ligue=3">Série A</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div id="myDropdown" class="dropdown-menu">
        <a href="Acceuil.php">Acceuil</a>
        <a href="Favoris.php">Favoris</a>
        <a href="Comparer.php">Comparer des joueurs</a>
        <a href="messagerie.php">Forum</a>
        <a href="Deconnexion.php">Déconnexion</a>
    </div>

    <main>

        <div class="data-container2">
            <h2>Vos informations</h2><br>
            <br>
            <?php
            session_start();

            $nom = $_SESSION['Clients']['nom'];
            $prenom = $_SESSION['Clients']['prenom'];
            $numero = $_SESSION['Clients']['numero'];
            $adresse = $_SESSION['Clients']['adresse'];
            $mail = $_SESSION['Clients']['mail'];

            echo '<p> Votre nom : - ' . $nom . '</p>';
            echo '<br>';
            echo '<p> Votre prénom : - ' . $prenom . '</p>';
            echo '<br>';
            echo '<p> Votre numéro : - ' . $numero . '</p>';
            echo '<br>';
            echo '<p> Votre adresse : - ' . $adresse . '</p>';
            echo '<br>';
            echo '<p> Votre mail : - ' . $mail . '</p><br>';

            ?>
        </div>

        <div class="data-container3">
            <p>Souhaitez-vous changer de mot de passe ?</p>
            <a href="mdpoublie.php">Changer de mot de passe ?</a>

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
    </script>


</body>

</html>