<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
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
    <div class="MIASHS">
        <div class="TitreCon">
            <h1 class="h1-contact">Contacter-nous</h1>
            <div class="contact">
                <div class="explain">
                    <span class="icon-message"></span>
                    Si vous avez une question concernant une information manquante
                    ou erronée, si vous souhaitez simplement prendre contact avec 
                    nous pour évoquer un partenariat, commercial ou non, 
                    vous pouvez utiliser le formulaire ci-dessous.
                </div> 
                <br>
                <br>

                <form class="contactform" action="traitement_formulaireCon.php" method="post">
                    <div class="form-group">
                        <label for="sujet" class="hidden">Sujet :</label>
                        <input type="text" name="sujet" id="sujet" placeholder="Sujet">
                    </div>
                    <div class="form-group">
                        <label for="type_message">Type de message :</label>
                        <select name="type_message" id="type_message">
                            <option value="">Sélectionner le type de message</option>
                            <option value="Problème technique">Problème technique</option>
                            <option value="Problème d’affichage">Problème d’affichage</option>
                            <option value="Information manquante">Information manquante</option>
                            <option value="Information erronée">Information erronée</option>
                            <option value="Problème avec votre compte">Problème avec votre compte</option>
                            <option value="Autre raison">Autre raison</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="commentaire">Commentaire :</label>
                        <textarea name="commentaire" id="commentaire" placeholder="Votre commentaire ici"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit">Envoyer</button>
                    </div>
                </form>
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

</body>
</html>
