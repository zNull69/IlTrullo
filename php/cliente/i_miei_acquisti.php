<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");


$username = mysqli_real_escape_string($conn, $_SESSION['user']);
$cliente  = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT id, nome FROM clienti
     WHERE nickname='$username' OR nome='$username' LIMIT 1"));

if (!$cliente) {
    echo "<div class='alert alert-info'>Nessun acquisto trovato per il tuo account.</div>";
    require_once(__DIR__ . "/../common/footer.php");
    exit;
}

$id_cliente = (int)$cliente['id'];


$acquisti = mysqli_query($conn,
    "SELECT a.id, a.data_acquisto, a.totale, a.totale_pagato, a.note
     FROM acquisti a
     WHERE a.id_cliente = $id_cliente
     ORDER BY a.data_acquisto DESC");


$totale_generale = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT
        COALESCE(SUM(totale), 0)         AS tot_dovuto,
        COALESCE(SUM(totale_pagato), 0)  AS tot_pagato
     FROM acquisti WHERE id_cliente = $id_cliente"));

$dovuto  = (float)$totale_generale['tot_dovuto'];
$pagato  = (float)$totale_generale['tot_pagato'];
$debito  = $dovuto - $pagato;
?>

<div class="page-header">
    <h1>I miei acquisti</h1>
</div>

<?php if (mysqli_num_rows($acquisti) === 0): ?>
    <div class="alert alert-info">Non hai ancora effettuato acquisti.</div>
<?php else: ?>

<?php while ($acq = mysqli_fetch_assoc($acquisti)):
    $id_acq = (int)$acq['id'];


    $righe = mysqli_query($conn,
        "SELECT r.quantita, r.prezzo_unitario, r.omaggio,
                p.nome AS prodotto, p.unita
         FROM righe_acquisto r
         JOIN prodotti p ON p.id = r.id_prodotto
         WHERE r.id_acquisto = $id_acq");
?>
    <div class="acquisto-card mb-3">
        <div class="acquisto-header">
            <span class="acquisto-data"><?= date('d/m/Y', strtotime($acq['data_acquisto'])) ?></span>
            <span class="acquisto-totale">€ <?= number_format((float)$acq['totale'], 2) ?></span>
        </div>
        <table class="table table-sm mb-0">
            <thead>
                <tr>
                    <th>Prodotto</th>
                    <th class="text-end">Quantità</th>
                    <th class="text-end">Prezzo unitario</th>
                    <th class="text-end">Subtotale</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($riga = mysqli_fetch_assoc($righe)): ?>
                <tr <?= $riga['omaggio'] ? 'class="riga-omaggio"' : '' ?>>
                    <td>
                        <?= htmlspecialchars($riga['prodotto']) ?>
                        <?php if ($riga['omaggio']): ?>
                            <span class="badge-omaggio">omaggio</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end"><?= number_format((float)$riga['quantita'], 2) ?> <?= htmlspecialchars($riga['unita']) ?></td>
                    <td class="text-end">€ <?= number_format((float)$riga['prezzo_unitario'], 2) ?></td>
                    <td class="text-end">
                        <?php if ($riga['omaggio']): ?>
                            <span style="color: var(--testo-muto)">—</span>
                        <?php else: ?>
                            € <?= number_format((float)$riga['quantita'] * (float)$riga['prezzo_unitario'], 2) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <?php if (!empty($acq['note'])): ?>
            <div class="acquisto-note"><?= htmlspecialchars($acq['note']) ?></div>
        <?php endif; ?>
    </div>
<?php endwhile; ?>

    <!-- Riepilogo debito totale -->
    <div class="debito-box">
        <div class="debito-row debito-finale">
            <span><?= $debito > 0 ? 'Debito residuo' : 'Saldo' ?></span>
            <span>€ <?= number_format(abs($debito), 2) ?></span>
        </div>
    </div>

<?php endif; ?>

<style>
.acquisto-card {
    background: var(--bianco);
    border: 1px solid var(--bordo);
    border-radius: 6px;
    overflow: hidden;
}
.acquisto-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.65rem 0.875rem;
    background: var(--superficie);
    border-bottom: 1px solid var(--bordo);
}
.acquisto-data {
    font-size: 0.8125rem;
    color: var(--testo-muto);
    font-weight: 600;
}
.acquisto-totale {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--verde-scuro);
}
.acquisto-note {
    padding: 0.5rem 0.875rem;
    font-size: 0.8rem;
    color: var(--testo-muto);
    border-top: 1px solid var(--bordo);
    font-style: italic;
}
.riga-omaggio td {
    color: var(--testo-muto);
}
.badge-omaggio {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
    background: #f5f0e6;
    color: #6b4f10;
    border: 1px solid #d4b86a;
    margin-left: 0.3rem;
    vertical-align: middle;
}
.debito-box {
    background: var(--bianco);
    border: 1px solid var(--bordo);
    border-radius: 6px;
    padding: 1rem 1.25rem;
    max-width: 340px;
    margin-left: auto;
    margin-top: 1.5rem;
}
.debito-row {
    display: flex;
    justify-content: space-between;
    padding: 0.4rem 0;
    font-size: 0.875rem;
    border-bottom: 1px solid var(--bordo);
    color: var(--testo);
}
.debito-row:last-child {
    border-bottom: none;
    padding-top: 0.65rem;
    margin-top: 0.25rem;
    font-weight: 700;
    font-size: 0.95rem;
}
.debito-finale { color: #3b5c14; }
</style>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>