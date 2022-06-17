<?php
    session_start();
    require __DIR__ . '/SharedFunctions.php';

    if(isset($_POST['esci'])) /* Ho schiacciato il tasto esci? */
    {
        session_destroy();
        header("Location:login.php");
    }

    if(!isset($_SESSION["id_utente"]))
    {
        /* ID Utente non settato Torna alla Login page */
        session_destroy();
        header("Location:login.php");
    }
    else
    {
        $user = $_SESSION["id_utente"];
        $id_Admin = get_Id_Admin();
        $id_Crea_Votazione = get_Id_Crea_Votazione();

        //ricerca ID Grupoo Utente
        $conn = connettiDb();
        $sql = "SELECT g.id FROM gruppo g
                   INNER JOIN appartienea a on (a.idGruppo = id)
                   INNER JOIN Utente u on (a.idUtente = u.id)
                   WHERE u.id = $user";
        $result = $conn->query($sql);

        $sqlDatiUtente = "SELECT nome, cognome, mail FROM Utente WHERE id = $user";
        $resultDatiUtente = $conn->query($sqlDatiUtente);

        if ($resultDatiUtente->num_rows == 1)
        {
            $row = $resultDatiUtente->fetch_assoc();
            $nome = $row['nome'];
            $cognome = $row['cognome'];
            $mail = $row['mail'];
        }
        else
        {
            echo "0 results";
        }

        if ($result->num_rows > 0)
        {
            $gruppoUtente = array();
            while ($row = $result->fetch_assoc())
            {
                $gruppoUtente[] = $row['id'];
            }

            $admin = false;
            $crea_votazione = false;
            if (in_array($id_Admin, $gruppoUtente))
            {
                $admin = true;
            }
            if (in_array($id_Crea_Votazione, $gruppoUtente))
            {
                $crea_votazione = true;
            }
        }
    }

    echo
        "<div class='sidebar'>".
            "<ul>";
            if($crea_votazione)
            {
                echo
                    "<li class='elenco' id='0'>" .
                    "<a href='gestisci_votazioni.php' class='link'>Gestisci votazioni</a>" .
                    "</li>";
            }

            if($admin)
            {
                echo
                    "<li class='elenco' id='1'>" .
                    "<a href='gestisci_utente.php' class='link'>Gestisci utenti</a>" .
                    "</li>" ;
            }
            if($admin)
            {
                echo
                    "<li class='elenco' id='2'>" .
                    "<a href='gestisci_gruppo.php' class='link'>Gestisci gruppi</a>" .
                    "</li>" ;
            }

            if($admin || $crea_votazione)
            {
                echo
                    "<li class='elenco' id='3'>" .
                    "<a href='home.php' class='link'>Home</a>" .
                    "</li>" ;
            }
                
            echo "<p>".$nome." ".$cognome."<br>".$mail."</p>";

            echo
                '<form method="post" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">'.
                        "<input type='submit' name='esci' value='Esci'/><br>
                    </form>" .
                "</li>" ;
    echo
            "</ul>" .

        "</div>";
    $conn->close();
?>
