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
            <select name="Category">
                <?php
                    require __DIR__ . '/SharedFunctions.php';

                $content ="";
                $content.="<select name='nome'>";
                $query = "SELECT  id, nome FROM gruppo";
                $conn = connettiDb();
                $ris=$conn->query($query);
                if ($ris->num_rows > 0) {
                    while($row = $ris->fetch_assoc()) {
                        $quesito = $row["nome"];
                        $id = $row["id"];
                        // $altro = $row["altro"];
                        $content.= "<option value='$id'>$quesito</option>";
                    }
                }
                $content.="</select>";
                echo $content;
                ?>
            </select>
        </div>
    </div>
</body>
</html>

    </div>
</body>
</html>
