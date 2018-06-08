<?php 
require_once "connect.php";
if (!isset($_GET['id'])) {
    
    $title='Książki, które warto wypożyczyć';             
                        }
else {
        $id=$_GET['id'];




        $sql = 'SELECT * FROM b_kategorie WHERE id = :id';
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
    
    $sth->execute(array(':id' => $id));
    
    
    $ksiazka = $sth->fetchAll();

    if( !$ksiazka ) exit();
    
    
$title = $ksiazka[0]['nazwa'];











    
}
    

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
  
    if(empty($_GET['id']))
    
        $sql = 'SELECT k_id, tytul, obrazek FROM b_ksiazki ORDER BY RAND() LIMIT 9';
    else 
        $sql = 'SELECT k_id, tytul, obrazek FROM b_ksiazki WHERE kat_id  = :id';
        $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        
        if(empty($_GET['id']))
        $sth->execute();
        else
        $sth->execute(array(':id' => $id));
        $ksiazka = $sth->fetchAll();


        if(isset($_SESSION['status']))


        echo "<article><h1>$title</h1>";
        


    
        // print_r($ksiazka);
        if(empty($_GET['id']))
         $category=$pdo->prepare("SELECT * FROM b_kategorie");
		 else
         $category=$pdo->prepare("SELECT * FROM b_kategorie WHERE id=$id");

         $category->execute();
         $result=$category->fetchAll();
        
        foreach($result as $row){           
            $test=$pdo->prepare('SELECT b_kategorie.id,b_ksiazki.kat_id,b_ksiazki.nazwa,b_ksiazki.cena,b_ksiazki.obrazek,b_ksiazki.k_id FROM b_kategorie INNER JOIN b_ksiazki ON b_kategorie.id=b_ksiazki.kat_id WHERE kat_id="'.$row['id'].'" ');
            $test->execute();
            $res=$test->fetchAll();
            foreach($res as $row){
                echo "<a href=\"ksiazka.php?id=".$row['k_id']."\"><div class=\"ksiazka\"><span class=\"tytul\">".$row['nazwa']."</span><br><img src=\"img/ksiazki/".$row['obrazek']."\" alt=\"".$row['obrazek']."\"><br><span class=ile>".$row['cena']." zł</span></div></a>";
               

                 
            }
        }

if( !$result && !$ksiazka ){
    
echo 'Brak książek';
}

        foreach ($ksiazka as $row)
               echo "<a href=\"ksiazka.php?id=".$row[0]."\"><div class=\"ksiazka\"><span class=\"tytul\">".$row[1]."</span><br><img src=\"img/ksiazki/".$row[2]."\" alt=\"".$row[1]."\"></div></a>";

        ?>
    
  

    
    
</article>

  
<?php require_once "footer.php";?>
