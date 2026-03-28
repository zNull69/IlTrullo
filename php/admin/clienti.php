<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$messaggio = "";
$errore    = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nome     = mysqli_real_escape_string($conn, $_POST['nome']);
    $nick     = mysqli_real_escape_string($conn, $_POST['nickname']);
    $contatto = mysqli_real_escape_string($conn, $_POST['contatto']);

    if (empty($nome)) {
        $errore = "Il nome è obbligatorio.";
    } else {
        mysqli_query($conn,
            "INSERT INTO clienti(nome, nickname, contatto)
             VALUES('$nome', '$nick', '$contatto')");
        $messaggio = "Cliente aggiunto con successo.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'update') {

    $id       = (int)$_POST['id'];
    $nome     = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $nick     = mysqli_real_escape_string($conn, trim($_POST['nickname']));
    $contatto = mysqli_real_escape_string($conn, trim($_POST['contatto']));

    if (empty($nome)) {
        $errore = "Il nome è obbligatorio.";
    } else {
        mysqli_query($conn,
            "UPDATE clienti SET nome='$nome', nickname='$nick', contatto='$contatto'
             WHERE id=$id");
        $messaggio = "Cliente modificato con successo.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'delete') {

    $id = (int)$_POST['id'];

    
    $check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS n FROM acquisti WHERE id_cliente=$id"));

    if ((int)$check['n'] > 0) {
        $errore = "Impossibile eliminare: il cliente ha acquisti registrati.";
    } else {
        mysqli_query($conn, "DELETE FROM clienti WHERE id=$id");
        $messaggio = "Cliente eliminato.";
    }
}


$res = mysqli_query($conn, "SELECT * FROM clienti ORDER BY nome");
?>

<div class="page-header"><h1>Clienti</h1></div>

<?php if ($messaggio): ?>
    <div class="alert alert-success mb-3"><?= htmlspecialchars($messaggio) ?></div>
<?php endif; ?>
<?php if ($errore): ?>
    <div class="alert alert-danger mb-3"><?= htmlspecialchars($errore) ?></div>
<?php endif; ?>


<div class="card mb-4">
    <div class="card-header">Nuovo cliente</div>
    <div class="card-body">
        <form method="POST" autocomplete="off">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input name="nome" class="form-control" placeholder="Nome e cognome" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nickname</label>
                    <input name="nickname" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contatto</label>
                    <input name="contatto" class="form-control" placeholder="Telefono o email">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
        </form>
    </div>
</div>


<div id="editForm" style="display:none;" class="card mb-4">
    <div class="card-header">Modifica cliente</div>
    <div class="card-body">
        <form method="POST" autocomplete="off">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="edit-id" name="id">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Nome</label>
                    <input type="text" id="edit-nome" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nickname</label>
                    <input type="text" id="edit-nickname" name="nickname" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contatto</label>
                    <input type="text" id="edit-contatto" name="contatto" class="form-control">
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Salva</button>
                <button type="button" class="btn btn-outline-secondary ms-2"
                    onclick="document.getElementById('editForm').style.display='none'">
                    Annulla
                </button>
            </div>
        </form>
    </div>
</div>


<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Nickname</th>
            <th>Contatto</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php while ($c = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= htmlspecialchars($c['nome']) ?></td>
        <td><?= htmlspecialchars($c['nickname'] ?? '—') ?></td>
        <td><?= htmlspecialchars($c['contatto'] ?? '—') ?></td>
        <td style="white-space: nowrap;">
            
            <button class="btn btn-outline-secondary btn-sm"
                onclick="showEditForm(
                    <?= $c['id'] ?>,
                    '<?= htmlspecialchars($c['nome'],     ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($c['nickname'] ?? '', ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($c['contatto'] ?? '', ENT_QUOTES) ?>'
                )">
                Modifica
            </button>

            
            <form method="POST" style="display:inline;"
                onsubmit="return confirm('Eliminare <?= htmlspecialchars($c['nome'], ENT_QUOTES) ?>?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                <button type="submit" class="btn btn-outline-secondary btn-sm ms-1">
                    Elimina
                </button>
            </form>
        </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<script>
function showEditForm(id, nome, nickname, contatto) {
    document.getElementById('edit-id').value       = id;
    document.getElementById('edit-nome').value     = nome;
    document.getElementById('edit-nickname').value = nickname;
    document.getElementById('edit-contatto').value = contatto;

    var form = document.getElementById('editForm');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>