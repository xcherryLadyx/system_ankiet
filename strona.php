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
	$kto = (int)$_SESSION['id_osoby'];
	$sql2 = "SELECT * FROM osoby WHERE id_osoby = '$kto'";

    $result2 = $conn->query($sql2);
	$arr_rows = array();
    while($row = $result2->fetch_assoc())
    {
        $arr_rows[] = $row;
    }
    $_SESSION['arr_rows']=$arr_rows;


	
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
				<b>Utwórz ankietę</b> 
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

<div class="zaloguj_center">		

        <form method="POST" name="ankietaForm" id="survey-form" action="add.php" class="form-utworz">
            <div class="form-uzytkownicy required" >
                <h3 >Wybierz osoby, które chcesz przydzielić do ankiety:</h3>
      
        <?php   

            $section = "SELECT email FROM osoby";
            $result = $conn->query($section);

            while ($row = mysqli_fetch_assoc($result))
            {

                if($row['email'] != $kto){
                echo "<input class='box' type='checkbox' name='osoby[]' value='".$row['email']."'";
                echo " />";
                echo "<label>".$row['email']."</label>";
                echo "<br/>";
                }
            }

        ?>
		<button style="margin-top: 2%" id="dodaj" class="klik" onclick="wyslij()"> Dodaj ankietę </button>
            </div>

            <div class="form-pytania" id="form-pytania">
            <label for="name" id="name-label" >Nazwa ankiety:</label><br>
            <input type="text" id="name" name="name" required placeholder="Podaj nazwę ankiety" class="text-inputs"><br>
            
<p class="buttons-container">   
 <button id="button" onclick="dodajPytanie()" class="register">Dodaj pytanie</button>     
           
 </p> 

	<label for="pytanie1" id="pytanie1-label">Pytanie nr 1</label><br>
            <input style="margin-top: 1%" type="text" id="pytanie1" name="pytanie1" required placeholder="Wprowadź pytanie" class="text-inputs"><br>
  
         </div>

   

        </form>

</div>
     
     
        

        <script>
    var nr_pytania = 2;
    function dodajPytanie(){
        var formularz = document.getElementById("form-pytania");
        var label = document.createElement("label");
        label.for = "pytanie" + nr_pytania;
        label.id = "pytanie" + nr_pytania + "-label";
        label.appendChild(document.createTextNode("Pytanie nr " + nr_pytania));
        formularz.appendChild(label);
        var input = document.createElement("input");
        input.type = "text";
        input.id =  "pytanie" + nr_pytania ;
        input.name =  "pytanie" + nr_pytania ;
        input.placeholder = "Wprowadź pytanie";
        input.class = "text-inputs";
        input.required = true;
        formularz.appendChild(document.createElement("br"));
        formularz.appendChild(input);
        formularz.appendChild(document.createElement("br"));
        nr_pytania++;
    }
    function wyslij(){
        var formValid = document.forms["survey-form"].checkValidity();
        console.log(formValid);
        
        
            document.ankietaForm.submit();

        
    }

    
    </script>
    </body>
  
</html>