<?php
require_once("../config/db.php");

$id = $_POST['prodotto'];
$qta = $_POST['quantita'];

mysqli_query($conn, "INSERT INTO riserva(id_prodotto,quantita,data_produzione)
VALUES($id,$qta,NOW())");
?>