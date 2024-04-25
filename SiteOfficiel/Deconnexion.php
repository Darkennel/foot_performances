<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
    <body>
    <?php

    session_start(); 
    session_destroy();
    header('Location: index.php');
    exit();
    
    ?>
    </body>
</html>