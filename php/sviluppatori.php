<?php
/**
* @author skenny;Matteo Schintu
* @author Daniele
* @author Negro
*/
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../stile/globalStyle.css">
    <title>Votazione</title>

</head>
<style>
    .img{
        width: 200px;
        heigth: 200px;
    }
</style>
<body>
    <div class="container">
        <div class="logo">
            <img src="../immagini//logoScuola.png" alt="logo scuola" class="logo-scuola">
        </div>
        <div class="titolo">
            <p class="titolo-header">Sviluppatori</p>
        </div>
         <?PHP
            include "Navbar.php";
        ?> 
        <div class="contenuto">
            <center>
                <table>
					<tr>
		
                        <td colspan="2" style="text-align: right;"><img class='img' src="../foto/Tonello.jpg"></td>
                        <td colspan="2"><h2>Andrea Tonello</h2><h3>Docente</h3></td>
                    </tr>
                    <tr>
                        <td><img class='img' src="../foto/Schintu.jpg"></td>
                        <td><h2>Matteo Schintu</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                        <td><img class='img' src="../foto/Daniele.jpg"></td>
                        <td><h2>Cosimo Daniele</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                    </tr>
                    <tr>
                        <td><img class='img' src="../foto/Negro.jpg"></td>
                        <td><h2>Simone Negro</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                        <td><img class='img' src="../foto/Gorla.jpeg"></td>
                        <td><h2>Alessandro Gorla</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                    </tr>
                    <tr>
                        <td><img class='img' src="../foto/Fusè.jpg"></td>
                        <td><h2>Danilo Fusè</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                        <td><img class='img' src="../foto/Groppo.jpg"></td>
                        <td><h2>Gabriele Groppo</h2><h3>5Bi</h3><h3>Anno Scolastico: 2021-2022</h3></td>
                    </tr>
                    
                </table>
            </center>
        <p class="errore"><?php  if(isset($_GET['hash'])) {echo "Non hai i permessi per accedere a quetsa votazione";$_GET['hash'] = "";}?></p>
        </div>
    </div>
</body>
</html>
