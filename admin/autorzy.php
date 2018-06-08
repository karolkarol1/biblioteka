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
        <li class="breadcrumb-item active">Zarządzanie autorami</li>
      </ol>
      <!-- Example DataTables Card-->
    <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Dodaj autora
        </div>
        <div class="container">
            <form class="form-signin" id="addProduct" method="POST" action="ksiazki.php" ENCTYPE="multipart/form-data"><br>
                <input type="text" name="imie" class="form-control" placeholder="imie" required autofocus>
                <input type="text" name="Nazwisko" class="form-control" placeholder="nazwisko" required>
                <div class="form-control">
                <select data-placeholder="Kategoria" name="kat_id" class="chosen-select" tabindex="2" required>
                <option value=""></option>

                    <?php
                        $categorys=$pdo->query("SELECT id, imie, nazwisko from b_autor");
                
                        foreach($categorys as $row){                 
                    ?>
	                   <option value="<?php echo $row['id']; ?>" ><?php echo $row['imie']; ?><?php echo $row['nazwisko']; ?></option>
                    
                    <?php }   
                    ?>
                </select>
                </div>
                <div class="form-control">
                <select data-placeholder="Wydawnictwo" name="wyd_id" class="chosen-select" tabindex="2" required>
                <option value=""></option>

                    <?php
                        $categorys=$pdo->query("SELECT w_id, nazwa_wydawnictwa from b_wydawnictwo");
                
                        foreach($categorys as $row){                 
                    ?>
	                   <option value="<?php echo $row['w_id']; ?>" ><?php echo $row['nazwa_wydawnictwa']; ?></option>
                    
                    <?php }   
                    ?>
                </select>
                </div>
                <br>
                <div class="form-control">
          <select data-placeholder="Autorzy" class="chosen-select" name="autorzy[]" multiple tabindex="4">
            <option value=""></option>


                    <?php
                        $autorzy=$pdo->query("SELECT a_id, imie, nazwisko from b_autor");
                
                        foreach($autorzy as $row){                 
                    ?>
	                   <option value="<?php echo $row['a_id']; ?>" ><?php echo $row['imie'].' '.$row['nazwisko']; ?></option>
                    
                    <?php }  ?>


          </select>
        </div>
       

                <br><br>
                <input type="file" name="userfile" required><br><br>
                <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj Autora</button>
            </form>
        </div>
        <?php 

// print_r($_POST);


            if(isset($_POST['submit'])){



                $addbook=$pdo->prepare("INSERT INTO b_ksiazki VALUES(null,:imie,:nazwisko)");
                $addbook->bindParam(':nazwa',$_POST['imie']);
                $addbook->bindParam(':opis',$_POST['nazwisko']);
            

                $addbook->execute();

                // $addbook->debugDumpParams();


                // print_r($addbook);

                $id_ksiazki = $pdo->lastInsertId();


                $autorzy=$_POST['autorzy'];

                foreach($autorzy as $row){                 

                    $addauthorbook=$pdo->prepare("INSERT INTO b_autorzyksiazka VALUES(:aid,:kid)");
                    $addauthorbook->bindParam(':aid',$row);
                    $addauthorbook->bindParam(':kid',$id_ksiazki);
                    $addauthorbook->execute();

                }

                
                $uploaddir = "../img/ksiazki/";
                $uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);
                
                if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Dodano</strong> poprawnie produkt.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <!-- <meta http-equiv="refresh" content="1"> -->
                <?php 
                } else {
                echo "Upload failed";
                }
            }
        ?>

    

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