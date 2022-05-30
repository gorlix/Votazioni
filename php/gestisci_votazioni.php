<!DOCTYPE html>
<html lang="it">
<head>
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
				
				if ($_SERVER["REQUEST_METHOD"] == "POST") 
				{
					if(isset($_POST['crea']))  
					{
						$content = "<form method='post'> 
									Quesito:<input type='text' name='modifica' value='' required><br>
									<select name='tipo'>	
										<option name='tipo' value='anonimo'>anonimo</option>\n
										<option name='tipo' value='nominale'>nominale</option>\n
									</select><br>
									Data e ora inizio:<input type='text' name='inizio' value=''required><br>
									Data e ora fine:<input type='text' name='tipo' value=''required><br>
									Numero di selte max:<input type='text' name='tipo' value=''required><br>
									
									<br><input type='submit' name='invia' value='salva'><br>

									</form>";
									
									
						echo $content;
						
						if($_POST['invia'] == 'salva'){
							$sql = "Insert into votazione (quesito, tipo, inizio, fine, scelteMax) values ()";
						
						}
						
						if(isset($_POST['invia'])){
							$query = "INSERT INTO votazione (quesito, tipo, inizio, fine, scelteMax) values ()";
							$ris=$conn->query($query);
						}
						
					}
					if(isset($_POST['modifica']))  
					{
						echo "modifica";
					}
					if(isset($_POST['cancella']))  
					{
						echo "cancella";
					}
				}else{
					$content = "<h3>OPERAZIONE VOTAZIONE </h3>
								<br>
						
								<p>Nome della votazione</p>".
						
								'<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
							
								"<select name='quesito'>";

					$query = "SELECT  id, quesito FROM votazione /*ORDER BY */";
					$ris=$conn->query($query);
					if ($ris->num_rows > 0) {
						while($row = $ris->fetch_assoc()) {
							$quesito = $row["quesito"];
							$id = $row["id"];
							// $altro = $row["altro"];
							$content.= "<option name='quesito' value='$id'>$quesito</option>\n";
						}
					}
					$content.="</select><br><input type='submit' name='crea' value='Crea votazione'/><br>
								<br><input type='submit' name='modifica' value='Modifica votazione'/><br>
								<br><input type='submit' name='cancella' value='Cancella votazione'/><br>
								<br><input type='submit' name='gestisci' value='Gestisci opzione'/><br>
								</form>";
					echo $content;
				}
			?>
			
		</center>
        </div>
    </div>
</body>
</html>