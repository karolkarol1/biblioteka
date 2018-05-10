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
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Opinie użytwonikow</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-table"></i> Lista opinii</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>Opis</th>
                            <th>Data</th>
                            <th>Login użytkownika</th>
                            <th>Nazwa produktu</th>
                            <th>Operacje</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Opis</th>
                            <th>Data</th>
                            <th>Login użytkownika</th>
                            <th>Nazwa produktu</th>
                            <th>Operacje</th>
                        </tr>
                      </tfoot>
                      <tbody>
                            <?php
                                $users = $pdo->query("SELECT o.o_id,o.data,o.opis,u.login,p.nazwa FROM s_opinie o JOIN s_uzytkownicy u ON o.u_id = u.u_id JOIN s_produkty p ON p.p_id = o.p_id");
                                foreach($users as $row){     
                            ?>
                            <tr><td><?php echo $row['o_id']; ?></td>
                                <td><?php echo $row['opis']; ?></td>
                                <td><?php echo $row['data']; ?></td>
                                <td><?php echo $row['login']; ?></td>
                                <td><?php echo $row['nazwa']; ?></td>
                          <td><form method="POST" action="opinie.php?id_opinie=<?php echo $row['o_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="del_opinie" value="Usuń"></form></td></tr>
                            <?php }
                                if(isset($_POST['del_opinie'])){ 
                                    $id=$_GET['id_opinie'];
                                    $delete=$pdo->exec("DELETE FROM s_opinie WHERE o_id=$id");
                            ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Usunięto</strong> opinię o id <?php echo $_GET['id_opinie']; ?>.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <meta http-equiv="refresh" content="1">
                          <?php } ?>
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>    