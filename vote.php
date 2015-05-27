<!DOCTYPE html>
<?php 
   session_start();
   require 'databaseConnection';
   
   $query = "SELECT * FROM parties";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Kloppen deze gegevens?</h1>
        <form name="vote" method="POST">
            
            <input name="submit" type="submit" value="Stem!"/>
        </form>
    </body>
</html>