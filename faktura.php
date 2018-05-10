<?php 
require_once "connect.php";
 include('admin/pdf/phpinvoice.php');

        try
        {
          $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
          $pdo->exec("SET CHARACTER SET utf8");
        }
        catch(PDOException $e)
        {
            echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
        }   

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
        $decrypted_txt = encrypt_decrypt('decrypt', $_GET['id']);
        
        $produkty=$pdo->prepare("SELECT p.id_produktu,p.id_zamowienia,p.ilosc,p.cena,z.u_id,z.z_id,z.dostawa,z.dane,z.status,date(z.data) as dejta,year(z.data) as rok,z.cena as cena2,z.czyoplacone,pro.nazwa FROM s_zamowienia z JOIN s_zamowienia_produkty p ON z.z_id = p.id_zamowienia JOIN s_produkty pro ON pro.p_id=p.id_produktu WHERE id_zamowienia='".$decrypted_txt."'");
        $produkty->execute();
        $r_produkty=$produkty->fetchAll();
        $parts = explode(':', $r_produkty[0]['dane']);
        $date=date('d-m-Y');
        $invoice = new phpinvoice();
        /* Header Settings */
        $invoice->setLogo("img/header.png");
        $invoice->setColor("#AA3939");
        $invoice->setType("Faktura");
        $invoice->setReference($decrypted_txt.'/'.$r_produkty[0]['rok']);
        $invoice->setDate($r_produkty[0]['dejta']);
        $invoice->setFrom(array("Lap-Kom.pl","LapKom Sp. z o.o.","ul. Sienkiewicza 4","02-366 Warszawa","NIP: 525 24 10 088"));
        $invoice->setTo(array($parts[0].' '.$parts[1],$parts[2],$parts[3].' '.$parts[4]));
        /* Adding Items in table */
    
        foreach($r_produkty as $row){
        $invoice->addItem($row['nazwa'],$row['ilosc'],$row['cena'],$row['ilosc']*$row['cena']);
        }
        if($r_produkty[0]['dostawa'] == 1){
            $tmp=20;
            $invoice->addItem("Przesylka - Kurier",0,20,20);
        }
        elseif($r_produkty[0]['dostawa'] == 2){
            $tmp=15;
            $invoice->addItem("Przesylka - Poczta Polska",0,15,15); 
        }
        /* Add totals */
        $invoice->addTotal("Suma",$r_produkty[0]['cena2']);
        $invoice->addTotal("VAT 23%",$r_produkty[0]['cena2']*0.23);
        $invoice->addTotal("Razem",$r_produkty[0]['cena2'],true);
        /* Set badge */
        /* Set footer note */
        $invoice->setFooternote($decrypted_txt);
        /* Render */
        $invoice->render('fakutra.pdf','I'); /* I => Display on browser, D => Force Download, F => local path save, S => return document path */

?>