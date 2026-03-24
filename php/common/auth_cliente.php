<?php
if (!isset($_SESSION['ruolo'])) {
    header("Location: /public/index.php");
    exit;
}
?>