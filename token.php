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

    if(isset ($_GET["nr"]))
    {
    $nr = (int)$_GET["nr"];

    $_SESSION['id_ankiety']=$nr;
    
    
 
    mysqli_close($conn);
    
    
    }
    else{
        header('Location: index.php');
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
                <div class="odp">
                    <h1 style="margin-top: 2%; margin-bottom: 1%; text-align: center;">Wpisz token, aby sprawdzić swoje odpowiedzi</h1>
                    <form class="form-pytania" action="view.php" id="odpowiedzi-form" method="POST">
                    <input type="text" id="token" name="token">
</form>
                    </br>
                    <input form="odpowiedzi-form" type="submit" value="Wyślij" class="view_button">
                </div>
                
</div>
            </div>
        </main>
        
    </body>
    
</html>