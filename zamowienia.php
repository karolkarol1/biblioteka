<?php 

$title = "Strona Główna";
require_once "header.php";
if (isset ($_SESSION['login'])){

require_once "connect.php";

    

if (is_numeric($_GET['id'])){
    
    echo '<article><h1>Szczegóły zamówienia numer '.$_GET['id'].'</h1><h3><a href="zamowienia.php">Powrót</a></h3>';
    
    
$sth = $pdo->prepare('select b.nazwa,a.ilosc,a.cena,a.id_produktu from s_zamowienia_produkty a inner join s_produkty b on a.id_produktu = b.p_id WHERE a.id_zamowienia = :zam');
$sth->bindParam(':zam', $_GET['id'], PDO::PARAM_INT);
$sth->execute();
$produkty = $sth->fetchAll();
 
    
    $sth2 = $pdo->prepare('SELECT * FROM s_zamowienia
    WHERE z_id = :zam AND u_id = :id');
$sth2->bindParam(':zam', $_GET['id'], PDO::PARAM_INT);
$sth2->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$sth2->execute();
$zamowienieszczegoly = $sth2->fetchAll();

    
    
 //   print_r($produkty);
//print_r($zamowienieszczegoly);

if (!empty($zamowienieszczegoly)) {

    
        if ($zamowienieszczegoly[0]['status']==1){
        $status='Złożone';
    }
    else if ($zamowienieszczegoly[0]['status']==2){
        $status='Anulowane';
    } 
      else if ($zamowienieszczegoly[0]['status']==3){
        $status='W realizacji';
    }
          else if ($zamowienieszczegoly[0]['status']==4){
        $status='Wysłano kurierem';
    }
          else if ($zamowienieszczegoly[0]['status']==5){
        $status='Gotowe do odbioru';
    }
          else if ($zamowienieszczegoly[0]['status']==6){
        $status='Odebrano';
    }
    
        if ($zamowienieszczegoly[0]['czyoplacone']==0){
        $opl='Nieopłacone';
    }
    else {$opl="Opłacone";}
    
    
            if ($zamowienieszczegoly[0]['dostawa']==1){
        $dostawa='Kurier';
    }
    else if($zamowienieszczegoly[0]['dostawa']==2){
        $dostawa="Poczta Polska";
    }
    else {$dostawa="Odbiór osobisty";
         
                          $sql = 'SELECT p.adres FROM s_zamowienia_partnerzy szp join s_punkty p ON szp.id_punktu=p.id_punktu WHERE szp.id_zamowienia=:id';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':id' => $_GET['id']));
  
$punkt = $sth->fetchAll();  
  
              
              $dostawa="Odbiór osobisty ";
          $dostawa.=$punkt[0][0];
         }
    
    
            $parts = explode(':', $zamowienieszczegoly[0]['dane']);

    
    echo 'Zamówienie numer: '.$_GET['id'].'<br>Data zamówienia: '.$zamowienieszczegoly[0]['data'].'<br>Status zamówienia: '.$status.'<br>Dostawa: '.$dostawa.'<br><br>Dane:<br>'.$parts[0].' '.$parts[1].'<br>ul. '.$parts[2].'<br>'.$parts[3].' '.$parts[4].'<br><br>';
    
    function encrypt_decrypt($action, $string) {
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'lapkopxdxdxdxd';
    $secret_iv = 'xdxdxdxd';
    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if( $action == 'decrypt' ) {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;

}
$tmp=$_GET['id'];
$encrypted_txt = encrypt_decrypt('encrypt', $tmp);
?>
<button type="button" class="btn btn-warning" onclick="location.href = 'faktura.php?id=<?php echo $encrypted_txt; ?>'">Pobierz fakturę</button>
<?
   echo '<table class="zamowienia">
    <thead><tr><td>Nazwa produktu</td><td>Ilość</td><td>Cena</td></tr></thead><tbody>';
    
                foreach ($produkty as $row){
                    
                        $cenazaprodukty+=$row['ilosc']*$row['cena'];
                    
               echo '<tr><td><a href="produkt.php?id='.$row['id_produktu'].'">'.$row['nazwa'].'</a></td><td>'.$row['ilosc'].'</td><td>'.$row['cena'].' zł</td></tr>';
                }
    
    if ($zamowienieszczegoly[0]['dostawa']==1){
        $dostawa='Przesyłka - Kurier';
    }
    else if ($zamowienieszczegoly[0]['dostawa']==2){
        $dostawa='Przesyłka - Poczta Polska';
    } 
      else if ($zamowienieszczegoly[0]['dostawa']==3){
        $dostawa='Odbiór osobisty ';
                    $dostawa.=$punkt[0][0];

          
          
          
    }
    
    $dostawacena=$zamowienieszczegoly[0]['cena']-$cenazaprodukty;
    
    echo '<tr><td>'.$dostawa.'</td><td></td><td>'.$dostawacena.' zł</td></tr><tr><td><span class="boldnormal">Całkowita wartość zamówienia:</span></td><td></td><td>'.$zamowienieszczegoly[0]['cena'].'</td></tr></tbody></table>';
}
    else{echo 'Błąd';}
    }
else{
$sql = 'SELECT z_id, data, status, cena, czyoplacone FROM s_zamowienia where u_id=:id ORDER BY z_id DESC';

$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    
    $sth->execute(array(':id' => $_SESSION['id']));
$zamowienia = $sth->fetchAll();


echo '<article><h1>Zamowienia</h1>';
    
    if ($zamowienia==false)
    {
                echo 'Brak zamówień';    
    }
    else{
        
echo '<table class="zamowienia">
    <thead><tr><td>Numer zamówienia</td><td>Data zamówienia</td><td>Status</td><td>Opłacone</td><td>Cena</td></tr></thead><tbody>';
    
                foreach ($zamowienia as $row){
     
                   if ($row['czyoplacone']==1){
                       $opl="Tak";
                   }
                    else{$opl="Nie";}
                    
                            if ($row['status']==1){
        $status='Złożone';
    }
    else if ($row['status']==2){
        $status='Anulowane';
    } 
      else if ($row['status']==3){
        $status='W realizacji';
    }
          else if ($row['status']==4){
        $status='Wysłano kurierem';
    }
          else if ($row['status']==5){
        $status='Gotowe do odbioru';
    }
          else if ($row['status']==6){
        $status='Odebrano';
    }
                    
                               
                               
               echo '<tr><td><a href="zamowienia.php?id='.$row['z_id'].'">'.$row['z_id'].'</a></td><td>'.$row['data'].'</td><td>'.$status.'</td><td>'.$opl.'</td><td>'.$row['cena'].' zł</td></tr>';
                }
        echo '</tbody></table>';
    }

}
?>




    
</article>

  
<?php  require_once "footer.php"; }?>
