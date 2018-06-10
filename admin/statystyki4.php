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


$statement=$pdo->prepare("select kat.nazwa, sum(b.r_id) nazwaa from b_rezerwacje b JOIN b_ksiazki k ON(b.id_ksiazki=k.k_id) JOIN b_kategorie kat ON(k.kat_id=kat.id) WHERE MONTH(CURDATE())=MONTH(b.data_poczatek) group by kat.nazwa");

$statement->execute();


$arr = array();
while($row = $statement->fetch(PDO::FETCH_NUM)) {
  $label[] = $row[0];
$data[] = $row[1];

}

//$resultJSON2 = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$arraycalosc=array($label,$data);


$resultJSON = json_encode($arraycalosc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

echo $resultJSON;

    
?>

