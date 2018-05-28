<?php 

$title = "Strona Główna";
require_once "header.php";









if (isset ($_SESSION['login'])){

    require_once "connect.php";
    
        
    

    $sql = 'SELECT r_id, data_poczatek, status, id_ksiazki, tytul, data_poczatek, data_koniec, status FROM b_rezerwacje LEFT JOIN b_ksiazki ON b_rezerwacje.id_ksiazki=b_ksiazki.k_id
    where b_rezerwacje.u_id=:id ORDER BY r_id DESC';
    
    $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    
        
        $sth->execute(array(':id' => $_SESSION['id']));
    $zamowienia = $sth->fetchAll();
    
    
    echo '<article><h1>Rezerwacje</h1>';
        
        if ($zamowienia==false)
        {
                    echo 'Brak zarejestrowanych książek';    
        }
        else{
            
    echo '<table class="zamowienia">
        <thead><tr><td>Numer rezerwacji</td><td>Tytuł</td><td>Data rozpoczęcia</td><td>Data zakończenia</td><td>Status</td></tr></thead><tbody>';
        
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
                        
                                   
                                   
                   echo '<tr><td>'.$row['r_id'].'</td><td><a href="ksiazka.php?id='.$row['id_ksiazki'].'">'.$row['tytul'].'</a></td><td>'.$row['data_poczatek'].'</td><td>'.$row['data_koniec'].'</td><td>'.$status.'</td></tr>';
                    }
            echo '</tbody></table>';
        }
    
    }







?>




    
</article>

  
<?php  require_once "footer.php";
?>