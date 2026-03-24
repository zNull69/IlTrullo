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
<a class="text-white" href="/php/admin/dashboard.php">Dashboard</a>

<div>
<a href="/php/admin/prodotti.php" class="text-white me-2">Prodotti</a>
<a href="/php/admin/categorie.php" class="text-white me-2">Categorie</a>
<a href="/php/admin/clienti.php" class="text-white me-2">Clienti</a>
<a href="/php/vendite/vendita.php" class="text-white me-2">Vendita</a>
<a href="/php/admin/report_vendite.php" class="text-white me-2">Report</a>
<a href="/php/auth/logout.php" class="text-white">Logout</a>
</div>

</nav>

<div class="container mt-4">