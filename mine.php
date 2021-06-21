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


    $sql2 = "SELECT id_ankiety FROM osoby WHERE email = '$email'";
    $sql3 = "SELECT tab_osoby_x_ankiety.id_osoby, tab_osoby_x_ankiety.id_ankiety, ankiety.nazwa_ankiety FROM tab_osoby_x_ankiety 
INNER JOIN ankiety ON tab_osoby_x_ankiety.id_ankiety = ankiety.id_ankiety WHERE id_osoby = '$id_osoby'";
	 $sql4 = "SELECT osoby.id_ankiety, ankiety.nazwa_ankiety FROM osoby 
    INNER JOIN ankiety ON osoby.id_ankiety = ankiety.id_ankiety WHERE email = '$email'";



    
          
  	  $result2 = $conn->query($sql4);
		$ankiety = array();
   	 while($row = $result2->fetch_assoc())
    	{
        	$ankiety[] = $row;
	}
    	$_SESSION['ankiety']=$ankiety;



        $result3 = $conn->query($sql3);
	$u_ankiety = array();
    while($row = $result3->fetch_assoc())
    {
        $u_ankiety[] = $row;
	}
	$_SESSION['u_ankiety']=$u_ankiety;

    
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
                <div class="container_obie">
                    <div class="container_wy">
                        <h1>Wypełnione</h1>
                        <table id="tb_wypelnione_ankiety" class="tabela">
                        </table>
                    </div>
                    <div class="container_do">
                        <h1>Do wypełnienia</h1>
                        <table id="tb_do_wypelnienia_ankiety" class="tabela">
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <script>



        var table = document.getElementById('tb_do_wypelnienia_ankiety');
		table.innerHTML = "";
       
		
		var complex = <?php echo json_encode($_SESSION['ankiety']); ?>;


        
        
        for(var i = 0; i<complex.length;i++)
		{ 
		    var tr = document.createElement("tr");
		    var td_opis = document.createElement("td");
		    var node = document.createTextNode(complex[i]['nazwa_ankiety']);
		    td_opis.appendChild(node);

            
		    var td_podglad = document.createElement("td");
            var td_a = document.createElement("button");
		    td_a.setAttribute("id", "numer_ankiety-"+ complex[i]['id_ankiety']);
           
		        td_a.setAttribute("onclick", "uzupelnij(this)");
                var node = document.createTextNode('Uzupełnij');
            

		    td_a.setAttribute("class", "view_button");
		    td_a.appendChild(node);	
		    td_podglad.appendChild(td_a);
            
			tr.appendChild(td_opis);
			tr.appendChild(td_podglad);

					table.appendChild(tr);

		

			
		}

 var table2 = document.getElementById('tb_wypelnione_ankiety');
 table2.innerHTML = "";

var complex = <?php echo json_encode($_SESSION['u_ankiety']); ?>;


for(var i = 0; i<complex.length;i++)
		{ 
		    var tr = document.createElement("tr");
		    var td_opis = document.createElement("td");
		    var node = document.createTextNode(complex[i]['nazwa_ankiety']);
		    td_opis.appendChild(node);

            
		    var td_podglad = document.createElement("td");
            var td_a = document.createElement("button");
		    td_a.setAttribute("id", "numer_ankiety-"+ complex[i]['id_ankiety']);

 
                td_a.setAttribute("onclick", "view2(this)");
                var node = document.createTextNode('Wyświetl');
   

		    td_a.setAttribute("class", "view_button");
		    td_a.appendChild(node);	
		    td_podglad.appendChild(td_a);
            
			tr.appendChild(td_opis);
			tr.appendChild(td_podglad);

					table2.appendChild(tr);
			
			
			
		}



        function uzupelnij(item){
            var a = item.id.split('-');
            console.log(a);

			window.location.href = "fill.php?nr=" + a[1];
        }

        function view2(item){
            var a = item.id.split('-');
            window.location.href = "token.php?nr=" + a[1];
        }
    </script>
    </body>

    
</html>