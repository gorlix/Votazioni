<?php
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbName = "votazioniscolastiche";
    $sql = "";
    $tab;


    $conn = new mysqli($server, $username, $password, $dbName);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $content = "<h3>OPERAZIONE VOTAZIONE </h3>
                <br>
                
                <p>Nome della votazione</p>
                
                <form action = '', method = 'POST'> 
                    
                <select name='quesito'>";

    $query = "SELECT  id, quesito FROM votazione /*ORDER BY */";
    $ris=$conn->query($query);
    if ($ris->num_rows > 0) {
        while($row = $ris->fetch_assoc()) {
            $quesito = $row["quesito"];
            $id = $row["id"];
        // $altro = $row["altro"];
        $content.= "<option value='$id'>$quesito</option>\n
                        </select>
                        <input type = 'submit'>";
      }
    }
    echo $content;
?>