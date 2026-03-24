<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");

$prodotti = mysqli_query($conn,"SELECT * FROM prodotti");

if ($_POST) {

$id = $_POST['prodotto'];
$nuovo = $_POST['prezzo'];

/* chiude prezzo attuale */
mysqli_query($conn,"UPDATE prezzi 
SET data_fine = NOW() 
WHERE id_prodotto=$id AND data_fine IS NULL");

/* inserisce nuovo prezzo */
mysqli_query($conn,"INSERT INTO prezzi(id_prodotto,prezzo,data_inizio)
VALUES($id,$nuovo,NOW())");
}
?>

<h3>Aggiornamento Prezzi</h3>

<form method="POST">
<select name="prodotto" class="form-control mb-2">
<?php while($p=mysqli_fetch_assoc($prodotti)){ ?>
<option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
<?php } ?>
</select>

<input name="prezzo" required class="form-control mb-2" placeholder="Nuovo prezzo">

<button class="btn btn-primary">Aggiorna</button>
</form>

<?php require_once("../common/footer.php"); ?>