<!doctype html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Votazione</title>
    <?php
    require __DIR__. '/SharedFunctions.php';
    ?>
    <?php
    session_start();
    $_SESSION['loginpageError'] = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        if(valida_mail($mail)){
            if (check_login(connettiDb(), $mail, $password)) {
                $_SESSION['id_utente'] = getIdUtente($mail);
                header("Location: home.php");
            } else {
                $_SESSION['loginpageError'] = "Wrong mail or password!";
            }
        }else
            $_SESSION['loginpageError'] = "Wrong mail format!";
    }
    ?>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
    </div>
    <div class="titolo">
        <p class="titolo-header">Login</p>
    </div>
    <div class="contenuto">
        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post'>
            <h2>Benvenuto nel sistema votazioni !</h2>
            <h1>Mail</h1>
            <input type="text" name="mail" id="mail">
            <h1>Password</h1>
            <input type="password" name="password" id="password">
            <br>
            <input style="margin-top: 20px" type="submit" value="Login">
            <h3><?php echo $_SESSION['loginpageError']?></h3>
        </form>
    </div>
</div>
</body>
</html>