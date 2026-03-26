<?php
    require_once(__DIR__ . "/../php/config/db.php");
    require_once(__DIR__ . "/../php/common/auth_admin.php");
    require_once(__DIR__ . "/../php/common/auth_cliente.php");
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Il Trullo — Accedi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main>
        <div class="content-side">
            <div class="auth-wrapper">
                <div class="auth-card">
                    <span class="auth-logo">Il Trullo</span>
                    <h1>Accedi</h1>
                    <p class="auth-sub">Inserisci le tue credenziali per continuare.</p>
            
                    <?php if (!empty($errore)): ?>
                        <div class="alert alert-danger mb-3"><?= htmlspecialchars($errore) ?></div>
                    <?php endif; ?>
            
                    <form action="../php/auth/login.php" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input class="form-control" id="username" name="username" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Accedi</button>
                    </form>
            
                    <hr class="my-3">
                    <div class="text-center">
                        <a href="register.php">Non hai un account? Registrati</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="side-img"></div>
    </main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>