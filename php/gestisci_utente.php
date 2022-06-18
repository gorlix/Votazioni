<!doctype html>
<html lang="it">
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Gestisci Utente</title>

    <script>
        $(document).ready(function(){
            $("#drpUsr").change(function() {    
                $("#btnSubDrpSelUsr").click();
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
        <p class="titolo-header">Gestisci Utente</p>
    </div>
    <?PHP
    include "Navbar.php";
    ?>
    <div class="contenuto">
        <?php
            stampaSelezioneUser();
        ?>
        <br>
        <br>
        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>' method='post'>

            <input type='submit' name='submit' value='Crea utente'>
            <input type='submit' name='submit' value='Modifica utente'>
            <input type='submit' name='submit' value='Elimina utente'>
        </form>
        <?php
            if($_SESSION["user_selected"] != 0 && isset($_SESSION["user_selected"])){
	            echo "<h4>Utente selezionato: ".getMailUtente($_SESSION["user_selected"])."</h4>";
                gestisciRichiestePageGestisciUtente();
	            echo "<h2>Gestione gruppi utente</h2><h3>Seleziona gruppo</h3>";
	            $str = "<form action='$_SERVER[PHP_SELF]' method='post'><select name='id_gruppo'>";
	            $str .= "<option value='0'></option>";
	            $query = "SELECT id, nome FROM gruppo ORDER BY nome ASC"; 
	            $conn = connettiDb();
	            $ris = $conn->query($query);
	            if ($ris->num_rows > 0) {
		            while ($row = $ris->fetch_assoc()) {
			            $str .= "<option value='" . $row["id"] . "'>" . $row["nome"] . "</option>";
		            }
	            }
	            $str .= "</select>
            <br><br>
            <input type='submit' name='submit' value='Aggiungi al gruppo'>
            <input type='submit' name='submit' value='Rimuovi dal gruppo'><br><br>
        </form>";
	            echo $str;
                stampaGruppiUser();
            }else{
                gestisciRichiestePageGestisciUtente();
            }
        ?>
    </div>
</div>
</body>
</html>

<?php

function gestisciRichiestePageGestisciUtente() {
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$aus = $_POST["submit"];
		if($aus == "selUser"){
			$_SESSION["user_selected"] = $_POST["submittedUsr"];
            $_SESSION["ErrorGestioneUtente"] = "";
            refreshPage("gestisci_utente.php");
		}else if($aus  == "Crea utente"){
			stampaFormCreazioneUtente();
		}else if($aus  == "Modifica utente"){
			stampaFormModificaUtente($_SESSION["user_selected"]);
		}else if($aus == "Elimina utente"){
			if(eliminaUtente($_SESSION["user_selected"]))
			    refreshPage("gestisci_utente.php");
            else
                stampaErrore();
		}else if($aus == "Modifica"){
			//Da prendere
			$new_pw = $_POST["pw_inpt"];
			$new_nome = $_POST["nome_inpt"];
			$new_cognome = $_POST["cognome_inpt"];
			$new_mail = $_POST["mail_inpt"];
			if(modificaUtente($new_pw, $new_nome, $new_cognome, $new_mail, $_SESSION["user_selected"]))
			    refreshPage("gestisci_utente.php");
            else
                stampaErrore();
		}else if($aus == "Crea"){
			$new_pw = $_POST["pw_inpt"];
			$new_nome = $_POST["nome_inpt"];
			$new_cognome = $_POST["cognome_inpt"];
			$new_mail = $_POST["mail_inpt"];
			if(creaUtente($new_pw, $new_nome, $new_cognome, $new_mail))
			    refreshPage("gestisci_utente.php");
            else
                stampaErrore();
		}else if ($aus == "Aggiungi al gruppo"){
			$id_gruppo = $_POST["id_gruppo"];
			echo getNomeGruppo($id_gruppo);
			aggiungiUntenteAlGruppo($_SESSION["user_selected"], $id_gruppo);
			refreshPage("gestisci_utente.php");
		}else if($aus == "Rimuovi dal gruppo"){
			$id_gruppo = $_POST["id_gruppo"];
			if(rimuoviUtenteDalGruppo($_SESSION["user_selected"], $id_gruppo))
			    refreshPage("gestisci_utente.php");
            else
                stampaErrore();
		}
	}
}

function stampaErrore() {
    echo "<h6 style='color: red'>Errore: ".$_SESSION["ErrorGestioneUtente"]."</h6>";
}
function stampaGruppiUser(){
    echo "<h4>Gruppi già associati</h4>";
    $conn = connettiDb();
    $str = "<table style='border: 1px solid black'><tr style='border: 1px solid black'><th style='border: 1px solid black'>Nome</th></tr>";

    $query = "SELECT nome FROM gruppo g INNER JOIN appartienea app ON app.idGruppo = g.id WHERE app.idUtente = '$_SESSION[user_selected]'";
    $conn = connettiDb();
    $ris = $conn->query($query);
    if ($ris->num_rows > 0) {
        while($row = $ris->fetch_assoc()) {
            $str .= "<tr style='border: 1px solid black'><td style='border: 1px solid black'>".$row["nome"]."</td></tr>";
        }
	    $str.="</table>";
    }else{
        $str = "L'utente non appartiene a nessun gruppo.";
    }
    echo $str;
}

function stampaFormCreazioneUtente(){
    echo "<form action='$_SERVER[PHP_SELF]' method='post'><br><br>
            <h2>Crea utente</h2>
            <label>Mail</label>
                <input type='text' name='mail_inpt' required>
            <label>Password</label>
                <input type='password' name='pw_inpt' required><br><br>
            <label>Nome</label>
                <input type='text' name='nome_inpt' required>
            <label>Cognome</label>
                <input type='text' name='cognome_inpt' required><br><br>
            <input type='submit' name='submit' value='Crea'>
         </form>";
}

function stampaFormModificaUtente($id_user){
    $nome = getNomeUtente($id_user);
    $cognome = getCognomeUtente($id_user);
    $mail = getMailUtente($id_user);
    echo "<form action='$_SERVER[PHP_SELF]' method='post'><br><br>
            <h2>Modifica utente</h2>
            <label>Nome</label>
                <input type='text' name='nome_inpt' value='$nome' required>
            <label>Cognome</label>
                <input type='text' name='cognome_inpt' value='$cognome' required><br><br>
            <label>Mail</label>
                <input type='text' name='mail_inpt' value='$mail' required>
            <label>Password</label>
                <input type='password' name='pw_inpt'><br><br>
            <input type='submit' name='submit' value='Modifica'>
         </form>";
}

function stampaSelezioneUser(){
    echo "<h1>Operazioni utente</h1>";
    echo "<h3>Seleziona utente </h3>";
    $str = "<form id='frmSelUsr' action='$_SERVER[PHP_SELF]' method='post'>";
    $str .= "<select name='submittedUsr' id='drpUsr'>";
    $str .= "<option value='0'></option>";
    $query = "SELECT id, mail FROM utente ORDER BY mail ASC"; 
    $conn = connettiDb();
    $ris = $conn->query($query);
    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            $str .= "<option value='" . $row["id"] . "'>" . $row["mail"] . "</option>";
        }
    }
    $str .= "</select><input type='submit' id='btnSubDrpSelUsr' hidden name='submit' value='selUser'></form>";
    echo $str;
}

function eliminaUtente($id_user){
	$opValida = false;
    //Eliminazione utente dal DB
    $conn = connettiDb();
    $query = "DELETE FROM utente WHERE id = '$id_user'";
    $ris = $conn->query($query);
    if (!$ris) {
	    $_SESSION["ErrorGestioneUtente"] = "Errore eliminazione utente";
    }else
        $opValida = true;
    $conn->close();
    $_SESSION["user_selected"] = 0;
    return $opValida;
}

function modificaUtente($new_pw, $new_nome, $new_cognome, $new_mail, $id_utente){
    $modificato = false;
    //Modifica utente nel DB
    $conn = connettiDb();
    $ris = null;
    if(!mailEsistente($new_mail)) {
        if(valida_mail($new_mail)) {
	        if($new_pw != "") {
		        $new_pw = hash_password($new_pw);
		        $query = "UPDATE utente SET pw = '$new_pw', nome = '$new_nome', cognome = '$new_cognome', mail = '$new_mail' WHERE id = '$id_utente'";
	        } else{
		        $query = "UPDATE utente SET nome = '$new_nome', cognome = '$new_cognome', mail = '$new_mail' WHERE id = '$id_utente'";
	        }
	        $ris = $conn->query($query);
	        if (!$ris) {
		        $_SESSION["ErrorGestioneUtente"] = "modifica utente fallita.";
	        }else{
                $modificato = true;
            }
        }else{
	        $_SESSION["ErrorGestioneUtente"] = "Mail non valida reinserire una mail valida.";
        }
    }else {
	    $_SESSION["ErrorGestioneUtente"] = "Mail esistente scegliere un'altra mail.";
    }
    return $modificato;
}

function creaUtente($pw, $nome, $cognome, $mail){
   $creato = false;
    //Creazione utente nel DB
    $hashPw = hash_password($pw);
    $conn = connettiDb();
    //$lock = "LOCK TABLES utente";
    //$conn->query($lock);
    //$startTrans = "START TRANSACTION";
    //$conn->query($startTrans);
    if (!mailEsistente($mail)) {
        if(valida_mail($mail)){
	        $query = "INSERT INTO utente (pw, nome, cognome, mail, forzaModificaPW) VALUES ('$hashPw', '$nome', '$cognome', '$mail', 0)";
	        $ris = $conn->query($query);
	        if (!$ris === TRUE) {
		        $_SESSION["ErrorGestioneUtente"] = "creazione utente fallita";
		        //$rollBack = "ROLLBACK";
		        //$conn->query($rollBack);
	        }else{
		        $_SESSION["user_selected"] = getIdUtente($mail);
                $creato = true;
	        }
        }else{
	        $_SESSION["ErrorGestioneUtente"] = "Mail non valida reinserire una mail valida.";
        }
    }else{
        $_SESSION["ErrorGestioneUtente"] = "Mail esistente scegliere un'altra mail.";
    }
    //$commit = "COMMIT";
    //$conn->query($commit);
    //$unlock = "UNLOCK TABLES";
    //$conn->query($unlock);
    return $creato;
}

function aggiungiUntenteAlGruppo($id_User, $id_Group){
    $opValida = false;
    //Aggiungi utente al gruppo
    $conn = connettiDb();
    $query = "INSERT INTO appartienea (idUtente, idGruppo) VALUES ('$id_User', '$id_Group')";
    $ris = $conn->query($query);
    if (!$ris === TRUE) {
	    $_SESSION["ErrorGestioneUtente"] = "aggiunta utente al gruppo fallita";
    }else
        $opValida = true;
    return $opValida;
}

function rimuoviUtenteDalGruppo($id_User, $id_Group){
	$opValida = false;
    //Rimuovi utente dal gruppo
    $conn = connettiDb();
    $query = "DELETE FROM appartienea WHERE idUtente = '$id_User' AND idGruppo = '$id_Group'";
    $ris = $conn->query($query);
    if (!$ris === TRUE) {
        echo "rimozione utente dal gruppo fallita";
    }else
	    $opValida = true;
    return $opValida;
}

function refreshPage($nome_pagina) {
    header("Location: ".$nome_pagina);
}
?>