<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");

if ($_POST) {

    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];
    $unita = $_POST['unita'];
    $cat = $_POST['categoria'];
    $prezzo = $_POST['prezzo'];

    mysqli_query($conn,"INSERT INTO prodotti(nome,tipo,unita,id_categoria)
    VALUES('$nome','$tipo','$unita',$cat)");

    $id = mysqli_insert_id($conn);

    mysqli_query($conn,"INSERT INTO prezzi(id_prodotto,prezzo,data_inizio)
    VALUES($id,$prezzo,NOW())");
}

$categorie = mysqli_query($conn,"SELECT * FROM categorie");
$prodotti = mysqli_query($conn,"SELECT * FROM prodotti");
?>

<h3>Prodotti</h3>

<form method="POST">
<input required name="nome" class="form-control mb-2" placeholder="Nome prodotto">
<input required name="prezzo" class="form-control mb-2" placeholder="Prezzo">

<select name="tipo" class="form-control mb-2">
<option>fresco</option>
<option>riserva</option>
<option>confezionato</option>
</select>

<select name="categoria" class="form-control mb-2">
<?php while($c=mysqli_fetch_assoc($categorie)){ ?>
<option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
<?php } ?>
</select>

<input name="unita" class="form-control mb-2" placeholder="Unità">

<button class="btn btn-primary">Salva</button>
</form>

<?php require_once("../common/footer.php"); ?>