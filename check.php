<!DOCTYPE html>
<?php 
   session_start();
   require 'databaseConnection.php';
   $session=$_SESSION["BSN"];
   
   if($session){
       $confirm = $cancel = "";
       $query="SELECT * FROM people WHERE BSN=".$session."";
       $result=mysqli_query($databaseConnection, $query);
       $row= mysqli_fetch_assoc($result);
       
       if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if(isset($_POST["confirm"])){
            header("Location:vote.php");
        } else if(isset ($_POST["cancel"])) {
            session_destroy();
            header("Location:index.php");
        }
       }
   } else {
       header("Location:index.php");
   }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Kloppen deze gegevens?</h1>
        <form name="check" method="POST">
            <p>
                <label>Voornaam:</label>
                <span><?= $row["firstName"] ?></span>
            </p>

            <p>
                <label>Achternaam:</label>
                <span><?= $row["lastName"] ?></span>
            </p>

            <p>
                <label>Leeftijd:</label>
                <span><?= $row["age"] ?></span>
            </p>
            
            <input name="confirm" type="submit" value="Ja"/><input name="cancel" type="submit" value="Nee"/>
        </form>
    </body>
</html>