<?php
    /**
    * @author Simone Negro
    * @author Cosimo Daniele
    */
?>
<!DOCTYPE html>
<html lang="it">
<head>
<!--
    Crea variabili di sessione
-->
    <?php
        require __DIR__ . '/sharedFunctions.php';
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "votazioniScolastiche";
        $error = "";

        session_start();
    
        $_SESSION['errore'] = "";
         
        // $hash = $_GET['hash'];
        $hash = "A0C299B71A9E59D5EBB07917E70601A3570AA103E99A7BB65A58E780EC9077B1902D1DEDB31B1457BEDA595FE4D71D779B6CA9CAD476266CC07590E31D84B206";
        //$hash = "C34D427B8B54B254AE843269019A6D5B747783DD230B0A18D66E6CFAE072CEC3339D8B571FFFCABCD6182D083EF3938A0260205A63E9F568582BFC601376BA83";
        //$hash = "ash sbagliato";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
        }
        
        $qryIdVot = "SELECT idVotazione, idUtente FROM esegue WHERE hash LIKE '$hash'";
        $resultIdVot = $conn->query($qryIdVot);

        // Ritorna l'ID della votazione in base all'hash dato
        if ($resultIdVot->num_rows > 0) {
            while($row = $resultIdVot->fetch_assoc()) {
                $_SESSION['idVot'] = $row['idVotazione'];
                $_SESSION['idUtente'] = $row['idUtente'];
            }
        } else {
            $_SESSION['errore'] = "ERRORE: ash errato";
        }

        $conn->close();
    ?>

<!--
    Usato solo quando viene eseguito un submit nella pagina
-->
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "votazioniScolastiche";
            $aus = 0;
            $error = "";

            if(isset($_POST['opzione'])) {
            
                $opzioni = $_POST['opzione'];
                
                $aus = count($opzioni);
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                }

                /**
                 * @todo 
                 * DA MIGLIORARE CONTROLLI
                */ 
                if($aus > 0 && $aus <= $_SESSION['numScelte']) {               
                    $qryTipoVot = "SELECT tipo FROM votazione WHERE id LIKE '" . $_SESSION['idVot'] . "'";
                    $resultTipoVot = $conn->query($qryTipoVot);

                    if($resultTipoVot->num_rows > 0) {
                        while($row = $resultTipoVot->fetch_assoc()) {
                            $tipoVot = $row['tipo'];
                        }
                    } else {
                        $_SESSION['errore'] = "ERRORE: tipo di votazione inesistente o votazione non valida";
                    }

                    $lock = "START TRANSACTION";
                    $conn->query($lock);

                    for($i = 0; $i < count($opzioni); $i++) {
                        $qrynVotOp = "SELECT nVoti FROM opzione WHERE id LIKE '" . $opzioni[$i] . "' AND idVotazione LIKE '" . $_SESSION['idVot'] . "'";
                        $resultnVotOp = $conn->query($qrynVotOp);

                        if($resultnVotOp->num_rows > 0) {
                            while($row = $resultnVotOp->fetch_assoc()) {
                                $row['nVoti']++;
                                
                                $qryAggiungiVoto = "UPDATE opzione SET nVoti = '" . $row['nVoti'] . "' WHERE id='" . $opzioni[$i] . "'";

                                if (!($conn->query($qryAggiungiVoto) === TRUE)) {
                                    $rollback = "ROLLBACK";
                                    $conn->query($rollback);
                                }
                            }
                        }
                        
                    }
                } else if($aus == 0) {
                    $_SESSION['errore'] = "ERRORE: selezione minimo una scelta";
                } else if($aus > $_SESSION['numScelte']) {
                    $_SESSION['errore'] = "ERRORE: superato il numero di scelte massime";
                } else {
                    $_SESSION['errore'] = "ERRORE: numero scelte errato";
                }

                $commit = "COMMIT";
                $conn->query($commit);

                if($tipoVot == "anonimo") {
                    $qryVotAnonim = "INSERT INTO risposta(data, ora, idUtente, idVotazione) VALUES 
                                    ('" . date("Y/m/d") . "', '" . date("h:i:s") . "', '" . $_SESSION['idUtente'] . "', '" . $_SESSION['idVot'] . "')";
                    
                    if(!($conn->query($qryVotAnonim) === TRUE)) {
                        die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                    }
                } else if($tipoVot == "nominale") {
                    for($i = 0; $i < count($opzioni); $i++) {
                        $qryVotNom = "INSERT INTO risposta(data, ora, idUtente, idVotazione, idOpzione) VALUES
                                    ('" . date("Y/m/d") . "', '" . date("h:i:s") . "', '" . $_SESSION['idUtente'] . "', '" . $_SESSION['idVot'] . "', '" . $opzioni[$i] . "')";

                        if(!($conn->query($qryVotNom) === TRUE)) {
                            die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                        }
                    }
                }
            }
        }
    ?>
<!----------------------------------------------------------------------
    HTML
---------------------------------------------------------------------->

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
            <p class="titolo-header">Votazione: 
                <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "votazioniScolastiche";

                    $_GLOBALS['error'] = "";
                    $_GLOBALS['nomQuesito'] = "";
                    
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                    }

                    if($_GLOBALS['error'] == "") {
                        
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
        <?php //include "Navbar.php"; ?>
        <div class="contenuto">
            <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "votazioniScolastiche";
            
                if($_GLOBALS['error'] == "") {
                    $_SESSION['numScelte'] = "";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                    }
                    // $conn = connettiDb();

                    $qryInfoVot = "SELECT tipo, inizio, fine, quorum, scelteMax, quesito FROM votazione WHERE id LIKE '" . $_SESSION['idVot'] . "'";
                    $resultInfoVot = $conn->query($qryInfoVot);

                    // In base all'id del quesito, stampo i dati necessari
                    if ($resultInfoVot->num_rows > 0) {
                        while($row = $resultInfoVot->fetch_assoc()) {
                            $_SESSION['numScelte'] = $row['scelteMax'];
                            echo "<p class=\"testo\">Data apertura votazione: " . $row['inizio'] . ".<br>
                                Data chiusura votazione: " . $row['fine'] . ".</p>
                                <p class=\"testo\">Tipo votazione: " . $row['tipo'] . ".</p>
                                <p class=\"testo\">Quorum: " . $row['quorum'] . "%</p>
                                <p class=\"quesito\">" . $row['quesito'] . " (max " . $_SESSION['numScelte'] . " scelte)</p>";
                        }
                    } else {
                        $_SESSION['errore'] = "ERRORE: votazione non valida";
                    }

                    $conn->close();

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die($_SESSION['errore'] = "Connection failed: " . $conn->connect_error);
                    }
                    //$conn = connettiDb();

                    $aus = 0;

                    //----------------------------------------------------------------------------------------------------------------------------------------------------------------

                    $qryTempo = "SELECT inizio, fine FROM votazione WHERE id LIKE '" . $_SESSION['idVot'] . "'";
                    $resultTempo = $conn->query($qryTempo);
                    
                    if($resultTempo->num_rows > 0) {
                        while($row = $resultTempo->fetch_assoc()) {
                            $tempoInizio = $row['inizio'];
                            $tempoFine = $row['fine'];
                        }
                    } else {
                        $_SESSION['errore'] = "ERRORE: votazione non valida";
                    }

                    // calcoli per il tempo
                    $dataCorrente = date("Y-m-d h:i:s");
                    $attScelta = "enable";
                    $vot = "aperta";

                    if($dataCorrente < $tempoInizio || $dataCorrente > $tempoFine) {
                        $attScelta = "disabled";
                        $vot = "chiusa";    
                    }

                    $qryVotChiusa = "SELECT idVotazione FROM risposta 
                                    WHERE idUtente LIKE '" . $_SESSION['idUtente'] ."' AND idVotazione LIKE '" . $_SESSION['idVot'] . "'";
                    $resultVotChiusa = $conn->query($qryVotChiusa);

                    if($resultVotChiusa->num_rows > 0) {
                        $attScelta = "disabled";
                    } 

                    $qryOpz = "SELECT id, testo FROM opzione WHERE idVotazione LIKE '" . $_SESSION['idVot'] . "'";
                    $resultOpz = $conn->query($qryOpz);
                    $_GLOBALS['numOpz'] = "";

                    // Ricevo informazioni delle opzioni agganciate alla votazione
                    if ($resultOpz->num_rows > 0) {
                        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        while($row = $resultOpz->fetch_assoc()) {
                            $mediaVot = ""; 

                            $lock = "START TRANSACTION";
                            $conn->query($lock);

                            if($vot == "chiusa") {
                                $qryTotVoti = "SELECT SUM(nVoti) AS totVoti FROM opzione 
                                                WHERE idVotazione LIKE '" . $_SESSION['idVot'] . "'";

                                $qrynVoti = "SELECT nVoti FROM opzione WHERE id LIKE '" . $row['id'] . "'";

                                $resultTotVoti = $conn->query($qryTotVoti);
                                $resultnVoti = $conn->query($qrynVoti);

                                if($resultTotVoti->num_rows > 0) {
                                    while($row1 = $resultTotVoti->fetch_assoc()) {
                                        $totVoti = $row1['totVoti'];
                                    }
                                } else {
                                    $_SESSION['errore'] = "ERRORE: opzione non valida";
                                }

                                if($resultnVoti->num_rows > 0) {
                                    while($row2 = $resultnVoti->fetch_assoc()) {
                                        $nVoti = $row2['nVoti'];
                                    }
                                } else {
                                    $_SESSION['errore'] = "ERRORE: opzione non valida";
                                }
                                
                                $mediaVot = "- " . round((100 * $nVoti) / $totVoti, 1) . "%";
                            }

                            if($_SESSION['numScelte'] == 1) {
                                echo "<input type=\"radio\" name=\"opzione[]\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\" " . $attScelta . ">
                                    <label class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</label><br><br>";
                                $aus++;
                                
                            } else {
                                $_GLOBALS['numOpz']++;
                                echo "<input name=\"opzione[]\" type=\"checkbox\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\" " .$attScelta . ">
                                    <a class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</a><br><br>";
                            }
                        }
                        echo "<input class=\"bottone\" type=\"submit\" name=\"submit\" value=\"Conferma e invia la tua votazione\" " . $attScelta . ">  
                            </form>";
                    } else {
                        $_SESSION['errore'] = "ERRORE: votazione non valida";
                    }
                } else {
                    echo "<p class=\"errore\">ERRORE: hai già risposto alla votazione o la votazione non esiste.</p>";
                }    
                $commit = "COMMIT";
                $conn->query($commit);
                
                $conn->close();
            ?>
            <p class="errore"><?php if(isset($_SESSION['errore'])) {echo $_SESSION['errore'];$_SESSION['errore'] = "";}?></p>
        </div>
    </div>
</body>
</html>


<!--
✓ Se la votazione selezionata è chiusa ma il tempo non è terminato, si mostra tutto ma con le opzioni bloccate
x Se la votazione selezionata è chiusa ma il tempo è terminato, e i dati non sono ancora stati pubblicati 
    allora verrà mostrato un messaggio di “Risultati in elaborazione”
✓ Se la votazione selezionata è chiusa ma il tempo è terminato e i dati sono stati pubblicati dal creatore della votazione, allora si mostreranno le opzioni con le varie percentuali
-->