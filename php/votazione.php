<!DOCTYPE html>
<html lang="it">
<head>
    <?php
        require __DIR__ . '/SharedFunctions.php';
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

                    $conn = connettiDb();

                    $qryIdVot = "SELECT ID_Votazione, ID_Utente FROM Esegue WHERE hash LIKE '$hash'";
                    $resultIdVot = $conn->query($qryIdVot);

                    // Ritorna l'ID della votazione in base all'hash dato
                    if ($resultIdVot->num_rows > 0) {
                        while($row = $resultIdVot->fetch_assoc()) {
                            $_GLOBALS['idVot'] = $row['ID_Votazione'];
                            $_GLOBALS['idUtente'] = $row['ID_Utente'];
                        }
                    } else {
                        $_GLOBALS['error'] = "ERROR";
                        echo  $_GLOBALS['error'];
                    }

                    $conn->close();

                    if($error != "") {
                        $conn = connettiDb();

                        $qryNomVot = "SELECT Quesito FROM Votazione WHERE ID LIKE '" . $_GLOBALS['idVot'] . "'";
                        $resultNomVot = $conn->query($qryNomVot);

                        // Ritorna il nome della votazione (quesito)
                        if ($resultNomVot->num_rows > 0) {
                            while($row = $resultNomVot->fetch_assoc()) {
                                echo $row['Quesito'];
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
                    $conn = connettiDb();

                    $qryInfoVot = "SELECT Tipo, Inizio, Fine, Quorum FROM Votazione WHERE ID LIKE '" . $_GLOBALS['idVot'] . "'";
                    $resultIdVot = $conn->query($qryIdVot);

                    // Ritorna l'ID della votazione in base all'hash dato
                    if ($resultIdVot->num_rows > 0) {
                        while($row = $resultIdVot->fetch_assoc()) {
                            $_GLOBALS['idVot'] = $row['ID_Votazione'];
                            $_GLOBALS['idUtente'] = $row['ID_Utente'];
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


/* 
Se la votazione selezionata è aperta mostrare le opzioni per poter votare.
Se la votazione selezionata è chiusa ma il tempo non è terminato, si mostra tutto ma con le opzioni bloccate
Se la votazione selezionata è chiusa ma il tempo è terminato, e i dati non sono ancora stati pubblicati allora verrà mostrato un messaggio di “Risultati in elaborazione”
Se la votazione selezionata è chiusa ma il tempo è terminato e i dati sono stati pubblicati dal creatore della votazione, allora si mostreranno le opzioni con le varie percentuali
Se la votazione è anonima, in risposta la chiave esterna sull’opzione non viene salvata, se invece è nominale si.
In entrambi i tipi di votazione si incrementa il numero di voti sull’opzione.
Se la votazione è con scelte multiple si inserirà nella tabella risposta N record in base alle N risposte. Prima di inserirle bisogna controllare che le N risposte date non siano maggiori del numero massimo di opzioni per cui si può rispondere. Se succede mostrare un errore e non fare nulla. Altrimenti con una transazione effettuare tutte le operazioni necessarie. 
*/