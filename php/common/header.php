<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: /public/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Il Trullo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <script src="/public/js/script.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="<?= $_SESSION['ruolo'] === 'admin' ? '/php/admin/dashboard.php' : '/php/cliente/dashboard.php' ?>">
            Il Trullo
        </a>
        <!-- Hamburger for mobile -->
        <button id="hamburger" class="navbar-toggler d-lg-none" type="button" aria-label="Menu" style="border-color: rgba(255,255,255,0.4);">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <!-- Desktop menu -->
        <div class="collapse navbar-collapse d-none d-lg-block" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
            <?php if ($_SESSION['ruolo'] === 'admin'): ?>
                <li class="nav-item"><a class="nav-link" href="/php/admin/prodotti.php">Prodotti</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/admin/categorie.php">Categorie</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/admin/clienti.php">Clienti</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/vendite/vendite.php">Vendita</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/gestione/riserva.php">Riserva</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/gestione/confezionamento.php">Confezionamento</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/gestione/spostamenti.php">Spostamenti</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/admin/prezzi.php">Prezzi</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/admin/report_vendite.php">Report</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/admin/report_giacenze.php">Giacenze</a></li>
            <?php else: ?>
                <li class="nav-item"><a class="nav-link" href="/php/cliente/catalogo.php">Catalogo</a></li>
                <li class="nav-item"><a class="nav-link" href="/php/cliente/i_miei_acquisti.php">I miei acquisti</a></li>
            <?php endif; ?>
                <li class="nav-item ms-lg-2">
                    <a class="nav-link" href="/php/auth/logout.php"
                        style="opacity: 0.6; font-size: 0.8rem;">Esci</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Offcanvas mobile menu -->
<div id="offcanvas" class="offcanvas-menu">
    <nav class="offcanvas-nav">
        <ul>
        <?php if ($_SESSION['ruolo'] === 'admin'): ?>
            <li><a href="/php/admin/prodotti.php">Prodotti</a></li>
            <li><a href="/php/admin/categorie.php">Categorie</a></li>
            <li><a href="/php/admin/clienti.php">Clienti</a></li>
            <li><a href="/php/vendite/vendite.php">Vendita</a></li>
            <li><a href="/php/gestione/riserva.php">Riserva</a></li>
            <li><a href="/php/gestione/confezionamento.php">Confezionamento</a></li>
            <li><a href="/php/gestione/spostamenti.php">Spostamenti</a></li>
            <li><a href="/php/admin/prezzi.php">Prezzi</a></li>
            <li><a href="/php/admin/report_vendite.php">Report</a></li>
            <li><a href="/php/admin/report_giacenze.php">Giacenze</a></li>
        <?php else: ?>
            <li><a href="/php/cliente/catalogo.php">Catalogo</a></li>
            <li><a href="/php/cliente/i_miei_acquisti.php">I miei acquisti</a></li>
        <?php endif; ?>
            <li><a href="/php/auth/logout.php" style="opacity: 0.6; font-size: 0.8rem;">Esci</a></li>
        </ul>
    </nav>
</div>
<div id="offcanvasOverlay" class="offcanvas-overlay"></div>

<style>
/* Offcanvas styles */
.offcanvas-menu {
    position: fixed;
    top: 0;
    right: -300px;
    width: 300px;
    height: 100vh;
    background: #fff;
    box-shadow: -2px 0 10px rgba(0,0,0,0.08);
    z-index: 1050;
    transition: right 0.3s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
    padding: 2rem 1.5rem 1.5rem 1.5rem;
}
.offcanvas-menu.active {
    right: 0;
}
.offcanvas-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.25);
    z-index: 1049;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s;
}
.offcanvas-overlay.active {
    opacity: 1;
    pointer-events: all;
}
.offcanvas-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.offcanvas-nav li {
    margin-bottom: 1.2rem;
}
.offcanvas-nav a {
    color: #222;
    font-size: 1.1rem;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}
.offcanvas-nav a:hover {
    color: #0d6efd;
}
@media (min-width: 992px) {
    #offcanvas, #offcanvasOverlay, #hamburger { display: none !important; }
}
</style>

<div class="container">