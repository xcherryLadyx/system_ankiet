<?php
session_start();
	require_once('connect.php');
    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
		exit();
	}
	$conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);}
    $id_osoby = (int)$_SESSION['id_osoby'];
    $email = $_SESSION['email'];
    if(!isset($_SESSION['id_ankiety'])){
        header('Location: index.php');
		exit();
    }
    $id_ankiety = $_SESSION['id_ankiety'];
    unset($_SESSION['id_ankiety']);
    unset($_SESSION['pytania']);

    function password_generate($chars) 
    {
       $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz!@#$%&*?';
       return substr(str_shuffle($data), 0, $chars);
    }
    $token = password_generate(10);

    $token_hash = hash('sha256', $token);

    $values = array();
    foreach($_POST as $field => $value) {
        $values[] = $value;
    }
  

     $sql_numer = "SELECT min(id_pytania) as minimum FROM pytania WHERE id_ankiety = '$id_ankiety'";
      
        


 $nr_pytania = 0;

        
            $results = $conn -> query($sql_numer);
            
            while($row = $results->fetch_assoc()) {

                $nr_pytania = $row['minimum'];
              }
           

    for ($i = 0; $i <sizeof($_POST) ;$i++)
    {   
        
        $nr_ankiety = (int)$id_ankiety;
	if($i == 0){
        $sql2="INSERT INTO odpowiedzi VALUES (NULL, '$nr_pytania', '$nr_ankiety', '$values[$i]', '$token_hash')";
		
	}
	else{
		$nr_pytania++;
		$sql2="INSERT INTO odpowiedzi VALUES (NULL, '$nr_pytania', '$nr_ankiety', '$values[$i]', '$token_hash')";

	}


        if ($conn->query($sql2) === TRUE ) {
        
        }
        else 
        {
            echo "Błędzik";
        }
    }




	$sql3= "INSERT INTO tab_osoby_x_ankiety(id_osoby,id_ankiety) VALUES ('$id_osoby', '$nr_ankiety')";
    if ($conn->query($sql3) === TRUE ) {

    }
    else 
    {
        echo "Błąd2";
    }
   
    
?>
<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Stwórz ankietę</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300i,400&display=swap&subset=latin-ext" rel="stylesheet">
	</head>

<body>

	<div style="background-color: gray" id="container">
				<b>Moje ankiety</b> 
	</div
		
<div>
		
	<ul class="topnav" style="margin-left: 20px;
	margin-right: 20px;">
		<li><a class="active" href="strona.php">Utwórz ankietę</a></li>
                <li><a href="mine.php">Ankiety</a></li>
		<li><a href="wyniki.php">Wyniki</a></li>
		<li><a href="logout.php"> Wyloguj </a>
		</li>
          </ul>

</div>
        <main>
            <div class="container">
                <div>
                    <h1 style="margin-top: 2%; text-align: center">Zapisz poniższy token, aby zweryfikować później swoje odpowiedzi.<br>
				Nie można wygenerować tokenu ponownie, zapisz token w bezpiecznym miejscu.</h1>
                   <b><u> <h2 style="text-align: center; margin-top: 5%; font-size: 30px;">
                    <?php
                        echo $token;
                    ?>
                <div>
                </h2></b></u>
            </div>
        </main>
    </body>
</html>
   