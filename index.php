<!DOCTYPE html>
<html lang="pl">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Ankieta</title>
		<meta name="description" content="Ankieta">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300i,400&display=swap&subset=latin-ext" rel="stylesheet">
		<link rel="icon" href="favicon.ico">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	</head>

	<body>
		

			
			<div style="background-color: gray" id="container">
				<b>Witaj!</b> 
			</div>
				<p class="hello">
					<br>
					Informujemy, iż wszelkie dane wprowadzone do ankiety pozostaną anonimowe.<br>
					<br>Prosimy o wypełnienie ankiety zgodnie ze stanem faktycznym.
					<br>
					<br>
				</p>
				<p class="hello2">Aby wziąć udział w ankiecie, zaloguj się lub zarejestruj!</p>
			
		<div class="pola">
			<form action="zaloguj.php" method="post">
				<input type="text" name="email" placeholder="Adres e-mail"/> <br><br>
        			<input type="password" name="haslo" placeholder="Hasło"/> <br/>
		</div>

			<div class="zaloguj_center">
				<input type="submit" class="klik" value="Zaloguj się"/>
				</form>


							<?php
   				 if(isset($_SESSION['blad']))
     				   echo $_SESSION['blad'];
   				 ?>
			</div>


				<div class="zarejestruj_center">
					  <a class="register" href="register.php">Zarejestruj się</a>
					<p class="rejestracja">Jeśli jeszcze nie masz konta, kliknij zarejestruj!</p>
				</div>
				
				

			

			<footer>
				ankieta_mw&copy;Creative Commons
			</footer>
			
		
	</body>
</html>
