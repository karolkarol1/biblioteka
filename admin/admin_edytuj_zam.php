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
                        $sql = 'UPDATE s_zal_zamowienia SET cena=:cena, status=:status where z_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':cena' => $_POST['cena'], ':status' => $_POST['status'], ':id' => $_GET['id']));
}      
      
      
      
      

              $sql = 'SELECT cena,status, z_id FROM s_zal_zamowienia where z_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_GET['id']));
  
$ustawienia = $sth->fetchAll();
?>

<article>
    
    

      <form method = "post" action = "admin_edytuj_zam.php?id=<?php echo $id;?>">
<h2>Edytujesz zamówienie o nr id <?php echo $ustawienia[0][2] ?></h2>

           <label>Cena:<br>
               <input name = "cena" value="<?php echo $ustawienia[0]['cena'] ?>" type = "text" size = "25" required/></label>
           <label>Status:<br>1 - zrealizowane<br>0 - w realizacji
               <br><input name = "status" value="<?php echo $ustawienia[0]['status'] ?>" type = "number" size = "1" min="0" max="1" required/></label>



            <p><input type="submit" name="bt" value="Zmień dane"></p>
</form>
    
<?php
    
      

      echo '<table class="admin_t"><thead>';
               
                     
echo '<tr><td>Id zamówienia</td><td>Kto kupił</td><td>Produkt</td><td>Data</td><td>Status</td></tr></thead><tbody>';
          $pdo->exec('SET NAMES utf8');

$sql = 'SELECT z.u_id, z_id, login, nazwa, data, status, z.cena FROM s_zal_zamowienia z inner join s_uzytkownicy u on z.u_id = u.u_id inner join s_produkty p on z.p_id = p.p_id where z_id=:id';
    
       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_GET['id']));

    $row = $sth->fetchAll();
$row=$row[0];
    if ($row['status']==0) $status='W realizacji'; else $status='Zrealizowane';

        echo "<tr><td>".$row['z_id']."</td><td><a href=\"index.php?s=1&e=".$row['u_id']."\">".$row['login']."</a></td><td>".$row['nazwa']."</td><td>".$row['data']."</td><td>$status</td><td><a href=\"index.php?s=1&e=".$row['z_id']."\">Zatwierdź</a></td></td><td><a href=\"index.php?s=1&u=".$row['z_id']."\">Usuń</a></td></tr>";

    
     

    

      
      ?>
   </tbody></table>
  </body>
</html>
