<?php
require_once "header.php";
require_once "../connect.php";
    try
    {
      $pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $db_user, $db_password);
      $pdo->exec("SET CHARACTER SET utf8");
    }
    catch(PDOException $e)
    {
        echo 'Połączenie nie mogło zostać utworzone: ' . $e->getMessage();
    } 
?>
<div class="content-wrapper">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
            <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Zamówienia</li>
        </ol>
        <div class="card mb-3">
                <?php
                    
                    if($_SESSION['admin']==2){
                        if($_GET['id_zamowienia']){
                        $id=$_SESSION['id'];
                        $zam_partnera=$pdo->prepare("SELECT p.id_produktu,p.id_zamowienia,p.ilosc,p.cena,z.z_id,z.dostawa,z.dane,z.status,z.data,z.cena as cena2,z.czyoplacone,pro.nazwa FROM s_zamowienia z JOIN s_zamowienia_produkty p ON z.z_id = p.id_zamowienia JOIN s_produkty pro ON pro.p_id=p.id_produktu WHERE id_zamowienia='".$_GET['id_zamowienia']."'");
                        $zam_partnera->execute();
                        $r_zam_partnera=$zam_partnera->fetchAll();
                        $parts = explode(':', $r_zam_partnera[0]['dane']);
                ?>
                        <h1>Szczegóły dla zamówienia numer <?php echo $_GET['id_zamowienia'];?></h1><br>
                            <p>Zamówienie numer: <?php echo $_GET['id_zamowienia']; ?></p>
                            <p>Data zamówienia: <?php echo $r_zam_partnera[0]['data']; ?></p>
                            <span>Status zamówienia: <?php 
                                    if($r_zam_partnera[0]['status'] == 1) echo "Złożone";
                                elseif($r_zam_partnera[0]['status'] == 2) echo "Anulowane";
                                elseif($r_zam_partnera[0]['status'] == 3) echo "W realizacji";
                                elseif($r_zam_partnera[0]['status'] == 4) echo "Wysłano kurierem";
                                elseif($r_zam_partnera[0]['status'] == 5) echo "Gotowe do odbioru";
                                elseif($r_zam_partnera[0]['status'] == 6) echo "Odebrano";
                                elseif($r_zam_partnera[0]['status'] == 0) echo "Zamowienie stacjonarne";?> ZMIEŃ NA => 
                                <form method="POST" action="zamowienia.php?id_zamowienia=<?php echo $_GET['id_zamowienia']; ?>" role="form">
                                    <select class="form" name="newstatus">
                                        <option value="1" >Złożone</option>
                                        <option value="2" >Anulowane</option>
                                        <option value="3" >W realizacji</option>
                                        <option value="4" >Wysłano kurierem</option>
                                        <option value="5" >Gotowe do odbioru</option>
                                        <option value="6" >Odebrano</option>
                                    </select>
                                    <button style="width:100px;" class="btn btn-warning" type="submit" name="status">Zmień</button>
                                </form>
                                <?php
                                    if(isset($_POST['status'])){
                                    $update_status=$pdo->prepare("UPDATE s_zamowienia SET status=:status WHERE z_id=:id");
                                    $update_status->bindValue(':status',$_POST['newstatus']);
                                    $update_status->bindValue(':id',$r_zam_partnera[0]['z_id']);
                                    $update_status->execute();
                                ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Zmieniono</strong> poprawnie status.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <meta http-equiv="refresh" content="2">
                                <?php } ?>
                            </span>
                            <p>Dostawa: <?php if($r_zam_partnera[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_zam_partnera[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_zam_partnera[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></p>
                            <p>Dane:<br><?php echo $parts[0].' '.$parts[1].'<br>ul. '.$parts[2].'<br>'.$parts[3].' '.$parts[4];?><br></p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nazwa produktu</th>
                                            <th>Ilość</th>
                                            <th>Cena</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($r_zam_partnera as $row){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['nazwa'];?></td>
                                            <td><?php echo $row['ilosc']; ?></td>
                                            <td><?php echo $row['cena']; ?></td>
                                        </tr>
                                        <?php } ?>
                                            <td><?php if($r_zam_partnera[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_zam_partnera[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_zam_partnera[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></td>
                                            <td></td>
                                            <td><?php if($r_zam_partnera[0]['dostawa'] == 1) echo "20"; elseif($r_zam_partnera[0]['dostawa'] == 2) echo "15"; elseif($r_zam_partnera[0]['dostawa'] == 3) echo "0"; ?></td>        
                                        <tr>
                                        <td><b>Całkowita wartość zamówienia:</b></td>
                                        <td></td>
                                        <td><?php echo $r_zam_partnera[0]['cena2']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><br>
                            <?php
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
                            $tmp=$_GET['id_zamowienia'];
                            $encrypted_txt = encrypt_decrypt('encrypt', $tmp);
                            ?>
                            <button type="button" class="btn btn-warning" onclick="location.href = 'faktura.php?id=<?php echo $encrypted_txt; ?>'">Pobierz fakturę</button>
                <?php
                    }
                        else { ?>
                <div class="card-header"><i class="fa fa-table"></i> Lista z zamówieniami</div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Numer zamówienia</th>
                            <th>Data zamówienia</th>
                            <th>Status</th>
                            <th>Opłacone</th>
                            <th>Cena</th>
                            <th>Sprawdź zamówienie</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Numer zamówienia</th>
                            <th>Data zamówienia</th>
                            <th>Status</th>
                            <th>Opłacone</th>
                            <th>Cena</th>
                            <th>Sprawdź zamówienie</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $zam_partner=$pdo->query("select * from s_zamowienia_partnerzy szp join s_partnerzy p on (szp.id_punktu=p.punkt_id) join s_zamowienia sz on (szp.id_zamowienia=sz.z_id) where p.u_id='".$_SESSION['id']."' ORDER BY z_id"); 
                            foreach($zam_partner as $row){
                        ?>
                        <tr>
                        <td><?php echo $row['z_id'];?></td>
                        <td><?php echo $row['data'];?></td>
                        <td>
                            <?php
                                    if($row['status'] == 1) echo "Złożone";
                                elseif($row['status'] == 2) echo "Anulowane";
                                elseif($row['status'] == 3) echo "W realizacji";
                                elseif($row['status'] == 4) echo "Wysłano kurierem";
                                elseif($row['status'] == 5) echo "Gotowe do odbioru";
                                elseif($row['status'] == 6) echo "Odebrano";
                                elseif($row['status'] == 0) echo "Zamowienie stacjonarne";
                            ?>
                        </td>
                        <td><?php if($row['czyoplacone'] == 0)echo "Nieopłacone"; else echo "Opłacone";?></td>
                        <td><?php echo $row['cena']; ?></td>
                        <td>
                            <form method="POST" action="zamowienia.php?id_zamowienia=<?php echo $row['z_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="zamowienie" value="Sprawdź"></form>
                        </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <?php }
                    }
                    else{
                    if($_GET['id_zamowienia']){
                        if($_GET['log']==1){
                            $produkty=$pdo->prepare("SELECT p.id_produktu,p.id_zamowienia,p.ilosc,p.cena,z.u_id,z.z_id,z.dostawa,z.dane,z.status,z.data,z.cena as cena2,z.czyoplacone,pro.nazwa FROM s_zamowienia z JOIN s_zamowienia_produkty p ON z.z_id = p.id_zamowienia JOIN s_produkty pro ON pro.p_id=p.id_produktu WHERE id_zamowienia='".$_GET['id_zamowienia']."'");
                            $produkty->execute();
                            $r_produkty=$produkty->fetchAll();
                            $parts = explode(':', $r_produkty[0]['dane']);
                            ?>
                            <h1>Szczegóły dla zamówienia numer <?php echo $_GET['id_zamowienia'];?></h1><br>
                            <p>Zamówienie numer: <?php echo $_GET['id_zamowienia']; ?></p>
                            <p>Data zamówienia: <?php echo $r_produkty[0]['data']; ?></p>
                            <span>Status zamówienia: <?php 
                                    if($r_produkty[0]['status'] == 1) echo "Złożone";
                                elseif($r_produkty[0]['status'] == 2) echo "Anulowane";
                                elseif($r_produkty[0]['status'] == 3) echo "W realizacji";
                                elseif($r_produkty[0]['status'] == 4) echo "Wysłano kurierem";
                                elseif($r_produkty[0]['status'] == 5) echo "Gotowe do odbioru";
                                elseif($r_produkty[0]['status'] == 6) echo "Odebrano";
                                elseif($r_produkty[0]['status'] == 0) echo "Zamowienie stacjonarne";?> ZMIEŃ NA => 
                                <form method="POST" action="zamowienia.php?log=1&id_zamowienia=<?php echo $_GET['id_zamowienia']; ?>" role="form">
                                    <select class="form" name="newstatus">
                                        <option value="1" >Złożone</option>
                                        <option value="2" >Anulowane</option>
                                        <option value="3" >W realizacji</option>
                                        <option value="4" >Wysłano kurierem</option>
                                        <option value="5" >Gotowe do odbioru</option>
                                        <option value="6" >Odebrano</option>
                                    </select>
                                    <button style="width:100px;" class="btn btn-warning" type="submit" name="status">Zmień</button>
                                </form>
                                <?php
                                    if(isset($_POST['status'])){
                                    $update_status=$pdo->prepare("UPDATE s_zamowienia SET status=:status WHERE z_id=:id");
                                    $update_status->bindValue(':status',$_POST['newstatus']);
                                    $update_status->bindValue(':id',$r_produkty[0]['z_id']);
                                    $update_status->execute();
                                    
                                ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Zmieniono</strong> poprawnie status.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <meta http-equiv="refresh" content="2">
                                <?php } ?>
                            </span>
                            <p>Dostawa: <?php if($r_produkty[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_produkty[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_produkty[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></p>
                            <p>Dane:<br><?php echo $parts[0].' '.$parts[1].'<br>ul. '.$parts[2].'<br>'.$parts[3].' '.$parts[4];?><br></p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nazwa produktu</th>
                                            <th>Ilość</th>
                                            <th>Cena</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($r_produkty as $row){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['nazwa'];?></td>
                                            <td><?php echo $row['ilosc']; ?></td>
                                            <td><?php echo $row['cena']; ?></td>
                                        </tr>
                                        <?php } ?>
                                            <td><?php if($r_produkty[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_produkty[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_produkty[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></td>
                                            <td></td>
                                            <td><?php if($r_produkty[0]['dostawa'] == 1) echo "20"; elseif($r_produkty[0]['dostawa'] == 2) echo "15"; elseif($r_produkty[0]['dostawa'] == 3) echo "0"; ?></td>        
                                        <tr>
                                        <td><b>Całkowita wartość zamówienia:</b></td>
                                        <td></td>
                                        <td><?php echo $r_produkty[0]['cena2']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><br>
                            <?php
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
                            $tmp=$_GET['id_zamowienia'];
                            $encrypted_txt = encrypt_decrypt('encrypt', $tmp);
                            ?>
                            <button type="button" class="btn btn-warning" onclick="location.href = 'faktura.php?id=<?php echo $encrypted_txt ?>';">Pobierz fakturę</button>        
                    <?php } else{
                            $produkty=$pdo->prepare("SELECT p.id_produktu,p.id_zamowienia,p.ilosc,p.cena,z.z_id,z.dostawa,z.dane,z.status,z.data,z.cena as cena2,z.czyoplacone,pro.nazwa FROM s_zamowienia z JOIN s_zamowienia_produkty p ON z.z_id = p.id_zamowienia JOIN s_produkty pro ON pro.p_id=p.id_produktu WHERE id_zamowienia='".$_GET['id_zamowienia']."'");
                            $produkty->execute();
                            $r_produkty=$produkty->fetchAll();
                            $parts = explode(':', $r_produkty[0]['dane']);
                            ?>
                            <h1>Szczegóły dla zamówienia numer <?php echo $_GET['id_zamowienia'];?></h1><br>
                            <p>Zamówienie numer: <?php echo $_GET['id_zamowienia']; ?></p>
                            <p>Data zamówienia: <?php echo $r_produkty[0]['data']; ?></p>
                            <span>Status zamówienia: <?php 
                                    if($r_produkty[0]['status'] == 1) echo "Złożone";
                                elseif($r_produkty[0]['status'] == 2) echo "Anulowane";
                                elseif($r_produkty[0]['status'] == 3) echo "W realizacji";
                                elseif($r_produkty[0]['status'] == 4) echo "Wysłano kurierem";
                                elseif($r_produkty[0]['status'] == 5) echo "Gotowe do odbioru";
                                elseif($r_produkty[0]['status'] == 6) echo "Odebrano";
                                elseif($r_zam_partnera[0]['status'] == 0) echo "Zamowienie stacjonarne";?> ZMIEŃ NA => 
                                <form method="POST" action="zamowienia.php?log=0&id_zamowienia=<?php echo $_GET['id_zamowienia']; ?>" role="form">
                                    <select class="form" name="newstatus">
                                        <option value="1" >Złożone</option>
                                        <option value="2" >Anulowane</option>
                                        <option value="3" >W realizacji</option>
                                        <option value="4" >Wysłano kurierem</option>
                                        <option value="5" >Gotowe do odbioru</option>
                                        <option value="6" >Odebrano</option>
                                    </select>
                                    <button style="width:100px;" class="btn btn-warning" type="submit" name="status">Zmień</button>
                                </form>
                                <?php
                                    if(isset($_POST['status'])){
                                    $update_status=$pdo->prepare("UPDATE s_zamowienia SET status=:status WHERE z_id=:id");
                                    $update_status->bindValue(':status',$_POST['newstatus']);
                                    $update_status->bindValue(':id',$r_produkty[0]['z_id']);
                                    $update_status->execute();
                                ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Zmieniono</strong> poprawnie status.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <meta http-equiv="refresh" content="2">
                                <?php } ?>
                            </span>
                            <p>Dostawa: <?php if($r_produkty[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_produkty[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_produkty[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></p>
                            <p>Dane:<br><?php echo $parts[0].' '.$parts[1].'<br>ul. '.$parts[2].'<br>'.$parts[3].' '.$parts[4];?><br></p>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered"  width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Nazwa produktu</th>
                                            <th>Ilość</th>
                                            <th>Cena</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            foreach($r_produkty as $row){
                                        ?>
                                        <tr>
                                            <td><?php echo $row['nazwa'];?></td>
                                            <td><?php echo $row['ilosc']; ?></td>
                                            <td><?php echo $row['cena']; ?></td>
                                        </tr>
                                        <?php } ?>
                                            <td><?php if($r_produkty[0]['dostawa'] == 1) echo "Przesyłka - Kurier"; elseif($r_produkty[0]['dostawa'] == 2) echo "Przesyłka - Poczta Polska"; elseif($r_produkty[0]['dostawa'] == 3) echo "Odbiór osobisty"; ?></td>
                                            <td></td>
                                            <td><?php if($r_produkty[0]['dostawa'] == 1) echo "20"; elseif($r_produkty[0]['dostawa'] == 2) echo "15"; elseif($r_produkty[0]['dostawa'] == 3) echo "0"; ?></td>        
                                        <tr>
                                        <td><b>Całkowita wartość zamówienia:</b></td>
                                        <td></td>
                                        <td><?php echo $r_produkty[0]['cena2']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><br>
                            <?php
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
                            $tmp=$_GET['id_zamowienia'];
                            $encrypted_txt = encrypt_decrypt('encrypt', $tmp);
                            ?>
                            <button type="button" class="btn btn-warning" onclick="location.href = 'faktura.php?id=<?php echo $encrypted_txt ?>';">Pobierz fakturę</button>
                    <?php } ?>
        
                <?php }else { ?>
                <div class="card-header"><i class="fa fa-table"></i> Lista z zamówieniami</div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Numer zamówienia</th>
                            <th>Data zamówienia</th>
                            <th>Status</th>
                            <th>Opłacone</th>
                            <th>Cena</th>
                            <th>Sprawdź zamówienie</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Numer zamówienia</th>
                            <th>Data zamówienia</th>
                            <th>Status</th>
                            <th>Opłacone</th>
                            <th>Cena</th>
                            <th>Sprawdź zamówienie</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $zamowienia=$pdo->query("SELECT z_id,data,status,czyoplacone,cena,u_id FROM s_zamowienia ORDER BY z_id"); 
                            foreach($zamowienia as $row){
                        ?>
                        <tr>
                        <td><?php echo $row['z_id'];?></td>
                        <td><?php echo $row['data'];?></td>
                        <td>
                            <?php
                                    if($row['status'] == 1) echo "Złożone";
                                elseif($row['status'] == 2) echo "Anulowane";
                                elseif($row['status'] == 3) echo "W realizacji";
                                elseif($row['status'] == 4) echo "Wysłano kurierem";
                                elseif($row['status'] == 5) echo "Gotowe do odbioru";
                                elseif($row['status'] == 6) echo "Odebrano";
                                elseif($row['status'] == 0) echo "Zamowienie stacjonarne";
                            ?>
                        </td>
                        <td><?php if($row['czyoplacone'] == 0)echo "Nieopłacone"; else echo "Opłacone";?></td>
                        <td><?php echo $row['cena']; ?></td>
                        <td>
                            <form method="POST" action="zamowienia.php?log=<?php if(isset($row['u_id'])) echo "1"; else echo "0";?>&id_zamowienia=<?php echo $row['z_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="zamowienie" value="Sprawdź"></form>
                        </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
                <?php } }?>
            </div>
        </div>
    </div>