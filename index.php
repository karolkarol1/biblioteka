<?php 

if (!isset($_GET['id'])) {
    
    $title='Produkty, które warto kupić';             
                        }
else {
        $id=$_GET['id'];

    switch ($_GET['id']) {
    case '1':
       $title='Telefony';
        break;
    case '2':
       $title='Komputery';
        break;
    case '3':
       $title='Laptopy';
        break;
    case '4':
       $title='Tablety';
        break;    }
    
}
    ;

require_once "header.php";
require_once "connect.php";


   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      $pdo->exec('SET NAMES utf8');


   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }
  
    if(empty($_GET['id'])) $sql = 'SELECT p_id, nazwa, obrazek, cena FROM s_produkty ORDER BY RAND() LIMIT 9';
    else 
        $sql = 'SELECT p_id, nazwa, obrazek, cena FROM s_produkty WHERE kat_id  = :id';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        if(empty($_GET['id'])) $sth->execute();
        else $sth->execute(array(':id' => $id));
        $produkt = $sth->fetchAll();
        echo "<article><h1>$title</h1>";
    
    
        $category=$pdo->prepare("SELECT * FROM s_kategorie WHERE id_rodzica=$id");
        $category->execute();
        $result=$category->fetchAll();
        
        foreach($result as $row){           
            $test=$pdo->prepare('SELECT s_kategorie.id,s_produkty.kat_id,s_produkty.nazwa,s_produkty.cena,s_produkty.obrazek,s_produkty.p_id FROM s_kategorie INNER JOIN s_produkty ON s_kategorie.id=s_produkty.kat_id WHERE kat_id="'.$row['id'].'" ');
            $test->execute();
            $res=$test->fetchAll();
            foreach($res as $row){
                echo "<a href=\"produkt.php?id=".$row['p_id']."\"><div class=\"produkt\"><span class=\"tytul\">".$row['nazwa']."</span><br><img src=\"img/produkty/".$row['obrazek']."\" alt=\"".$row['obrazek']."\"><br><span class=ile>".$row['cena']." zł</span></div></a>";
            }
        }

if( !$result && !$produkt ){
    
echo 'Brak produktów';
}

        foreach ($produkt as $row)
               echo "<a href=\"produkt.php?id=".$row[0]."\"><div class=\"produkt\"><span class=\"tytul\">".$row[1]."</span><br><img src=\"img/produkty/".$row[2]."\" alt=\"".$row[1]."\"><br><span class=ile>".$row[3]." zł</span></div></a>";

        ?>
    
  

    
    
</article>

  
<?php require_once "footer.php";?>
