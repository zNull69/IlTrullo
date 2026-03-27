<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");

$query = "
SELECT 
    a.id_cliente,
    c.nome AS nome_cliente,
    a.data_acquisto,
    p.nome AS nome_prodotto,
    r.quantita,
    r.prezzo_unitario
FROM righe_acquisto r
JOIN acquisti a ON r.id_acquisto = a.id
JOIN prodotti p ON r.id_prodotto = p.id
JOIN clienti c ON a.id_cliente = c.id
ORDER BY a.data_acquisto DESC
";

$res = mysqli_query($conn, $query);
?>

<h3>Report Vendite</h3>

<table class="table mt-3">
    <tr>
        <th>Id Cliente</th>
        <th>Nome</th>
        <th>Data</th>
        <th>Prodotto</th>
        <th>Quantità</th>
        <th>Prezzo</th>
    </tr>

    <?php while ($r = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td><?= htmlspecialchars($r['id_cliente']) ?></td>
            <td><?= htmlspecialchars($r['nome_cliente']) ?></td>
            <td><?= htmlspecialchars($r['data_acquisto']) ?></td>
            <td><?= htmlspecialchars($r['nome_prodotto']) ?></td>
            <td><?= htmlspecialchars($r['quantita']) ?></td>
            <td><?= htmlspecialchars($r['prezzo_unitario']) ?></td>
        </tr>
    <?php } ?>
</table>

<?php require_once("../common/footer.php"); ?>