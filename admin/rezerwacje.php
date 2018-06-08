<?php
session_start();

echo 'test';

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
            <li class="breadcrumb-item active">Rezerwacje</li>
        </ol>
        <div class="card mb-3">
                <div class="card-header"><i class="fa fa-table"></i> Rezerwacje</div>
                <div class="card-body">
                <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Nr zam.</th>
                            <th>Użytkownik</th>
                            <th>Status</th>
                            <th>Data_początek</th>
                            <th>Data_koniec</th>
                            <th>Tytuł</th>
                            <th>Operacje</th>

                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                        <th>Nr zam.</th>
                            <th>Użytkownik</th>
                            <th>Status</th>
                            <th>Data_początek</th>
                            <th>Data_koniec</th>
                            <th>Tytuł</th>
                            <th>Operacje</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                            $zamowienia=$pdo->query("SELECT r.r_id, u.login, r.status, r.data_poczatek, r.data_koniec, k.tytul FROM b_rezerwacje r JOIN b_ksiazki k ON r.id_ksiazki= k.k_id JOIN b_uzytkownicy u ON r.u_id=u.u_id"); 
                            foreach($zamowienia as $row){
                        ?>
                        <tr>
                        <td><?php echo $row[0];?></td>
                        <td><?php echo $row[1];?></td>
                        <td>
                            <?php
                                if ($row['status']==0){
                                    echo 'Zarezerwowana';
                                }
                                else if ($row['status']==1){
                                    echo 'Wypożyczona';
                                } 
                                  else if ($row['status']==2){
                                    echo 'Oddana';
                                }
                            ?>
                        </td>
                        <td><?php echo $row['data_poczatek']; ?></td>
                        <td><?php echo $row['data_koniec']; ?></td>
                        <td><?php echo $row['tytul']; ?></td>

<td><?php if($row['status']==0){ ?><form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Wypożyczona"><input type="hidden" name="status" value="1"></form>
                          <form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Oddana"><input type="hidden" name="status" value="2"></form>
                          <?php
                        }
                        ?>

                        <?php if($row['status']==1){ ?><form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Zarezerwowana"><input type="hidden" name="status" value="0"></form>
                          <form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Oddana"><input type="hidden" name="status" value="2"></form>
                          <?php
                        }
                        ?>

                        <?php if($row['status']==2){ ?><form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Zarezerwowana"><input type="hidden" name="status" value="0"></form>
                          <form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Wypożyczona"><input type="hidden" name="status" value="1"></form>
                          <?php
                        }
                        ?>
                          
                          <br>
                          
    
                        
                        <form method="POST" action="rezerwacje.php?rez_id=<?php echo $row['r_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="del_rez" value="Usuń"></form></td></tr>
</td>

                        </tr>
                        <?php } ?>







                            <?php  
                                if(isset($_POST['del_rez'])){ 
                                    $id=$_GET['rez_id'];
                                    $delete=$pdo->exec("DELETE FROM b_rezerwacje WHERE r_id=$id");
                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Usunięto</strong> rezerwacje o ID: <?php echo $_GET['rez_id']; ?>.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <meta http-equiv="refresh" content="1">
                            <?php }
                            if(isset($_POST['changestatus'])){ 
                                $id=$_GET['rez_id'];
                                $chstatus=$pdo->prepare("UPDATE b_rezerwacje SET status=:s  WHERE r_id=:u_id");
                                $chstatus->bindValue(':s',0);
                                $chstatus->bindValue(':u_id',$_GET['rez_id']);
                                $chstatus->bindValue(':s',$_POST['status']);
                                $chstatus->execute();

                                $s=$_POST['status'];

                            
                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Poprawnie</strong> wykonano operację dla rezerwacji o ID: <?php echo $_GET['rez_id']; ?>.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <meta http-equiv="refresh" content="2">
                            <?php } ?>
                          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                                            </div>
                                            <div class="dash">

                                            </div>
                                        </div>
                                    </div>
                              </div>




                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>