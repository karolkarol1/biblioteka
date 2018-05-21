<?php 
require_once "connect.php";

if (isset ($_GET['dodaj'])){
    $int=60*60;
    setcookie($_GET['dodaj'],1,time()+$int);

      
	
	$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'produkt.php?id='.$_GET['dodaj'];
header("Location: http://$host$uri/$extra");
}


if((empty($_GET['id'])) || ($_GET['id']<0)) {
    exit;
}
$id=$_GET['id'];


$title = "Strona Główna";
require_once "header.php";

if (isset ($_POST['tresc']) && (isset ($_GET['id']))){
    
       try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      

   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }


$sql = 'INSERT INTO s_opinie VALUES (null, :uid, DEFAULT, :opis, :pid)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':uid' => $_SESSION['id'], ':opis' => $_POST['tresc'], ':pid' => $_GET['id']));
    
    
    

}

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

if( !$produkt ) exit();


$sql2 = 'SELECT opis, imie FROM s_opinie o inner join s_uzytkownicy u on o.u_id = u.u_id where p_id= :id';
$sth = $pdo->prepare($sql2, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':id' => $id));
$opinie = $sth->fetchAll();

//print_r($opinie);

?>

<article><h1><?php echo $produkt[0]['nazwa'];?></h1>
    
    

<img class="improdukt" src="img/produkty/<?php echo $produkt[0]['obrazek'];?>" alt="pamięć ram corsair"><span><?php echo $produkt[0]['opis'];?></span>
    <div id="produktopis">

    <div class="cena">Cena: <span class="bold"><?php echo $produkt[0]['cena'];?></span><br>
        <?php

        echo "<form method=\"post\" action=\"produkt.php?dodaj=".$produkt[0]['p_id']."\"><input type=\"submit\" value=\"Dodaj do koszyka\"></form>";
?>
        </div>
    </div>
            

   <section><h2>Opinie</h2>

    <?php
       if (isset ($_SESSION['id'])){
           $sql = 'select * from s_zamowienia z join s_zamowienia_produkty zp on (z.z_id=zp.id_zamowienia) where zp.id_produktu=:productid and z.u_id=:userid and z.czyoplacone=1';


       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':userid' => $_SESSION['id'], ':productid' => $_GET['id']));
      
           
           $liczba = $sth->rowCount();
           

           
           
                      $sql = 'select * from s_opinie where u_id=:userid and p_id=:productid';


       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':userid' => $_SESSION['id'], ':productid' => $_GET['id']));
    
                      $liczba2 = $sth->rowCount();
           

           
        if($liczba>$liczba2){
?>
          <form method = "post" action="produkt.php?id=<?php echo $_GET['id']?>">    
 <label>Opis:<br><textarea name="tresc" required></textarea></label>


            <p><input type="submit" value="Wyślij opinię"></p>
</form>
<?php         }}
           
           
       ?>
   <table class="opinie"> 
    <thead>
           <tr><td>Kto</td><td>Opinia</td></tr>
       </thead>
    <tbody>
        
        <?php 
     // print_r($opinie);

        foreach ($opinie as $row)
               echo "<tr><td>$row[1]</td><td>$row[0]</td></tr>";


        ?>
        
    </tbody>
    </table>   
       </section> 
</article>

  
<?php require_once "footer.php";?>
