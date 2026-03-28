<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$messaggio = "";
$errore    = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['action'])) {
    $nome   = mysqli_real_escape_string($conn, $_POST['nome']);
    $tipo   = mysqli_real_escape_string($conn, $_POST['tipo']);
    $unita  = mysqli_real_escape_string($conn, $_POST['unita']);
    $cat    = (int)$_POST['categoria'];
    $prezzo = (float)$_POST['prezzo'];

    mysqli_query($conn,
        "INSERT INTO prodotti(nome, tipo, unita, id_categoria)
         VALUES('$nome', '$tipo', '$unita', $cat)");

    $id = mysqli_insert_id($conn);

    mysqli_query($conn,
        "INSERT INTO prezzi(id_prodotto, prezzo, data_inizio)
         VALUES($id, $prezzo, NOW())");

    $messaggio = "Prodotto aggiunto.";
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id    = (int)$_POST['id'];
    $nome  = mysqli_real_escape_string($conn, trim($_POST['nome']));
    $tipo  = mysqli_real_escape_string($conn, $_POST['tipo']);
    $unita = mysqli_real_escape_string($conn, trim($_POST['unita']));
    $cat   = (int)$_POST['categoria'];

    if (empty($nome)) {
        $errore = "Il nome è obbligatorio.";
    } else {
        mysqli_query($conn,
            "UPDATE prodotti SET nome='$nome', tipo='$tipo', unita='$unita', id_categoria=$cat
             WHERE id=$id");
        $messaggio = "Prodotto modificato.";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $id = (int)$_POST['id'];

    
    $acq = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS n FROM righe_acquisto WHERE id_prodotto=$id"));
    $ris = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS n FROM riserva WHERE id_prodotto=$id"));
    $con = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) AS n FROM confezioni WHERE id_prodotto=$id"));

    if ((int)$acq['n'] + (int)$ris['n'] + (int)$con['n'] > 0) {
        $errore = "Impossibile eliminare: il prodotto ha acquisti, riserve o confezioni registrate.";
    } else {
        
        mysqli_query($conn, "DELETE FROM prezzi WHERE id_prodotto=$id");
        mysqli_query($conn, "DELETE FROM prodotti WHERE id=$id");
        $messaggio = "Prodotto eliminato.";
    }
}


$categorie = mysqli_query($conn, "SELECT * FROM categorie ORDER BY nome");
$lista     = mysqli_query($conn,
    "SELECT p.*, c.nome AS categoria,
            (SELECT pr.prezzo FROM prezzi pr
             WHERE pr.id_prodotto = p.id AND pr.data_fine IS NULL LIMIT 1) AS prezzo_attuale
     FROM prodotti p
     LEFT JOIN categorie c ON p.id_categoria = c.id
     ORDER BY c.nome, p.nome");
?>

<div class="page-header"><h1>Prodotti</h1></div>

<?php if ($messaggio): ?>
    <div class="alert alert-success mb-3"><?= htmlspecialchars($messaggio) ?></div>
<?php endif; ?>
<?php if ($errore): ?>
    <div class="alert alert-danger mb-3"><?= htmlspecialchars($errore) ?></div>
<?php endif; ?>


<div class="card mb-4">
    <div class="card-header">Nuovo prodotto</div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input required name="nome" class="form-control" placeholder="Es. Miele di acacia">
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label class="form-label">Prezzo</label>
                    <input required name="prezzo" type="number" step="0.01" min="0"
                           class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="fresco">Fresco</option>
                        <option value="riserva">Riserva</option>
                        <option value="confezionato">Confezionato</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Unità</label>
                    <input name="unita" class="form-control" placeholder="kg / pezzo / litro">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select name="categoria" class="form-select">
                    <?php while ($c = mysqli_fetch_assoc($categorie)): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salva</button>
        </form>
    </div>
</div>


<div id="editForm" style="display:none;" class="card mb-4">
    <div class="card-header">Modifica prodotto</div>
    <div class="card-body">
        <form method="POST">
            <input type="hidden" name="action" value="update">
            <input type="hidden" id="edit-id" name="id">
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" id="edit-nome" name="nome" class="form-control" required>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Tipo</label>
                    <select id="edit-tipo" name="tipo" class="form-select">
                        <option value="fresco">Fresco</option>
                        <option value="riserva">Riserva</option>
                        <option value="confezionato">Confezionato</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Unità</label>
                    <input type="text" id="edit-unita" name="unita" class="form-control">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select id="edit-categoria" name="categoria" class="form-select">
                    <?php
                    // Ricarica le categorie per il form modifica
                    $cat2 = mysqli_query($conn, "SELECT * FROM categorie ORDER BY nome");
                    while ($c = mysqli_fetch_assoc($cat2)):
                    ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salva</button>
                <button type="button" class="btn btn-outline-secondary"
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
            <th>Tipo</th>
            <th>Unità</th>
            <th>Categoria</th>
            <th>Prezzo attuale</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php while ($p = mysqli_fetch_assoc($lista)): ?>
    <tr>
        <td><?= htmlspecialchars($p['nome']) ?></td>
        <td><span class="badge-tipo badge-<?= $p['tipo'] ?>"><?= $p['tipo'] ?></span></td>
        <td><?= htmlspecialchars($p['unita']) ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><?= $p['prezzo_attuale'] ? '€ ' . number_format((float)$p['prezzo_attuale'], 2) : '—' ?></td>
        <td style="white-space: nowrap;">
            <button class="btn btn-outline-secondary btn-sm"
                onclick="showEditForm(
                    <?= $p['id'] ?>,
                    '<?= htmlspecialchars($p['nome'],      ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($p['tipo'],      ENT_QUOTES) ?>',
                    '<?= htmlspecialchars($p['unita'],     ENT_QUOTES) ?>',
                    <?= (int)$p['id_categoria'] ?>
                )">
                Modifica
            </button>
            <form method="POST" style="display:inline;"
                onsubmit="return confirm('Eliminare \'<?= htmlspecialchars($p['nome'], ENT_QUOTES) ?>\'?\nSe ha acquisti, riserve o confezioni non sarà eliminabile.')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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
function showEditForm(id, nome, tipo, unita, idCategoria) {
    document.getElementById('edit-id').value    = id;
    document.getElementById('edit-nome').value  = nome;
    document.getElementById('edit-unita').value = unita;

    
    var tipoSelect = document.getElementById('edit-tipo');
    for (var i = 0; i < tipoSelect.options.length; i++) {
        if (tipoSelect.options[i].value === tipo) {
            tipoSelect.selectedIndex = i;
            break;
        }
    }

    
    var catSelect = document.getElementById('edit-categoria');
    for (var j = 0; j < catSelect.options.length; j++) {
        if (parseInt(catSelect.options[j].value) === idCategoria) {
            catSelect.selectedIndex = j;
            break;
        }
    }

    var form = document.getElementById('editForm');
    form.style.display = 'block';
    form.scrollIntoView({ behavior: 'smooth', block: 'center' });
}
</script>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>