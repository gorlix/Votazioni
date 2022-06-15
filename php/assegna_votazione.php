<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Assegna</title>

</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Lista Votazioni</p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <center>
            <h1 style="align:center; font-family: 'Roboto Mono', monospace;font-family: 'Space Mono', monospace;">Lista Votazioni</h1>
			<?php
				//ini_set('display_errors', 0);
				//ini_set('log_errors', 1);
				//session_start();
				
				$server = "127.0.0.1";
				$username = "root";
				$password = "";
				$dbName = "votazioniscolastiche";
				
				$sql = "";
				$tab;

				$conn = new mysqli($server, $username, $password, $dbName);

				if ($conn->connect_error) {
					die("Connection failed: " . $conn->connect_error);
				}
				
				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					//Assegna 
				}

			?>
			
		</center>
        </div>
    </div>
</body>
</html>