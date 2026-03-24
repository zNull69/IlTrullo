<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/public/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar p-3">
    <a class="text-white fw-bold" href="<?= $_SESSION['ruolo'] === 'admin' ? '/php/admin/dashboard.php' : '/php/cliente/dashboard.php' ?>">
        Il Trullo
    </a>
    <div>
    <?php if ($_SESSION['ruolo'] === 'admin'): ?>
        <a href="/php/admin/prodotti.php" class="text-white me-2">Prodotti</a>
        <a href="/php/admin/categorie.php" class="text-white me-2">Categorie</a>
        <a href="/php/admin/clienti.php" class="text-white me-2">Clienti</a>
        <a href="/php/vendite/vendita.php" class="text-white me-2">Vendita</a>
        <a href="/php/gestione/riserva.php" class="text-white me-2">Riserva</a>
        <a href="/php/gestione/confezionamento.php" class="text-white me-2">Confezionamento</a>
        <a href="/php/gestione/spostamenti.php" class="text-white me-2">Spostamenti</a>
        <a href="/php/admin/prezzi.php" class="text-white me-2">Prezzi</a>
        <a href="/php/admin/report_vendite.php" class="text-white me-2">Report Vendite</a>
        <a href="/php/admin/report_giacenze.php" class="text-white me-2">Giacenze</a>
    <?php else: ?>
        <a href="/php/cliente/catalogo.php" class="text-white me-2">Catalogo</a>
        <a href="/php/cliente/i_miei_acquisti.php" class="text-white me-2">I miei acquisti</a>
    <?php endif; ?>
        <a href="/php/auth/logout.php" class="text-white ms-3">Logout</a>
    </div>
</nav>
<div class="container mt-4">