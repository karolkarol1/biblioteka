<?php
session_start();
if(isset($_POST['id_ksiazka'])){
$id_ksiazka = $_POST['id_ksiazka'];
$ilosc = $_POST['ilosc'];
}

else if(isset($_SESSION['rezerwacja'])){
$id_ksiazka = $_SESSION['rezerwacja'];
$ilosc = $_SESSION['ilosc'];
}

if(isset($_SESSION['login']))
$login = $_SESSION['login'];
else{
    $_SESSION['rezerwacja'] = $id_ksiazka;
     $_SESSION['ilosc'] = $ilosc;
    header('Location: panel.php');
}


//echo $login." ".$id_ksiazka." ".$ilosc;

if(isset($login) & isset($id_ksiazka) & isset($ilosc))
{
require_once "connect.php";
//Connect
   try
   {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      
$pdo->exec('SET NAMES utf8');


   }
   catch(PDOException $e)
   {
      echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
   }

//User ID
        $sql ='SELECT * FROM b_uzytkownicy WHERE login = :login';
$sth = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$sth->execute(array(':login' => $login));
$produkt = $sth->fetchAll();

foreach ($produkt as $key) {
            $id_user = $key['u_id'];
          };

$pdo= null;
    
//echo $id_user;

//REZERWACJA KSIAZKI
        try{
            $p = new mysqli($host,$db_user,$db_password,$db_name);
            mysqli_set_charset($p,"utf8");
            if($p->connect_errno != 0){
            throw new Exception(mysqli_connect_errno());
            }
            else{
            	$datetime1 = new DateTime("now");
      			$d=$datetime1->format('Y-m-d H:i:s');

       			$datetime2=$datetime1->modify('+14 day');
        		$d2=$datetime2->format('Y-m-d H:i:s');
                    if($p->query(" INSERT INTO b_rezerwacje VALUES ('NULL','$id_user','0','$d','$d2','$id_ksiazka')")){
                        $ok = true;
                    }
                    else{
                        $ok = false;
                        throw new Exception($p->error);}
                $p->close();
            }
        }
        catch(Exception $e){
            echo '<span style="color:red">Error!</span>';
            echo '<br />Info:'.$e;
        }

// ILOSC --
if($ok){
    $ilosc--;
            try{
            $p = new mysqli($host,$db_user,$db_password,$db_name);
            mysqli_set_charset($p,"utf8");
            if($p->connect_errno != 0){
            throw new Exception(mysqli_connect_errno());
            }
            else{
                    if($p->query(" UPDATE b_ksiazki SET `ilosc`= '$ilosc' WHERE `k_id` = '$id_ksiazka'")){
                        $ok = true;
                    }
                    else{
                        $ok = false;
                        throw new Exception($p->error);}
                $p->close();
            }
        }
        catch(Exception $e){
            echo '<span style="color:red">Error!</span>';
            echo '<br />Info:'.$e;
        }
}

if($ok)
    $_SESSION['alert'] = '<script>alert("Zarezerwowano książkę");</script>';
else
    $_SESSION['alert'] = '<script>alert("Coś poszło nie tak");</script>';

unset($_SESSION['ilosc']);
unset($_SESSION['rezerwacja']);
header("Location: ksiazka.php?id=$id_ksiazka");
}






?>