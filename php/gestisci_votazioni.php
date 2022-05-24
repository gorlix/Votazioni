<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Votazione</title>
    <script>
        jQuery(document).ready(function($){
            $(".clickable-row").click(function(){
                window.location = $(this).data("href");
            });
        });
    </script>
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
			$server = "localhost";
			$username = "root";
			$password = "";
			$dbName = "votazioniscolastiche";
			$sql = "";
			$tab;


			$conn = new mysqli($server, $username, $password, $dbName);

			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}


			$content = "<h3>OPERAZIONE VOTAZIONE </h3>
						<br>
						
						<p>Nome della votazione</p>
						
						<form action = '', method = 'POST'> 
							
						<select name='quesito'>";

			$query = "SELECT  id, quesito FROM votazione /*ORDER BY */";
			$ris=$conn->query($query);
			if ($ris->num_rows > 0) {
				while($row = $ris->fetch_assoc()) {
					$quesito = $row["quesito"];
					$id = $row["id"];
				// $altro = $row["altro"];
				$content.= "<option value='$id'>$quesito</option>\n
								</select>
								<input type = 'submit'>";
			  }
			}
			echo $content;
		?>
		</center>
        </div>
    </div>
</body>
</html>