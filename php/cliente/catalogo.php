<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");

$res = mysqli_query($conn,
    "SELECT p.nome, p.tipo, p.unita, c.nome AS categoria, pr.prezzo
     FROM prodotti p
     LEFT JOIN categorie c ON p.id_categoria = c.id
     LEFT JOIN prezzi pr ON pr.id_prodotto = p.id AND pr.data_fine IS NULL
     WHERE p.disponibile = TRUE
     ORDER BY c.nome, p.nome");
?>
<h3>Catalogo Prodotti</h3>
<table class="table mt-3">
    <tr><th>Prodotto</th><th>Categoria</th><th>Tipo</th><th>Unità</th><th>Prezzo</th></tr>
    <?php while($r = mysqli_fetch_assoc($res)): ?>
    <tr>
        <td><?= htmlspecialchars($r['nome']) ?></td>
        <td><?= htmlspecialchars($r['categoria']) ?></td>
        <td><?= $r['tipo'] ?></td>
        <td><?= htmlspecialchars($r['unita']) ?></td>
        <td>€ <?= number_format($r['prezzo'], 2) ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php require_once(__DIR__ . "/../common/footer.php"); ?>