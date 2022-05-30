<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Gestisci Gruppo</title>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Gestisci Gruppo</p>
        </div>
        <?PHP
            //include "Navbar.php";
        ?>
        <div class="contenuto">
                <?php
                    require __DIR__ . '/SharedFunctions.php';


                        if(isset($_POST['Elimina']))
                        {
                            $id = $_POST['Elimina'];
                            $query = "DELETE FROM gruppo WHERE id = $id";
                            $conn = connettiDb();
                            $conn->query($query);
                            $conn -> close();
                        }
                        if(isset($_POST['Crea']))
                        {
                            $nome = $_POST['Crea'];
                            echo $query = "INSERT INTO gruppo(nome) value ('$nome')";
                            $conn = connettiDb();
                            $conn->query($query);
                            $conn -> close();
                        }

                        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' "method="post">';
                        $var = "<label for='nome''>Elimina Gruppo: </label>";
                        $var .= "<select name='Elimina'>";

                        $query = "SELECT  id, nome FROM gruppo";
                        $conn = connettiDb();
                        $ris=$conn->query($query);
                        if ($ris->num_rows > 0) {
                            while($row = $ris->fetch_assoc()) {
                                $quesito = $row["nome"];
                                $id = $row["id"];
                                $var .= "<option value='$id' name='Elimina'>$quesito</option>";
                            }
                        }
                        $var .= "</select>";
                        echo $var;
                        echo "\n" . "<button type='submit' >Elimina Gruppo</button>";
                        echo "</form>";

                        echo '<form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' "method="post">';
                        $var = "<label for='Crea'>Crea Gruppo: </label>";
                        $var .= "<input type='text' name='Crea' id='CreaGruppo'>";
                        echo $var;
                        echo "\n<button type='submit'>Crea Gruppo</button>";
                        echo "</form>";

                ?>
        </div>
    </div>
</body>
</html>
