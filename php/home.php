<?php
/**
* @author skenny;Matteo Schintu
* @author Daniele
* @author Negro
*/
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Votazione</title>

</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Lista Votazioni</p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <center>
            <h1 style="align:center; font-family: 'Roboto Mono', monospace;font-family: 'Space Mono', monospace;">Lista Votazioni</h1>
        <?php
        if(!isset($_SESSION)) { 
            session_start(); 
        }
        $_SESSION["user_selected"] = 0;
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbName = "votazioniscolastiche";

        $sql = "";
        $tab;
        $idUtente = $_SESSION['id_utente'];

        $conn = mysqli_connect($server,$username,$password,$dbName);

        if(!$conn){
            die("Connessione Fallita: " . mysqli_connect_error());
        }

        $sql = "select id,quesito,tipo,fine FROM votazione";

        $result = mysqli_query($conn,$sql);

        $tab = "<table style='width:100%'>
                    <tr style='width:100%; border-bottom: 2px solid black'>
                        <td id='titoli'>TITOLO</td>
                        <td id='titoli'>TIPO</td>
                        <td id='titoli'>COMPLETATO</td>
                        <td></td>
                    </tr>";

        if(mysqli_num_rows($result) > 0){
            while($row=mysqli_fetch_assoc($result)){
                $tab .= "<form method = 'GET' action = 'votazione.php'>";

                $query2 = "select hash FROM esegue WHERE idUtente = " . $idUtente . " AND idVotazione = " . $row['id'];
                
                $result2 = mysqli_query($conn,$query2);
                
                $tab .= "<tr class='clickable-row' style='width:100%' >";
            
                if(mysqli_num_rows($result2) > 0){
                    while($row2=mysqli_fetch_assoc($result2)){
                        if($row2['hash'] != "")
                            $tab .="<input type='hidden' name = 'hash' value = '" . $row2['hash'] . "'>";                  
                    }
                }
                
                $tab .= "<td name='id'>" . $row['quesito'] . "</td>
                <input type='hidden' name='id' value='".$row['id']."'>
                <td>" . $row['tipo'] . "</td>";
                
                if($row['fine'] >= date("Y-M-D h:i:sa")){
                    $tab .= "<td> SI </td>";
                }else{
                    $tab .= "<td> NO </td>";
                }

                $tab .= "<td><input type='submit' class='button' value='vai a votazione'></td>";
                $tab .= "</tr> </form>";
            }
        }
        echo $tab . "</table>";
    ?>
        </center>
        <p class="errore"><?php  if(isset($_GET['hash'])) {echo "Non hai i permessi per accedere a quetsa votazione";$_GET['hash'] = "";}?></p>
        </div>
    </div>
</body>
</html>