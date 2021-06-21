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


$sql4 = "SELECT * FROM ankiety";



    
          
  	  $result2 = $conn->query($sql4);
		$ankiety = array();
   	 while($row = $result2->fetch_assoc())
    	{
        	$ankiety[] = $row;
	}
    	$_SESSION['ankiety']=$ankiety;
    
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
		<li><a class="active" href="strona.php">Strona główna</a></li>
                <li><a href="mine.php">Ankiety</a></li>
		<li><a href="wyniki.php">Wyniki</a></li>
		<li><a href="logout.php"> Wyloguj </a>
		</li>
          </ul>

</div>
       <main>
            <div class="container">
                <h1 style="text-align: center; margin-top: 2%;">Wyniki</h1>
                <table id="tb_moje_ankiety" class="tabela">
                </table>
                
            </div>
        </main>
        <script>
        var table = document.getElementById('tb_moje_ankiety');
	    
		table.innerHTML = "";
		
		var complex = <?php echo json_encode($_SESSION['ankiety']); ?>;
        console.log(complex);
        
        
        for(var i = 0; i<complex.length;i++)
		{ 
			console.log("xd");
		
		    console.log("xd2");
		    var tr = document.createElement("tr");
		    var td_opis = document.createElement("td");
		    var node = document.createTextNode(complex[i]['nazwa_ankiety']);
		    td_opis.appendChild(node);

            
		    var td_podglad = document.createElement("td");
		    var td_a = document.createElement("button");
		    td_a.setAttribute("id", "numer_ankiety-"+ complex[i]['id_ankiety']);
		    td_a.setAttribute("onclick", "view(this)");
		    td_a.setAttribute("class", "view_button");
		    var node = document.createTextNode('Podgląd');
		    td_a.appendChild(node);	
		    td_podglad.appendChild(td_a);
            
			tr.appendChild(td_opis);
			tr.appendChild(td_podglad);


			table.appendChild(tr);
			
		}
        function view(item){
            console.log(item);
            var a = item.id.split('-');
            console.log(a);

			window.location.href = "wyniki2.php?nr=" + a[1];
        }
    </script>
    </body>

    
</html>