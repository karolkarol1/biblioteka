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
    
    $category=$pdo->prepare("SELECT * FROM s_kategorie WHERE id_rodzica = 0 ORDER BY kolejnosc");
    $category->execute();
    $result=$category->fetchAll();
?>
<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Edytor kategorii</li>
        </ol>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Dodaj kategorie
            </div>
            <div class="container">
                <form class="form-signin" id="addCategory" method="POST" action="kategorie.php" ENCTYPE="multipart/form-data"><br>
                    <input type="text" name="nazwa" class="form-control" placeholder="Nazwa" required autofocus>
                    Obrazek <br><input type="file" name="userfile" required><br><br>
                    <button type="submit" name="submit" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj Kategorie</button>
                </form>
            </div>
            <?php 
                if(isset($_POST['submit'])){                   
                    $max=$pdo->prepare("SELECT MAX(kolejnosc) FROM s_kategorie WHERE id_rodzica=0");
                    $max->execute();
                    $r_max=$max->fetchAll();
                    $addProduct=$pdo->prepare("INSERT INTO s_kategorie VALUES(null,:nazwa,:obrazek,:kolejnosc,:id_rodzica)");
                    $addProduct->bindParam(':nazwa',$_POST['nazwa']);
                    $addProduct->bindParam(':obrazek',$_FILES['userfile']['name']);
                    $addProduct->bindValue(':kolejnosc',$r_max[0][0]+1);
                    $addProduct->bindValue(':id_rodzica',0);
                    $addProduct->execute();

                    $uploaddir = "../img/produkty/";
                    $uploadfile = $uploaddir . basename($_FILES["userfile"]["name"]);

                    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Dodano</strong> nową kategorię.
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
                <i class="fa fa-table"></i> Dodaj Podkategorie
            </div>
            <div class="container">
                <form class="form-signin" id="addSubCategory" method="POST" action="kategorie.php" ENCTYPE="multipart/form-data"><br>
                    <input type="text" name="nazwa" class="form-control" placeholder="Nazwa" required autofocus>
                    Wybierz kategorię:  <select name="id_rodzica" required>
                        <?php    
                            foreach($result as $row){                 
                        ?>
                           <option value="<?php echo $row['id'] ?>" ><?php echo $row['nazwa'] ?></option>

                        <?php }   
                        ?>
                    </select><br><br>
                    <button type="submit" name="submit1" class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Dodaj Podkategorie</button>
                </form>
            </div>
            <?php 
                if(isset($_POST['submit1'])){
                    $max=$pdo->prepare("SELECT MAX(kolejnosc) FROM s_kategorie WHERE id_rodzica='".$_POST['id_rodzica']."'");
                    $max->execute();
                    $r_max=$max->fetchAll();
                    $addProduct=$pdo->prepare("INSERT INTO s_kategorie VALUES(null,:nazwa,:obrazek,:kolejnosc,:id_rodzica)");
                    $addProduct->bindParam(':nazwa',$_POST['nazwa']);
                    $addProduct->bindValue(':obrazek','');
                    $addProduct->bindValue(':kolejnosc',$r_max[0][0]+1);
                    $addProduct->bindValue(':id_rodzica',$_POST['id_rodzica']);
                    $addProduct->execute();
            ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Dodano</strong> nową podkategorię.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <meta http-equiv="refresh" content="1">
            <?php
                }
            ?>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <i class="fa fa-table"></i> Edytor kategorii i podkategorii
            </div>
            <div class="container">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <ul class="treeview">
                            <?php 
                                foreach($result as $row){
                            ?>
                            <li><a href="#"><?php echo $row['nazwa']; ?></a>&nbsp;&nbsp;<p class="fa fa-edit" data-backdrop="false" data-toggle="modal" data-target="#exampleModal" data-whateverr="1" data-whatever="<?php echo $row['id']; ?>"></p>&nbsp;&nbsp;<form  method="POST" class="fa" action="kategorie.php?id_category=<?php echo $row['id'] ?>"><input  class="btn btn-danger btn-sm" type="submit" name="del_category" onclick="return confirm('Czy na pewno chcesz usunąć produkt ?')" value="Usuń"></form>
                                <?php 
                                    $tmp=$pdo->query("SELECT * FROM s_kategorie WHERE id_rodzica='".$row['id']."' ORDER BY kolejnosc");
                                    foreach($tmp as $row){
                                ?>
                                <ul>
                                    <li><a href="#"><?php echo $row['nazwa']; ?></a>&nbsp;&nbsp;<p class="fa fa-edit" data-backdrop="false" data-toggle="modal" data-target="#exampleModal" data-whateverr="2" data-whatever="<?php echo $row['id']; ?>"></p>&nbsp;&nbsp;<form method="POST" class="fa" action="kategorie.php?id_category=<?php echo $row['id'] ?>"><input class="btn btn-danger btn-sm" type="submit" name="del_category" onclick="return confirm('Czy na pewno chcesz usunąć produkt ?')" value="Usuń"></form></li>
                                </ul>
                                <?php }?>
                            </li>
                            <?php } ?>
                        </ul>
                    <?php 
                        if(isset($_POST['del_category'])){ 
                        $id=$_GET['id_category'];
                        $delete=$pdo->exec("DELETE FROM s_kategorie WHERE id=$id");
                    ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Usunięto</strong> podkategorię o id <?php echo $_GET['id_category']; ?>.
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
                    </div>
                </div>
            </div>
        </div>
    </div>
<style>
div.panel:first-child {
    margin-top:20px;
}

div.treeview {
    min-width: 100px;
    min-height: 100px;
    
    max-height: 256px;
    overflow:auto;
	
	padding: 4px;
	
	margin-bottom: 20px;
	
	color: #369;
	
	border: solid 1px;
	border-radius: 4px;
}
div.treeview ul:first-child:before {
    display: none;
}
.treeview, .treeview ul {
    margin:0;
    padding:0;
    list-style:none;
    
	color: #369;
}
.treeview ul {
    margin-left:1em;
    position:relative
}
.treeview ul ul {
    margin-left:.5em
}
.treeview ul:before {
    content:"";
    display:block;
    width:0;
    position:absolute;
    top:0;
    left:0;
    border-left:1px solid;
    
    /* creates a more theme-ready standard for the bootstrap themes */
    bottom:15px;
}
.treeview li {
    margin:0;
    padding:0 1em;
    line-height:3em;
    font-weight:700;
    position:relative
}
.treeview ul li:before {
    content:"";
    display:block;
    width:10px;
    height:0;
    border-top:1px solid;
    margin-top:-1px;
    position:absolute;
    top:1em;
    left:0
}
.tree-indicator {
    margin-right:5px;
    
    cursor:pointer;
}
.treeview li a {
    text-decoration: none;
    color:inherit;
    
    cursor:pointer;
}
.treeview li button, .treeview li button:active, .treeview li button:focus {
    text-decoration: none;
    color:inherit;
    border:none;
    background:transparent;
    margin:0px 0px 0px 0px;
    padding:0px 0px 0px 0px;
    outline: 0;
}
</style>
<script>
    
$.fn.extend({
	treeview:	function() {
		return this.each(function() {
			// Initialize the top levels;
			var tree = $(this);
			
			tree.addClass('treeview-tree');
			tree.find('li').each(function() {
				var stick = $(this);
			});
			tree.find('li').has("ul").each(function () {
				var branch = $(this); //li with children ul
				
				branch.prepend("<i class='tree-indicator fa fa-arrow-right'></i>");
				branch.addClass('tree-branch');
				branch.on('click', function (e) {
					if (this == e.target) {
						var icon = $(this).children('i:first');
						
						icon.toggleClass("fa-arrow-down fa-arrow-right");
						$(this).children().children().toggle();
					}
				})
				branch.children().children().toggle();
				
				/**
				 *	The following snippet of code enables the treeview to
				 *	function when a button, indicator or anchor is clicked.
				 *
				 *	It also prevents the default function of an anchor and
				 *	a button from firing.
				 */
				branch.children('.tree-indicator, button, a').click(function(e) {
					branch.click();
					
					e.preventDefault();
				});
			});
		});
	}
});

/**
 *	The following snippet of code automatically converst
 *	any '.treeview' DOM elements into a treeview component.
 */
$(window).on('load', function () {
	$('.treeview').each(function () {
		var tree = $(this);
		tree.treeview();
	});
});
</script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
      <script>
    $('#exampleModal').on('show.bs.modal', function (event) {
          var button = $(event.relatedTarget) // Button that triggered the modal
          var recipient = button.data('whatever')// Extract info from data-* attributes
          var recipient1 = button.data('whateverr')
          var modal = $(this);
          var dataString = 'idd=' +recipient1 + '&id=' + recipient;
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