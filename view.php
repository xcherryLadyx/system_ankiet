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
        die("Connection failed: " . $conn->connect_error);
    }
    $id_osoby = (int)$_SESSION['id_osoby'];
    $email = $_SESSION['email'];



    $id_ankiety = $_SESSION['id_ankiety'];
    
    
    $token = $_POST['token'];
    
    $token_hash = hash('sha256', $token);
   

    
    $sql = "SELECT id_pytania, tresc_odpowiedzi FROM odpowiedzi WHERE id_ankiety = '$id_ankiety' AND hash_odpowiedzi = '$token_hash'";
    $result = $conn->query($sql);
    if((mysqli_num_rows($result)!=0)){
        while($row = $result->fetch_assoc())
        {   
            $odpowiedzi[] = $row;
        }
        $licz = count($odpowiedzi);
$sql2 = "SELECT id_pytania, hash_odpowiedzi FROM odpowiedzi WHERE id_ankiety = '$id_ankiety'";
        $result2 = $conn->query($sql2);
        while($row = $result2->fetch_assoc())
        {
              $odphash[] = $row;
        }
    

        
        for($i = 0; $i < $licz; $i++)
        {
            $odptoken = $odpowiedzi[$i]["tresc_odpowiedzi"].$token;
            $odphashed =  hash('sha256', $odptoken);
            for($j = 0; $j < count($odphash); $j++)
            {
                if($odphash[$j]["hash_odpowiedzi"] != 'NULL' && $odphash[$j]["id_pytania"] == $odpowiedzi[$i]["id_pytania"])
                {
                    $tablica[] = $odpowiedzi[$i];
                }
		
            }
            
        }
        $licz2 = (int)count($tablica);
    
    
        $sql3 = "SELECT id_pytania, tresc_pytania FROM pytania WHERE id_ankiety = '$id_ankiety'";
        $result3 = $conn->query($sql3);
        $rows = mysqli_num_rows($result3);
        if($licz2 == $rows){
            $tekst = "Odpowiedzi nie zostały zmienione";
            $_SESSION['tablica'] = $tablica; 
            while($row = $result3->fetch_assoc())
            {
                  $nowytab[] = $row;
            }
            $_SESSION['nowytab']=$nowytab;
        }
        else
        {
            $tekst= "Odpowiedzi zostały zmienione!";
            $_SESSION['nowytab'] = [0];
        }
    
        $_SESSION['tekst']=$tekst;

        
    }
    else
        {
            $tekst =  "Podano błędny token albo odpowiedzi użytkownika zostały zmienione";
            $_SESSION['nowytab'] = [0];
            $_SESSION['tablica'] = [0]; 
            $_SESSION['tekst']=$tekst;
        }




    
    
    mysqli_close($conn);
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
	
                <h1 style="margin-top: 2%; margin-bottom: 1%; text-align: center">Odpowiedzi</h1>
	
               <b> <table style="margin: 0 auto" id="tb_odpowiedzi">
		
		</table></b>		
               
		
	
</div>

        </main>
    </body>
    <script>
        console.log("test2");
        var tekst = "<?php echo $_SESSION['tekst']; ?>";
        var table = document.getElementById('tb_odpowiedzi');
        table.innerHTML = "";

	 	var tr = document.createElement("tr");
	 	var td_opis = document.createElement("td");
	 	var node = document.createTextNode(tekst);
	 	td_opis.appendChild(node);
        td_opis.setAttribute("class", "tekst");
	td_opis.style.backgroundColor = "#20B2AA";
        tr.appendChild(td_opis);
        table.appendChild(tr);

        if(tekst == "Odpowiedzi nie zostały zmienione")
        {
            var complex = <?php echo json_encode($_SESSION['tablica']); ?>;
            var complex2 = <?php echo json_encode($_SESSION['nowytab']); ?>;

            for(var i = 0; i<complex.length;i++)
            {
                var tr = document.createElement("tr");
                var td_pytanie = document.createElement("td");
                var node = document.createTextNode(complex2[i]['tresc_pytania']);
                td_pytanie.setAttribute("class", "td_pytanie");
                td_pytanie.appendChild(node);
                tr.appendChild(td_pytanie);


            

                var td_odpowiedz = document.createElement("td");
                var node = document.createTextNode(complex[i]['tresc_odpowiedzi']);
                td_odpowiedz.setAttribute("class", "td_odpowiedz");
                td_odpowiedz.appendChild(node);
                tr.appendChild(td_odpowiedz);
                table.appendChild(tr);
            }
        }
    </script>
</html>