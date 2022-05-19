<?php
    $user = $_SESSION["Id_Utente"];
    if(!isset($user))
    {
        die;
    }

    echo
        "<div class='sidebar'>".
            "<ul>";
        if()
        {
            echo
                "<li class='elenco' id='0'>" .
                "<a href='gestisci_votazioni.php' class='link'>Gestisci votazioni</a>" .
                "</li>";
        }

        if()
        {
            echo
                "<li class='elenco' id='1'>" .
                "<a href='gestisci_utente.php' class='link'>Gestisci utenti</a>" .
                "</li>" ;
        }
            if()
            {
                echo
                    "<li class='elenco' id='2'>" .
                    "<a href='gestisci_gruppo.php' class='link'>Gestisci gruppi</a>" .
                    "</li>" ;
            }
            if()
            {
                echo
                    "<li class='elenco' id='3'>" .
                    "<a href='home.php' class='link'>Home</a>" .
                    "</li>" ;
            }

    echo
            "</ul>" .
        "</div>";
?>

