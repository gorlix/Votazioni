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
        <?php stampaSelezioneUser();?>
        <br>
        <br>
        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>' method='post'>
            <input type='submit' name='submit' value='Crea utente'>
            <input type='submit' name='submit' value='Modifica utente'>
            <input type='submit' name='submit' value='Elimina utente'>
        </form>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST"){
                $aus = $_POST["submit"];
                if($aus  == "Crea utente"){
                    stampaFormCreazioneUtente();
                }else if($aus  == "Modifica utente"){
                    $mail_selectded = $_POST["mail_inpt"];
                    stampaFormModificaUtente($mail_selectded);
                }else if($aus == "Elimina utente"){
                    $mail_selectded = "";
                    eliminaUtente($mail_selectded);
                }else if($aus == "SalvaModificaUtente"){
                    $new_pw = "";
                    $new_nome = "";
                    $new_cognome = "";
                    $new_mail = "";
                    $mail_selectded = "";
                    modificaUtente($new_pw, $new_nome, $new_cognome, $new_mail, getIdUtente(connettiDb(), $mail_selectded));
                }else if($aus == "SalvaCreazioneUtente"){
                    $new_pw = $_POST["pw_inpt"];
                    $new_nome = $_POST["nome_inpt"];
                    $new_cognome = $_POST["cognome_inpt"];
                    $new_mail = $_POST["mail_inpt"];
                    creaUtente($new_pw, $new_nome, $new_cognome, $new_mail);
                }else if ($aus == "Aggiungi al gruppo"){
                    $id_User = "";
                    $id_Group = "";
                    aggiungiUntenteAlGruppo($id_User, $id_Group);
                }else if($aus == "Rimuovi dal gruppo"){
                    $id_User = "";
                    $id_Group = "";
                    rimuoviUtenteDalGruppo($id_User, $id_Group);
                }
                //echo $aus, $_POST["submittedUsr"];
            }
        ?>

        <?php
        echo "<h2>Gestione gruppi utente</h2><h3>Seleziona gruppo</h3>";
        $str = "<form action='$_SERVER[PHP_SELF]' method='post'><select name='nome'>";
        $str .= "<option value='0'></option>";
        $query = "SELECT id, nome FROM gruppo";
        $conn = connettiDb();
        $ris=$conn->query($query);
        if ($ris->num_rows > 0) {
            while($row = $ris->fetch_assoc()) {
                $str .= "<option value='".$row["id"]."'>".$row["nome"]."</option>";
            }
        }
        $str.="</select>
            <br><br>
            <input type='submit' name='submit' value='Aggiungi al gruppo'>
            <input type='submit' name='submit' value='Rimuovi dal gruppo'><br><br>
        </form>";
        echo $str;
        //stampaGruppi("mail.prova@mail.com");
        ?>
    </div>
</div>
</body>
</html>

<?php
function stampaGruppi($mail_selected){
    echo "<h4>Gruppi già associati</h4>";
    echo "Mail: ".$mail_selected."<br><br>";
    $conn = connettiDb();
    $str = "<table style='border: 1px solid black'><tr style='border: 1px solid black'><th style='border: 1px solid black'>Nome</th></tr>";

    $query = "SELECT nome FROM gruppo g INNER JOIN appartienea app ON app.idGruppo = g.id WHERE app.idUtente = (SELECT id FROM utente WHERE mail = '$mail_selected')";
    $conn = connettiDb();
    //echo $query;
    $ris = $conn->query($query);
    if ($ris->num_rows > 0) {
        while($row = $ris->fetch_assoc()) {
            $str .= "<tr style='border: 1px solid black'><td style='border: 1px solid black'>".$row["nome"]."</td></tr>";
        }
    }
    $str.="</table>";
    echo $str;
}

function stampaFormCreazioneUtente(){
    echo "<form action='$_SERVER[PHP_SELF]' method='post'><br><br>
            <h2>Crea utente</h2>
            <label>Mail</label>
                <input type='text' name='mail_inpt' required>
            <label>Password</label>
                <input type='text' name='pw_inpt' required><br><br>
            <label>Nome</label>
                <input type='text' name='nome_inpt' required>
            <label>Cognome</label>
                <input type='text' name='cognome_inpt' required><br><br>
            <input type='submit' name='submit' value='SalvaCreazioneUtente'>
         </form>";
}

function stampaFormModificaUtente($mail_selected){
    echo "<form action='$_SERVER[PHP_SELF]' method='post'><br><br>
            <h2>Modifica utente</h2>
            <label>Mail</label>
                <input type='text' name='mail_inpt' value='$mail_selected'>
            <label>Password</label>
                <input type='text' name='pw_inpt'><br><br>
            <label>Nome</label>
                <input type='text' name='nome_inpt'>
            <label>Cognome</label>
                <input type='text' name='cognome_inpt'><br><br>
            <input type='submit' name='submit' value='SalvaModificaUtente'>
         </form>";
}

function stampaSelezioneUser(){
    echo "<h1>Operazioni utente</h1>";
    echo "<h3>Seleziona utente </h3>";
    $str = "<form id='frmSelUsr' action='$_SERVER[PHP_SELF]' method='post'>";
    $str .= "<select name='submittedUsr' id='drpUsr'>";
    $str .= "<option value='0'></option>";
    $query = "SELECT id, mail FROM utente";
    $conn = connettiDb();
    $ris = $conn->query($query);
    if ($ris->num_rows > 0) {
        while ($row = $ris->fetch_assoc()) {
            $str .= "<option value='" . $row["id"] . "'>" . $row["mail"] . "</option>";
        }
    }
    $str .= "</select><input type='submit' id='btnSubDrpSelUsr' hidden name='submit' value='subButton'></form>";
    echo $str;
}

function eliminaUtente($mail_selected){
    //Eliminazione utente dal DB
    $conn = connettiDb();
    $query = "DELETE FROM utente WHERE mail = '$mail_selected'";
    $ris = $conn->query($query);
    if (!$ris) {
        echo "Errore eliminazione utente";
    }
    $conn->close();
}

function modificaUtente($new_pw, $new_nome, $new_cognome, $new_mail, $id_utente){
    //Modifica utente nel DB
    $conn = connettiDb();
    $query = "UPDATE utente SET password = '$new_pw', nome = '$new_nome', cognome = '$new_cognome', mail = '$new_mail' WHERE id = '$id_utente'";
    $ris = $conn->query($query);
    if (!$ris) {
        echo "Errore modifica utente";
    }
}

function creaUtente($pw, $nome, $cognome, $mail){
    //Creazione utente nel DB
    $hashPw = hash_password($pw);
    $conn = connettiDb();
    $lock = "LOCK TABLES utente";
    $conn->query($lock);
    $startTrans = "START TRANSACTION";
    $conn->query($startTrans);
    $esisteUtente = "SELECT id from utente WHERE mail = '$mail'";
    $ris = $conn->query($esisteUtente);
    if ($ris->num_rows == 0) {
        $query = "INSERT INTO utente (pw, nome, cognome, mail, forzaModificaPW) VALUES ('$hashPw', '$nome', '$cognome', '$mail', 0)";
        $ris = $conn->query($query);
        if (!$ris === TRUE) {
            echo "Errore creazione utente 1";
            $rollBack = "ROLLBACK";
            $conn->query($rollBack);
        }
    }
    $commit = "COMMIT";
    $conn->query($commit);
    $unlock = "UNLOCK TABLES";
    $conn->query($unlock);
}

function aggiungiUntenteAlGruppo($id_User, $id_Group){
    //Aggiungi utente al gruppo
    $conn = connettiDb();
    $query = "INSERT INTO appartienea (idUtente, idGruppo) VALUES ('$id_User', '$id_Group')";
    $ris = $conn->query($query);
    if (!$ris === TRUE) {
        echo "Errore aggiunta utente al gruppo";

    }
}

function rimuoviUtenteDalGruppo($id_User, $id_Group){
    //Rimuovi utente dal gruppo
    $conn = connettiDb();
    $query = "DELETE FROM appartienea WHERE idUtente = '$id_User' AND idGruppo = '$id_Group'";
    $ris = $conn->query($query);
    if (!$ris === TRUE) {
        echo "Errore rimozione utente dal gruppo";
    }
}