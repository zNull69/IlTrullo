<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");

$where = "1=1";

if (!empty($_GET['prodotto'])) {
    $where .= " AND r.id_prodotto=".$_GET['prodotto'];
}

if (!empty($_GET['cliente'])) {
    $where .= " AND a.id_cliente=".$_GET['cliente'];
}

if (!empty($_GET['data_inizio'])) {
    $where .= " AND a.data_acquisto >= '".$_GET['data_inizio']."'";
}

if (!empty($_GET['data_fine'])) {
    $where .= " AND a.data_acquisto <= '".$_GET['data_fine']."'";
}

$query = "
SELECT a.data_acquisto, p.nome, r.quantita, r.prezzo_unitario
FROM righe_acquisto r
JOIN acquisti a ON r.id_acquisto=a.id
JOIN prodotti p ON r.id_prodotto=p.id
WHERE $where
";

$res = mysqli_query($conn,$query);

$prodotti = mysqli_query($conn,"SELECT * FROM prodotti");
$clienti = mysqli_query($conn,"SELECT * FROM clienti");
?>

<h3>Report Vendite</h3>

<form method="GET">
<select name="prodotto" class="form-control mb-2">
<option value="">Tutti i prodotti</option>
<?php while($p=mysqli_fetch_assoc($prodotti)){ ?>
<option value="<?= $p['id'] ?>"><?= $p['nome'] ?></option>
<?php } ?>
</select>

<select name="cliente" class="form-control mb-2">
<option value="">Tutti i clienti</option>
<?php while($c=mysqli_fetch_assoc($clienti)){ ?>
<option value="<?= $c['id'] ?>"><?= $c['nome'] ?></option>
<?php } ?>
</select>

<input type="date" name="data_inizio" class="form-control mb-2">
<input type="date" name="data_fine" class="form-control mb-2">

<button class="btn btn-primary">Filtra</button>
</form>

<table class="table mt-3">
<tr><th>Data</th><th>Prodotto</th><th>Quantità</th><th>Prezzo</th></tr>

<?php while($r=mysqli_fetch_assoc($res)){ ?>
<tr>
<td><?= $r['data_acquisto'] ?></td>
<td><?= $r['nome'] ?></td>
<td><?= $r['quantita'] ?></td>
<td><?= $r['prezzo_unitario'] ?></td>
</tr>
<?php } ?>
</table>

<?php require_once("../common/footer.php"); ?>