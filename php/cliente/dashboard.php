<?php
require_once(__DIR__ . "/../common/header.php");
require_once(__DIR__ . "/../common/auth_cliente.php");
?>
<h3>Benvenuto, <?= htmlspecialchars($_SESSION['user']) ?>!</h3>
<div class="row">
    <a href="/php/cliente/catalogo.php" class="btn btn-primary m-2">Catalogo Prodotti</a>
    <a href="/php/cliente/i_miei_acquisti.php" class="btn btn-primary m-2">I miei acquisti</a>
</div>
<?php require_once(__DIR__ . "/../common/footer.php"); ?>