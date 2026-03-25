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