<?php 
$title = "Strona Główna";
require_once "header.php";

$t=$_POST['tekst'];

require_once "connect.php";


   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      


   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }

$sql = "select k_id ,tytul, obrazek from b_ksiazki where tytul LIKE :tekst";


$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$pdo->exec('SET NAMES utf8');


$sth->execute(array(':tekst' => "%".$t."%"));

$szukaj = $sth->fetchAll();
if(!$szukaj ) exit();



?>
<article><h1>Wyszukiwanie</h1>
    
<table class="wyszukaj">
         <thead>
     <tr><td>Okładka</td><td>Tytuł</td></tr>    
    </thead>
    <tbody>
        <?php
                foreach ($szukaj as $row)
               echo " <tr><td><div class='ksiazkawyszukaj'><a href=\"ksiazka.php?id=".$row[0]."\"><img src=\"img/ksiazki/".$row[2]."\" alt=\".$row[2].\"></a></div></td><td>".$row[1]."</td></tr>";

        ?>
        

    </tbody>
    
    </table>    
    
 

    
    
</article>
  
<?php require_once "footer.php";?>
