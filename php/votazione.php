<!DOCTYPE html>
<html lang="it">
<head>
    <?php
        require __DIR__ . '/sharedFunctions.php';
    ?>
    <!--Usato solo quando viene eseguito un submit nella pagina-->
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            //if($_GLOBALS['numScelte'] == 1) {
                $name = $_POST['opzione'];
                foreach ($name as $color){ 
                    echo $color."<br />";
                }
            //}
            
            $aus = array();

            //for($i = 0; i < $_GLOBALS['n'])
        }
    ?>
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
                    // $hash = $_GET['hash'];
                    //$hash = "A0C299B71A9E59D5EBB07917E70601A3570AA103E99A7BB65A58E780EC9077B1902D1DEDB31B1457BEDA595FE4D71D779B6CA9CAD476266CC07590E31D84B206";
                    $hash = "C34D427B8B54B254AE843269019A6D5B747783DD230B0A18D66E6CFAE072CEC3339D8B571FFFCABCD6182D083EF3938A0260205A63E9F568582BFC601376BA83";
                    //$hash = "ash sbagliato";

                    $_GLOBALS['idVot'] = "";
                    $_GLOBALS['idUtente'] = "";
                    $_GLOBALS['error'] = "";
                    $_GLOBALS['nomQuesito'] = "";

                    $conn = connettiDb();

                    $qryIdVot = "SELECT idVotazione, idUtente FROM esegue WHERE hash LIKE '$hash'";
                    $resultIdVot = $conn->query($qryIdVot);

                    // Ritorna l'ID della votazione in base all'hash dato
                    if ($resultIdVot->num_rows > 0) {
                        while($row = $resultIdVot->fetch_assoc()) {
                            $_GLOBALS['idVot'] = $row['idVotazione'];
                            $_GLOBALS['idUtente'] = $row['idUtente'];
                        }
                    } else {
                        $_GLOBALS['error'] = "ERROR";
                        echo  $_GLOBALS['error'];
                    }

                    $conn->close();
                    
                    if($_GLOBALS['error'] == "") {
                        $conn = connettiDb();   
                        $qryNomVot = "SELECT quesito FROM votazione WHERE ID LIKE '" . $_GLOBALS['idVot'] . "'";
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
                ?>
            </p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
        <?php
            if($_GLOBALS['error'] == "") {
                $_GLOBALS['numScelte'] = "";

                $conn = connettiDb();

                $qryInfoVot = "SELECT tipo, inizio, fine, quorum, scelteMax, quesito FROM votazione WHERE id LIKE '" . $_GLOBALS['idVot'] . "'";
                $resultInfoVot = $conn->query($qryInfoVot);

                // In base all'id del quesito, stampo i dati necessari
                if ($resultInfoVot->num_rows > 0) {
                    while($row = $resultInfoVot->fetch_assoc()) {
                        $_GLOBALS['numScelte'] = $row['scelteMax'];
                        echo "<p class=\"testo\">Data apertura votazione: " . $row['inizio'] . ".<br>
                            Data chiusura votazione: " . $row['fine'] . ".</p>
                            <p class=\"testo\">Tipo votazione: " . $row['tipo'] . ".</p>
                            <p class=\"testo\">Quorum: " . $row['quorum'] . "%</p>
                            <p class=\"quesito\">" . $row['quesito'] . " (max " . $_GLOBALS['numScelte'] . " scelte)</p>";
                    }
                } else {
                    $_GLOBALS['error'] = "ERROR";
                    echo  $_GLOBALS['error'];
                }

                $conn->close();

                $conn = connettiDb();

                $aus = 0;
                $qryOpz = "SELECT id, testo FROM opzione WHERE idVotazione LIKE '" . $_GLOBALS['idVot'] . "'";
                $resultOpz = $conn->query($qryOpz);
                $_GLOBALS['numOpz'] = "";

                // Ricevo informazioni delle opzioni aggangiate alla votazione
                if ($resultOpz->num_rows > 0) {
                    echo '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                    while($row = $resultOpz->fetch_assoc()) {
                        if($_GLOBALS['numScelte'] == 1) {
                            echo "<input type=\"radio\" name=\"opzione[]\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\">
                                <label class=\"testo\">" . $row['testo'] . "</label><br><br>";
                            $aus++;
                        } else {
                            $_GLOBALS['numOpz']++;
                            echo "<input name=\"checkbox[]\" type=\"checkbox\" id=\"" . $aus . "\" value=\"" . $row['id'] . "\">
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
        ?>
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