<?php 
require_once "connect.php";

if (isset ($_GET['dodaj'])){
    $int=60*60;
    setcookie($_GET['dodaj'],1,time()+$int);

      
  
  $host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'ksiazka.php?id='.$_GET['dodaj'];
header("Location: http://$host$uri/$extra");
}


if((empty($_GET['id'])) || ($_GET['id']<0)) {
    exit;
}
$id=$_GET['id'];


$title = "Strona Główna";
require_once "header.php";


   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      
$pdo->exec('SET NAMES utf8');


   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }


$sql = 'SELECT * FROM b_ksiazki
    WHERE k_id = :id';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $id));


$produkt = $sth->fetchAll();


?>

<article><h1><?php echo $produkt[0]['tytul'];?></h1>
    
    

<div class="calosc"><div class="obrazekksiazkaa"><img class="improduktksiazka" src="img/ksiazki/<?php echo $produkt[0]['obrazek'];?>"></div><div id="produktopis">
<span><h4>Opis:</h4><?php echo $produkt[0]['opis'];?></span>
   
      <br>
      <h4>Autorzy:</h4>
      <p>
        <?php

        $zapytanie ='SELECT * FROM b_autor WHERE a_id IN (SELECT a_id FROM b_autorzyksiazka WHERE k_id = :id)';
        

        $sth = $pdo->prepare($zapytanie, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $autorzy = $opinie = $sth->fetchAll();
      
            foreach ($autorzy as $key) {
            print "{$key['imie']} {$key['nazwisko']} <br>";


          };
          ?>

      </p><h4>Wydawnictwo:</h4>
      <p>
        <?php
         $zapytanie ="SELECT nazwa_wydawnictwa FROM b_wydawnictwo WHERE w_id = '".$produkt[0]['wydawnictwo']."'";
        

        $sth = $pdo->prepare($zapytanie, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $sth->execute(array(':id' => $id));
        $wydawnictwo = $sth->fetchAll();
      
            foreach ($wydawnictwo as $key) {
            print "{$key['nazwa_wydawnictwa']} <br>";


          };
          ?>
      </p>


    <div >
        <?php
        if($produkt[0]['ilosc'] != 0){
        print "<h4>Dostepnych egzemplarzy:</h4>
        <p>{$produkt[0]['ilosc']}</p>";
        echo "<form method=\"post\" class=\"prawo\" action=\"ksiazkaReserv.php\">
        <input type=\"hidden\" value=".$produkt[0]['k_id']." name=\"id_ksiazka\">
        <input type=\"hidden\" value=".$produkt[0]['ilosc']." name=\"ilosc\">
        <input type=\"submit\" value=\"Zarezerwuj książkę\"></form> <br>";
        }
?>
        </div>
    </div>
    </div>
</article>
        <?php
            if(isset($_SESSION['alert'])){
                echo $_SESSION['alert'];
                unset($_SESSION['alert']);
            }
        ?>
  
<?php require_once "footer.php";?>