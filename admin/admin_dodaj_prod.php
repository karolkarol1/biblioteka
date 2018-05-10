<?php 
session_start();

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
                        $sql = 'INSERT INTO s_produkty VALUES(null, :nazwa, :opis, :cena, :kat_id, :obrazek)';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$sth->execute(array(':opis' => $_POST['opis'], ':nazwa' => $_POST['nazwa'], ':cena' => $_POST['cena'], ':obrazek' => $_FILES['userfile']['name'], ':kat_id' => $_POST['kat_id']));
  
      

    
    $uploaddir = '../img/produkty/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);


if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
  echo "Produkt został poprawnie dodany";
} else {
   echo "Upload failed";
}


    
    
}    
      
?> 
    

<article>
    
    

      <form method="post" action="admin_dodaj_prod.php" ENCTYPE="multipart/form-data">

           <label>Nazwa:<br>
               <input name = "nazwa" type="text" size = "25" required/></label>
           <label>Opis:<br>
               <textarea name="opis"></textarea></label>
                     <label>Cen:<br>
               <input name="cena" type ="text" size="3" required/></label>
                     <label>Kategoria:<br>
                           <select name="kat_id">
    <option value="1">Płyty główne</option>
    <option value="2">Procesory</option>
    <option value="3">Karty graficzne</option>
    <option value="4">Laptopy</option>
    <option value="5">Telefony</option>
    <option value="6">Tablety</option>

  </select>
                         <br>
    <input name="userfile" type="file">


            <p><input type="submit" name="bt" value="Dodaj produkt"></p>
</form>
    

  </body>
</html>
