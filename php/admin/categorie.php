<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$messaggio = "";
$errore    = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    if (empty($nome)) {
        $errore = "Il nome è obbligatorio.";
    } else {
        mysqli_query($conn, "INSERT IGNORE INTO categorie(nome) VALUES('$nome')");
        if (mysqli_affected_rows($conn) === 0) {
            $errore = "Categoria già esistente.";
        } else {
            $messaggio = "Categoria aggiunta.";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id   = (int)$_POST['id'];
    $nome = mysqli_real_escape_string($conn, trim($_POST['nome']));
    if (empty($nome)) {
        $errore = "Il nome è obbligatorio.";
    } else {
        $dup = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id FROM categorie WHERE nome='$nome' AND id != $id"));
        if ($dup) {
            $errore = "Esiste già una categoria con questo nome.";
        } else {
            mysqli_query($conn, "UPDATE categorie SET nome='$nome' WHERE id=$id");
            $messaggio = "Categoria modificata.";
        }
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];
    
    $check = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS n FROM prodotti WHERE id_categoria=$id"));
    if ((int)$check['n'] > 0) {
        $errore = "Impossibile eliminare: esistono prodotti in questa categoria.";
    } else {
        mysqli_query($conn, "DELETE FROM categorie WHERE id=$id");
        $messaggio = "Categoria eliminata.";
    }
}


$res = mysqli_query($conn, "SELECT * FROM categorie ORDER BY nome");
?>

<div class="page-header"><h1>Categorie</h1></div>

<?php if ($messaggio): ?>
    <div class="alert alert-success mb-3"><?= htmlspecialchars($messaggio) ?></div>
<?php endif; ?>
<?php if ($errore): ?>
    <div class="alert alert-danger mb-3"><?= htmlspecialchars($errore) ?></div>
<?php endif; ?>


<div class="card mb-4">
    <div class="card-header">Nuova categoria</div>
    <div class="card-body">
        <form method="POST" autocomplete="off">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Nome</label>
                    <input name="nome" class="form-control"
                           placeholder="Es. Frutta fresca, Marmellate, Olio" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Aggiungi</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div id="editForm" style="display:none;" class="card mb-4">
    <div class="card-header">Modifica categoria</div>
    <div class="card-body">
        <form method="POST" autocomplete="off">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="edit-id" name="id">
            <div class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label">Nome</label>
                    <input type="text" id="edit-nome" name="nome" class="form-control" required>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">Salva</button>
                    <button type="button" class="btn btn-outline-secondary w-100"
                        onclick="document.getElementById('editForm').style.display='none'">
                        Annulla
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<table class="table table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php while ($row = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= htmlspecialchars($row['nome']) ?></td>
        <td style="white-space: nowrap;">
            <button class="btn btn-outline-secondary btn-sm"
                onclick="showEditForm(
                    <?= $row['id'] ?>,
                    '<?= htmlspecialchars($row['nome'], ENT_QUOTES) ?>'
                )">
                Modifica
            </button>
            <form method="POST" style="display:inline;"
                onsubmit="return confirm('Eliminare la categoria \'<?= htmlspecialchars($row['nome'], ENT_QUOTES) ?>\'?\nSe ha prodotti associati non sarà eliminabile.')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $row['id'] ?>">
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
function showEditForm(id, nome) {
    document.getElementById('edit-id').value   = id;
    document.getElementById('edit-nome').value = nome;
    var form = document.getElementById('editForm');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>