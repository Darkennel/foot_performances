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
        echo "Veuillez saisir un message";
    }
}

// Suppression des messages plus anciens
$deleteQuery = $bdd->prepare("DELETE FROM Message WHERE TIMESTAMPDIFF(SECOND, time, NOW()) > 600");
$deleteQuery->execute();
?>

<!DOCTYPE html>
<html class="messafond">

<head>
    <title>Messagerie</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="utf-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <h2>Forum</h2>
    <p>Vous voici sur le forum de notre site.</p>
    <a href="acceuilConnecte.php">Page d'accueil</a>
    <p>Nous vous prions de discuter sans utiliser des mots, phrases, expressions 'offensives'. Merci</p>
    <img id="logdis" src="discussion.jpeg" alt="Image de discussion" />
    <div id="messageContainer">
        <form id="messageForm" action="" method="post">
            <textarea name="message"></textarea>
            <br>
            <input type="submit" value="Envoyer">
        </form>

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

</html>
