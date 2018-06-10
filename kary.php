<?php 

$title = "Strona Główna";
require_once "header.php";









if (isset ($_SESSION['login'])){

    require_once "connect.php";
    
        
    

    $sql = 'select * from b_kary kary JOIN b_rezerwacje r ON (kary.r_id=r.r_id) JOIN b_ksiazki ks ON(ks.k_id=r.id_ksiazki)
    where r.u_id=:id ORDER BY kary.k_id DESC';
    
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
        
        $sth->execute(array(':id' => $_SESSION['id']));
    $zamowienia = $sth->fetchAll();
    
    
    echo '<article><h1>Naliczone kary</h1>';
        
        if ($zamowienia==false)
        {
                    echo 'Brak naliczonych kar';    
        }
        else{
            
    echo '<table class="zamowienia">
        <thead><tr><td>Numer kary</td><td>Numer rezerwacji</td><td>Książka</td><td>Opóźnienie<br>[w dniach]</td><td>Cena</td><td></td></tr></thead><tbody>';
        
                    foreach ($zamowienia as $row){
         

                        
                                if ($row['status']==0){
            $status='Zarezerwowana';
        }
        else if ($row['status']==1){
            $status='Wypożyczona';
        } 
          else if ($row['status']==2){
            $status='Oddana';
        }
                        
             
        
        $datetime1 = new DateTime($row['data_koniec']);
        $datetime2 = new DateTime($row['data_poczatek']);
        $interval = $datetime1->diff($datetime2);
        $elapsed = $interval->format('%a');
        // echo $elapsed;

        if($row['oplacona']==0){
$oplacona="<form name='do_platnosci' method='POST' action='https://ssl.dotpay.pl/test_payment/'>
        
    <input type='hidden' name='api_version' value='dev' />

<input type='hidden' name='id' value='748012' />
<input type='hidden' name='opis' value=".$row['k_id']." />
<input type='hidden' name='amount' value=".$row['cena']." />
<input type='hidden' name='type' value='0' /> 
<input type='hidden' name='URL' value='http://lap-kom.2ap.pl/koniec.php' /> 

<input type='submit' name='bt' value='Opłać' /> </form>";



        }
        else{
            $oplacona='Opłacona';
        }
                                   
                   echo '<tr><td>'.$row['k_id'].'</td><td><a href="rezerwacje.php">'.$row['r_id'].'</a></td><td><a href="ksiazka.php?id='.$row['k_id'].'">'.$row['tytul'].'</a></td><td>'.$elapsed.'</td><td>'.$row['cena'].'</td><td>'.$oplacona.'</td></tr>';
                    }
            echo '</tbody></table>';
        }
    
    }







?>




    
</article>

  
<?php  require_once "footer.php";
?>