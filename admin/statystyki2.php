<?php
require_once('../connect.php');

    try
    {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      $pdo->exec("SET CHARACTER SET utf8");
    }
    catch(PDOException $e)
    {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    } 


$statement=$pdo->prepare("
select month(z.data_poczatek) as datac, COUNT(z.r_id) as suma, year(z.data_poczatek) as y
from b_rezerwacje z
group by datac
order by y ASC, datac ASC");

$statement->execute();


$arr = array();
while($row = $statement->fetch(PDO::FETCH_NUM)) {
    if (($row[0])==1){
        $tekst="Styczeń";
    }
        else if (($row[0])==2){
        $tekst="Luty";
    }
            else if (($row[0])==3){
        $tekst="Marzec";
    }
            else if (($row[0])==4){
        $tekst="Kwiecień";
    }
            else if (($row[0])==5){
        $tekst="Maj";
    }
            else if (($row[0])==6){
        $tekst="Czerwiec";
    }
            else if (($row[0])==7){
        $tekst="Lipiec";
    }
            else if (($row[0])==8){
        $tekst="Sierpień";
    }
            else if (($row[0])==9){
        $tekst="Wrzesień";
    }
            else if (($row[0])==10){
        $tekst="Październik";
    }
            else if (($row[0])==11){
        $tekst="Listopad";
    }
          else if (($row[0])==12){
        $tekst="Grudzień";
    }
  $label[] = $tekst;
$data[] = $row[1];

}

//$resultJSON2 = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$arraycalosc=array($label,$data);


$resultJSON = json_encode($arraycalosc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

echo $resultJSON;

    
?>

