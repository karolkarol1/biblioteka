<?php
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
    if($_GET['idd']==1){
        $id=$_GET['id'];
        $kategorie = $pdo ->query("SELECT * FROM s_kategorie WHERE id_rodzica=0 ORDER BY kolejnosc");
        $category= $pdo->prepare("SELECT * FROM s_kategorie WHERE id=$id");
        $category->execute();
        $cat=$category->fetchAll();
     ?>
        <form method="POST" action="editdata.php?idd=1&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Nazwa</label>
                    <input type="text" class="form-control" id="id" name="nazwa" value="<?php echo $cat[0][1]; ?>" />

                </div>
                <div class="form-group">
                    <label for="name">Zmień kolejność:</label>
                    <select class="form" name="kolejnosc">
                    <option selected hidden value="<?php echo $cat[0][3]; ?>"><?php echo $cat[0][3]; ?></option>
                    <?php
                        foreach($kategorie as $row){                 
                    ?>
	                <option value="<?php echo $row['kolejnosc']; ?>" ><?php echo $row['kolejnosc']; ?></option>
                    <?php }   
                    ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Obrazek</label>
                    <input type="file" name="userfile" value="" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="kat_send" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </form>
    <?php
        if(isset($_POST['kat_send'])){
        $select=$pdo->prepare("SELECT kolejnosc FROM s_kategorie WHERE id='".$_GET['id']."' AND id_rodzica=0");
        $select->execute();
        $r_select=$select->fetchAll();
            
        $selectt=$pdo->prepare("SELECT id FROM s_kategorie WHERE kolejnosc='".$_POST['kolejnosc']."' AND id_rodzica=0");
        $selectt->execute();
        $r_selectt=$selectt->fetchAll();
        $changeKolejnosc=$pdo->prepare("UPDATE s_kategorie SET kolejnosc=:kolejnosc WHERE id=:id");
        $changeKolejnosc->bindValue(':kolejnosc',$r_select[0]['kolejnosc']);
        $changeKolejnosc->bindValue(':id',$r_selectt[0]['id']);
        $changeKolejnosc->execute();
            
        $updateKolejnosc=$pdo->prepare("UPDATE s_kategorie SET nazwa=:nazwa,obrazek=:obrazek,kolejnosc=:kolejnosc WHERE id=:id");
        $updateKolejnosc->bindParam(':nazwa',$_POST['nazwa']);
        $updateKolejnosc->bindParam(':obrazek',$_FILES['userfile']['name']);
        $updateKolejnosc->bindValue(':kolejnosc',$_POST['kolejnosc']);
        $updateKolejnosc->bindValue(':id',$_GET['id']);
        $updateKolejnosc->execute();
        $uploaddir = "../img/produkty/";
        $uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);

        }
    }
    elseif($_GET['idd']==2){
        $id=$_GET['id'];
        $category= $pdo->prepare("SELECT id, nazwa FROM b_kategorie WHERE id=$id");
        $category->execute();
        $cat=$category->fetchAll();
     ?>
     <form method="POST" action="editdata.php?idd=2&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
	   <div class="modal-body">
            <div class="form-group">
                <label for="name">Nazwa</label>
                <input type="text" class="form-control" id="id" name="nazwa" value="<?php echo $cat[0]['nazwa']; ?>" />
                <input type="hidden" id="id" name="id" value="<?php echo $cat[0]['id']; ?>" />

            </div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="kat_send" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </form>
    <?php
        if(isset($_POST['kat_send'])){
            $changeKolejnosc=$pdo->prepare("UPDATE b_kategorie SET nazwa=:nazwa WHERE id=:id");
            $changeKolejnosc->bindValue(':nazwa',$_POST['nazwa']);
            $changeKolejnosc->bindValue(':id',$_POST['id']);
            $changeKolejnosc->execute();

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'kategorie.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }

elseif($_GET['idd']==7){
        $id=$_GET['id'];
        $category= $pdo->prepare("SELECT nazwa_wydawnictwa FROM b_wydawnictwo WHERE w_id=$id");
        $category->execute();
        $cat=$category->fetchAll();
     ?>
     <form method="POST" action="editdata.php?idd=7&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
       <div class="modal-body">
            <div class="form-group">
            <label for="imie">Nazwa Wydawnictwa</label>
                <input type="text" class="form-control" id="id" name="nazwa_wydawnictwa" value="<?php echo $cat[0]['nazwa_wydawnictwa']; ?>" />
                <input type="hidden" id="id" name="imie2" value="<?php echo $cat[0]['nazwa_wydawnictwa']; ?>" />
              
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" name="aut_send" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </form>
    <?php
        if(isset($_POST['aut_send'])){
            $changeKolejnosc=$pdo->prepare("UPDATE b_wydawnictwo SET nazwa_wydawnictwa=:imie2 WHERE w_id=$id");
            $changeKolejnosc->bindValue(':imie2',$_POST['nazwa_wydawnictwa']);
            $changeKolejnosc->execute();

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'wydawnictwa.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }





    elseif($_GET['idd']==10){
        $id=$_GET['id'];
        $category= $pdo->prepare("SELECT imie, nazwisko FROM b_autor WHERE a_id=$id");
        $category->execute();
        $cat=$category->fetchAll();
     ?>
     <form method="POST" action="editdata.php?idd=10&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
	   <div class="modal-body">
            <div class="form-group">
            <label for="imie">Imię</label>
                <input type="text" class="form-control" id="id" name="imie" value="<?php echo $cat[0]['imie']; ?>" />
                <input type="hidden" id="id" name="imie2" value="<?php echo $cat[0]['imie']; ?>" />
                <br>
                <label for="nazwisko">Nazwisko</label>
                <input type="text" class="form-control" id="id" name="nazwisko" value="<?php echo $cat[0]['nazwisko']; ?>" />
                <input type="hidden" id="id" name="nazwisko2" value="<?php echo $cat[0]['nazwisko']; ?>" />

            </div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="qwe" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </form>
    <?php
        if(isset($_POST['qwe'])){
            $changeKolejnosc=$pdo->prepare("UPDATE b_autor SET imie=:imie2, nazwisko=:nazwisko2 WHERE a_id=$id");
            $changeKolejnosc->bindValue(':imie2',$_POST['imie']);
            $changeKolejnosc->bindValue(':nazwisko2',$_POST['nazwisko']);
            $changeKolejnosc->execute();

            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'autorzy.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }

    elseif($_GET['idd']==3){
        if(!isset($_POST['ksiazka_edit'])){
        $id=$_GET['id'];
        $category= $pdo->prepare("SELECT id, k.tytul, k.ilosc, kat.nazwa, k.opis, k.obrazek, w.nazwa_wydawnictwa FROM b_ksiazki k JOIN b_wydawnictwo w ON(k.wydawnictwo=w.w_id) JOIN b_kategorie kat ON (k.kat_id=kat.id) where k.k_id=$id");
        $category->execute();
        $cat=$category->fetchAll();
     ?>
     <form method="POST" action="editdata.php?idd=3&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
	   <div class="modal-body">
            <div class="form-group">
                <input type="hidden" id="id" name="id" value="<?php echo $cat[0]['id']; ?>" />


            <label for="name">Nazwa</label>

                <input type="text" name="nazwa" class="form-control" placeholder="Tytuł" value="<?php echo $cat[0]['tytul']; ?>" required autofocus>
                <label for="opis">Opis</label>

                <textarea name="opis" class="form-control"><?php echo $cat[0]['opis']; ?></textarea>
                <label for="kat_id">Kategoria</label>
                <div class="form-control">
                <select data-placeholder="Kategoria" name="kat_id" class="chosen-select" tabindex="2" required>
                

                
                <option value=""></option>

                    <?php
                        $categorys=$pdo->query("SELECT id, nazwa from b_kategorie");
                
                        foreach($categorys as $row){  
                            if($cat[0]['nazwa']==$row['nazwa']){
                                echo '<option value="'.$row['id'].'" selected>'.$row['nazwa'].'</option>';
                            }  
                            else{
                            echo '<option value="'.$row['id'].'" >'.$row['nazwa'].'</option>';
                            }}     
                    ?>
                </select>
                <br>
                </div>
                <label for="wyd_id">Wydawnictwo</label>
                <div class="form-control">
                <select data-placeholder="Wydawnictwo" name="wyd_id" class="chosen-select" tabindex="3" required>
                <option value=""></option>

                    <?php
                        $wyd=$pdo->query("SELECT w_id, nazwa_wydawnictwa from b_wydawnictwo");
                      foreach($wyd as $row){  
                            if($cat[0]['nazwa_wydawnictwa']==$row['nazwa_wydawnictwa']){
                                echo '<option value="'.$row['w_id'].'" selected>'.$row['nazwa_wydawnictwa'].'</option>';
                            }  
                            else{
                            echo '<option value="'.$row['w_id'].'" >'.$row['nazwa_wydawnictwa'].'</option>';
                            }}  

                    ?>
                </select>
                </div>
                <br>
                <label for="autorzy[]">Autorzy</label>
                <div class="form-control">
          <select data-placeholder="Autorzy" class="chosen-select" name="autorzy[]" multiple tabindex="4">


                    <?php

$wpisani=$pdo->prepare("SELECT a_id from b_autorzyksiazka where k_id=$id");
$wpisani->execute();
$wpis=$wpisani->fetchAll();




                        $autorzy=$pdo->query("SELECT a_id, imie, nazwisko from b_autor");
                       
                       

                        foreach($autorzy as $row){  
                            

                            $x = false;


                            foreach($wpis as $roww){
                                if($roww['a_id']==$row['a_id']){
                                    $x=true;
                                    echo '<option value="'.$row['a_id'].'" selected>'.$row['imie'].' '.$row['nazwisko'].'</option>';
                                    break;
                                }
                                }
                            if($x==false){
                                echo '<option value="'.$row['a_id'].'">'.$row['imie'].' '.$row['nazwisko'].'</option>';
                                }}?>
          </select><br>
        </div>

                    <label for="ilosc">Ilość</label>
        <input type="number" name="ilosc" class="form-control" value="<?php echo $cat[0]['ilosc'];?>" placeholder="Ilość" required>
<input type="hidden" name="obrazek" value="<?php echo $cat[0]['obrazek'];?>">
                <br><br>
                <label for="name">Obraz</label><br>

<img src="../img/ksiazki/<?php echo $cat[0]['obrazek']; ?>" height="100" width="100">

                <input type="file" name="userfile"><br><br>










            </div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="ksiazka_edit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
		</div>
    </form>
    <?php


                            }
        else{


$id=$_GET['id'];

            if(empty($_FILES['userfile']['name'])){
$obr=$_POST['obrazek'];
}
            else{
                $obr=$_FILES['userfile']['name'];
            }

            $changeKolejnosc=$pdo->prepare("UPDATE b_ksiazki SET tytul=:nazwa, opis=:opis,kat_id=:kat_id,obrazek=:obrazek,wydawnictwo=:wydawnictwo,ilosc=:ilosc WHERE k_id=:id");
            $changeKolejnosc->bindValue(':nazwa',$_POST['nazwa']);
            $changeKolejnosc->bindValue(':opis',$_POST['opis']);
            $changeKolejnosc->bindValue(':kat_id',$_POST['kat_id']);
            $changeKolejnosc->bindValue(':obrazek',$obr);
            $changeKolejnosc->bindValue(':wydawnictwo',$_POST['wyd_id']);
            $changeKolejnosc->bindValue(':ilosc',$_POST['ilosc']);
            $changeKolejnosc->bindValue(':id',$id);

            $changeKolejnosc->execute();

                // $changeKolejnosc->debugDumpParams();

                $autorzy=$_POST['autorzy'];


                        $pdo->query("DELETE from b_autorzyksiazka where k_id=$id");

 
                        print_r($autorzy);

                foreach($autorzy as $row){                 

                    $addauthorbook=$pdo->prepare("INSERT INTO b_autorzyksiazka VALUES(:aid,:kid)");
                    $addauthorbook->bindParam(':aid',$row);
                    $addauthorbook->bindParam(':kid',$id);
                    $addauthorbook->execute();

                }


            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'ksiazki.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }

    else{
    $id = $_GET['id'];
    $ksiazki = $pdo->prepare("SELECT * FROM b_ksiazki WHERE k_id=$id");
    $ksiazki->execute();
    $result=$ksiazki->fetchAll();

    print_r($result);
    
    ?>

    <form method="POST" action="editdata.php?id=<?php echo $id; ?>" role="form" ENCTYPE="multipart/form-data">
	<div class="modal-body">
		<div class="form-group">
			<label for="name">Nazwa</label>
			<input type="text" class="form-control" id="id" name="nazwa" value="<?php echo $result[0][1]; ?>" />

		</div>
        <div class="form-group">
			<label for="name">Opis</label>
			<input type="text" class="form-control" id="id" name="opis" value="<?php echo $result[0][2]; ?>" />

		</div>
        <div class="form-group">
			<label for="name">Cena</label>
			<input type="text" class="form-control" id="id" name="cena" value="<?php echo $result[0][3]; ?>" />

		</div>
        <div class="form-group">
			<label for="name">Podkategorie</label>
            <select class="form" name="kat_id">
                    <?php foreach($categoriess as $row){ ?>
                         <option selected hidden value="<?php echo $row['id'] ?>"><?php echo $row['nazwarodzica'].'->'.$row['nazwa']; ?></option>
                    <?php } ?>
                    <?php
                        foreach($categories as $row){                 
                    ?>
	                <option value="<?php echo $row['id']; ?>" ><?php echo $row['nazwarodzica'].'->'.$row['nazwa']; ?></option>
                    <?php }   
                    ?>
            </select>
		</div>
		<div class="form-group">
			<label for="name">Obrazek</label>
			<input type="file" name="userfile" value="<?php echo $result[0][5]; ?>" required>
		</div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
</form>
<?php
    if(isset($_POST['submit'])){
        $updateProduct=$pdo->prepare("UPDATE s_produkty SET nazwa=:nazwa,opis=:opis,cena=:cena,kat_id=:kat_id,obrazek=:obrazek WHERE p_id=:p_id");
        $updateProduct->bindParam(':nazwa',$_POST['nazwa']);
        $updateProduct->bindParam(':opis',$_POST['opis']);
        $updateProduct->bindValue(':cena',$_POST['cena']);
        $updateProduct->bindValue(':kat_id',$_POST['kat_id']);
        $updateProduct->bindParam(':obrazek',$_FILES['userfile']['name']);
        $updateProduct->bindValue(':p_id',$_GET['id']);
        $updateProduct->execute();
        $uploaddir = "../img/produkty/";
        $uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);

        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "Produkt został poprawnie dodany";
             $host  = $_SERVER['HTTP_HOST'];
             $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
             $extra = 'produkty.php';
            // header("Location: http://$host$uri/$extra");
             exit;
       } else {
        echo "Upload failed";
        }
    }
}
?> 

  <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>   