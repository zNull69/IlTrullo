<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome     = mysqli_real_escape_string($conn, $_POST['nome']);
    $nick     = mysqli_real_escape_string($conn, $_POST['nickname']);
    $contatto = mysqli_real_escape_string($conn, $_POST['contatto']);

    mysqli_query($conn,
        "INSERT INTO clienti(nome, nickname, contatto)
         VALUES('$nome', '$nick', '$contatto')");
    echo "<div class='alert alert-success'>Cliente aggiunto!</div>";
}

$res = mysqli_query($conn, "SELECT * FROM clienti");
?>

<h3>Clienti</h3>
<form method="POST">
    <input name="nome" class="form-control mb-2" placeholder="Nome cliente">
    <input name="nickname" class="form-control mb-2" placeholder="Es. famiglia, amico, clienteX">
    <input name="contatto" class="form-control mb-2" placeholder="Telefono o email (opzionale)">
    <button class="btn btn-primary">Aggiungi</button>
</form>

<table class="table mt-3">
<tr><th>Nome</th><th>Nickname</th><th>Contatto</th></tr>
<?php while($c = mysqli_fetch_assoc($res)): ?>
<tr>
    <td><?= htmlspecialchars($c['nome']) ?></td>
    <td><?= htmlspecialchars($c['nickname']) ?></td>
    <td><?= htmlspecialchars($c['contatto']) ?></td>
</tr>
<?php endwhile; ?>
</table>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>