<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si tous les champs requis sont remplis
    if (!empty($_POST['sujet']) && !empty($_POST['type_message']) && !empty($_POST['commentaire'])) {
        // Récupérer les données soumises par le formulaire
        $sujet = $_POST['sujet'];
        $type_message = $_POST['type_message'];
        $commentaire = $_POST['commentaire'];

        // Connexion à la base de données
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "Performances_Football";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // Définir le mode d'erreur PDO à exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Préparer et exécuter la requête d'insertion
            $stmt = $conn->prepare("INSERT INTO formulaire_contact (sujet, type_message, commentaire) VALUES (:sujet, :type_message, :commentaire)");
            $stmt->bindParam(':sujet', $sujet);
            $stmt->bindParam(':type_message', $type_message);
            $stmt->bindParam(':commentaire', $commentaire);
            $stmt->execute();

            echo "Votre message a été envoyé avec succès.";
        } catch(PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }

        // Fermer la connexion à la base de données
        $conn = null;
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
    }
} else {
    // Redirection vers la page de contact si le formulaire n'a pas été soumis
    header("Location: contact.php");
    exit();
}
?>
