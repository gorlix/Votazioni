<!DOCTYPE html>
<html lang="it">
<head>
    <?php
        require __DIR__ . '/sharedFunctions.php';
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
                    $hash = $_GET['hash'];

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

                    if($error != "") {
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
                            $_GLOBALS['error'] = "ERROR";
                            echo  $_GLOBALS['error'];
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
                $numScelte = "";

                $conn = connettiDb();

                $qryInfoVot = "SELECT tipo, inizio, fine, quorum, scelteMax, quesito FROM votazione WHERE id LIKE '" . $_GLOBALS['idVot'] . "'";
                $resultInfoVot = $conn->query($qryInfoVot);

                // In base all'id del quesito, stampo i dati necessari
                if ($resultInfoVot->num_rows > 0) {
                    while($row = $resultInfoVot->fetch_assoc()) {
                        echo "<p>Votazione aperta " . $row['inizio'] . " e termina " . $row['fine'] . "</p>
                            <p>Tipo votazione: " . $row['tipo'] . "</p>
                            <p>Quorum: " . $row['quorum'] . "%</p>
                            <p class=\"quesito\">" . $row['quesito'] . "</p>";
                        $numScelte = $row['scelteMax'];
                    }
                } else {
                    $_GLOBALS['error'] = "ERROR";
                    echo  $_GLOBALS['error'];
                }

                $conn->close();

                $conn = connettiDb();

                $qryOpz = "SELECT id, testo FROM opzione WHERE idVotazione LIKE '" . $_GLOBALS['idVot'] . "'";
                $resultOpz = $conn->query($qryOpz);

                 // Ricevo informazioni delle opzioni aggangiate alla votazione
                 if ($resultOpz->num_rows > 0) {
                    echo "<form action";
                    while($row = $resultOpz->fetch_assoc()) {
                        if($numScelte == 1) {
                            echo "<input type=\"radio\" id=\"".$aus."\" value=\"".$row['id']."\">
                                <p>" . $row['testo'] . "</p>";
                        }
                    }
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