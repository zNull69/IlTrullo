<?php
session_start();
require_once("../config/db.php");

$errore = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = hash('sha256', $_POST['password']);

    $check = mysqli_query($conn, "SELECT id FROM utenti WHERE username='$user'");

    if (mysqli_num_rows($check) > 0) {
        $errore = "Username già esistente.";
    } else {
        mysqli_query($conn, "INSERT INTO utenti(username, password, ruolo)
            VALUES('$user', '$pass', 'utente')");
        header("Location: /php/auth/login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body class="container mt-5">
    <h2>Registrazione</h2>
    <?php if ($errore): ?>
        <div class="alert alert-danger"><?= $errore ?></div>
    <?php endif; ?>
    <form method="POST">
        <input name="username" required class="form-control mb-2" placeholder="Scegli username">
        <input type="password" name="password" required class="form-control mb-2" placeholder="Scegli password">
        <button class="btn btn-primary">Registrati</button>
    </form>
    <a href="/php/auth/login.php">Hai già un account? Accedi</a>
</body>
</html>