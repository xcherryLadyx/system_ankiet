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
    $sql3 = "SELECT * FROM ankiety WHERE id_ankiety = '$nr'";
    $result2 = $conn->query($sql3); 
    $row = $result2->fetch_assoc();
    
    $nazwa= $row['nazwa_ankiety'];
    $_SESSION['nazwa']=$nazwa;
   

    $sql4 = "SELECT id_pytania, tresc_pytania FROM pytania WHERE id_ankiety = '$nr'";
    $result3 = $conn->query($sql4);
    while($row = $result3->fetch_assoc())
    {   
        $pytania[] = $row;
		
    }

    $_SESSION['pytania']=$pytania;

    $sql5 = "SELECT id_pytania, tresc_odpowiedzi FROM odpowiedzi WHERE id_ankiety = '$nr' ORDER BY id_pytania";
	$result4 = $conn->query($sql5);
    while($row = $result4->fetch_assoc())
    {   
        $odpowiedzi[] = $row;
		
    }
    if(empty($odpowiedzi))
    {   
        $odpowiedzi = 0; 
    }
    $_SESSION['odpowiedzi']=$odpowiedzi;

$sql6 = "SELECT tab_osoby_x_ankiety.id_osoby, tab_osoby_x_ankiety.id_ankiety, osoby.email 
FROM tab_osoby_x_ankiety INNER JOIN osoby ON tab_osoby_x_ankiety.id_osoby = osoby.id_osoby 
WHERE tab_osoby_x_ankiety.id_ankiety = '$nr' ";


	$result5 = $conn->query($sql6);
    while($row = $result5->fetch_assoc())
    {   
        $uzytkownicy[] = $row;
    }
  if(empty($uzytkownicy))
    {   
        $uzytkownicy = 0; 
    }


    $_SESSION['uzytkownicy']=$uzytkownicy;



$sql7 = "SELECT id_osoby, email FROM osoby WHERE id_ankiety = '$nr' ";



	$result6 = $conn->query($sql7);
    while($row = $result6->fetch_assoc())
    {   
        $uzytkownicy_brak[] = $row;
	
    }

  if(empty($uzytkownicy_brak))
    {   
        $uzytkownicy_brak = 0; 
    }



    $_SESSION['uzytkownicy_brak']=$uzytkownicy_brak;



    mysqli_close($conn);
    
    
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
				<b>Wyniki</b> 
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
                
                    <h1 style= "margin-top: 2%; margin-bottom: 1%; text-align: center" id="nazwa">
                    </h1>
<div class = "pytania">
                    <table style="margin: 0 auto" id="tb_pytania">
                    </table>
                </div>
                	<div style="margin-top: 2%" class = "container_obie border">
                    <div class ="container_wy">
                        <h3 style= "margin-top: 2%; margin-bottom: 1%; text-align: center; color: green"><u>Wypełnione</u></h3>
                        <table id="tb_uzytkownicy_wypelnione" class="tabela">
                        </table>
                    </div>
                    <div class ="container_do">
                        <h3 style= "margin-top: 2%; margin-bottom: 1%; text-align: center; color: red"><u>Niewypełnione</u></h3>
                        <table id="tb_uzytkownicy_niewypelnione" class="tabela">
                        </table>
                    </div>
                </div>
            
</div>
	</div>
        </main>
    </body>
    <script>
        var nazwa = "<?php echo $_SESSION['nazwa']; ?>";
        var pytania = <?php echo json_encode($_SESSION['pytania']); ?>;
        var odpowiedzi = <?php echo json_encode($_SESSION['odpowiedzi']); ?>;
        var uzytkownicy = <?php echo json_encode($_SESSION['uzytkownicy']); ?>;
	var uzytkownicy_brak = <?php echo json_encode($_SESSION['uzytkownicy_brak']); ?>;
        var table = document.getElementById('tb_pytania');
        table.innerHTML = "";

        var nazwa_ankiety = document.getElementById('nazwa');
        nazwa_ankiety.innerHTML = "Nazwa ankiety: " + nazwa;

  for(var i = 0; i<pytania.length;i++)
            {
                var tr = document.createElement("tr");
                var td_numer = document.createElement("td");
                var node = document.createTextNode("Pytanie " + (i+1) +":");
                td_numer.setAttribute("class", "td_numer");
                td_numer.appendChild(node);
                tr.appendChild(td_numer);
                

                var td_pytanie = document.createElement("td");
                var node = document.createTextNode(pytania[i]['tresc_pytania']);
                td_pytanie.setAttribute("class", "td_pytanie");
                td_pytanie.appendChild(node);
                tr.appendChild(td_pytanie);
                table.appendChild(tr);
                if(odpowiedzi != null)
                {
			
		
                    for(var j = 0; j<odpowiedzi.length; j++)
                    {
                        
                         if(pytania[i]['id_pytania'] == odpowiedzi[j]['id_pytania'])
                        {	
				
                            console.log(odpowiedzi[j]['id_pytania']);
                            var tr = document.createElement("tr");
                            var td_odpowiedz = document.createElement("td");
                            var node = document.createTextNode("Odpowiedź: " + odpowiedzi[j]['tresc_odpowiedzi']);
                            td_odpowiedz.setAttribute("class", "td_odpowiedz");
                            td_odpowiedz.setAttribute("colspan", "2");
                            td_odpowiedz.appendChild(node);
                            tr.appendChild(td_odpowiedz);
                            table.appendChild(tr);
                        }

			
                    }
                }
            }
        
 var table1 = document.getElementById('tb_uzytkownicy_wypelnione');
       

       table1.innerHTML = "";
	
        

        for(var i = 0; i<uzytkownicy.length;i++)
        {   
            
            var tr = document.createElement("tr");
            var td_uzytkownicy = document.createElement("td");
            var node = document.createTextNode(uzytkownicy[i]['email']);
            td_uzytkownicy.setAttribute("class", "td_uzytkownicy");
            td_uzytkownicy.appendChild(node);
            tr.appendChild(td_uzytkownicy);
            if(parseInt(uzytkownicy[i]))
            {
                   table2.appendChild(tr);
            }
            else
            {
                table1.appendChild(tr);
            }
        }




   var table2 = document.getElementById('tb_uzytkownicy_niewypelnione');
table2.innerHTML = "";


 for(var i = 0; i<uzytkownicy_brak.length;i++)
        {   
 for(var j = 0; j<uzytkownicy.length;j++)
	{

		if(uzytkownicy[j]["id_osoby"] != uzytkownicy_brak[i]["id_osoby"])
       {
            
            var tr = document.createElement("tr");
            var td_uzytkownicy_brak = document.createElement("td");
            var node = document.createTextNode(uzytkownicy_brak[i]['email']);
            td_uzytkownicy_brak.setAttribute("class", "td_uzytkownicy_brak");
            td_uzytkownicy_brak.appendChild(node);
            tr.appendChild(td_uzytkownicy_brak);
            if(parseInt(uzytkownicy_brak[i]))
            {
                table1.appendChild(tr);   
            }
            else
            {
                table2.appendChild(tr);
            }
        

	}

	}
	

	}






        
        

       
    </script>
    
</html>