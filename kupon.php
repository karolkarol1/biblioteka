<?php
require_once('connect.php');

    try
    {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      $pdo->exec("SET CHARACTER SET utf8");
    }
    catch(PDOException $e)
    {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    } 


                     $sql = 'select procent from s_rabaty where poczatek<=CURDATE() AND koniec>=CURDATE() AND nazwa=:nazwa';

       $sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));


$sth->execute(array(':nazwa' => $_POST['nazwa']));



$kupon = $sth->fetchAll();

//print_r($kupon);

//$resultJSON2 = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$arraycalosc=array($label,$data);


$resultJSON = json_encode($kupon, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

echo $resultJSON;

    
?>

