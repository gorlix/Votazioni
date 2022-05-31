<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Gestisci Opzione</title>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Gestisci Opzione</p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <!--Contenuto della pagine qui sotto-->


            <?php
                //session_start();
                $server = "localhost";
			    $username = "root";
			    $password = "";
			    $dbName = "votazioniscolastiche";
                $idVotazione = 3;

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                if($_SERVER["REQUEST_METHOD"] == "POST"){  
                    if($_POST["operazione"] == 1){
                        $opzione = $_POST['opzione'];
                        //$idVotazione = $_POST['id'];
                        $query = "insert into opzione(testo,idVotazione) values ('" . $opzione . "', '" . $idVotazione . "')";
                        $conn->query($query);
                        /*$result = $conn->query($query);
                        $row = $result->fetch_assoc();*/
                    
                    }else if($_POST["operazione"] == 2){
                        $testo = $_POST["testo"];
                        $idOpzione = $_POST["idOperazione"];
                        //echo "allert('" . $testo . "')";

                        $query = "UPDATE opzione SET testo = '" . $testo . "' WHERE id = " . $idOpzione;
                        $conn -> query($query);
                    }else if($_POST["operazione"] == 3){
                        $idOpzione = $_POST["idOperazione"];

                        $query = "DELETE FROM opzione WHERE id = " . $idOpzione;
                        $conn -> query($query);

                    }
                         //echo "ciaooooooooooo giggggiii";
                   
    
                }

                /*if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $idVotazione = $_POST["quesito"];
                }*/

                //$query = "select quesito from votazione WHERE votazione = " . $idVotazione;
                //$result = mysqli_query($conn,$query);
                //$row=mysqli_fetch_assoc($result);

                $query = "select quesito from votazione WHERE id = " . $idVotazione;
                $result = $conn->query($query);
                
                
                while($row = $result->fetch_assoc()){
                    //$conn->close();
                    echo "<center>
                            <p class='titoli'>AGGIUNGI OPZIONI AL QUESITO:</p>
                            <p class='titoli'>" . $row['quesito'] . "</p>
                        </center>";
                }
                    echo '<form method="post" action= "' . htmlspecialchars($_SERVER["PHP_SELF"]).'">';
                    echo "<center><input type='text' name = 'opzione'></center><br>";
                    echo "<center><input type='hidden' name = 'operazione' value=1></center><br>";
                    //echo "<center><input type='hidden' name='id' value=" . $idVotazione . "/></center>";
                    echo "<center><input type='submit' name='crea' value='Aggiungi opzione'/></center>";
                    echo "</form>";
                    echo "<br><br><br><br><br>";
                

                $query = "select testo, id FROM opzione WHERE idVotazione = " . $idVotazione;
                $result = $conn->query($query);

                while($row = $result->fetch_assoc()){
                    echo "<center>";
                    echo '<form method="post" action= "' . htmlspecialchars($_SERVER["PHP_SELF"]).'">';
                    echo "<input type='text' name = 'testo' value='" . $row["testo"] . "'>";
                    echo "<input type='hidden' name = 'operazione' value=2>";
                    echo "<input type='hidden' name = 'idOperazione' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='modifica'>";
                    echo "</form>";
                    //echo '</form>';
                    echo '<form method="post" action= "' . htmlspecialchars($_SERVER["PHP_SELF"]).'">';
                    echo "<input type='hidden' name = 'operazione' value=3>";
                    echo "<input type='hidden' name = 'idOperazione' value='" . $row["id"] . "'>";
                    echo "<input type='submit' value='cancella'>";
                    echo '</form>';
                    echo "</center>";
                    echo "<br>";
                }
            //$conn->close();
            ?>


        </div>
    </div>
</body>
</html>
