<!DOCTYPE html>
<html lang="it">
<head>
<link rel="stylesheet" href="../stile/globalStyle.css">
<!----------------------------------------------------------------------
    HTML
---------------------------------------------------------------------->
<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "votazioniScolastiche";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $_GLOBALS['idVotazione'] = $_POST['id'];

        $conn = mysqli_connect($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            die($_SESSION['errore'] = "Connection failed 1: " . $conn->connect_error);
        }

        $qryPubblicaVot = "UPDATE votazione SET pubblica = 1 WHERE id = ".$_GLOBALS['idVotazione'];

        if ($conn->query($qryPubblicaVot) === FALSE) {
            echo "ERRORE durante la pubblicazione della votazione"; 
        }
    };
?>
<script>
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
    coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        var content = this.nextElementSibling;
        if (content.style.display === "block") {
        content.style.display = "none";
        } else {
        content.style.display = "block";
        }
    });
    }
</script>
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
                        die($_SESSION['errore'] = "Connection failed 1: " . $conn->connect_error);
                    }

                    if(isset($_GET['id'])) {
                        $_GLOBALS['idVotazione'] = $_GET['id']; 

                        $sql = "SELECT quesito FROM votazione WHERE id = " . $_GLOBALS['idVotazione'] . "";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $_GLOBALS['nomQuesito'] = $row["quesito"];
                                echo $_GLOBALS['nomQuesito'];  
                            }
                        } else {
                            die($_SESSION['errore'] = "0 results");
                        }
                    } else {
                        $sql = "SELECT quesito FROM votazione WHERE id = " . $_GLOBALS['idVotazione'] . "";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $_GLOBALS['nomQuesito'] = $row["quesito"];
                                echo $_GLOBALS['nomQuesito'];  
                            }
                        } else {
                            die($_SESSION['errore'] = "0 results");
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
            <?php
                $servername = "127.0.0.1";
                $username = "root";
                $password = "";
                $dbname = "votazioniScolastiche";

                $conn = new mysqli($servername, $username, $password, $dbname);
                
                if ($conn->connect_error) {
                    die($_SESSION['errore'] = "Connection failed 1: " . $conn->connect_error);
                }

                $qryInfoVot = "SELECT tipo, inizio, fine, quorum, scelteMax, quesito FROM votazione WHERE id LIKE '" . $_GLOBALS['idVotazione'] . "'";
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

                $qryTempo = "SELECT inizio, fine FROM votazione WHERE id = " . $_GLOBALS['idVotazione'] . "";
                $resultTempo = $conn->query($qryTempo);

                if ($resultTempo->num_rows > 0) {
                    while($row = $resultTempo->fetch_assoc()) {
                        $inizio = $row["inizio"];
                        $fine = $row["fine"];
                    }
                } else {
                    die("time: 0 results");
                }

                // calcoli per il tempo
                $dataCorrente = date("Y-m-d h:i:s");
                $attScelta = "disabled";
               
                if($dataCorrente < $inizio || $dataCorrente > $fine) {
                    $vot = "chiusa";    
                } else {
                    $vot = "aperta";
                }

                if(isset($_SESSION['id_utente'])) {
                    $qryAppartenenzaGruppo = "SELECT idGruppo FROM appartienea WHERE idUtente LIKE '" . $_SESSION['id_utente'] . "'";
                    $resultAppartenenzaGruppo = $conn->query($qryAppartenenzaGruppo);
                
                    if($resultAppartenenzaGruppo->num_rows > 0) {
                        while($row = $resultAppartenenzaGruppo->fetch_assoc()) {
                            $idGruppo[] = $row["idGruppo"];
                        }
                    } else {
                        $_SESSION['errore'] = "ERRORE: gruppo non trovato";
                    }
                }

                $qryOpz = "SELECT id, testo FROM opzione WHERE idVotazione LIKE '" . $_GLOBALS['idVotazione'] . "'";
                $resultOpz = $conn->query($qryOpz);
                $votazioneAperta = 0;

                if ($resultOpz->num_rows > 0) {
                    while($row = $resultOpz->fetch_assoc()) {
                        $mediaVot = ""; 

                        // e dati pubblicati
                        $qryDatiPub = "SELECT pubblica FROM votazione WHERE id LIKE '" . $_GLOBALS['idVotazione'] . "'";
                        $resultDatiPub = $conn->query($qryDatiPub);

                        if($resultDatiPub->num_rows > 0) {
                            while($row3 = $resultDatiPub->fetch_assoc()) {
                                $pubblica = $row3['pubblica'];
                            }
                        } else {
                            $_SESSION['errore'] = "ERRORE: votazione non trovata";
                        }
                        
                        if(($vot == "chiusa" && $pubblica == 1) || in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo)) {
                            $qryTotVoti = "SELECT SUM(nVoti) AS totVoti FROM opzione 
                                            WHERE idVotazione LIKE '" . $_GLOBALS['idVotazione'] . "'";

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
                            
                            // se la votazione è chiusa, stampo i risultati dei voti
                            $qryVotApertaChiusa = "SELECT pubblica FROM votazione WHERE id LIKE '" . $_GLOBALS['idVotazione'] . "'";
                            $resultVotApertaChiusa = $conn->query($qryVotApertaChiusa);

                            if($resultVotApertaChiusa->num_rows > 0) {
                                while($row4 = $resultVotApertaChiusa->fetch_assoc()) {
                                    $votazionePubblicata = $row4['pubblica'];

                                    if(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo) || $votazionePubblicata == 1) {
                                        if($totVoti > 0) {
                                            $mediaVot = "- " . round((100 * $nVoti) / $totVoti, 1) . "% - Numero voti: " . $nVoti." / " . $totVoti;
                                        }
                                    } 
                                }
                            } else {
                                $_SESSION['errore'] = "ERRORE: votazione non trovata";
                            }
                        }
                        $qryVontantiOpzione = "SELECT u.nome, u.cognome, u.mail FROM utente u
                                                INNER JOIN risposta r ON u.id = r.idUtente
                                                WHERE r.idOpzione IS NOT NULL AND r.idOpzione LIKE '" . $row['id'] . "'";
                        $resultVontantiOpzione = $conn->query($qryVontantiOpzione);

                        if($resultVontantiOpzione->num_rows > 0) {
                            while($row5 = $resultVontantiOpzione->fetch_assoc()) {
                                echo "   • ".$row5['nome'] . " " . $row5['cognome'] . " (" . $row5['mail'] . ")<br>";
                            }
                            echo "<br>";
                        }

                        echo "<button type=\"button\" class=\"collapsible\"><label class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</label></button>
                        <div class=\"content\">
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>";
                        // echo "• <label class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</label><br>";
                        
                        
                    } 
                    if($votazionePubblicata == 0 && !(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo))) {
                        echo "DATI IN ELABORAZIONE. VEDRAI I RISULTATI APPENA VERRANO PUBBLICATI.";
                    }
                } 

                // lista di chi non ha ancora votato
                $qryNonVotanti = "SELECT idUtente FROM esegue WHERE idVotazione LIKE '" . $_GLOBALS['idVotazione'] . "' AND hash IS NOT NULL";
                $resultNonVotanti = $conn->query($qryNonVotanti);

                if($resultNonVotanti->num_rows > 0) {
                    echo "Non votanti:<br>";
                    while($row = $resultNonVotanti->fetch_assoc()) {
                        $qryDatiNonVotanti = "SELECT nome, cognome, mail FROM utente WHERE id LIKE '" . $row['idUtente'] . "'";
                        $resultDatiNonVotanti = $conn->query($qryDatiNonVotanti);
                        
                        if($resultDatiNonVotanti->num_rows == 1) {
                            $row2 = $resultDatiNonVotanti->fetch_assoc();
                            echo "• <label class=\"testo\">" . $row2['nome'] . " " . $row2['cognome'] . " - " . $row2['mail'] . "</label><br>";
                        } else {
                            echo "ERRORE: utente non trovato 1";
                        }
                    }
                    echo "<br>";
                } else {
                    echo "<br>Hanno votato tutti.<br><br>";
                }

                // Pulsante pubblica solo quando la votazione è chiusa
                $qryTempo = "SELECT fine FROM votazione WHERE id LIKE '" . $_GLOBALS['idVotazione'] . "'";
                $resultTempo = $conn->query($qryTempo);
                
                if($resultTempo->num_rows == 1) {
                    $row = $resultTempo->fetch_assoc();
                    $tempoFine = $row['fine'];
                } else {
                    $_SESSION['errore'] = "ERRORE: votazione non valida";
                }

                
                // calcoli per il tempo
                $dataCorrente = date("Y-m-d h:i:s");

                if($dataCorrente > $tempoFine) {
                    if($pubblica == 0) {
                        if(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo)) {
                        // chiamata con php self e il method post --> pubblica risultati della votazione
                        echo '<form style="display: inline-block" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                        echo "<input type=\"submit\" name=\"vota\" value=\"pubblica risultati votazione\">
                                <input type='hidden' name='id' value='".$_GLOBALS['idVotazione']."'>
                                </form>";
                        }
                    } else {
                        echo "RISULTATI PUBBLICATI";
                    }
                }
                
                $conn->close();    
            ?>
        </div>
    </div>
</body>
</html>