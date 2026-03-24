<?php
session_start();
require_once("../config/db.php");

$errore = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = hash('sha256', $_POST['password']);

    $res = mysqli_query($conn,
        "SELECT * FROM utenti WHERE username='$user' AND password='$pass'");

    if (mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['user']  = $row['username'];
        $_SESSION['ruolo'] = $row['ruolo'];
        $_SESSION['id']    = $row['id'];

        if ($row['ruolo'] === 'admin') {
            header("Location: /php/admin/dashboard.php");
        } else {
            header("Location: /php/cliente/dashboard.php");
        }
        exit;
    } else {
        $errore = "Credenziali non valide.";
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
    <h2>Login — Il Trullo</h2>
    <?php if ($errore): ?>
        <div class="alert alert-danger"><?= $errore ?></div>
    <?php endif; ?>
    <form method="POST">
        <input class="form-control mb-2" name="username" placeholder="Username" required>
        <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
        <button class="btn btn-primary w-100">Accedi</button>
    </form>
    <div class="mt-2">
        <a href="/public/register.php">Non hai un account? Registrati</a>
    </div>
</body>
</html>