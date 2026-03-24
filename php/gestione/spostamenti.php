<?php
require_once("../config/db.php");
require_once("../common/header.php");

$prodotti = mysqli_query($conn,"SELECT * FROM prodotti");
$luoghi = mysqli_query($conn,"SELECT * FROM luoghi");

if ($_POST) {

$p = $_POST['prodotto'];
$da = $_POST['da'];
$a = $_POST['a'];
$q = $_POST['quantita'];

mysqli_query($conn,"INSERT INTO spostamenti
(id_prodotto,da_luogo,a_luogo,quantita,data_spostamento)
VALUES($p,$da,$a,$q,NOW())");
}
?>

<h3>Spostamenti</h3>

<form method="POST">
<select name="prodotto" class="form-control mb-2">
<?php while($p=mysqli_fetch_assoc($prodotti)){ ?>
<option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
<?php } ?>
</select>

<select name="da" class="form-control mb-2">
<?php mysqli_data_seek($luoghi,0); while($l=mysqli_fetch_assoc($luoghi)){ ?>
<option value="<?= $l['id'] ?>"><?= $l['nome'] ?></option>
<?php } ?>
</select>

<select name="a" class="form-control mb-2">
<?php mysqli_data_seek($luoghi,0); while($l=mysqli_fetch_assoc($luoghi)){ ?>
<option value="<?= $l['id'] ?>"><?= $l['nome'] ?></option>
<?php } ?>
</select>

<input name="quantita" class="form-control mb-2" placeholder="Quantità">

<button class="btn btn-primary">Sposta</button>
</form>

<?php require_once("../common/footer.php"); ?>