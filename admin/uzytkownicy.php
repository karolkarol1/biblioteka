<?php
session_start();

if($_SESSION['status']<1){
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
            <li class="breadcrumb-item active">Lista użytkownikow</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-table"></i> Użytkownicy</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                      <thead>
                        <tr>
                            <th>ID</th>
                            <th>login</th>
                            <th>Imie</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Ulica</th>
                            <th>Miasto</th>
                            <th>Kod pocztowy</th>
                            <th>Telefon</th>
                            <th>Status</th>
                            <th>Operacje</th>
                        </tr>
                      </thead>
                      <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>login</th>
                            <th>Imie</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Ulica</th>
                            <th>Miasto</th>
                            <th>Kod pocztowy</th>
                            <th>Telefon</th>
                            <th>Status</th>
                            <th>Operacje</th>
                        </tr>
                      </tfoot>
                      <tbody>
                            <?php
                                $users = $pdo->query("SELECT * FROM b_uzytkownicy");
                                foreach($users as $row){     
                            ?>
                            <tr><td><?php echo $row['u_id']; ?></td>
                                <td><?php echo $row['login']; ?></td>
                                <td><?php echo $row['imie']; ?></td>
                                <td><?php echo $row['nazwisko']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['ulica']; ?></td>
                                <td><?php echo $row['miasto']; ?></td>
                                <td><?php echo $row['kodpocztowy']; ?></td>
                                <td><?php echo $row['telefon']; ?></td>
                                <td><?php if($row['status']==-1) echo 'Nieaktywny'; elseif($row['status']==0) echo "Użytkownik"; elseif($row['status']==1) echo "Bibliotekarz"; else echo 'Admin';?></td>
                          <td><?php if($row['status']!=-1){ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Zdezaktywuj"><input type="hidden" name="status" value="-1"></form><?php }
                          else{ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Aktywuj"><input type="hidden" name="status" value="0"></form><?php }
                          ?><br>
                          
                          

                          <?php if($row['status']==1){ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Usuń bibliotekarza"><input type="hidden" name="status" value="0"></form><?php }
                          else{ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Aktywuj bibliotekarza"><input type="hidden" name="status" value="1"></form><?php }
                          ?><br>


                          <?php if($row['status']==2){ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Usuń admina"><input type="hidden" name="status" value="0"></form><?php }
                          else{ ?><form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="changestatus" value="Dodaj admina"><input type="hidden" name="status" value="2"></form><?php }
                          ?><br>
                        
                        
                        <form method="POST" action="uzytkownicy.php?id_user=<?php echo $row['u_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="del_u" value="Usuń"></form></td></tr>
                            <?php } 
                                if(isset($_POST['del_u'])){ 
                                    $id=$_GET['id_user'];
                                    $delete=$pdo->exec("DELETE FROM b_uzytkownicy WHERE u_id=$id");
                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Usunięto</strong> użytkownika o ID: <?php echo $_GET['id_user']; ?>.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <meta http-equiv="refresh" content="1">
                            <?php }
                            if(isset($_POST['changestatus'])){ 
                                $id=$_GET['id_user'];
                                $chstatus=$pdo->prepare("UPDATE b_uzytkownicy SET status=:s  WHERE u_id=:u_id");
                                $chstatus->bindValue(':u_id',$_GET['id_user']);
                                $chstatus->bindValue(':s',$_POST['status']);
                                $chstatus->execute();

                                $s=$_POST['status'];


                            ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Poprawnie</strong> wykonano operację dla użytkownika o ID: <?php echo $_GET['id_user']; ?>.
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
</div> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script>
    $('#exampleModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever') // Extract info from data-* attributes
          var partner = button.data('partner')
          var modal = $(this);
          var dataString = 'id_partnera=' + partner + '&id=' + recipient;
            $.ajax({
                type: "GET",
                url: "editdata.php",
                data: dataString,
                cache: false,
                success: function (data) {
                    console.log(data);
                    modal.find('.dash').html(data);
                },
                error: function(err) {
                    console.log(err);
                }
            });
    })
      </script>