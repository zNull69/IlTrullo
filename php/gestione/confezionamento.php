<?php
require_once("../config/db.php");
require_once("../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$prodotti = mysqli_query($conn, "SELECT * FROM prodotti WHERE tipo='riserva'");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id  = (int)$_POST['prodotto'];
    $num = (float)$_POST['quantita'];

    // Prende il lotto di riserva più vecchio con quantità sufficiente
    $r = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id, quantita, data_produzione FROM riserva
         WHERE id_prodotto=$id AND quantita >= $num
         ORDER BY data_produzione ASC LIMIT 1"));

    if (!$r) {
        echo "<div class='alert alert-danger'>Riserva insufficiente.</div>";
    } else {
        $nuova = $r['quantita'] - $num;
        $data_orig = $r['data_produzione'];

        mysqli_query($conn,
            "UPDATE riserva SET quantita=$nuova WHERE id=" . $r['id']);

        mysqli_query($conn,
            "INSERT INTO confezioni(id_prodotto, id_riserva, quantita,
                data_produzione_originale, data_confezionamento, giacenza)
             VALUES($id, " . $r['id'] . ", $num, '$data_orig', NOW(), $num)");

        echo "<div class='alert alert-success'>Confezionamento registrato!</div>";
    }
}
?>

<h3>Confezionamento</h3>
<form method="POST">
    <select name="prodotto" class="form-control mb-2">
        <?php while($p = mysqli_fetch_assoc($prodotti)): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
        <?php endwhile; ?>
    </select>
    <input name="quantita" required type="number" step="0.01" class="form-control mb-2" placeholder="Quantità da confezionare">
    <button class="btn btn-primary">Confeziona</button>
</form>

<?php require_once("../common/footer.php"); ?>