<?php
session_start();

if($_SESSION['admin']!=1){
exit();
}
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
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Sprawdź kody rabatowe</li>
        </ol>
        <div class="card-header">
          <i class="fa fa-table"></i> Dodaj rabat
        </div>
        <div class="container">
            <form class="form-signin" id="addProduct" method="POST" action="rabaty.php" ENCTYPE="multipart/form-data"><br>
                <input type="text" name="nazwa" class="form-control" placeholder="Nazwa" required autofocus>
                <input type="text" name="procent" class="form-control" placeholder="Procent" required>
                <label for="name">Aktywny od</label>
                <input type="date" class="form-control" id="id" name="poczatek" required />
                <label for="name">Wygasa</label>
                <input type="date" class="form-control" id="id" name="koniec"  required /><br>
                <button type="submit" name="rabat" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj rabat</button>
            </form>
            <?php
            if(isset($_POST['rabat'])){
                $rabat=$pdo->prepare("INSERT INTO s_rabaty VALUES(null,:nazwa,:procent,:poczatek,:koniec)");
                $rabat->bindParam(':nazwa',$_POST['nazwa']);
                $rabat->bindValue(':procent',$_POST['procent']);
                $rabat->bindValue(':poczatek',$_POST['poczatek']);
                $rabat->bindValue(':koniec',$_POST['koniec']);
                $rabat->execute();
            ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Dodano</strong> nowy rabat.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <meta http-equiv="refresh" content="1">
            <?php } ?>
        </div> 
        <div class="card mb-3">
            <div class="card-header">
            <i class="fa fa-table"></i> Lista kodow </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa</th>
                                <th>Zniżka %</th>
                                <th>Utworzono</th>
                                <th>Wygasa</th>
                                <th>Produkt</th>
                                <th>Status</th>
                                <th>Operacje</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Nazwa</th>
                                <th>Zniżka %</th>
                                <th>Utworzono</th>
                                <th>Wygasa</th>
                                <th>Produkt</th>
                                <th>Status</th>
                                <th>Operacje</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php 
                                $rabaty=$pdo->query("SELECT *FROM s_rabaty");
                                foreach($rabaty as $row){
                            ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td><td><?php echo $row['nazwa']; ?></td><td><?php echo $row['procent']; ?></td><td><?php echo $row['poczatek']; ?></td><td><?php echo $row['koniec']; ?></td><td><?php echo $row['produkty']; ?></td><td><?php if(date('Y-m-d') > $row['koniec']) echo "<font color='red'>Wygasł</font>"; else echo "<font color='green'>Aktywny</font>";?></td><td><form method="POST" action="rabaty.php?id_rabatu=<?php echo $row['id'] ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="rabat" onclick="return confirm('Czy na pewno chcesz usunąć kod ?')" value="Usuń"></form></td>
                            </tr>
                            <?php } 
                                if(isset($_POST['rabat'])){ 
                                    $delete=$pdo->exec("DELETE FROM s_rabaty WHERE id='".$_GET['id_rabatu']."'");
                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Kod rabatowy</strong> o ID: <?php echo $_GET['id_rabatu']; ?> został usunięty.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <meta http-equiv="refresh" content="3">
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
