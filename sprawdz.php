<?php
ob_start();

require_once "connect.php";
require_once "header.php";

        session_start();
    


if (isset ($_POST['kuponznizka'])){
    
    $sql ="select procent from s_rabaty where poczatek<=CURDATE() AND koniec>=CURDATE() AND nazwa=:nazwa";


       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':nazwa' => $_POST['kuponznizka']));
  
$kupon = $sth->fetchAll(); 
    
    if ((is_numeric($kupon[0][0]))&&($kupon[0][0]>0)) {
   $znizka=(1-($kupon[0][0]/100));
    
}
    else{
        $znizka=1;
    }

}

if (isset ($_POST['zaplac'])){
    
    
    if(($_SESSION['admin']==1)||($_SESSION['admin']==2)){
    
     $arrayOfValues = array_keys($_COOKIE);
$questionMarks = join(",", array_pad(array(), count($arrayOfValues), "?"));


$sql ="SELECT p_id, cena, nazwa FROM s_produkty  WHERE p_id IN ($questionMarks)";

$sth = $pdo->prepare($sql);

    
    $sth->execute($arrayOfValues);
$zaplac = $sth->fetchAll();
    
        
        
        
        
            $sql ="select punkt_id from s_partnerzy where u_id=:id";


       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_SESSION['id']));
  
$id_punktu = $sth->fetchAll(); 
        
    
        
        
        $suma=0;
    foreach ($zaplac as $row){
                           $suma+=$row[1]*$_COOKIE[$row[0]]; 
                        }
                            $sql = 'INSERT INTO s_zamowienia VALUES (\'\', :uid, 0, DEFAULT, :cena,:dane,0,1,null)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':uid' => $_SESSION['id'], ':cena' => $suma, ':dane' =>'brak'));
$idzam = $pdo->lastInsertId();
  
                        foreach ($zaplac as $row){
                    $sql = 'INSERT INTO s_zamowienia_produkty VALUES (null, :pid, :cena, :ilosc,:id_zam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':cena' => $row[1]*$znizka, ':pid' => $row[0], ':ilosc' => $_COOKIE[$row[0]], ':id_zam' => $idzam));
                 
                            
                            
                                    $sql = 'INSERT INTO s_zamowienia_partnerzy VALUES (:idpkt, :idzam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':idpkt' => $id_punktu[0][0], ':idzam' => $idzam));
                            
                }
        
        
          $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
                if (is_numeric($parts[0])) {
        setcookie($name, '', time()-1000);                                            
        }}
        
    echo "<h1>Zamówienie klienta zostało poprawnie wprowadzone</h1>";
    
    
}
    else{
    
        if (((($_POST['platnosc'])==3) && (($_POST['dostawa'])==1)) || ((($_POST['platnosc'])==3) && (($_POST['dostawa']))==2)) {
        exit();
    }
    
    
    if (($_POST['dostawa'])==1) {$dostawa=20;}
        else if (($_POST['dostawa'])==2) {$dostawa=15;}
     else if (($_POST['dostawa'])==3){$dostawa=0; }     
    

    
 $arrayOfValues = array_keys($_COOKIE);
$questionMarks = join(",", array_pad(array(), count($arrayOfValues), "?"));


$sql ="SELECT p_id, cena, nazwa FROM s_produkty  WHERE p_id IN ($questionMarks)";

$sth = $pdo->prepare($sql);

    
    $sth->execute($arrayOfValues);
$zaplac = $sth->fetchAll();
    
    
if (isset ($_SESSION['login'])){
                 $sql = 'SELECT imie,nazwisko,ulica,miasto,kodpocztowy,telefon,email FROM s_uzytkownicy where u_id=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_SESSION['id']));
  
$ustawienia = $sth->fetchAll();  
  
       $imie=$ustawienia[0][0];
    $nazwisko=$ustawienia[0][1];
    $adres=$ustawienia[0][2];
    $miejscowosc=$ustawienia[0][3];
    $kod=$ustawienia[0][4];
    $telefon=$ustawienia[0][5];
    $email=$ustawienia[0][6];
    
if (isset($_POST['alternatywny'])) 
{
    $imie=$_POST['imie2'];
    $nazwisko=$_POST['nazwisko2'];
    $adres=$_POST['adres2'];
    $miejscowosc=$_POST['miejscowosc2'];
    $kod=$_POST['kod2'];
    $telefon=$_POST['telefon2'];
    

}
    
        $dane=$imie.':'.$nazwisko.':'.$adres.':'.$kod.':'.miejscowosc.':'.$telefon;
      

   


    
    if ($zaplac==true)
    {
$suma=0;
    foreach ($zaplac as $row){
                           $suma+=$row[1]*$_COOKIE[$row[0]]; 
                            $data1 .= "<tr style='text-align:center'><td>".$row[0]."</td><td>".$row[2]."</td><td>".$_COOKIE[$row[0]]."</td><td>".$row[1]*$_COOKIE[$row[0]]."</td></tr>";
                        }
     $suma=$suma*$znizka+$dostawa;

                            $sql = 'INSERT INTO s_zamowienia VALUES (\'\', :uid, 1, DEFAULT, :cena,:dane,:dostawa,0,:email)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $sth->execute(array(':uid' => $_SESSION['id'], ':cena' => $suma, ':dane' =>$dane, ':dostawa' => $_POST['dostawa'], ':email' => $email));
$idzam = $pdo->lastInsertId();
  
                        foreach ($zaplac as $row){
                    $sql = 'INSERT INTO s_zamowienia_produkty VALUES (null, :pid, :cena, :ilosc,:id_zam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':cena' => $row[1]*$znizka, ':pid' => $row[0], ':ilosc' => $_COOKIE[$row[0]], ':id_zam' => $idzam));
                 
                }
        
        
        if (($_POST['dostawa'])==3){

        
        $sql = 'INSERT INTO s_zamowienia_partnerzy VALUES (:idpkt, :idzam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':idpkt' => $_POST['punkty'], ':idzam' => $idzam));
        

        } 
        
        
        
        
                    if (isset($_SERVER['HTTP_COOKIE'])) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        if (is_numeric($cookie)) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
        setcookie($name, '', time()-1000);                                            
        }}
                }
        
        

        
        
    }

        }
    

else{
        $imie=$_POST['imie2'];
    $nazwisko=$_POST['nazwisko2'];
    $adres=$_POST['adres2'];
    $miejscowosc=$_POST['miejscowosc2'];
    $kod=$_POST['kod2'];
    $telefon=$_POST['telefon2'];
    $email=$_POST['email'];
    
    
        $dane=$imie.':'.$nazwisko.':'.$adres.':'.$kod.':'.miejscowosc.':'.$telefon;

        if ($zaplac==true)
    {
            
            
        $suma=0;

                         foreach ($zaplac as $row){

                           $suma+=$row[1]*$_COOKIE[$row[0]]; 
                            $data1 .= "<tr style='text-align:center'><td>".$row[0]."</td><td>".$row[2]."</td><td>".$_COOKIE[$row[0]]."</td><td>".$row[1]*$_COOKIE[$row[0]]."</td></tr>";
                        }
                    $suma=$suma*$znizka+$dostawa;

                            $sql = 'INSERT INTO s_zamowienia VALUES (\'\', null , 1, DEFAULT, :cena,:dane,:dostawa,0,:email)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            
    $sth->execute(array(':cena' => $suma, ':dane' =>$dane, ':email' =>$email,':dostawa' => $_POST['dostawa']));

$idzam = $pdo->lastInsertId();
     
            
            
        
            

  
                        foreach ($zaplac as $row){
                    $sql = 'INSERT INTO s_zamowienia_produkty VALUES (null, :pid, :cena, :ilosc,:id_zam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':cena' => $row[1]*$znizka, ':pid' => $row[0], ':ilosc' => $_COOKIE[$row[0]], ':id_zam' => $idzam));
                 
                }
            
                    if (($_POST['dostawa'])==3){
        
        
        $sql = 'INSERT INTO s_zamowienia_partnerzy VALUES (:idpkt, :idzam)';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':idpkt' => $_POST['punkty'], ':idzam' => $idzam));
        

        } 
            
            
if ($dostawa==0){
    $odb="Odbiór osobisty";
}
            else if($dostawa==15){
                   $odb="Poczta Polska";
 
            }
                    else if($dostawa==20){
                   $odb="Kurier";
 
            }
        

  $to = $email;
$subject = 'Lap-kom.2ap.pl - informacja o złożonym zamówieniu';
$from = 'zamowienia@lap-kom.2ap.pl';
 
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
 
// Create email headers
$headers .= 'From: '.$from."\r\n".
    'Reply-To: '.$from."\r\n" .
    'X-Mailer: PHP/' . phpversion();
 
            $message="<html><body>
<h1 style='color:#f40;'>Dzień dobry!</h1>
<p style='color:#080;font-size:18px;'>Twoje zamówienie właśnie wpłynęło do naszego systemu.</p><br><hr>
<b>Podstawowe informacje o zamówieniu</b><br>
Nr zamówienia: $idzam<br><hr>
<b>Dane do faktury:</b> <br>
Imię: $imie<br>
Nazwisko: $nazwisko<br>
Ulica: $adres<br>
Miejscowość: $miejscowosc<br>
Kod pocztowy: $kod<br>
Telefon: $telefon<br>
Email: $email<br>
<br><br>
<hr><br><br>

<table style='width: 100%' style='padding:10px;'>
<tr style='text-align: center;background-color:#343333;color:white'><td style='padding:10px;'>ID produktu</td><td style='padding:10px;'>Nazwa produktu</td><td style='padding:10px;'>Ilość</td><td>Cena</td></tr>
$data1
<tr style='text-align: center'><td><b>$odb</b></td><td></td><td></td><td>$dostawa</td></tr>
<tr style='text-align: center;background-color:#343333;color:white'><td><b>Całkowita wartość zamówienia:</b></td><td></td><td></td><td>$suma</td></tr>

</table>

</body>
</html>";
 
// Sending email
if(mail($to, $subject, $message, $headers)){
} else{
    echo 'Wystąpił problem zgłoś to do administratora';
}
            

            
            
        
    }
    
    
    
    
    
    
    
    

    
}
    
    
                        if (isset($_SERVER['HTTP_COOKIE'])) {

                            ?>

<script>

$(document).ready(function() {
   

                                    $(".liczba").text("0");
                                    $(".suma").text("0,00 zł");

});

</script>

<?
                            
                            
                            
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach($cookies as $cookie) {
        $parts = explode('=', $cookie);
        $name = trim($parts[0]);
                if (is_numeric($parts[0])) {
        setcookie($name, '', time()-1000);                                            
        }}
                }
 
    echo '<article><h1><center>';
            if($_POST['platnosc']==1){
        echo "Dziękujemy za zakupy</center></h1><center><br>Prosimy o wpłatę na konto: xxxx xxxx xxxx xxxx<br>LapKom Sp. z o.o. <br>
ul. Sienkiewicza 4<br> 02-366 Warszawa<br>Kwota: $suma<br>Tytułem: $idzam</center>";
        }
        else if($_POST['platnosc']==2){
            echo "Dziękujemy za zakupy</center></h1><center><br>Teraz możesz opłacić zamówienie</center>";
          echo "<form name='do_platnosci' method='POST' action='https://ssl.dotpay.pl/test_payment/'>
        
                <input type='hidden' name='api_version' value='dev' />

        <input type='hidden' name='id' value='748012' />
        <input type='hidden' name='opis' value=".$idzam." />
        <input type='hidden' name='amount' value=".$suma." />
        <input type='hidden' name='type' value='0' /> 
        <input type='hidden' name='URL' value='http://lap-kom.2ap.pl/koniec.php' /> 

        <input type='submit' name='dalej' value='Opłać' /> </form>";  
        }
        else if($_POST['platnosc']==3){
                echo "<center>Dziękujemy za zakupy</center></h1><center><br>Poinformujemy Ciebie, jeżeli zamówienie zostanie zrealizowane</center>";    
        }
}
}
  



    
    
    

    
    
    



      






    
ob_end_flush();?>