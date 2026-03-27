<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

if (isset($_POST['nome'])) {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    mysqli_query($conn, "INSERT IGNORE INTO categorie(nome) VALUES('$nome')");
}

$res = mysqli_query($conn, "SELECT * FROM categorie, ORDER BY id");
?>

<h3>Categorie</h3>

<form method="POST">
<input name="nome" class="form-control mb-2" placeholder="Es. Frutta fresca, Marmellate, Olio">
<button class="btn btn-primary">Aggiungi</button>
</form>

<table class="table mt-3">
<tr><th>ID</th><th>Nome</th></tr>

<?php while($row = mysqli_fetch_assoc($res)) { ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['nome'] ?></td>
</tr>
<?php } ?>

</table>

<?php require_once("../common/footer.php"); ?>