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