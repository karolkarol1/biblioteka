<!doctype html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>LapKom - <?php echo $title;
        
        require_once "connect.php";
        try
        {
            //tescik
          $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
          $pdo->exec("SET CHARACTER SET utf8");
          //plik
        }
        catch(PDOException $e)
        {
            echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
        }
        $arrayOfValues = array_keys($_COOKIE);
        $questionMarks = join(",", array_pad(array(), count($arrayOfValues), "?"));
        
        $category=$pdo->prepare("SELECT b_kategorie.id, b_kategorie.nazwa, COUNT(b_ksiazki.kat_id) as liczba FROM b_ksiazki LEFT JOIN b_kategorie ON b_ksiazki.kat_id = b_kategorie.id GROUP BY b_ksiazki.kat_id");
        $category->execute();
        $result=$category->fetchAll();

        $sql ="SELECT k_id, obrazek, nazwa, cena FROM b_ksiazki  WHERE k_id IN ($questionMarks)";
        $sth = $pdo->prepare($sql);
        $sth->execute($arrayOfValues);
        $koszyk = $sth->fetchAll();
        $suma=0;
        $liczbaprod=0;
        foreach ($koszyk as $row){
            
            $suma+=$row[3]*$_COOKIE[$row[0]];
        $liczbaprod+=$_COOKIE[$row[0]];

        }
            $suma = number_format($suma, 2, ',', '');


        ?>
    </title>
    <link rel="stylesheet" href="main.css">
    <script type="text/javascript" src="odlicz.js"></script>
      <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
<link rel=icon href="img/favicon.ico" sizes="32x32 48x48" type="image/vnd.microsoft.icon">
      
  </head>
  <body onload="odlicz()">
      <header>
        <div id="logo"><a href="index.php"><img src="img/header.png" alt="logo"/></a></div>
        <div id="wyszukaj"><form method="post" action="szukaj.php?id=<?php echo $result[0]['id_rodzica']; ?>"><input class="szukaj" name="tekst" type="search"         placeholder="Wpisz szukaną frazę...">
                  <select name = "po_czym">
                  <option selected = "selected" value="0">Wszystkie kategorie</option>
                  <?php 
                    foreach($result as $row){
                  ?>
                  <option value="<?php echo $row['id_rodzica'];?>"><?php echo $row['nazwa'];?> </option>
                  <?php } ?>
               </select>
        <input class="btnszukaj" type="submit" value=""></form>
        </div>
    <div id="koszyk"><span class="liczba"><?php echo $liczbaprod;
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
        <li class="plog"><a href="rezerwacje.php">Rezerwacje</a></li>
        <li class="plog"><a href="ustawienia.php?akcja=wyloguj">Wyloguj</a></li>  ';
        if (isset ($_SESSION['admin']))
            echo '<li class="plog"><a href="admin/zamowienia.php">Panel admina</a></li>';
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
       <!-- <li><a href="odbiorosobisty.php">Odbiór osobisty</a></li> -->
        <li><a href="onas.php">O nas</a></li>
        <li><a href="kontakt.php">Kontakt</a></li>
    </ul></nav>
<main>
    <aside id="menu">
        <ul>
        <?php 
            foreach($result as $row){
        ?>            
        <li><a href="index.php?id=<?php echo $row['id']; ?>"><?php echo $row['nazwa'].' ('.$row['liczba'].')';?></a>
        </li>    
        <?php } ?>
        </ul>
    </aside>