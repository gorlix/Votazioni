<!doctype html>
<head>
    <title>Login</title>
    <?php
    require DIR . '/SharedFunction.php';
    ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $password = hash_password($password);
        if(valida_mail($mail)){
            if (check_login(connettiDb(), $mail, $password)) {
                header("Location: home.php");
            } else {
                echo "Wrong mail or password!";
            }
        }else
            echo "Wrong mail format!";
    }
    function hash_password($password) {
        return sha1($password);
    }
    function connettiDb(){
        $servername = "localhost";
        $username = "root";
        $password = "root";
        $dbname = "votazioniScolastiche";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }
    function valida_mail($mail) {
        $valida = true;
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
            $valida = false;
        return $valida;
    }
    function check_login($conn, $mail, $password){
        $sql = "SELECT * FROM utente WHERE mail = '$mail' AND pw = '$password'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
    ?>
</head>
<body>
<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post'>
    <h4>Mail</h4>
    <input type="text" name="mail" id="mail">
    <h4>Password</h4>
    <input type="password" name="password" id="password">
    <input type="submit" value="Login">
</form>
</body>
</html>