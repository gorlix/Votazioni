<!doctype html>
<head>
    <title>Login</title>
    <?php
    require __DIR__. '/SharedFunctions.php';
    ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        if(valida_mail($mail)){
            if (check_login(connettiDb(), $mail, $password)) {
                header("Location: home.php");
            } else {
                echo "Wrong mail or password!";
            }
        }else
            echo "Wrong mail format!";
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