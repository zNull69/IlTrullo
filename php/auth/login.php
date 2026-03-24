<?php
session_start();
require_once("../config/db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user = $_POST['username'];
    $pass = hash('sha256', $_POST['password']);

    $q = "SELECT * FROM utenti WHERE username='$user' AND password='$pass'";
    $res = mysqli_query($conn, $q);

    if (mysqli_num_rows($res) == 1) {
        $_SESSION['user'] = $user;
        header("Location: /php/admin/dashboard.php");
        exit;
    } else {
        echo "Credenziali non valide";
    }
}
?>