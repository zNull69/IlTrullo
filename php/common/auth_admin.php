<?php
if (!isset($_SESSION['ruolo']) || $_SESSION['ruolo'] !== 'admin') {
    header("Location: /php/cliente/dashboard.php");
    exit;
}
?>