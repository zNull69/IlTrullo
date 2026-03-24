<?php
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST['username'];
    $pass = hash('sha256', $_POST['password']);

    // controllo duplicati
    $check = mysqli_query($conn, "SELECT * FROM utenti WHERE username='$user'");

    if (mysqli_num_rows($check) > 0) {
        die("Username già esistente");
    }

    mysqli_query($conn, "INSERT INTO utenti(username,password,ruolo)
    VALUES('$user','$pass','utente')");

    header("Location: /public/index.php");
    exit;
}
?>