<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$res = mysqli_query($conn,"
SELECT p.nome, c.giacenza
FROM confezioni c
JOIN prodotti p ON c.id_prodotto=p.id
");

?>

<h3>Giacenze</h3>

<table class="table">
<tr><th>Prodotto</th><th>Giacenza</th></tr>

<?php while($row=mysqli_fetch_assoc($res)){ ?>
<tr>
<td><?= $row['nome'] ?></td>
<td><?= $row['giacenza'] ?></td>
</tr>
<?php } ?>

</table>

<?php require_once("../common/footer.php"); ?>