<form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>' method='post'>
    <h1>Scrivi una stringa per generare l'hash (SHA1)</h1>
    <input type="text" name="car" id="car">

</form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo sha1($_POST["car"]);
}