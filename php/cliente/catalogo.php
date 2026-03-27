<?php
require_once(__DIR__ . "/../config/db.php");
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");

$messaggio = "";
$errore    = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_prodotto = (int)$_POST['id_prodotto'];
    $qta         = (float)$_POST['quantita'];

    if ($qta <= 0) {
        $errore = "La quantità deve essere maggiore di zero.";
    } else {
        
        $prod = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT * FROM prodotti WHERE id=$id_prodotto AND disponibile=TRUE"));

        
        $prow = mysqli_fetch_assoc(mysqli_query($conn,
            "SELECT prezzo FROM prezzi
             WHERE id_prodotto=$id_prodotto AND data_fine IS NULL LIMIT 1"));

        if (!$prod || !$prow) {
            $errore = "Prodotto non disponibile.";
        } else {
            $prezzo = (float)$prow['prezzo'];
            $ok     = false;

            
            if ($prod['tipo'] === 'confezionato') {
                $g = mysqli_fetch_assoc(mysqli_query($conn,
                    "SELECT id, giacenza FROM confezioni
                     WHERE id_prodotto=$id_prodotto AND giacenza >= $qta
                     ORDER BY data_confezionamento ASC LIMIT 1"));
                if (!$g) {
                    $errore = "Giacenza insufficiente per \"" . htmlspecialchars($prod['nome']) . "\".";
                } else {
                    mysqli_query($conn,
                        "UPDATE confezioni SET giacenza = giacenza - $qta WHERE id=" . $g['id']);
                    $ok = true;
                }
            } elseif ($prod['tipo'] === 'riserva') {
                $r = mysqli_fetch_assoc(mysqli_query($conn,
                    "SELECT SUM(quantita) AS tot FROM riserva WHERE id_prodotto=$id_prodotto"));
                if ((float)$r['tot'] < $qta) {
                    $errore = "Quantità in riserva insufficiente per \"" . htmlspecialchars($prod['nome']) . "\".";
                } else {
                    
                    $lotti = mysqli_query($conn,
                        "SELECT id, quantita FROM riserva
                         WHERE id_prodotto=$id_prodotto AND quantita > 0
                         ORDER BY data_produzione ASC");
                    $da_scalare = $qta;
                    while ($da_scalare > 0 && $lotto = mysqli_fetch_assoc($lotti)) {
                        if ((float)$lotto['quantita'] <= $da_scalare) {
                            $da_scalare -= (float)$lotto['quantita'];
                            mysqli_query($conn,
                                "UPDATE riserva SET quantita=0 WHERE id=" . $lotto['id']);
                        } else {
                            mysqli_query($conn,
                                "UPDATE riserva SET quantita=quantita-$da_scalare WHERE id=" . $lotto['id']);
                            $da_scalare = 0;
                        }
                    }
                    $ok = true;
                }
            } else {
                
                $ok = true;
            }

            if ($ok) {
                
                $username   = mysqli_real_escape_string($conn, $_SESSION['user']);
                $cliente    = mysqli_fetch_assoc(mysqli_query($conn,
                    "SELECT id FROM clienti
                     WHERE nickname='$username' OR nome='$username' LIMIT 1"));
                $id_cliente = $cliente ? (int)$cliente['id'] : 1;

                $totale = round($qta * $prezzo, 2);

                
                mysqli_query($conn,
                    "INSERT INTO acquisti(id_cliente, data_acquisto, totale, totale_pagato)
                     VALUES($id_cliente, NOW(), $totale, 0)");
                $id_acquisto = mysqli_insert_id($conn);

                
                mysqli_query($conn,
                    "INSERT INTO righe_acquisto(id_acquisto, id_prodotto, quantita, prezzo_unitario)
                     VALUES($id_acquisto, $id_prodotto, $qta, $prezzo)");

                $messaggio = "Acquisto di <strong>" . htmlspecialchars($prod['nome']) .
                             "</strong> confermato: " . $qta . " " . htmlspecialchars($prod['unita']) .
                             " — <strong>€ " . number_format($totale, 2) . "</strong>";
            }
        }
    }
}


$res = mysqli_query($conn,
    "SELECT p.id, p.nome, p.tipo, p.unita, c.nome AS categoria, pr.prezzo,
            CASE
                WHEN p.tipo = 'confezionato' THEN
                    (SELECT COALESCE(SUM(cf.giacenza), 0)
                     FROM confezioni cf WHERE cf.id_prodotto = p.id)
                WHEN p.tipo = 'riserva' THEN
                    (SELECT COALESCE(SUM(r.quantita), 0)
                     FROM riserva r WHERE r.id_prodotto = p.id)
                ELSE NULL
            END AS disponibilita
     FROM prodotti p
     LEFT JOIN categorie c  ON p.id_categoria = c.id
     LEFT JOIN prezzi pr    ON pr.id_prodotto = p.id AND pr.data_fine IS NULL
     WHERE p.disponibile = TRUE
     ORDER BY c.nome, p.nome");
?>

<div class="page-header">
    <h1>Catalogo prodotti</h1>
</div>

<?php if ($messaggio): ?>
    <div class="alert alert-success mb-4"><?= $messaggio ?></div>
<?php endif; ?>
<?php if ($errore): ?>
    <div class="alert alert-danger mb-4"><?= htmlspecialchars($errore) ?></div>
<?php endif; ?>

<div class="catalogo-grid">
<?php
$categoria_corrente = null;
while ($r = mysqli_fetch_assoc($res)):
    if ($r['categoria'] !== $categoria_corrente):
        if ($categoria_corrente !== null) 
            echo '</div>'; 
        $categoria_corrente = $r['categoria'];
        echo '<div class="catalogo-gruppo">';
        echo '<p class="dash-section-label">' . htmlspecialchars($categoria_corrente) . '</p>';
        echo '<div class="catalogo-cards">';
    endif;

    $disponibilita = $r['disponibilita'];
    $esaurito = ($r['tipo'] !== 'fresco') && ((float)$disponibilita <= 0);
?>
    <div class="prodotto-card <?= $esaurito ? 'prodotto-esaurito' : '' ?>">
        <div class="prodotto-nome"><?= htmlspecialchars($r['nome']) ?></div>
        <div class="prodotto-meta">
            <span class="badge-tipo badge-<?= $r['tipo'] ?>"><?= $r['tipo'] ?></span>
            <?php if ($r['tipo'] !== 'fresco' && $disponibilita !== null): ?>
                <span class="prodotto-stock <?= $esaurito ? 'stock-zero' : '' ?>">
                    <?= $esaurito ? 'Esaurito' : 'Disponibile: ' . number_format((float)$disponibilita, 2) . ' ' . htmlspecialchars($r['unita']) ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="prodotto-prezzo">€ <?= number_format((float)$r['prezzo'], 2) ?> / <?= htmlspecialchars($r['unita']) ?></div>

        <?php if (!$esaurito): ?>
        <form method="POST" class="prodotto-form">
            <input type="hidden" name="id_prodotto" value="<?= $r['id'] ?>">
            <div class="prodotto-form-row">
                <input type="number" name="quantita" min="0.1" step="0.1"
                       class="form-control form-control-sm"
                       placeholder="Qtà" required>
                <button type="submit" class="btn btn-primary btn-sm">Acquista</button>
            </div>
        </form>
        <?php else: ?>
            <p class="prodotto-esaurito-label">Non disponibile</p>
        <?php endif; ?>
    </div>
<?php endwhile; ?>
<?php if ($categoria_corrente !== null): ?>
    </div></div>
<?php endif; ?>
</div>

<style>
.catalogo-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-bottom: 1rem;
}
.prodotto-card {
    background: var(--bianco);
    border: 1px solid var(--bordo);
    border-radius: 6px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.prodotto-card.prodotto-esaurito {
    opacity: 0.55;
}
.prodotto-nome {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--testo);
}
.prodotto-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.prodotto-stock {
    font-size: 0.75rem;
    color: var(--testo-muto);
}
.stock-zero {
    color: #a32d2d;
    font-weight: 600;
}
.prodotto-prezzo {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--verde-scuro);
}
.prodotto-form-row {
    display: flex;
    gap: 0.4rem;
    align-items: center;
}
.prodotto-form-row .form-control {
    width: 80px;
}
.prodotto-esaurito-label {
    font-size: 0.8rem;
    color: var(--testo-muto);
    margin: 0;
}
</style>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>