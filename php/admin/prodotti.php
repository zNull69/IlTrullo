<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    echo "<div class='alert alert-success'>Prodotto aggiunto!</div>";
}

$categorie = mysqli_query($conn, "SELECT * FROM categorie ORDER BY nome");
?>

<div class="page-header"><h1>Prodotti</h1></div>

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
                    <input required name="prezzo" type="number" step="0.01" min="0" class="form-control" placeholder="0.00">
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
                    <?php while($c = mysqli_fetch_assoc($categorie)): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Salva</button>
        </form>
    </div>
</div>

<?php
$lista = mysqli_query($conn,
    "SELECT p.*, c.nome AS categoria,
            (SELECT pr.prezzo FROM prezzi pr
             WHERE pr.id_prodotto = p.id AND pr.data_fine IS NULL LIMIT 1) AS prezzo_attuale
     FROM prodotti p
     LEFT JOIN categorie c ON p.id_categoria = c.id
     ORDER BY c.nome, p.nome");
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Unità</th>
            <th>Categoria</th>
            <th>Prezzo attuale</th>
        </tr>
    </thead>
    <tbody>
    <?php while($p = mysqli_fetch_assoc($lista)): ?>
    <tr>
        <td><?= htmlspecialchars($p['nome']) ?></td>
        <td><span class="badge-tipo badge-<?= $p['tipo'] ?>"><?= $p['tipo'] ?></span></td>
        <td><?= htmlspecialchars($p['unita']) ?></td>
        <td><?= htmlspecialchars($p['categoria']) ?></td>
        <td><?= $p['prezzo_attuale'] ? '€ ' . number_format($p['prezzo_attuale'], 2) : '—' ?></td>
    </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>