<?php 
    require __DIR__ . '/SharedFunctions.php';
    if(!isset($_SESSION)) { 
        session_start(); 
    }   
?>
<!DOCTYPE html>
<html lang="it">
<head>
<link rel="stylesheet" href="../stile/globalStyle.css">
<!----------------------------------------------------------------------
    HTML
---------------------------------------------------------------------->

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Risultati</title>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Risultati votazione: 
                <?php
                    $servername = "127.0.0.1";
                    $username = "root";
                    $password = "";
                    $dbname = "votazioniScolastiche";

                    $_GLOBALS['nomQuesito'] = "";
                    
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die($_SESSION['errore'] = "Connection failed 5: " . $conn->connect_error);
                    }

                    if($_SESSION['errore'] == "") {
                        
                        //$conn = connettiDb();   
                        $qryNomVot = "SELECT quesito FROM votazione WHERE ID LIKE '" . $_SESSION['idVot'] . "'";
                        $resultNomVot = $conn->query($qryNomVot);

                        // Ritorna il nome della votazione (quesito)
                        if ($resultNomVot->num_rows > 0) {
                            while($row = $resultNomVot->fetch_assoc()) {
                                $_GLOBALS['nomQuesito'] = $row['quesito'];
                                echo $_GLOBALS['nomQuesito'];
                            }
                        } else {
                            $_SESSION['errore'] = "ERRORE: quesito inesistente o votazione non valida";
                        }
                    }

                    $conn->close();
                ?>
            </p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            
        </div>
    </div>
</body>
</html>