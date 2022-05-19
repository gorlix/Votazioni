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
                $query="";

                $sql="SELECT nome, id FROM gruppo order by nome";

                /* You can add order by clause to the sql statement if the names are to be displayed in alphabetical order */

                echo "<select name=gruppo value=''></option>"; // list box select command

                foreach (connettiDb()->query($sql) as $row){//Array or records stored in $row

                    echo "<option value=$row[id]>$row[name]</option>";

                    /* Option values are added by looping through the array */

                }

                echo "</select>";// Closing of list box
                ?>
            </select>
        </div>
    </div>
</body>
</html>

    </div>
</body>
</html>
