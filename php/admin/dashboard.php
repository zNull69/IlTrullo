<?php
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_admin.php");
?>

<div class="page-header">
    <h1>Gestione</h1>
</div>

<p class="dash-section-label">Archivio</p>
<div class="dash-grid">
    <a href="/php/admin/prodotti.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Prodotti
    </a>
    <a href="/php/admin/categorie.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Categorie
    </a>
    <a href="/php/admin/clienti.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Clienti
    </a>
    <a href="/php/admin/prezzi.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Prezzi
    </a>
</div>

<p class="dash-section-label">Produzione</p>
<div class="dash-grid">
    <a href="/php/gestione/riserva.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Carico riserva
    </a>
    <a href="/php/gestione/confezionamento.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Confezionamento
    </a>
    <a href="/php/gestione/spostamenti.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Spostamenti
    </a>
</div>

<p class="dash-section-label">Vendite e report</p>
<div class="dash-grid">
    <a href="/php/vendite/vendite.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Nuova vendita
    </a>
    <a href="/php/admin/report_vendite.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Report vendite
    </a>
    <a href="/php/admin/report_giacenze.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Giacenze
    </a>
    <a href="/php/admin/report_produzione.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Produzione
    </a>
</div>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>