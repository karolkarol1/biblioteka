<?php 
$title = "Strona Główna";
require_once "header.php";

$id=$_POST['po_czym'];
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

if ($id==0) $sql = "select p_id, obrazek, nazwa, cena from s_produkty where nazwa LIKE :tekst";
else 
$sql = "select p_id, obrazek, nazwa, cena from s_produkty where kat_id IN (select id from s_kategorie where id_rodzica=:id) AND nazwa LIKE :tekst";

$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


if ($id==0) $sth->execute(array(':tekst' => "%".$t."%"));
else $sth->execute(array(':tekst' => "%".$t."%",':id' => $id));
$szukaj = $sth->fetchAll();
if(!$szukaj ) exit();



?>
<article><h1>Wyszukiwanie</h1>
    
<table class="wyszukaj">
         <thead>
     <tr><td>Zdjęcie</td><td>Nazwa produktu</td><td>Cena</td></tr>    
    </thead>
    <tbody>
        <?php
                foreach ($szukaj as $row)
               echo " <tr><td><a href=\"produkt.php?id=".$row[0]."\"><img src=\"img/produkty/".$row[1]."\" alt=\".$row[2].\"></a></td><td><a href=\"produkt.php?id=".$row[0]."\">".$row[2]."</a></td><td>".$row[3]." zł</td></tr>";

        ?>
        

    </tbody>
    
    </table>    
    
 

    
    
</article>
  
<?php require_once "footer.php";?>
