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
            <li class="breadcrumb-item active">Zarządzanie Partnerami</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
              <i class="fa fa-table"></i> Dodaj punkt
            </div>
            <div class="container">
                <form class="form-signin" id="addPunkt" method="POST" action="partnerzy.php"> <br>
                    <input type="text" name="adres" class="form-control" placeholder="Adres punktu" required autofocus>
                    <button type="submit" name="punkt" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj Punkt</button>
                </form>
            </div>
            <?php 
                if(isset($_POST['punkt'])){
                    $dodajPunkt=$pdo->prepare("INSERT INTO s_punkty VALUES(null,:adres)");
                    $dodajPunkt->bindParam(':adres',$_POST['adres']);
                    $dodajPunkt->execute();
            ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Dodano</strong> poprawnie nowy punkt.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <meta http-equiv="refresh" content="1">
            <?php } ?>
        </div>   
        <div class="card mb-3">
            <div class="card-header"><i class="fa fa-table"></i> Lista punktow</div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Adres</th>
                                <th>Operacje</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Adres</th>
                                <th>Operacje</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                              $punkty=$pdo->query('SELECT * FROM s_punkty');
                              foreach($punkty as $row){
                            ?>
                              <tr>
                                  <td><?php echo $row['id_punktu']; ?></td>
                                  <td><?php echo $row['adres']; ?></td>
                                  <td><form method="POST" action="partnerzy.php?id_punktu=<?php echo $row['id_punktu']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="delete" onclick="return confirm('Czy na pewno chcesz usunąć produkt ?')" value="Usuń"></form><br><button class="btn btn-lg btn-primary btn-block btn-signin" data-backdrop="false" data-toggle="modal" data-target="#exampleModal" data-punkt="3" data-whatever="<?php echo $row['id_punktu']; ?>">Edit</button><br>
                                  </td>
                              </tr>
                            <?php } 
                                if(isset($_POST['delete'])){
                                    $id=$_GET['id_punktu'];
                                    $delete=$pdo->exec("DELETE FROM s_punkty WHERE id_punktu=$id");
                            ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Usunięto</strong> punkt o ID: <?php echo $_GET['id_punktu']; ?>.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <meta http-equiv="refresh" content="1">
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script>
    $('#exampleModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever') // Extract info from data-* attributes
          var punkt = button.data('punkt')
          var modal = $(this);
          var dataString = 'id_punktu=' + punkt + '&id=' + recipient;
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