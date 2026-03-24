<?php
require_once(__DIR__ . "/../config/db.php");
require_once("../common/header.php");

if ($_POST) {
    $nome = $_POST['nome'];
    $nick = $_POST['nickname'];
    $contatto = $_POST['contatto'];

    mysqli_query($conn, "INSERT INTO clienti(nome,nickname,contatto)
    VALUES('$nome','$nick','$contatto')");
}

$res = mysqli_query($conn, "SELECT * FROM clienti");
?>

<h3>Clienti</h3>

<form method="POST">
<input name="nome" class="form-control mb-2" placeholder="Nome cliente o Cliente generico">

<input name="nickname" class="form-control mb-2" placeholder="Es. famiglia, amico, clienteX">

<input name="contatto" class="form-control mb-2" placeholder="Telefono o email (opzionale)">
<button class="btn btn-primary">Aggiungi</button>
</form>

<table class="table mt-3">
<tr><th>Nome</th><th>Nickname</th></tr>

<?php while($c = mysqli_fetch_assoc($res)) { ?>
<tr>
<td><?= $c['nome'] ?></td>
<td><?= $c['nickname'] ?></td>
</tr>
<?php } ?>

</table>

<?php require_once("../common/footer.php"); ?>