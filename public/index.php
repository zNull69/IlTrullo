<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="container mt-5">
    <h2>Login</h2>
    <form method="POST" action="/php/auth/login.php">
        <input class="form-control mb-2" name="username" placeholder="Username" required>
        <input type="password" class="form-control mb-2" name="password" placeholder="Password" required>
        <button class="btn btn-primary">Accedi</button>
    </form>
    <a href="register.php">Non hai un account? Registrati</a>
</body>
</html>