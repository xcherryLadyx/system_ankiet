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
    $sql3 = "SELECT EXISTS(SELECT * FROM tab_osoby_x_ankiety WHERE id_osoby = '$id_osoby' AND id_ankiety = '$nr')"; 
    $result2 = $conn->query($sql3);
    $row = mysqli_fetch_row($result2);

    
 
    $prawda = $row[0];

    if($prawda == '0')
    {
        $sql2 = "SELECT id_pytania, tresc_pytania FROM pytania WHERE id_ankiety = '$nr'";
        $result = $conn->query($sql2);
        while($row = $result->fetch_assoc())
        {
            $pytania[] = $row;
        }
        $_SESSION['pytania']=$pytania;
        $_SESSION['id_ankiety']=$nr;
        
    }
    else{
	header('Location: index.php');
    }
    
        
    
    
 
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
                <form style="margin-top: 2%" class="form-pytania" method="POST" id="uzupelnij-form" action="save.php" >
                </form>    

                <input type="submit" form="uzupelnij-form" value="Zatwierdź" class="view_button"/>
    		 </div>
	</div>
</div>
        </main>
    </body>
    <script>
          var form = document.getElementById('uzupelnij-form');
	   
		form.innerHTML = "";
		
		var complex = <?php echo json_encode($_SESSION['pytania']); ?>;
        
        
        
        for(var i = 0; i<complex.length;i++)
		{ 
		    var formularz = document.getElementById("uzupelnij-form");
            var label = document.createElement("label");
            label.for = "odpowiedz-" + complex[i]['id_pytania'];
            label.id = "odpowiedz" + complex[i]['id_pytania'] + "-label";
            label.appendChild(document.createTextNode(complex[i]['tresc_pytania']));
            formularz.appendChild(label);
            var input = document.createElement("input");
            input.type = "text";
            input.id =  "odpowiedz" + complex[i]['id_pytania'] ;
            input.name =  "odpowiedz" + complex[i]['id_pytania'] ;
            input.placeholder = "Wprowadź odpowiedź";
            input.class = "text-inputs";
            input.required = true;
            formularz.appendChild(document.createElement("br"));
            formularz.appendChild(input);
            formularz.appendChild(document.createElement("br"));

			
		}
    </script>
</html>