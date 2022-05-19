<!doctype html>
<head>
    <title>Login</title>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $mail = $_POST['mail'];
        $password = $_POST['password'];
        $password = hash_password($password);
        if (check_login($mail, $password)) {
            header("Location: home.php");
        } else {
            echo "Wrong mail or password!";
        }
    }
    function hash_password($password) {
        return sha1($password);
    }
    function check_login($mail, $password) {
        return ($mail == "admin" && $password == hash_password("admin"));
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