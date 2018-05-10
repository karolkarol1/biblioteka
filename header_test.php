<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>KarolSklep - <?php echo $title;
        
require_once "connect.php";
           try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      

   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }
        $arrayOfValues = array_keys($_COOKIE);
$questionMarks = join(",", array_pad(array(), count($arrayOfValues), "?"));

        $category=$pdo->query("Select * From s_kategorie ORDER BY kolejnosc");
        $categorys=$pdo->query("Select * From s_kategorie ORDER BY kolejnosc");

        
$sql ="SELECT p_id, obrazek, nazwa, cena FROM s_produkty  WHERE p_id IN ($questionMarks)";

$sth = $pdo->prepare($sql);

    
    $sth->execute($arrayOfValues);
$koszyk = $sth->fetchAll();



    $suma=0;
                foreach ($koszyk as $row){
            
                    $suma+=$row[3];
              
                    
                }
        
        ?>
</title>
       <link rel="stylesheet" href="main.css">
                  <script type="text/javascript" src="odlicz.js"></script>

  </head>
  <body onload="odlicz()">


<header>
    <div id="logo"><a href="index.php"><img src="img/header.png" alt="logo"/></a></div>
   <div id="wyszukaj"><form method="post" action="szukaj.php"><input class="szukaj" name="tekst" type="search" placeholder="Wpisz szukaną frazę...">
        
                  <select name = "po_czym">
                  <option selected = "selected" value="0">Wszystkie kategorie</option>
                  <?php 
                              echo $category[0];

                    foreach($category as $row){
                  ?>
                  <option value="<?php echo $row['id'];?>"><?php echo $row['nazwa'];?> </option>
                  <?php } ?>
               </select>
        
        <input class="btnszukaj" type="submit" value=""></form>
 
    
    </div>
    <div id="koszyk"><span class="liczba"><?php echo count(array_keys($_COOKIE))-1;
 ?></span><a href="koszyk.php"><img src="img/basket.png" alt="koszyk"><p class="suma"><?php echo $suma;
        ?> zł</p></a></div>
    
    <div id="plogowania">
    <?php

        session_start();
        

        
    if (isset ($_SESSION['login'])){

        echo "<a href=\"#\"><img src=\"img/member.png\" alt=\"logowanie\">Witaj ".$_SESSION['login']."<br>Ustawienia</a>";
        
    echo '<div id="dplog">
    <ul class="plog">
        <li class="plog"><a href="ustawienia.php">Ustawienia</a></li>
        <li class="plog"><a href="zamowienia.php">Historia zamówień</a></li>
        <li class="plog"><a href="ustawienia.php?akcja=wyloguj">Wyloguj</a></li>  ';
        if (isset ($_SESSION['admin']))
            echo '<li class="plog"><a href="admin/index.php">Panel admina</a></li>';
        echo '    
        </ul>
       </div> ';

        
    }
    else {
         echo '<a href="panel.php"><img src="img/member.png" alt="logowanie">Zaloguj się<br>Zarejestruj się</a>';
    } 
    
          ?>
        
        </div>
</header>
<nav><ul>
    
    <li><a href="index.php">Strona Główna</a></li>
    <li><a href="regulamin.php">Regulamin</a></li>
    <li><a href="odbiorosobisty.php">Odbiór osobisty</a></li>
    <li><a href="onas.php">O nas</a></li>
    <li><a href="kontakt.php">Kontakt</a></li>

    </ul></nav>
<main>
    <?php 
    //    foreach ($categorys as $row){
   //         echo $row['id'];
    //    }
   // print_r($category);
    ?>
    <aside id="menu">
        <ul>
            
            
          <li><a href="#"><img src="img/kategorie/komputer.png" alt="kategoria komputery"> Komputery</a>
              
                <ul class="sub-menu">
				    <li class="sub-menu"><a href="index.php?id=1">Płyty Główne</a></li>
				    <li class="sub-menu"><a href="index.php?id=2">Procesory</a></li>
				    <li class="sub-menu"><a href="index.php?id=3">Karty graficzne</a></li>
				</ul>
              
              </li>
          <li><a href="index.php?id=4"><img src="img/kategorie/laptop.png" alt="kategoria laptopy"> Laptopy</a></li>
          <li><a href="index.php?id=5"><img src="img/kategorie/phone.png" alt="kategoria telefony">Telefony</a></li>
          <li><a href="index.php?id=6"><img src="img/kategorie/tablet.png" alt="kategoria tablety">Tablety</a></li>
            
            
        </ul>
    </aside>
    
    
    