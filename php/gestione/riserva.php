<?php
require_once("../config/db.php");
require_once("../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$prodotti = mysqli_query($conn, "SELECT * FROM prodotti WHERE tipo='riserva'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id  = (int)$_POST['prodotto'];
    $qta = (float)$_POST['quantita'];

    mysqli_query($conn,
        "INSERT INTO riserva(id_prodotto, quantita, data_produzione)
         VALUES($id, $qta, NOW())");

    echo "<div class='alert alert-success'>Riserva aggiornata!</div>";
}
?>

<h3>Carico Riserva</h3>
<form method="POST">
    <select name="prodotto" class="form-control mb-2">
        <?php while($p = mysqli_fetch_assoc($prodotti)): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
        <?php endwhile; ?>
    </select>
    <input name="quantita" required type="number" step="0.01" class="form-control mb-2" placeholder="Quantità prodotta">
    <button class="btn btn-primary">Registra</button>
</form>

<?php require_once("../common/footer.php"); ?>