<?php
            session_start();
			require_once "connect.php";     

			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}

    $kto = (int)$_SESSION['id_osoby'];
    $kto2 = $_SESSION['email'];

 echo sizeof($_POST);
        echo $kto2;  
$conn->query('SET foreign_key_checks = 0');

        $values = array();
        foreach($_POST as $field => $value) {
            $values[] = $value;
        }
        $sql="INSERT INTO ankiety (nazwa_ankiety) VALUES ('$values[1]')";
        $sql_numer = "SELECT id_ankiety FROM ankiety WHERE nazwa_ankiety = '$values[1]'";
      
        
 	$name = $_POST['osoby'];


 $nr_ankiety = 0;

        if ($conn->query($sql) === TRUE ) {
            $results = $conn -> query($sql_numer);
            
            while($row = $results->fetch_assoc()) {

                $nr_ankiety = $row['id_ankiety'];
              }
            echo $nr_ankiety;
        }
        else 
        {
            echo "Błąd";
        }

        for ($i = 2; $i <sizeof($_POST) ;$i++)
        {
          
           $sql2="INSERT INTO pytania(tresc_pytania, id_ankiety) VALUES ('$values[$i]', '$nr_ankiety')";
           if ($conn->query($sql2) === TRUE ) {
 
        }
        else 
        {
            echo "Błąd";
        }
        

        }

	
		
          foreach ($name as $osoby){ 
            $sql3="UPDATE osoby SET id_ankiety = '$nr_ankiety' where email = '$osoby' ";
           if ($conn->query($sql3) === TRUE ) {
            
           header('Location: mine.php');
        }

}

	$conn->query('SET foreign_key_checks = 1');
	mysqli_close($conn);

?>

