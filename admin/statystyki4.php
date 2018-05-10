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


$statement=$pdo->prepare("select k2.nazwa nazwa, sum(zp.ilosc) nazwaa from s_zamowienia_produkty zp JOIN s_zamowienia z ON (zp.id_zamowienia=z.z_id) JOIN s_produkty p ON (zp.id_produktu=p.p_id) JOIN s_kategorie k ON(p.kat_id=k.id) JOIN s_kategorie k2 ON (k.id_rodzica=k2.id) WHERE MONTH(CURDATE())=MONTH(z.data) AND z.czyoplacone=1 group by k2.nazwa");

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

