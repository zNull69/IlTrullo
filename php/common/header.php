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
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand" href="<?= $_SESSION['ruolo'] === 'admin' ? '/php/admin/dashboard.php' : '/php/cliente/dashboard.php' ?>">
            Il Trullo
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
            style="border-color: rgba(255,255,255,0.4);">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
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

<div class="container">ZZZ