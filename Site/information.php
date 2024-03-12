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

<body style="text-align: center;">
    <header>
        <img src="logo.png" alt="Logo du site" class="logo-left">
        <a href="#" class="logo-link">
            <img src="connexion.jpg" alt="Logo de connexion" class="logo-right">
        </a>
        <div class="title-and-search">
            <h1>LES PERFORMANCES DES ATTAQUANTS DE FOOTBALL</h1>
            <form action="#" method="get" class="formulaire" id="searchForm">
                <label for="searchInput" hidden>Recherche</label>
                <input type="text" name="search" id="searchInput" placeholder="Rechercher...">
                <button type="submit">Rechercher</button>
            </form>
        </div>

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
    <div class="rendrelisible">
    <h1>Profil</h1><br>
    <br>
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

    <p>Souhaitez-vous changer de mot de passe ?</p>
    <a href="mdpoublie.php">Changer de mot de passe ?</a>
    
    </div>
      
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
    document.addEventListener("DOMContentLoaded", function () {
        var logoLink = document.querySelector(".logo-link");
        var dropdownMenu = document.getElementById("myDropdown");

        logoLink.addEventListener("click", function (event) {
            event.stopPropagation(); 
            dropdownMenu.style.display = (dropdownMenu.style.display === "block") ? "none" : "block";
        });

        document.addEventListener("click", function () {
            dropdownMenu.style.display = "none";
        });

        dropdownMenu.addEventListener("click", function (event) {
            event.stopPropagation();
        });
    });
</script>


</body>

</html>

