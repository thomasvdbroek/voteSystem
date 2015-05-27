<!DOCTYPE html>
<?php 
    session_start();
    require "formFunctions.php";
    require "databaseConnection.php";
    $BSN="";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $BSN=$_POST["BSN"];
        
        isRequired("BSN", "een burger service nummer");
        onlyDigit("BSN", "Burger service nummer");
        setMinCharacters("BSN", "BSN", 9);
        setMaxCharacters("BSN", "BSN", 9);
        
        $query="SELECT * FROM people WHERE BSN=".$BSN."";
        $result=mysqli_query($databaseConnection, $query);
        $row=  mysqli_fetch_assoc($result);
        
        if($row["BSN"] == null){
            addValidationError("Dit burgerservice nummer word niet herkent door ons systeem. Vraag om assistentie");
        } 
        
        if($row["age"] < 18){
            addValidationError("U bent niet oud genoeg om te stemmen.");
        }
        
        if(!hasValidationErrors()){
            $_SESSION["BSN"] = $BSN;
            header("Location:check.php");
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Welkom!</h1>
        <?= showValidationSummary(); ?>
        <form name="BSNForm" method="post">
            <p>
                <label>Voer hier uw burger service nummer in(dit nummer staat vermeld op uw paspoort, identiteitskaart en/of rijbewijs):</label>
                <input name="BSN" type="text/css" value=<?= $BSN ?>>
            </p>
            <p>
                <label>&nbsp;</label>
                <input name="submit" type="submit" value="Controleer Gegevens">
            </p>
        </form>
    </body>
</html>