<?php 
ob_start();
$title = "Strona Główna";
require_once "header.php";

require_once "connect.php";

?>


<?php
if (isset ($_GET['usun'])){
  unset($_COOKIE[($_GET['usun'])]);
        setcookie($_GET['usun'], "", time()-3600);
$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'koszyk.php';
header("Location: http://$host$uri/$extra");
}




                     $sql = 'SELECT id_punktu, adres FROM s_punkty';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth = $pdo->prepare($sql);

    
    $sth->execute();
$punkty = $sth->fetchAll();



   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      $pdo->exec('SET NAMES utf8');


   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }


$arrayOfValues = array_keys($_COOKIE);
$questionMarks = join(",", array_pad(array(), count($arrayOfValues), "?"));


$sql ="SELECT p_id, obrazek, nazwa, cena FROM s_produkty  WHERE p_id IN ($questionMarks)";

$sth = $pdo->prepare($sql);

    
    $sth->execute($arrayOfValues);
$koszyk = $sth->fetchAll();


//print_r($koszyk);

echo '<article><h1>Twój koszyk</h1>';
    
    if ($koszyk==false)
    {
                echo 'Brak przedmiotów';    
    }
    else{
        
echo '<table class="wyszukaj">
    <thead><tr><td>Zdjęcie</td><td>Nazwa</td><td>Cena</td><td>Ilość</td><td>Usuń</td></tr></thead>';
    $suma=0;
                foreach ($koszyk as $row){
            $val=$row[0];
               echo "<tr class='cart-row'><td><a href=\"produkt.php?id=$row[0]\"><img src=\"img/produkty/$row[1]\" class='obrkoszyk' alt=\"$row[0]\"></a></td><td><a href=\"produkt.php?id=$row[0]\">$row[2]</a></td><td>$row[3]</td><td><input type='number' id=\"$row[0]\" data-unit-price=\"$row[3]\" name='points' class='cart-variant--quantity_input' min='1' max='10' step='1' value=\"$_COOKIE[$val]\"></td>
<td><a href=\"koszyk.php?usun=$row[0]\"><img src=\"img/delete.png\" alt=\"usuń\"></a></td></tr>";
                    $suma+=$row[3];
              
                    
                }
        
        

        
        echo '</tbody></table>    <hr>
                <form id="formularz" action="sprawdz.php" method="post">
<div id="kupon"><input type="text" id="kuponpole" name="kuponznizka" placeholder="Wprowadź kupon"><input id="kuponprzycisk" type="button" value="Zatwierdź"></div>
    <div id="podsumowanie">
    Podsumowanie: <span class="red" id="obnizka"></span><span class="bold" id="pods"></span> zł<br><br>
                ';
    }


?>
</div>
    
    



  
<div class="adres_calosc">
<div class="kont1_adres"><h2>Adres korespondecyjny</h2>
    <?php
    if (isset ($_SESSION['login'])){
        
        
                     $sql = 'SELECT imie,nazwisko,ulica,miasto,kodpocztowy,telefon FROM s_uzytkownicy where u_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_SESSION['id']));
  
$ustawienia = $sth->fetchAll();
        
      if ($_SESSION['admin']==0){  
      ?>  

  <label>Imię:<br><input name = "imie" type = "text" size = "25" value="<?php echo $ustawienia[0][0] ?>" disabled></label>        
  <label>Nazwisko:<br><input name = "nazwisko" type = "text" size = "25" value="<?php echo $ustawienia[0][1] ?>" disabled></label>  
  <label>Adres:<br><input name = "adres" type = "text" size = "25" value="<?php echo $ustawienia[0][2] ?>" disabled></label>  
        <label>Miejscowość:<br><input name = "miejscowosc" type = "text" size = "25" value="<?php echo $ustawienia[0][4] ?>" disabled></label> 
<label>Kod pocztowy:<br><input name = "kod" type = "text" size = "25" value="<?php echo $ustawienia[0][3] ?>" disabled></label> 

  <label>Telefon:<br><input name = "telefon" type = "text" size = "25" value="<?php echo $ustawienia[0][5] ?>" disabled></label>    
    
    </div>
    <div class="kont2_adres"><h2>Alternatywny <input type="checkbox" id="checkbox_alt" name="alternatywny"></h2>
        
         <div class="pola"> <label>Imię:<br><input class="alt" name = "imie2" type = "text" size = "25" required></label>        
  <label>Nazwisko:<br><input class="alt" name = "nazwisko2" type = "text" size = "25" required></label>  
  <label>Adres:<br><input class="alt" name = "adres2" type = "text" size = "25" required></label>  
   <label>Miejscowość:<br><input class="alt" name = "miejscowosc2" type = "text" size = "25" required></label> 
<label>Kod pocztowy:<br><input class="alt" name = "kod2" type = "text" size = "25" pattern="[0-9]{2}\-[0-9]{3}" required></label> 
  <label>Telefon:<br><input class="alt" name = "telefon2" type = "text" size = "25" required></label>    
        </div>
        
    </div>
    
    
    <?
    }
    ?>
    
    <script>
$('#checkbox_alt').change(function() {
    if (this.checked) {
        $(".pola").show();
        
        $(".alt").each(function() {
    $(this).prop('required',true);
}); 
        
        }
    else {
        $(".pola").hide();
                $(".alt").each(function() {
    $(this).prop('required',false);
}); 


    }
})
          .change();

    </script>
    
    

    
    
    
    
    <?php
    }
    else{
              if ($_SESSION['admin']==0){  

        ?>
    
    <div class="niezal-adres">
<label>E-mail:<br><input name = "email" type = "email" size = "25" required></label>        
<label>Imię:<br><input class="alt" name = "imie2" type = "text" size = "25" required></label>        
  <label>Nazwisko:<br><input class="alt" name = "nazwisko2" type = "text" size = "25" required></label>  
  <label>Adres:<br><input class="alt" name = "adres2" type = "text" size = "25" required></label>  
   <label>Miejscowość:<br><input class="alt" name = "miejscowosc2" type = "text" size = "25" required></label> 
<label>Kod pocztowy:<br><input class="alt" name = "kod2" type = "text" size = "25" pattern="[0-9]{2}\-[0-9]{3}" required></label> 
  <label>Telefon:<br><input class="alt" name = "telefon2" type = "text" size = "25" required></label>    
    <?php
    }}
    
          if ($_SESSION['admin']==0){  

        ?>

</div>

<div class="kont_calosc">
<div class="kont1"><h2>Sposób dostawy</h2>
    
  <input type="radio" class="radio_koszyk" name="dostawa" value="1" required> Kurier <span class="boldnormal">(20 zł)</span><br>
  <input type="radio" class="radio_koszyk" name="dostawa" value="2"> Poczta-Polska <span class="boldnormal">(15 zł)</span><br>
  <input type="radio" class="radio_koszyk" id="odbior" name="dostawa" value="3"> Odbiór osobisty <span class="boldnormal">(0 zł)</span> <select id="punkty" name="punkty">
    <option value="" selected disabled hidden>Wybierz punkt odbioru</option>
                      <?foreach ($punkty as $row){
echo "<option value=".$row[0].">".$row[1]."</option>";
}
    
    
          } ?>

</select><br>
        

        
    
    <script>
        var koszt=0;
$(document).ready(function() {
    $('input[type=radio][name=dostawa]').change(function() {
        if (this.value == 3) {
$( "#opcje" ).prop( "disabled", false );
$( "#gotowka" ).prop( "disabled", false );
                $("#kosztdostawy").text("0");
                    $('#punkty').prop('disabled', false);
            koszt=0.00;


        }
        else if(this.value == 1){
$( "#opcje" ).prop( "disabled", true );
            $( "#gotowka" ).prop( "disabled", true );
                            $("#kosztdostawy").text("20");
                                $('#punkty').prop('disabled', true);

            koszt=20.00;


        }
        else if(this.value == 2){
$( "#opcje" ).prop( "disabled", true );
            $( "#gotowka" ).prop( "disabled", true );
                            $("#kosztdostawy").text("15");
                                $('#punkty').prop('disabled', true);

            koszt=15.00;


        }	
        
         var result = parseFloat(st) + parseFloat(koszt);

                                    $("#calosc").text(result.toFixed(2));

    });
});

        
        

    </script>
    
    </div>
        <?      if ($_SESSION['admin']==0){ 
    
?>
<div class="kont2"><h2>Sposób płatności</h2>
    

    
  <input type="radio" class="radio_koszyk" name="platnosc" value="1" required> Przelew tradycyjny<br>
  <input type="radio" class="radio_koszyk" name="platnosc" value="2"> Przelew błyskawiczny/Karta płatnicza<br>
      <input type="radio" class="radio_koszyk" name="platnosc" id="gotowka" value="3"> Gotówka<br>

<? }if(($_SESSION['admin']==1)||($_SESSION['admin']==2)){
    
    echo "<input type='hidden' name='platnosc' value='0'>";
    echo "<input type='hidden' name='dostawa' value='0'>";
}
    
    ?>
    
    </div>
<div class="kont3"><h2>Podsumowanie</h2>
Wartość w koszyku: <span class="red" id="obnizka"></span><span class="boldnormal" id="pods2"></span> zł<br class="odstep">
Koszt dostawy: <span class="boldnormal" id="kosztdostawy"></span> zł<br class="odstep">

<hr>
Całkowita kwota zapłaty: <span id="calosc" class="bold"></span>zł
    </div>
</div>
    <br class="odstep"><input name="zaplac" type="submit" value="Przejdź do płatności">
</form>

  <script>
        var st = 0;

$('.cart-variant--quantity_input').change(function () {
Cookies.set(this.id, this.value);
    st=0;
    var ilosc=0;
    $('.cart-row').each(function () {
        var i = $('.cart-variant--quantity_input', this);
        var up = $(i).data('unit-price');
        var q = $(i).val();
        st = +st + (up * q);
        ilosc += +q;
    });
    // Subtotal price
	st=(st).toFixed(2)
    
    if(obnizka>0){
                        $(".red").text(st);
        st=obnizka* +st;
    }

$("#pods").text(st);
    $("#pods2").text(st);
        $(".suma").text(st);
            $(".liczba").text(ilosc);

    $("#calosc").text(+st + koszt);

    
    
    
    
    
    

        })
        .change(); // Add .change() here
</script> 

            
       <script> 
           var obnizka;
    $("#kuponprzycisk").click(function(e) {
    e.preventDefault();
    $.ajax({
        type: "POST",
        url: "http://lap-kom.2ap.pl/kupon.php",
        data: { 
            nazwa: $("#kuponpole").val() 
        },
        success: function(result) {
var parsed_data = JSON.parse(result);
            obnizka=1-(parsed_data["0"]["0"]/100);
                                          $(".red").text(+st);
            st=+st*obnizka + koszt;
            st=(st).toFixed(2)
          $("#pods2").text(st);
                      $("#pods").text(st);

                                  $("#calosc").text(+st + +koszt);

            
        },
        error: function(result) {
            alert('error');
        }
    });
});
    </script>         
            

</article>

<?php require_once "footer.php";
ob_end_flush();?>
