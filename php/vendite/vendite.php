<?php
require_once("../config/db.php");
require_once("../common/header.php");

$prodotti = mysqli_query($conn,"SELECT * FROM prodotti");

if ($_POST) {

$id = $_POST['prodotto'];
$qta = $_POST['quantita'];

$prod = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM prodotti WHERE id=$id"));

$prezzo = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT prezzo FROM prezzi WHERE id_prodotto=$id AND data_fine IS NULL"))['prezzo'];

/* CONTROLLO GIACENZE */

if ($prod['tipo']=='confezionato') {
    $g = mysqli_fetch_assoc(mysqli_query($conn,"SELECT giacenza FROM confezioni WHERE id_prodotto=$id"));
    if ($g['giacenza'] < $qta) die("Errore giacenza");
    mysqli_query($conn,"UPDATE confezioni SET giacenza=giacenza-$qta WHERE id_prodotto=$id");
}

if ($prod['tipo']=='riserva') {
    $r = mysqli_fetch_assoc(mysqli_query($conn,"SELECT quantita FROM riserva WHERE id_prodotto=$id"));
    if ($r['quantita'] < $qta) die("Errore riserva");
    mysqli_query($conn,"UPDATE riserva SET quantita=quantita-$qta WHERE id_prodotto=$id");
}

/* CALCOLO */

$tot = $qta * $prezzo;

/* INSERIMENTO */

mysqli_query($conn,"INSERT INTO acquisti(id_cliente,data_acquisto,totale,totale_pagato)
VALUES(1,NOW(),$tot,$tot)");

$id_a = mysqli_insert_id($conn);

mysqli_query($conn,"INSERT INTO righe_acquisto
(id_acquisto,id_prodotto,quantita,prezzo_unitario)
VALUES($id_a,$id,$qta,$prezzo)");
}
?>

<h3>Vendita</h3>

<form method="POST">
<select name="prodotto" class="form-control mb-2">
<?php while($p=mysqli_fetch_assoc($prodotti)){ ?>
<option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
<?php } ?>
</select>

<input name="quantita" required class="form-control mb-2" placeholder="Quantità">

<button class="btn btn-primary">Vendi</button>
</form>