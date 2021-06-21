
<?php
    session_start();

    if (isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		
		//Sprawdź poprawność imienia
		$imie = $_POST['imie'];
		
		//Sprawdzenie długości imienia
		if ((strlen($imie)<3) || (strlen($imie)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Imię musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($imie)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_imie']="Imię może składać się tylko z liter (bez polskich znaków)";
		}


		//Sprawdź poprawność nazwiska

		$nazwisko = $_POST['nazwisko'];

		//Sprawdzenie długości nazwiska
		if ((strlen($nazwisko)<3) || (strlen($nazwisko)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_nazwisko']="Nazwisko musi posiadać od 3 do 20 znaków!";
		}
		
		if (ctype_alnum($nazwisko)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_nazwisko']="Nazwisko może składać się tylko z liter (bez polskich znaków)";
		}


// Sprawdź poprawność adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail!";
		}
		
		//Sprawdź poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if ((strlen($haslo1)<8) || (strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if ($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}	

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

//Czy zaakceptowano regulamin?
		if (!isset($_POST['regulamin']))
		{
			$wszystko_OK=false;
			$_SESSION['e_regulamin']="Potwierdź akceptację regulaminu!";
		}				
		
		


	$_SESSION['fr_imie'] = $imie;
	$_SESSION['fr_nazwisko'] = $nazwisko;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_haslo1'] = $haslo1;
		$_SESSION['fr_haslo2'] = $haslo2;
		if (isset($_POST['regulamin'])) $_SESSION['fr_regulamin'] = true;

 require_once "connect.php";

        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_errno!=0) {
                
                throw new Exception(mysqli_connect_errno());
            }

	else
            {
                $result = $conn->query("SELECT id_osoby FROM osoby WHERE email='$email'");
                if(!$result) throw new Exception($conn->error);

                $ile_takich_miali = $result->num_rows;
                if($ile_takich_miali>0)
                {
                    $wszystko_OK=false;
                    $_SESSION['e_email']="Istnieje już konto z takim adresem email";
                     
                }






                if($wszystko_OK==true)
                {
			
			$conn->query('SET foreign_key_checks = 0');

                    if($conn->query("INSERT INTO osoby VALUES (NULL, NULL, '$imie', '$nazwisko','$email', '$haslo_hash')"))
                    {
                        $_SESSION['udanarejestracja']=true;
                        header('Location: index.php');
                    }
	
                    else
                    {
                        throw new Exception($conn->error);
                    }

			$conn->query('SET foreign_key_checks = 1');
                }

                $conn->close();
            }
        }

catch(Exception $e)
        {
            echo '<span style="color:red;">Błąd serwera, przepraszamy za utrudnienia, spróbuj pózniej</span>';
            echo '<br /> dev inf'.$e;
        }
    }
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Rejestracja</title>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Raleway:300i,400&display=swap&subset=latin-ext" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .error
        {
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>



<body>
		<div style="background-color: gray" id="container">
		<b>Rejestracja użytkownika</b> 
		</div>



	<div class="zarejestruj_center">
<p style= "margin-top: 3%; margin-bottom: 2%" class="hello2"><b>Aby zarejstrować użytkownika w systemie, uzupełnij dane poniżej</b></p>
    <form method="post">
        <input placeholder="Imię" type="text" value="<?php
            if(isset($_SESSION['fr_imie']))
            {
                echo $_SESSION['fr_imie'];
                unset($_SESSION['fr_imie']);
            }
        ?>" name="imie"/> <br/>


 <?php
            if(isset($_SESSION['e_imie']))
            {
                echo '<div class="error">'.$_SESSION['e_imie'].'</div>';
                unset($_SESSION['e_imie']);
            }
        ?>



 <input style= "margin-top: 0.5%" placeholder="Nazwisko" type="text" value="<?php
            if(isset($_SESSION['fr_nazwisko']))
            {
                echo $_SESSION['fr_nazwisko'];
                unset($_SESSION['fr_nazwisko']);
            }
        ?>" name="nazwisko"/> <br/>


 <?php
            if(isset($_SESSION['e_nazwisko']))
            {
                echo '<div class="error">'.$_SESSION['e_nazwisko'].'</div>';
                unset($_SESSION['e_nazwisko']);
            }
        ?>


 <input style= "margin-top: 0.5%" placeholder="E-mail" type="text" value="<?php
            if(isset($_SESSION['fr_email']))
            {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }
        ?>" name="email"/> <br/>

        <?php
			if (isset($_SESSION['e_email']))
			{
				echo '<div class="error">'.$_SESSION['e_email'].'</div>';
				unset($_SESSION['e_email']);
			}
        ?>


<input style= "margin-top: 0.5%" placeholder="Hasło" type="password" name="haslo1"/> <br/>

        
		<?php
			if (isset($_SESSION['e_haslo']))
			{
				echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
				unset($_SESSION['e_haslo']);
			}
        ?>		
        
        <input style= "margin-top: 0.5%" placeholder="Powtórz hasło" type="password" name="haslo2"/> <br/><br>
        <label>
            <input type="checkbox" name="regulamin" <?php 
                if(isset($_SESSION['fr_regulamin']))
                {
                    echo "checked";
                    unset($_SESSION['fr_regulamin']);
                }
            ?>/> Akceptuję regulamin
        </label>


 <?php
			if (isset($_SSION['e_regulamin']))
			{
				echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
				unset($_SESSION['e_regulamin']);
			}
        ?>	

    

       

 <br />	
        <input style="margin-top: 2.5%" class="register" type="submit" value="Zarejestruj się">
	</input>
</div>
    </form>
   
<footer>
				ankieta_mw&copy;Creative Commons
			</footer>

</body>
</html> 