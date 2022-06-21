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
	    require __DIR__ . '/SharedFunctions.php';
	    include "Navbar.php";
	    ?>
        <div class="contenuto">
            <center>
            <h1 style="align:center; font-family: 'Roboto Mono', monospace;font-family: 'Space Mono', monospace;">Lista Votazioni</h1>
        <?php
        if(!isset($_SESSION)) { 
            session_start(); 
        }

        $conn =  connettiDb();
        $idUtente = $_SESSION['id_utente'];
        $sql = "select id,quesito,tipo,fine FROM votazione";

        $result = $conn->query($sql);
        $tab = "<table style='width:100%'>
                    <tr style='width:100%; border-bottom: 2px solid black'>
                        <td id='titoli'>TITOLO</td>
                        <td id='titoli'>TIPO</td>
                        <td id='titoli'>COMPLETATO</td>
                        <td></td>
                    </tr>";
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $tab .= "<form method = 'GET' action = 'votazione.php'>";

                $query2 = "select hash FROM esegue WHERE idUtente = " . $idUtente . " AND idVotazione = " . $row['id'];
                
                $result2 = $conn->query($query2);
                
                $tab .= "<tr class='clickable-row' style='width:100%' >";
            
                if($result2->num_rows > 0){
                    while($row2 = $result2->fetch_assoc()){
                        if($row2['hash'] != "")
                            $tab .="<input type='hidden' name = 'hash' value = '" . $row2['hash'] . "'>";                  
                    }
                }
                
                $tab .= "<td name='id'>" . $row['quesito'] . "</td>
                <input type='hidden' name='id' value='".$row['id']."'>
                <td>" . $row['tipo'] . "</td>";

                if(date("Y-m-d H:i:s") <= $row['fine'])
				{
                    $tab .= "<td> NO </td>";
                }else{
                    $tab .= "<td> SI </td>";
                }

                $tab .= "<td><input type='submit' class='button' value='vai a votazione'></td></form>";
                $tab .= "<td><form method='get' action = 'risultati.php'>
                                <input type='submit' class='button' value='vai ai risultati'>
                                <input type='hidden' name='id' value='".$row['id']."'>
                            </form></td>";
                $tab .= "</tr> ";
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