<?php 
session_start();

$id=$_GET['id'];
if (!isset($_SESSION['admin'])) {
    
exit();
                        }




?>


<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
             <link rel="stylesheet" href="../main.css">

    <title>Panel administratora</title>
  </head>
  <body>
    <h1>Panel administratora</h1>
      
      <ul><li><a href="..">Strona Główna</a></li><li><a href="index.php?s=1">Zamówienia</a></li><li><a href="index.php?s=2">Produkty</a></li><li><a href="index.php?s=3">Użytkownicy</a></li></ul>
      
      <?php
      
      require_once "../connect.php";




   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      

   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }
          $pdo->exec('SET NAMES utf8');
 
      

      
if(isset($_POST['bt'])){
                        $sql = 'UPDATE s_produkty SET nazwa=:nazwa, opis=:opis, cena=:cena, obrazek=:obrazek where p_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$sth->execute(array(':opis' => $_POST['opis'], ':nazwa' => $_POST['nazwa'], ':cena' => $_POST['cena'], ':obrazek' => $_FILES['userfile']['name'], ':id' => $_GET['id']));
    
    
        $uploaddir = '../img/produkty/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);


if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "Produkt został poprawnie dodany";
} else {
   echo "Upload failed";
}
    
}      
      
      
      
      

              $sql = 'SELECT nazwa,opis,cena,obrazek FROM s_produkty where p_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_GET['id']));
  
$ustawienia = $sth->fetchAll();
?>

<article>
    
    

      <form method = "post" ENCTYPE="multipart/form-data" action = "admin_edytuj_prod.php?id=<?php echo $id;?>">

           <label>Nazwa:<br>
               <input name = "nazwa" value="<?php echo $ustawienia[0]['nazwa'] ?>" type = "text" size = "50" required/></label>
           <label>Opis:<br>
               <textarea name="opis"><?php echo $ustawienia[0]['opis']; ?></textarea></label>
                     <label>Cena:<br>
               <input name="cena" value="<?php echo $ustawienia[0]['cena'] ?>" type = "text" size = "3" required/></label>
                     <label>Obrazek:<br>
               <img src="../img/produkty/<?php echo $ustawienia[0]['obrazek'] ?>" alt="obrazek produktu"></label>
                      <br>
    <input name="userfile" type="file">


            <p><input type="submit" name="bt" value="Zmień dane"></p>
</form>
    
<?php
    
      

      echo '<table class="admin_t"><thead>';
               
                     
echo '<tr><td>Id produktu</td><td>Nazwa</td><td>cena</td></tr></thead><tbody>';
          $pdo->exec('SET NAMES utf8');

$sql = 'SELECT * FROM s_produkty where p_id=:id';
    
       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_GET['id']));

    $row = $sth->fetchAll();
$row=$row[0];

          echo "<tr><td>".$row['p_id']."</td><td>".$row['nazwa']."</td><td>".$row['cena']."</td><td><a href=\"admin_edytuj_prod.php?id=".$row['p_id']."\">Edytuj</a></td></td><td><a href=\"index.php?s=2&u=".$row['p_id']."\">Usuń</a></td></tr>";

    
     

    

      
      ?>
   </tbody></table>
  </body>
</html>
