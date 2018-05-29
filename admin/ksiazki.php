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
        <li class="breadcrumb-item active">Zarządzanie książkami</li>
      </ol>
      <!-- Example DataTables Card-->
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Dodaj książkę
        </div>
        <div class="container">
            <form class="form-signin" id="addProduct" method="POST" action="ksiazki.php" ENCTYPE="multipart/form-data"><br>
                <input type="text" name="nazwa" class="form-control" placeholder="Nazwa" required autofocus>
                <input type="text" name="opis" class="form-control" placeholder="Opis" required>
                <input type="text" name="cena" class="form-control" placeholder="Cena" required>
                <select name="kat_id" class="form-control" required>
                <option value="">Kategoria</option>

                    <?php
                        $categorys=$pdo->query("SELECT id, nazwa from b_kategorie");
                
                        foreach($categorys as $row){                 
                    ?>
	                   <option value="<?php echo $row['id']; ?>" ><?php echo $row['nazwa']; ?></option>
                    
                    <?php }   
                    ?>
                </select>
                <br>
                <div class="form-control">
          <select data-placeholder="Autorzy" class="chosen-select" multiple tabindex="4">
            <option value=""></option>


                    <?php
                        $autorzy=$pdo->query("SELECT a_id, imie, nazwisko from b_autor");
                
                        foreach($autorzy as $row){                 
                    ?>
	                   <option value="<?php echo $row['a_id']; ?>" ><?php echo $row['imie'].' '.$row['nazwisko']; ?></option>
                    
                    <?php }  ?>

            <option value="United States">United States</option>

          </select>
        </div>
                
                <br><br>
                <input type="file" name="userfile" required><br><br>
                <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj książkę</button>
            </form>
        </div>
        <?php 
            if(isset($_POST['submit'])){
                $addProduct=$pdo->prepare("INSERT INTO s_produkty VALUES(null,:nazwa,:opis,:cena,:kat_id,:obrazek)");
                $addProduct->bindParam(':nazwa',$_POST['nazwa']);
                $addProduct->bindParam(':opis',$_POST['opis']);
                $addProduct->bindValue(':cena',$_POST['cena']);
                $addProduct->bindValue(':kat_id',$_POST['kat_id']);
                $addProduct->bindParam(':obrazek',$_FILES['userfile']['name']);
                $addProduct->execute();
                
                $uploaddir = "../img/produkty/";
                $uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);
                
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Dodano</strong> poprawnie produkt.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <meta http-equiv="refresh" content="1">
                <?php 
                } else {
                echo "Upload failed";
                }
            }
        ?>
    </div>   
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Modyfikuj książkę</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Tytuł</th>
                  <th>Autorzy</th>
                  <th>Kategoria</th>
                  <th>Okładka</th>
                </tr>
              </thead>
              <tfoot>
              <tr>
              <th>ID</th>
                  <th>Tytuł</th>
                  <th>Autorzy</th>
                  <th>Kategoria</th>
                  <th>Okładka</th>
                </tr>
              </tfoot>
              <tbody>
                <?php
                  $ksiazki=$pdo->query('SELECT * FROM b_ksiazki');
                  foreach($ksiazki as $row){
                ?>
                  <tr><td><?php echo $row['k_id']; ?></td><td><?php echo $row['tytul']; ?></td><td><?php echo $row['kat_id']; ?></td><td><img src="../img/ksiazki/<?php echo $row['obrazek'];?>" alt="<?php echo $row['obrazek']; ?>" width="100" height="100"></td><td><button class="btn btn-lg btn-primary btn-block btn-signin" data-backdrop="false" data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $row['p_id']; ?>">Edit</button><br><form method="POST" action="produkty.php?id_product=<?php echo $row['p_id']; ?>"><input class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="delete" onclick="return confirm('Czy na pewno chcesz usunąć produkt ?')" value="Usuń"></form><br></td></tr>
                <?php } 
                    if(isset($_POST['delete'])){
                        $id=$_GET['id_product'];
                        $delete=$pdo->exec("DELETE FROM s_produkty WHERE p_id=$id");
                ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Usunięto</strong> produkt o ID: <?php echo $_GET['id_product']; ?>.
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
          var dataString = 'id=' + recipient;
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