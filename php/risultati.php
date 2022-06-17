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
                                    $votazioneAperta = $row4['pubblica'];

                                    if(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo) || $votazioneAperta == 1) {
                                        $mediaVot = "- " . round((100 * $nVoti) / $totVoti, 1) . "% - Numero voti: " . $nVoti." / " . $totVoti;
                                    } 
                                }
                            } else {
                                $_SESSION['errore'] = "ERRORE: votazione non trovata";
                            }
                        }

                        if($_SESSION['numScelte'] == 1) {
                            echo "<input type=\"radio\" name=\"opzione[]\" value=\"" . $row['id'] . "\" " . $attScelta . ">
                                <label class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</label><br><br>";
                        } else {
                            $_GLOBALS['numOpz']++;
                            echo "<input name=\"opzione[]\" type=\"checkbox\" value=\"" . $row['id'] . "\" " .$attScelta . ">
                                <a class=\"testo\">" . $row['testo'] . " " . $mediaVot . "</a><br><br>";
                        }
                    } 
                    if($votazioneAperta == 0 && !(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo))) {
                        echo "DATI IN ELABORAZIONE. VEDRAI I RISULTATI APPENA VERRANO PUBBLICATI.";
                    }
                } 

                if(in_array(GRUPPO_ADMIN, $idGruppo) || in_array(GRUPPO_CREA_VOTAZIONI, $idGruppo)) {
                    // chiamata con php self e il method post --> pubblica risultati della votazione
                    echo '<form style="display: inline-block" method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';
                    echo "<input type=\"submit\" name=\"vota\" value=\"pubblica risultati votazione\">
                            <input type='hidden' name='id' value='".$_GLOBALS['idVotazione']."'>
                            </form>";
                }

                $conn->close();    
            ?>
        </div>
    </div>
</body>
</html>