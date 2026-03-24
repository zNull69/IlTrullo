<?php
require_once("../config/db.php");
require_once("../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$prodotti = mysqli_query($conn, "SELECT * FROM prodotti");
$clienti  = mysqli_query($conn, "SELECT * FROM clienti");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_prodotto = (int)$_POST['prodotto'];
    $id_cliente  = (int)$_POST['cliente'];
    $qta         = (float)$_POST['quantita'];

    $prod = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT * FROM prodotti WHERE id=$id_prodotto"));

    $prow = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT prezzo FROM prezzi 
         WHERE id_prodotto=$id_prodotto AND data_fine IS NULL LIMIT 1"));
    $prezzo = $prow ? $prow['prezzo'] : 0;

    // Controllo giacenza solo per non-freschi
    if ($prod['tipo'] == 'confezionato') {
        $g = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT id, giacenza FROM confezioni
             WHERE id_prodotto=$id_prodotto AND giacenza >= $qta
             ORDER BY data_confezionamento ASC LIMIT 1"));
        if (!$g) die("Giacenza insufficiente.");
        mysqli_query($conn,
            "UPDATE confezioni SET giacenza=giacenza-$qta WHERE id=" . $g['id']);
    } elseif ($prod['tipo'] == 'riserva') {
        $r = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT SUM(quantita) as tot FROM riserva WHERE id_prodotto=$id_prodotto"));
        if ($r['tot'] < $qta) die("Riserva insufficiente.");
        mysqli_query($conn,
            "UPDATE riserva SET quantita=quantita-$qta
             WHERE id_prodotto=$id_prodotto AND quantita>0 LIMIT 1");
    }
    // tipo='fresco': nessun controllo giacenza

    $tot = $qta * $prezzo;

    mysqli_query($conn,
        "INSERT INTO acquisti(id_cliente, data_acquisto, totale, totale_pagato)
         VALUES($id_cliente, NOW(), $tot, $tot)");
    $id_a = mysqli_insert_id($conn);

    mysqli_query($conn,
        "INSERT INTO righe_acquisto(id_acquisto, id_prodotto, quantita, prezzo_unitario)
         VALUES($id_a, $id_prodotto, $qta, $prezzo)");

    echo "<div class='alert alert-success'>Vendita registrata!</div>";
}
?>

<h3>Nuova Vendita</h3>
<form method="POST">
    <select name="cliente" class="form-control mb-2">
        <?php while($c = mysqli_fetch_assoc($clienti)): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
        <?php endwhile; ?>
    </select>

    <select name="prodotto" class="form-control mb-2">
        <?php while($p = mysqli_fetch_assoc($prodotti)): ?>
        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?> (<?= $p['tipo'] ?>)</option>
        <?php endwhile; ?>
    </select>

    <input name="quantita" required type="number" step="0.01" class="form-control mb-2" placeholder="Quantità">
    <button class="btn btn-primary">Registra Vendita</button>
</form>

<?php require_once("../common/footer.php"); ?>