<?php
session_start();

function getBD()
{
    $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
    return $bdd;
}

$bdd = getBD();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    if (!empty($_POST['message'])) {
        $message = nl2br(htmlspecialchars($_POST['message']));

        if (isset($_SESSION['Clients']['id_client'])) {
            if (strlen($message) <= 256) {
                $id_client = $_SESSION['Clients']['id_client'];

                $inserer = $bdd->prepare('INSERT INTO Message(id_client, message, time) VALUES(?, ?, CURRENT_TIMESTAMP)');
                $inserer->execute(array($id_client, $message));

                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else {
                echo "Le message ne doit pas dépasser 256 caractères.";
            }
        } else {
            echo "La session n'est pas correctement initialisée.";
        }
    } else {
        echo"";
    }
}

// Suppression des messages plus anciens
$deleteQuery = $bdd->prepare("DELETE FROM Message WHERE TIMESTAMPDIFF(SECOND, time, NOW()) > 600");
$deleteQuery->execute();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Messagerie</title>
    <link rel="stylesheet" type="text/css" href="Sites.css">
    <meta charset="utf-8">
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <a href="Comparer.php">Comparer des joueurs</a>
        <a href="Deconnexion.php">Déconnexion</a>
    </div>
    <div class ="data-container5">
    <p>Nous vous prions de discuter sans utiliser des mots, phrases, expressions 'offensives'. Merci</p><br>
    
        <div class="formulaire">
            
        <form class="formess" action="" method="post">
            <textarea name="message"></textarea><br>
            <br>
            <button><input type="submit" value="Envoyer"></button>
        </form>
        </div>
        <section>
            <?php
            $messagesQuery = $bdd->prepare("
            SELECT m.message, m.time, c.nom, c.prenom
            FROM Message m
            JOIN Client c ON m.id_client = c.id
            WHERE TIMESTAMPDIFF(MINUTE, m.time, NOW()) <= 10
            ORDER BY m.time DESC
            ");
            $messagesQuery->execute();

            while ($message = $messagesQuery->fetch()) {
                echo '<p>' . $message['nom'] . ' ' . $message['prenom'] . ' dit : - ' . $message['message'] . '</p>';
            }
            ?>
        </section>
    </div>
</body>
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
</html>
