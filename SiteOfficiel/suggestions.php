<!DOCTYPE html>

<html>
    <body>
<?php

ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    function getBD()
    {
        $bdd = new PDO('mysql:host=localhost;dbname=ClientFoot;charset=utf8', 'root', 'root');
        return $bdd;
    }
    
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
    
        $bdd = getBD();
    
        $query = $bdd->prepare('SELECT NomPrenom FROM table_joueur WHERE NomPrenom LIKE :searchTerm LIMIT 5');
        $searchTermWithWildcard = $searchTerm . '%';
        $query->bindParam(':searchTerm', $searchTermWithWildcard, PDO::PARAM_STR);
        $query->execute();
    
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
    
        $suggestions = [];
    
        foreach ($results as $result) {
            $suggestions[] = [
                'nom' => $result['NomPrenom'],
            ];
        }
    
        echo json_encode($suggestions);
    }
    
?>
</body>
</html>