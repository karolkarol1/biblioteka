<?php 
ob_start();
$title = "Strona Główna";
require_once "header.php";

   echo '<article>';

if (isset ($_POST['bt'])){

    
           if ((empty ($_POST['haslonowe']) ) || (empty ($_POST['haslonowe2'])) ){ 
        echo '<div class="error">Nowe hasła są puste</div>';
    }
            elseif (strcmp($_POST['haslonowe'],$_POST['haslonowe2'])){
        echo '<div class="error">Podane hasła różnią się od siebie</div>';
    }
       else {
           
          $pass = hash('sha256', $_POST['haslo']);
           
        $nowypass = hash('sha256', $_POST['haslonowe']);

                         $sql = 'UPDATE b_uzytkownicy SET haslo=:nowy where u_id=:id AND haslo=:haslo';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_SESSION['id'],  ':nowy' => $nowypass,  ':haslo' => $pass));
           

           
       } 



              if ( $sth->rowCount()==0){
                    echo '<div class="error">Wprowadzone zmiany nie zostały zaktualizowane</div><br class="clearboth">';
  
          }
          else{
            echo '<div class="correct">Wprowadzone zmiany zostały zaktualizowane</div><br class="clearboth">';
   
          }
}
if (isset ($_GET['akcja'])){
    
        session_unset();
        setcookie("status", "", time() - 3600);
	$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'index.php';
header("Location: http://$host$uri/$extra");    
}



if (isset ($_SESSION['login'])){
    
    
               $sql = 'SELECT ulica,miasto,kodpocztowy,telefon,haslo FROM b_uzytkownicy where u_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_SESSION['id']));
  
$ustawienia = $sth->fetchAll();
?>

    
    

<h1>Ustawienia konta</h1>
      <form method = "post" action = "ustawienia.php">

          
           <label>Ulica:<br>
               <input name = "ulica" value="<?php echo $ustawienia[0][0] ?>" type = "text" size = "25" disabled/></label>
          
            <label>Miasto:<br>
               <input name = "miasto" value="<?php echo $ustawienia[0][1] ?>" type = "text" size = "25" disabled/></label>
            
          <label>Kod pocztowy:<br>
               <input name = "kod" value="<?php echo $ustawienia[0][2] ?>" type = "text" size = "25" disabled/></label>
               
          
           <label>Telefon:<br>
               <input name = "telefon" value="<?php echo $ustawienia[0][3] ?>" type = "text" size = "25" disabled/></label>

           <label>Stare hasło:<br><input name = "haslo" type = "password" size = "25" /></label>
 <label>Nowe hasło:<br><input name = "haslonowe" type = "password" size = "25" /></label>
 <label>Powtórz nowe hasło:<br><input name = "haslonowe2" type = "password" size = "25" /></label>


            <p><input type="submit" name="bt" value="Zmień dane"></p>
</form>
    
    
</article>

  
<?php 
                               }
                                require_once "footer.php";
ob_end_flush();?>
