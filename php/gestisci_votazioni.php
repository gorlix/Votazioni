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
				//ini_set('display_errors', 0);
				//ini_set('log_errors', 1);
				//session_start();
				
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
					//form di creazione del quesito, si genera se si schiaccia il tasto nel form 
					//principale name='crea'
					if(isset($_POST['crea']))  
					{
						
						$content = "".'<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
											"Quesito:<input type='text' name='quesito' value='' required><br>
											tipo:<select name='tipo'>	
												<option name='tipo' value='anonimo'>anonimo</option>\n
												<option name='tipo' value='nominale'>nominale</option>\n
											</select><br>
											Data e ora inizio:<input type='datetime-local' name='inizio' value=''required><br>
											Data e ora fine:<input type='datetime-local' name='fine' value=''required><br>
											Numero di selte max:<input type='number' name='scelteMax' value='' required min='1' ><br>
											
											<br><input type='submit' name='invia' value='salva'><br>
									</form>";
									
						echo $content;
					}
					
					//aggiunge il quesito del db
					else if(isset($_POST['invia'])){
						
						$inizio = $_POST['inizio'];
						$fine = $_POST['fine'];
						
						$sec = strtotime($inizio);  
						$inizio = date ("Y-m-d H:i", $sec);  
						$inizio .= ":00"; 
						
						$sec = strtotime($fine);  
						$fine = date ("Y-m-d H:i", $sec);  
						$fine .= ":00"; 
						
						$sql = "Insert into votazione (quesito, tipo, inizio, fine, scelteMax, quorum)
								values ('".$_POST['quesito']."','".$_POST['tipo']."','".$inizio."' ,'".$fine."' ,'".$_POST['scelteMax']."', '0')";
						
						if ($conn->query($sql) === TRUE) {
						  echo "New record created successfully";
						} else {
						  echo "Error: " . $sql . "<br>" . $conn->error;
						}
					} 
					
					//form di modifica del quesito, si genera se si schiaccia il tasto nel form 
					//principale name='modifica'
					else if(isset($_POST['modifica']))  
					{
						echo $_POST['quesito'];
						
						$sql = "SELECT quesito, tipo, inizio, fine, scelteMax FROM votazione where id = '".$_POST['quesito']."'";
						$result = $conn->query($sql);
												/*if ($result === TRUE) 
													echo "ecco";
												else
													echo "ciao";*/
						if ($result->num_rows > 0) {
							while($row = $result->fetch_assoc()) {
								$content = "<br>".'<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
											"Quesito:<input type='text' name='quesito' value='".$row['quesito']."' required><br>
											tipo:<select name='tipo'>";
											
								if (strcmp($row['tipo'], "anonimo") === 0) {
									$scelta1 = "<option name='tipo' value='anonimo'>anonimo</option>\n";
									$scelta2 = "<option name='tipo' value='nominale'>nominale</option>\n";
								} else {
									$scelta2 = "<option name='tipo' value='anonimo'>anonimo</option>\n";
									$scelta1 = "<option name='tipo' value='nominale'>nominale</option>\n";
								}
								$inMod = str_replace(" ","T",$row['inizio']);
								$fiMod = str_replace(" ","T",$row['fine']);
									
								$content .= $scelta1.$scelta2."</select><br>
											<input hidden type='text' name='id' value='".$_POST['quesito']."' required>
											Data e ora inizio:<input type='datetime-local' name='inizio' value='".$inMod."' required><br>
											Data e ora fine:<input type='datetime-local' name='fine' value='".$fiMod."'required><br>
											Numero di selte max:<input type='number' name='scelteMax' value='".$row['scelteMax']."' required min='1' ><br>
											
											<br><input type='submit' name='update' value='salva'><br>
									</form>";
							}
						} else {
						  echo "0 results";
						}
						
						echo $content;
					}			
					
					//modifica il quesito scelto
					else if(isset($_POST['update']))
					{
						echo $_POST['inizio'];
						
						$sql = "UPDATE votazione SET quesito = '".$_POST['quesito']."', tipo = '".$_POST['tipo']."',
								inizio = '".$_POST['inizio']."', fine = '".$_POST['fine']."', scelteMax = '"
								.$_POST['scelteMax']."' where id = '".$_POST['id']."'";
								
						if ($conn->query($sql) === TRUE) {
						  echo "Record updated successfully";
						} else {
						  echo "Error updating record: " . $conn->error;
						}
					}
					/////////
					else if(isset($_POST['cancella']))  
					{
						echo "cancella";
					}
					// reinderizza alla pagina gestisci opzione
					else if(isset($_POST['gestisci']))  
					{
						$_SESSION["idVotazione_Opzione"] = $_POST['quesito'];
						header("location: gestisci_opzione.php");
					}
				} else {
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
				$_sessio
			?>
			
		</center>
        </div>
    </div>
</body>
</html>