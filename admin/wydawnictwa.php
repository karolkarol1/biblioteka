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
  <link rel="stylesheet" href="docsupport/style.css">
  <link rel="stylesheet" href="docsupport/prism.css">
  <link rel="stylesheet" href="chosen.css">

    <meta http-equiv="Content-Security-Policy" content="default-src &apos;self&apos;; script-src &apos;self&apos; https://ajax.googleapis.com; style-src &apos;self&apos;; img-src &apos;self&apos; data:">

<div class="content-wrapper">
<div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Zarządzanie wydawnictwami</li>
      </ol>
      <!-- Example DataTables Card-->
      <div class="card mb-3">
      <div class="card-header">
          <i class="fa fa-table"></i> Dodaj wydawnictwo
        </div>
        <div class="container">
            <form class="form-signin" id="addProduct" method="POST" action="wydawnictwa.php" ENCTYPE="multipart/form-data"><br>
                <input type="text" name="nazwa_wydawnictwa" class="form-control" placeholder="Nazwa wydawnictwa" required autofocus>
                
               
            
       

                <br><br>
                <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin">Dodaj Wydawnictwo</button>
            </form>
         
        </div>
        <?php 
                if(isset($_POST['submit'])){                   
                    $addProduct=$pdo->prepare("INSERT INTO b_wydawnictwo VALUES(null,:nazwa_wydawnictwa)");
                    $addProduct->bindParam(':nazwa_wydawnictwa',$_POST['nazwa_wydawnictwa']);
                  
                    $addProduct->execute();
                    ?>
                    <meta http-equiv="refresh" content="1">

                    <?php

}
                    ?>
        </div>
        <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Modyfikuj Wydawnictwo</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nazwa Wydawnictwa</th>
                  <th></th>
                </tr>
              </thead>
              <tfoot>
              <tr>
                  <th>ID</th>
                  <th>Nazwa Wydawnictwa</th>
                  <th></th>
                </tr>
              </tfoot>
              <tbody>
                <?php
                  $autorzy=$pdo->query('SELECT w_id, nazwa_wydawnictwa FROM b_wydawnictwo');
                  foreach($autorzy as $row){
                ?>
                  <tr><td><?php echo $row['w_id']; ?></td><td><?php echo $row['nazwa_wydawnictwa']; ?></td><td></td>
                  <td><button class="btn btn-lg btn-primary btn-block btn-signin" data-backdrop="false" data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $row['w_id']; ?>">Edytuj</button>
                  <br>
                  <form method="POST" action="wydawnictwa.php?w_id=<?php echo $row['w_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="delete" onclick="return confirm('Czy na pewno chcesz usunąć produkt ?')" value="Usuń"></form><br></td></tr>
                <?php } 
                    if(isset($_POST['delete'])){
                        $id=$_GET['w_id'];
                        $delete=$pdo->exec("DELETE FROM b_wydawnictwo WHERE w_id=$id");
                ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Usunięto</strong> Wydawnictwo o ID: <?php echo $_GET['w_id']; ?>.
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
    </form>
        </div>
        </div>
        


    

  <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>


    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script>
    $('#exampleModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever') // Extract info from data-* attributes
          var modal = $(this);
          var dataString = 'idd=7&id=' + recipient;
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