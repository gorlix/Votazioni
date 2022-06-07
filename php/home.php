
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Votazione</title>
    <script>
        jQuery(document).ready(function($){
            $(".clickable-row").click(function(){
                window.location = $(this).data("href");
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Lista Votazioni</p>
        </div>
        <?PHP
            include "Navbar.php";
        ?>
        <div class="contenuto">
            <center>
            <h1 style="align:center; font-family: 'Roboto Mono', monospace;font-family: 'Space Mono', monospace;">Lista Votazioni</h1>
        <?php
        $_SESSION["user_selected"] = 0;
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbName = "votazioniscolastiche";
        $sql = "";

        $conn = mysqli_connect($server,$username,$password,$dbName);
        if(!$conn){
            die("Connessione Fallita: " . mysqli_connect_error());
        }
        $sql = "select id,quesito,tipo,fine FROM votazione";

        $result = mysqli_query($conn,$sql);
        $tab = "<table style='width:100%'>
                    <tr style='width:100%; border-bottom: 2px solid black'>
                        <td id='titoli'>TITOLO</td>
                        <td id='titoli'>TIPO</td>
                        <td id='titoli'>COMPLETATO</td>
                    </tr>";
        if(mysqli_query($conn,$sql)){
            while($row=mysqli_fetch_assoc($result)){
                $tab .= "<form method = 'post' action = 'votazione.php'>";
                $tab .= "<tr class='clickable-row' style='width:100%' >
                            <td name='id'>" . $row['quesito'] . "</td>
                            <input type='hidden' name='id' value='".$row['id']."'>
                            <td>" . $row['tipo'] . "</td>";
                if($row['fine'] >= date("Y-M-D h:i:sa")){
                    $tab .= "<td> SI </td>
                        </tr> </form>";
                }else{
                    $tab .= "<td> NO </td>
                        </tr> </form>";
                }
            }
        }

        echo $tab;
        

        
    ?>
    </center>
        </div>
    </div>
</body>
</html>