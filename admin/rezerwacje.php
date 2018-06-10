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


          <i class="fa fa-table"> Dodaj rezerwację</i>
        <div class="container">
            <form class="form-signin" id="addProduct" method="POST" action="rezerwacje.php"><br>

                <div class="form-control">
                <select data-placeholder="Użytkownik" name="u_id" class="chosen-select" tabindex="2" required style="width:100%">
                <option value=""></option>

                    <?php
                        $categorys=$pdo->query("SELECT u_id, login from b_uzytkownicy");
                
                        foreach($categorys as $row){                 
                    ?>
                     <option value="<?php echo $row['u_id']; ?>" ><?php echo $row['login']; ?></option>
                    
                    <?php }   
                    ?>
                </select>
                
                </div>
                <br>
                <div class="form-control">
                <select data-placeholder="Książka" name="k_id" class="chosen-select" tabindex="2" required style="width:100%">
                <option value=""></option>

                    <?php
                        $categorys=$pdo->query("SELECT k_id, tytul from b_ksiazki");
                
                        foreach($categorys as $row){                 
                    ?>
                     <option value="<?php echo $row['k_id']; ?>" ><?php echo $row['tytul']; ?></option>
                    
                    <?php }   
                    ?>
                </select>
                </div>
                <br>

                <div class="form-control">
                <select data-placeholder="Status" name="status" class="chosen-select" tabindex="2" required style="width:100%">
                <option value="0">Zarezerwowana</option>
                <option value="1">Wypożyczona</option>
                <option value="2">Oddana</option>

                </select>
                </div>
<?php
        $datetime1 = new DateTime("now");
        $d=$datetime1->format('Y-m-d H:i:s');

        $datetime2=$datetime1->modify('+14 day');
        $d2=$datetime2->format('Y-m-d H:i:s');



?>
Data początkowa:

        <input type="text" name="data_poczatek" id="datetimepicker4" class="form-control" placeholder="Data początkowa" value="<?php echo $d ?>" required>
Data końcowa:
        <input type="text" name="data_koniec" class="form-control" placeholder="Data końcowa" value="<?php echo $d2 ?>" required>

                <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj rezerwację</button>
            </form>
        </div>


<?php
    if(isset($_POST['submit'])){



        $addres=$pdo->prepare("INSERT INTO b_rezerwacje VALUES(null,:u_id,:status,:data_poczatek,:data_koniec,:id_ksiazki)");
        $addres->bindParam(':u_id',$_POST['u_id']);
        $addres->bindParam(':status',$_POST['status']);
        $addres->bindParam(':data_poczatek',$_POST['data_poczatek']);
        $addres->bindParam(':data_koniec',$_POST['data_koniec']);
        $addres->bindParam(':id_ksiazki',$_POST['k_id']);

        $addres->execute();

echo '<meta http-equiv="refresh" content="1">';
    }
?>


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
    <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>

  <link rel="stylesheet" href="docsupport/style.css">
  <link rel="stylesheet" href="docsupport/prism.css">
  <link rel="stylesheet" href="chosen.css">

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script>


