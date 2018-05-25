<?php 
ob_start();
$title = "Strona Główna";
require_once "header.php";

echo '<article>';

if (isset ($_SESSION['login'])){
            echo '<div class="error">Jesteś już zalogowany</div>';
    exit();
}

if(isset($_POST['rejestracja'])  ) {

    if (strcmp($_POST['haslo'],$_POST['haslo2'])){
        echo '<div class="error">Podane hasła różnią się od siebie</div>';
    }
    elseif (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
        echo '<div class="error">Podano niepoprawny adres email</div>';    

    else {
    
    
$pass = hash('sha256', $_POST['haslo']);


require_once "connect.php";


   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      

   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }


$sql = 'INSERT INTO b_uzytkownicy VALUES (null, :login, :haslo, :imie, :nazwisko, :email, :ulica, :miasto, :kodpocztowy, :telefon, 0)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));



$sth->execute(array(':login' => $_POST['login'], ':haslo' => $pass, ':imie' => $_POST['imie'], ':nazwisko' => $_POST['nazwisko'], ':email' => $_POST['mail'], ':ulica' => $_POST['ulica'], ':miasto' => $_POST['miasto'], ':kodpocztowy' => $_POST['kod'], ':telefon' => $_POST['telefon']));
        if($sth==true){
                   echo '<div class="correct">Poprawnie utworzono konto. Teraz możesz się zalogować</div>';     
        }
}
}
    

if(isset($_POST['zaloguj'])  ) {

    require_once "connect.php";

    
$pass = hash('sha256', $_POST['haslo']);

require_once "header.php";


   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      

   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }


$sql = 'select u_id, login, status from b_uzytkownicy where login = :u AND haslo = :p';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    
    $sth->execute(array(':u' => $_POST['login'], ':p' => $pass));
$zalogowany = $sth->fetchAll();

   //print_r($zalogowany);


    
    if ($zalogowany==false)
    {
                echo '<div class="error">Podane dane są nieprawidłowe</div>';    
    }
    else{
        
        $_SESSION['login'] = $zalogowany[0]['login'];
        $_SESSION['id'] = $zalogowany[0]['u_id'];
        
        if ($zalogowany[0]['status']==1){
            $_SESSION['admin'] = 1;
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header("Location: http://$host$uri/$extra");
        }
        elseif($zalogowany[0]['status']==2){
            $_SESSION['admin'] = 2;
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header("Location: http://$host$uri/$extra");
        }
        else{
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'index.php';
            header("Location: http://$host$uri/$extra");
        }
    }
    
}



?>

    
    <div id="panelpodzial">
<div class="podziall"><h1>Zaloguj się</h1>
      <form method = "post" action="panel.php">
 <label>Login:<br><input name = "login" type = "text" size = "25" required>
            </label><label>Hasło:<br><input name = "haslo" type = "password" size = "25" required>
            </label>


            <p><input type="submit" name="zaloguj" value="Zaloguj się"></p>
</form>
    </div><div class="podzialp"><h1>Rejestracja</h1>
      <form method = "post" action = "panel.php">
 <label>Login:<br><input name = "login" type = "text" size = "25" required></label>
  <label>Email:<br><input name = "mail" type = "email" size = "25" required></label>        
  <label>Imię:<br><input name = "imie" type = "text" size = "25" required></label>        
  <label>Nazwisko:<br><input name = "nazwisko" type = "text" size = "25" required></label>  
  <label>Ulica:<br><input name = "ulica" type = "text" size = "25" required></label> 
  <label>Kod-pocztowy:<br><input name = "kod" type = "text" size = "6" required></label>  
  <label>Miejscowość:<br><input name = "miasto" type = "text" size = "30" required></label>  
  <label>Telefon:<br><input name = "telefon" type = "text" size = "10" required></label>        
 <label>Hasło:<br><input name = "haslo" type = "password" size = "25" required></label>
 <label>Powtórz Hasło:<br><input name = "haslo2" type = "password" size = "25" required></label>


            <p><input type="submit" name="rejestracja" value="Wyślij"></p>
</form>
 </div>  
        </div>
</article>

  
<?php require_once "footer.php";
ob_end_flush();?>
