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
            <p class="titolo-header">Gestisci Votazioni</p>
        </div>
        <?PHP
			//require __DIR__. '/SharedFunctions.php';
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <center>
            <h1 style="align:center; font-family: 'Roboto Mono', monospace;font-family: 'Space Mono', monospace;">Lista Votazioni</h1>
			<?php
				
				$conn = connettiDb();

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
					else if(isset($_POST['invia']))
					{
						
						$inizio = $_POST['inizio'];
						$fine = $_POST['fine'];
						
						$sec = strtotime($inizio);  
						$inizio = date ("Y-m-d H:i", $sec);  
						$inizio .= ":00"; 
						
						$sec = strtotime($fine);  
						$fine = date ("Y-m-d H:i", $sec);  
						$fine .= ":00";


                        $sql = "UNLOCK TABLES votazione";
                        $conn->query($sql);

                        $sql = "LOCK TABLES votazione";
                        if($conn->query($sql) === TRUE) {

                            //Transazione
                            $sql = "BEGIN TRANSACTION";
                            echo $conn->query($sql);

                            //Inserisci Votazione
                            $sql = "Insert into votazione (quesito, tipo, inizio, fine, scelteMax, quorum)
                                values ('" . $_POST['quesito'] . "','" . $_POST['tipo'] . "','" . $inizio . "' ,'" . $fine . "' ,'" . $_POST['scelteMax'] . "', '0')";

                                if ($conn->query($sql) === TRUE) {
                                    echo "New record created successfully";

                                    $sql = "SELECT MAX(id) from votazione";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        $row = $result->fetch_assoc();
                                        $idInsert = $row["id"];
                                        //Commit Transaction
                                        $sql = "COMMIT";
                                        $conn->query($sql);
                                        //End Transaction
                                        $sql = "END TRANSACTION";
                                        $conn->query($sql);
                                        
                                        $sql = "UNLOCK TABLES votazione";
                                        $conn->query($sql);

                                    } //Getting max ID
                                    else
                                    {
                                        echo "Error: " . $sql . "<br>" . $conn->error;
                                        //Rollbakc transaction
                                        $sql = "ROLLBACK";
                                        $conn->query($sql);
                                        //END Transaction
                                        $sql = "END TRANSACTION";
                                        $conn->query($sql);
                                    }
                                    //header("Location: ");
                                } //Insert Votazione
                                else
                                {
                                    echo "Error: " . $sql . "<br>" . $conn->error;
                                }
                        } //Lock Table
                        else
                        {
                            $sql = "UNLOCK TABLES votazione";
                            $conn->query($sql);
                            //tabella gia bloccata
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
					//cancella il quesito selezionato 
					else if(isset($_POST['cancella']))  
					{
						$sql = "DELETE FROM votazione WHERE id='".$_POST['quesito']."'";

						if ($conn->query($sql) === TRUE) {
						  echo "Record deleted successfully";
						} else {
						  echo "Error deleting record: " . $conn->error;
						}
					}
					
					// reinderizza alla pagina gestisci opzione
					else if(isset($_POST['gestisci']))  
					{
						$_SESSION["idVotazione_Opzione"] = $_POST['quesito'];
						header("location: gestisci_opzione.php");
					}
					// pagina gestione della votazione
					else if(isset($_POST['gestisciG']))  
					{
						$query = "select quesito from votazione WHERE id = " . $_POST['quesito'];
						$result = $conn->query($query);
						
						
						while($row = $result->fetch_assoc()){
							//$conn->close();
							echo "<center>
									<p class='titoli'>GESTIONE GRUPPI DEL QUESITO:</p>
									<p class='titoli'>" . $row['quesito'] . "</p>
								</center>";
						}
						
						$content = "".'<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
									"Gruppo:<select name='gruppo'>";
						$query = "SELECT  id, nome FROM gruppo";
						$ris=$conn->query($query);
						
						if ($ris->num_rows > 0) {
							while($row = $ris->fetch_assoc()) {
								$nome = $row["nome"];
								$id = $row["id"];
								// $altro = $row["altro"];
								$content.= "<option name='gruppo' value='$id'>$nome</option>\n";
							}
						}
						$content.="</select>.
								<input hidden type='text' name='idVotazione' value='".$_POST['quesito']."' >
								<br><br><input type='submit' name='aggiungiG' value='Aggiungi'/><br>
								<br><input type='submit' name='rimuoviG' value='Rimuovi gruppo'/><br>
								</form>";
						echo $content;
					}

					//aggiunge tutti gli utenti del gruppo alla votazione
					else if(isset($_POST['aggiungiG']))  
					{
						$query = "SELECT  idUtente FROM appartienea where idGruppo=".$_POST['gruppo'];
						$ris=$conn->query($query);
						
						if ($ris->num_rows > 0) 
						{
							while($row = $ris->fetch_assoc()) 
							{
								
								$idUtente = $row["idUtente"];
								//genera l'hash con idUtente + idVotazione
								$stringhash = $idUtente.$_POST['idVotazione'];
								$inserisci = "Insert into esegue (idUtente, idVotazione, hash)
									values ('".$idUtente."','".$_POST['idVotazione']."','".hash_password($stringhash)."')";
								if ($conn->query($inserisci) === TRUE) 
								{
	
								  echo "New record created successfully";
								}else{
									echo "Error: " . $query . "<br>" . $conn->error;
								}
							}
						}
					}
					//rimuovi tutti gli utenti appartenenti a quel gruppo
					else if(isset($_POST['aggiungiG']))  
					{
						
					}
					echo "".'<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
								"<br><input type='submit' name='' value='Ritorna alla scelta votazione'/><br>
							</form>"; 
				}else 
				{
					$content = "<h3>OPERAZIONE VOTAZIONE </h3>
								<br>
						
								<p>Nome della votazione</p>".
						
								'<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
							
								"<select name='quesito'>";

					$query = "SELECT  id, quesito FROM votazione";
					$ris=$conn->query($query);
					
					if ($ris->num_rows > 0) {
						while($row = $ris->fetch_assoc()) {
							$quesito = $row["quesito"];
							$id = $row["id"];
							// $altro = $row["altro"];
							$content.= "<option name='quesito' value='$id'>$quesito</option>\n";
						}
					}
					$content.="</select>.
								<br><input type='submit' name='crea' value='Crea votazione'/><br>
								<br><input type='submit' name='modifica' value='Modifica votazione'/><br>
								<br><input type='submit' name='cancella' value='Cancella votazione'/><br>
								<br><input type='submit' name='gestisci' value='Gestisci opzione'/><br>
								<br><input type='submit' name='gestisciG' value='Gestisci gruppo'/><br>
								</form>";
					
					echo $content;
				}

			?>
			
		</center>
        </div>
    </div>
</body>
</html>