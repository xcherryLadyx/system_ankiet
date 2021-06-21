
<?php
    session_start();

    if((!isset($_POST['email'])) || (!isset($_POST['haslo'])))
    {
        header('Location: index.php');
        exit();
    }
    require_once "connect.php";

    // Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    else
    {
        $email=$_POST['email'];
        $haslo=$_POST['haslo'];

        $email = htmlentities($email, ENT_QUOTES, "UTF-8");
	$haslo = htmlentities($haslo, ENT_QUOTES, "UTF-8");


        if($result = $conn->query(sprintf("SELECT * FROM osoby WHERE email = '%s' ",mysqli_real_escape_string($conn,$email))))
        {
            $ilu_userow = $result->num_rows;
            if($ilu_userow>0)
            {   
                $wiersz = $result->fetch_assoc();
                if(password_verify($haslo, $wiersz['haslo']))
                {
                    $_SESSION['zalogowany'] = true;

                
                    $_SESSION['email'] = $wiersz['email'];
                    $_SESSION['id_osoby'] = $wiersz['id_osoby'];
                    $kto = (int)$_SESSION['id_osoby'];
                    $sql2 = "SELECT * FROM osoby WHERE id_osoby = '$kto'";

                    $result2 = $conn->query($sql2);
                    $arr_rows = array();
                    while($row = $result2->fetch_assoc())
                    {
                        $arr_rows[] = $row;
                    }
                    $_SESSION['arr_rows']=$arr_rows;

                    unset($_SESSION['blad']);
                    $result->free_result();
                    $result2->free_result();
			if($email == 'admin@admin.pl')
			{
                    		header('Location: strona.php');
			}
			 else
			{
				header('Location: strona.php');
			}
                }
                else
                {
                    $_SESSION['blad'] = '<p class="wrong" style="color:red">Nieprawidłowy email lub hasło!</p>';
                    header ('Location: index.php');
                }
            }
            else
            {
                $_SESSION['blad'] = '<p class="wrong" style="color:red">Nieprawidłowy email lub hasło!</p>';
                header ('Location: index.php');
            }
        }

     

        $conn->close();
    }

    
?>