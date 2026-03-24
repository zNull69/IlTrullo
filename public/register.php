<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-5">
    <h2>Registrazione</h2>
    <form method="POST" action="/php/auth/register.php">
        <input name="username" required class="form-control mb-2" placeholder="Scegli username">
        <input type="password" name="password" required class="form-control mb-2" placeholder="Scegli password">
        <button class="btn btn-primary">Registrati</button>
    </form>
    <a href="index.php">Hai già un account? Accedi</a>
</body>
</html>