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
            include "Navbar.php";
        ?>
        <div class="contenuto">
                <?php
                    //require __DIR__ . '/SharedFunctions.php';
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
                            $conn = connettiDb();
                            $lock = "LOCK TABLES gruppo WRITE";
                            $conn->query($lock);
                            $startTrans = "START TRANSACTION";
                            $query = "SELECT id FROM gruppo WHERE nome = '".$_POST['Crea']."'";
                            $result = $conn->query($query);
                            if($result->num_rows == 0)
                            {
                               $nome = $_POST['Crea'];
                                $query = "INSERT INTO gruppo(nome) value ('$nome')";
                                $conn->query($query); 
                            }
                            $commit = "COMMIT";
                            $conn->query($commit);
                            $endTrans = "END TRANSACTION";
                            $conn->query($endTrans);
                            $unlock = "UNLOCK TABLES";
                            $conn->query($unlock);
                            $conn -> close();
                        }

                        echo '<table>
                                <tr>
                                    <td>
                                        <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' "method="post">';
                        $var = "<label for='nome''><p>Elimina Gruppo: </label>";
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
                        $var .= "</p></select></td>";
                        echo $var;
                        echo "\n" . "<td><button style=\"width: 100%\" type='submit' class=\"button\">Elimina Gruppo</button>";
                        echo "</form></td></tr>";

                        echo '<tr>
                                <td>
                                    <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . ' "method="post">';
                        $var = "<label for='Crea'><p>Crea Gruppo: </label>";
                        $var .= "<input type='text' name='Crea' id='CreaGruppo' required></td>";
                        echo $var;
                        echo "\n<td><button style=\"width: 100%\" class=\"button\" type='submit'>Crea Gruppo</button></p>";
                        echo "</form></td></tr></table>";
                ?>
        </div>
    </div>
</body>
</html>
