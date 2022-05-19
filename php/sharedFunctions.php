<?php
/*
 * Funzioni Databse
 */

/*
 * Connessione al Database
 */
function connettiDb(){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "votazioniScolastiche";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
/*
 * Verifica Login
 * funzione per verificare la correttezza delle credenziali (mail e password) inserite
 */
function check_login($conn, $mail, $password){
    $valido = false;
    $password = hash_password($password);
    $sql = "SELECT * FROM utente WHERE mail = '$mail' AND pw = '$password'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
        $valido = true;
    $conn->close();
    return $valido;
}

/*
 * hash password tipo sha1
 */
function hash_password($password) {
    return sha1($password);
}

/*
 * funzioner per verificare il formato valido della mail inserita
 */
function valida_mail($mail) {
    $valida = true;
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
        $valida = false;
    return $valida;
}
?>