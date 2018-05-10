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

        if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
            echo "Produkt został poprawnie dodany";
             $host  = $_SERVER['HTTP_HOST'];
             $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
             $extra = 'kategorie.php';
            header("Location: http://$host$uri/$extra");
             exit;
       } else {
        echo "Upload failed";
        }
        }
    }
    elseif($_GET['idd']==2){
        $id=$_GET['id'];
        $category= $pdo->prepare("SELECT * FROM s_kategorie WHERE id=$id");
        $category->execute();
        $cat=$category->fetchAll();
        $kategorie = $pdo ->query("SELECT * FROM s_kategorie WHERE id_rodzica='".$cat[0][4]."'");
     ?>
     <form method="POST" action="editdata.php?idd=2&id=<?php echo $id;?>" role="form" ENCTYPE="multipart/form-data">
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
		</div>
		<div class="modal-footer">
			<button type="submit" name="podkat_send" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </form>
    <?php
        if(isset($_POST['podkat_send'])){
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

            $updateKolejnosc=$pdo->prepare("UPDATE s_kategorie SET nazwa=:nazwa,kolejnosc=:kolejnosc WHERE id=:id");
            $updateKolejnosc->bindParam(':nazwa',$_POST['nazwa']);
            $updateKolejnosc->bindValue(':kolejnosc',$_POST['kolejnosc']);
            $updateKolejnosc->bindValue(':id',$_GET['id']);
            $updateKolejnosc->execute();
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'kategorie.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }
    elseif($_GET['id_punktu']==3){
        $id=$_GET['id'];
        $punkty= $pdo->prepare("SELECT * FROM s_punkty WHERE id_punktu=$id");
        $punkty->execute();
        $r_punkty=$punkty->fetchAll();
     ?>
     <form method="POST" action="editdata.php?id_punktu=3&id=<?php echo $id;?>" role="form">
	   <div class="modal-body">
            <div class="form-group">
                <label for="name">Nazwa</label>
                <input type="text" class="form-control" id="id" name="nazwa" value="<?php echo $r_punkty[0][1]; ?>" />
            </div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="nowy_punkt" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Edytuj</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </form>
    <?php
        if(isset($_POST['nowy_punkt'])){
            $id=$_GET['id'];
            $add_admin=$pdo->prepare("UPDATE s_punkty SET adres=:adres WHERE id_punktu=:id_punktu");
            $add_admin->bindParam(':adres',$_POST['nazwa']);
            $add_admin->bindValue(':id_punktu',$id);
            $add_admin->execute();
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'partnerzy.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }
    elseif($_GET['id_partnera']==4){
        $id=$_GET['id'];
        $punkty= $pdo->query("SELECT * FROM s_punkty");
     ?>
     <form method="POST" action="editdata.php?id_partnera=4&id=<?php echo $id;?>" role="form">
	   <div class="modal-body">
            <div class="form-group">
                <label for="name">Lista adresow:</label>
                <select class="form" name="adresy">
                <?php
                    foreach($punkty as $row){                 
                ?>
                <option value="<?php echo $row['id_punktu']; ?>" ><?php echo $row['adres']; ?></option>
                <?php }   
                ?>
                </select>
            </div>
		</div>
		<div class="modal-footer">
			<button type="submit" name="nowy_partner" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj partnera</button>
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
    </form>
    <?php
        if(isset($_POST['nowy_partner'])){
            $id=$_GET['id'];
            $addPartner=$pdo->prepare("INSERT INTO s_partnerzy VALUES(:u_id,:punkt_id)");
            $addPartner->bindValue(':u_id',$id);
            $addPartner->bindParam(':punkt_id',$_POST['adresy']);
            $addPartner->execute();
            
            $updateStatus=$pdo->prepare("UPDATE s_uzytkownicy SET jestadminem=:jestadminem WHERE u_id=:u_id");
            $updateStatus->bindValue(':jestadminem',2);
            $updateStatus->bindValue(':u_id',$id);
            $updateStatus->execute();
                
            $host  = $_SERVER['HTTP_HOST'];
            $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $extra = 'uzytkownicy.php';
            header("Location: http://$host$uri/$extra");
            exit;   
        }
    }
    else{
    $id = $_GET['id'];
    $products = $pdo->prepare("SELECT * FROM s_produkty WHERE p_id=$id");
    $products->execute();
    $result=$products->fetchAll();
    $categories=$pdo->query("SELECT A.id, B.nazwa AS nazwarodzica, A.nazwa AS nazwa FROM s_kategorie A, s_kategorie B WHERE A.id_rodzica = B.id  ORDER BY nazwarodzica");
    $categoriess=$pdo->query("SELECT A.id, B.nazwa AS nazwarodzica, A.nazwa AS nazwa FROM s_kategorie A, s_kategorie B WHERE A.id_rodzica = B.id AND A.id='".$result[0][4]."' ORDER BY nazwarodzica");
    
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
            header("Location: http://$host$uri/$extra");
             exit;
       } else {
        echo "Upload failed";
        }
    }
}
?>    