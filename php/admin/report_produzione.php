<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$res = mysqli_query($conn,"
SELECT p.nome, r.quantita, r.data_produzione
FROM riserva r
JOIN prodotti p ON r.id_prodotto=p.id
");

?>

<h3>Produzione</h3>

<table class="table">
<tr><th>Prodotto</th><th>Quantità</th><th>Data</th></tr>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<tr>
<td><?= $row['nome'] ?></td>
<td><?= $row['quantita'] ?></td>
<td><?= $row['data_produzione'] ?></td>
</tr>
<?php } ?>

</table>

<?php require_once("../common/footer.php"); ?>