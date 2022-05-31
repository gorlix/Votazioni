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
                $idVotazione = 1;

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                /*if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $idVotazione = $_POST["quesito"];
                }*/

                //$query = "select quesito from votazione WHERE votazione = " . $idVotazione;
                //$result = mysqli_query($conn,$query);
                //$row=mysqli_fetch_assoc($result);

                $query = "select quesito from votazione WHERE id = " . $idVotazione;
                $result = $conn->query($query);
                $row = $result->fetch_assoc();
                //$conn->close();

                echo "<center>
                        <p class='titoli'>AGGIUNGI OPZIONI AL QUESITO:</p>
                        <p class='titoli'>" . $row['quesito'] . "</p>
                    </center>";
                
                echo "<center><input type='text'></center><br>";
                echo "<center><input type='submit' name='crea' value='Aggiungi opzione'/></center>";
            
            ?>
        </div>
    </div>
</body>
</html>
