<!DOCTYPE html>
<html lang="it">
<head>
<!--
    Crea variabili di sessione
-->
    <?php
        //require __DIR__ . '/sharedFunctions.php';
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "votazioniScolastiche";
        
        session_start();

        // $hash = $_GET['hash'];
        //$hash = "A0C299B71A9E59D5EBB07917E70601A3570AA103E99A7BB65A58E780EC9077B1902D1DEDB31B1457BEDA595FE4D71D779B6CA9CAD476266CC07590E31D84B206";
        $hash = "C34D427B8B54B254AE843269019A6D5B747783DD230B0A18D66E6CFAE072CEC3339D8B571FFFCABCD6182D083EF3938A0260205A63E9F568582BFC601376BA83";
        //$hash = "ash sbagliato";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        //$conn = connettiDb();

        $qryIdVot = "SELECT idVotazione, idUtente FROM esegue WHERE hash LIKE '$hash'";
        $resultIdVot = $conn->query($qryIdVot);

        // Ritorna l'ID della votazione in base all'hash dato
        if ($resultIdVot->num_rows > 0) {
            while($row = $resultIdVot->fetch_assoc()) {
                $_SESSION['idVot'] = $row['idVotazione'];
                $_SESSION['idUtente'] = $row['idUtente'];
            }
        } else {
            $_GLOBALS['error'] = "ERROR";
            echo  $_GLOBALS['error'];
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

            $opzioni = $_POST['opzione'];
            
            $aus = count($opzioni);
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            if($aus == $_SESSION['numScelte']) {
                /*
                ✓ bisogna fare un alter table per il nVoti, 
                ✓ query per vedere il tipo di votazione,
                x bisogna creare la ternaria in base al tipo di votazione
                */
                
                $qryTipoVot = "SELECT tipo FROM votazione WHERE id LIKE '" . $_SESSION['idVot'] . "'";
                $resultTipoVot = $conn->query($qryTipoVot);

                if($resultTipoVot->num_rows > 0) {
                    while($row = $resultTipoVot->fetch_assoc()) {
                        $tipoVot = $row['tipo'];
                    }
                } else {
                    $_GLOBALS['error'] = "ERROR";
                    echo  $_GLOBALS['error'];
                }

                for($i = 0; $i < count($opzioni); $i++) {
                    $qrynVotOp = "SELECT nVoti FROM opzione WHERE id LIKE '" . $opzioni[$i] . "' AND idVotazione LIKE '" . $_SESSION['idVot'] . "'";
                    $resultnVotOp = $conn->query($qrynVotOp);

                    if($resultnVotOp->num_rows > 0) {
                        while($row = $resultnVotOp->fetch_assoc()) {
                            $row['nVoti']++;
                            
                            $qryAggiungiVoto = "UPDATE opzione SET nVoti = '" . $row['nVoti'] . "' WHERE id='" . $opzioni[$i] . "'";

                            if (!($conn->query($qryAggiungiVoto) === TRUE)) {
                                echo "Error updating record: " . $conn->error;
                            }
                        }
                    }
                    
                }


            } else {
                $_SESSION['erroreScel'] = "error";
            }
           // $aus = array();

            //for($i = 0; i < $_GLOBALS['n'])
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
                        die("Connection failed: " . $conn->connect_error);
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
                            if($_GLOBALS['error'] == "") {
                                $_GLOBALS['error'] = "ERROR";
                                echo  $_GLOBALS['error'];
                            }
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
                        die("Connection failed: " . $conn->connect_error);
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
                        $_GLOBALS['error'] = "ERROR";
                        echo  $_GLOBALS['error'];
                    }

                    $conn->close();

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }
                    //$conn = connettiDb();

                    $aus = 0;
                    $qryOpz = "SELECT id, testo FROM opzione WHERE idVotazione LIKE '" . $_SESSION['idVot'] . "'";
                    $resultOpz = $conn->query($qryOpz);
                    $_GLOBALS['numOpz'] = "";

                    // Ricevo informazioni delle opzioni aggangiate alla votazione
                    if ($resultOpz->num_rows > 0) {
                        echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        while($row = $resultOpz->fetch_assoc()) {
                            if($_SESSION['numScelte'] == 1) {
                                echo "<input type=\"radio\" name=\"opzione[]\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\">
                                    <label class=\"testo\">" . $row['testo'] . "</label><br><br>";
                                $aus++;
                            } else {
                                $_GLOBALS['numOpz']++;
                                echo "<input name=\"opzione[]\" type=\"checkbox\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\">
                                    <a class=\"testo\">" . $row['testo'] . "</a><br><br>";
                            }
                        }
                        echo "<input type=\"submit\" name=\"submit\" value=\"Conferma e invia la tua votazione\">  
                            </form>";
                    } else {
                        $_GLOBALS['error'] = "ERROR";
                        echo  $_GLOBALS['error'];
                    }
                } else {
                    echo "<p class=\"errore\">ERRORE: hai già risposto alla votazione o la votazione non esiste.</p>";
                }    
                
                $conn->close();
            ?>
            <p class="errore"><?php if(isset($_SESSION['erroreScel'])) {echo 'Errore: numero scelte sbagliate.';}?></p>
        </div>
    </div>
</body>
</html>


<!--
Se la votazione selezionata è aperta mostrare le opzioni per poter votare.
Se la votazione selezionata è chiusa ma il tempo non è terminato, si mostra tutto ma con le opzioni bloccate
Se la votazione selezionata è chiusa ma il tempo è terminato, e i dati non sono ancora stati pubblicati allora verrà mostrato un messaggio di “Risultati in elaborazione”
Se la votazione selezionata è chiusa ma il tempo è terminato e i dati sono stati pubblicati dal creatore della votazione, allora si mostreranno le opzioni con le varie percentuali
Se la votazione è anonima, in risposta la chiave esterna sull’opzione non viene salvata, se invece è nominale si.
In entrambi i tipi di votazione si incrementa il numero di voti sull’opzione.
Se la votazione è con scelte multiple si inserirà nella tabella risposta N record in base alle N risposte. Prima di inserirle bisogna controllare che le N risposte date non siano maggiori del numero massimo di opzioni per cui si può rispondere. Se succede mostrare un errore e non fare nulla. Altrimenti con una transazione effettuare tutte le operazioni necessarie. 
            -->