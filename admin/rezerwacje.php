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

<td>
<td><?php if($row['status']!=0){ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Zdezaktywuj"><input type="hidden" name="status" value="-1"></form><?php }
                          else{ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Aktywuj"><input type="hidden" name="status" value="0"></form><?php }
                          ?><br>
                          
                          

                          <?php if($row['status']==1){ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Usuń bibliotekarza"><input type="hidden" name="status" value="0"></form><?php }
                          else{ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Aktywuj bibliotekarza"><input type="hidden" name="status" value="1"></form><?php }
                          ?><br>


                          <?php if($row['status']==2){ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Usuń admina"><input type="hidden" name="status" value="0"></form><?php }
                          else{ ?><form method="POST" action="rezerwacje.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Dodaj admina"><input type="hidden" name="status" value="2"></form><?php }
                          ?><br>
                        
                        
                        <form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="del_u" value="Usuń"></form></td></tr>
</td>

                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>