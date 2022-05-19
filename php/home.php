<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Dati</title>
</head>
<body>
    <?php
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbName = "votazioniscolastiche";
        $sql = "";
        $tab;

        /*$conn = mysqli_connect($server,$username,$password,$dbName);
        if(!$conn){
            die("Connessione Fallita: " . mysqli_connect_error());
        }*/

        /*$sql = "select quesito,tipo,fine FROM votazione";

        $result = mysqli_query($conn,$sql);
        $tab = "<table>
                    <tr>
                        <th>TITOLO</th>
                        <th>TIPO</th>
                        <th>COMPLETATO</th>
                    </tr>";
        if(mysqli_query($conn,$sql)){
            while($row=mysqli_fetch_assoc($result)){
                $tab .= "<tr>
                            <td>" . $row['quesito'] . "</td>
                            <td>" . $row['tipo'] . "</td>";
                if($row['fine'] >= date("Y-M-D h:i:sa")){
                    $tab .= "<td> SI </td>
                        </tr>";
                }else{
                    $tab .= "<td> NO </td>
                        </tr>";
                }
            }
        }*/
        

        
    ?>
</body>
</html>