<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");

// Collega l'utente loggato al suo record in clienti tramite nickname o nome
$username = mysqli_real_escape_string($conn, $_SESSION['user']);
$cliente  = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT id FROM clienti WHERE nickname='$username' OR nome='$username' LIMIT 1"));

if (!$cliente) {
    echo "<div class='alert alert-info'>Nessun acquisto trovato per il tuo account.</div>";
    require_once(__DIR__ . "/../common/footer.php");
    exit;
}

$id_cliente = $cliente['id'];
$res = mysqli_query($conn,
    "SELECT a.data_acquisto, a.totale, a.totale_pagato, a.note,
            p.nome AS prodotto, r.quantita, r.prezzo_unitario
     FROM acquisti a
     JOIN righe_acquisto r ON r.id_acquisto = a.id
     JOIN prodotti p ON p.id = r.id_prodotto
     WHERE a.id_cliente = $id_cliente
     ORDER BY a.data_acquisto DESC");
?>
<h3>I miei acquisti</h3>
<table class="table mt-3">
    <tr><th>Data</th><th>Prodotto</th><th>Quantità</th><th>Prezzo unitario</th><th>Note</th></tr>
    <?php while($r = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= $r['data_acquisto'] ?></td>
        <td><?= htmlspecialchars($r['prodotto']) ?></td>
        <td><?= $r['quantita'] ?></td>
        <td>€ <?= number_format($r['prezzo_unitario'], 2) ?></td>
        <td><?= htmlspecialchars($r['note']) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php require_once(__DIR__ . "/../common/footer.php"); ?>