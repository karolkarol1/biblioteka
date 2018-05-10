<?php 
$title = "Kontakt";
require_once "header.php";

echo "<article><h1>$title</h1>";
    ?>
    
<?php
if (isset($_POST['bt'])){

       if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) echo '<div class="error">Podano niepoprawny adres email</div>';   
    else {
        
        mail($_POST['email'], $_POST['tytul'], $_POST['tresc'],"From: lapkom@gmail.com");
                mail('karolks94@gmail.com', $_POST['tytul'], $_POST['tresc'],"From: lapkom@gmail.com");

     echo '<div class="correct">Dziękujemy za kontakt</div>';
       
    }

}
?>
      <form method = "post" action="kontakt.php">
 <label>Imię i nazwisko:<br><input name="imie" type="text" size="25" required></label>
 <label>Email:<br><input name="email" type="text" size="25" required></label>
  <label>Tytuł:<br><input name="tytul" type = "text" size = "30" required></label>  
          
 <label>Treść:<br><textarea name="tresc" required></textarea></label>


            <p><input type="submit" name="bt" value="Wyślij"></p>
</form>

    


</article>


<?php require_once "footer.php";?>
