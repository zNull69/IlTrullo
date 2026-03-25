<?php
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");
?>

<div class="page-header">
    <h1>Benvenuto, <?= htmlspecialchars($_SESSION['user']) ?></h1>
</div>

<div class="dash-grid">
    <a href="/php/cliente/catalogo.php" class="dash-tile">
        <span class="dash-tile-bar"></span>Catalogo prodotti
    </a>
    <a href="/php/cliente/i_miei_acquisti.php" class="dash-tile">
        <span class="dash-tile-bar"></span>I miei acquisti
    </a>
</div>

<?php require_once(__DIR__ . "/../common/footer.php"); ?>