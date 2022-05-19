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
                    $idVot = "";
                    $error = "";

                    $connessione = connettiDb();

                    $qryIdVot = "SELECT ID_Votazione FROM Esegue WHERE hash LIKE '$hash'";
                    $resultIdVot = $connessione->query($qryIdVot);

                    // Ritorna l'ID della votazione in base all'hash dato
                    if ($resultIdVot->num_rows > 0) {
                        while($row = $resultIdVot->fetch_assoc()) {
                            $idVot = $row['ID_Votazione'];
                        }
                    } else {
                        echo $error = "ERROR";
                    }

                    $connessione->close();

                    if($error != "") {
                        $connessione = connettiDb();

                        $qryNomVot = "SELECT Quesito FROM Votazione WHERE ID LIKE '$idVot'";
                        $resultNomVot = $connessione->query($qryNomVot);

                        // Ritorna il nome della votazione (quesito)
                        if ($resultNomVot->num_rows > 0) {
                            while($row = $resultNomVot->fetch_assoc()) {
                                echo $row['Quesito'];
                            }
                        } else {
                            echo $error = "ERROR";
                        }
                    }
                ?>
            </p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <!--Contenuto della pagine qui sotto-->
        </div>
    </div>
</body>
</html>