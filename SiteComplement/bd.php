<?php
   function getBd(){
        $bdd = new PDO('mysql:host=localhost;dbname=Performances_Football;charset=utf8', 'root', 'root');
        return $bdd;
    } 
                     
?>    
