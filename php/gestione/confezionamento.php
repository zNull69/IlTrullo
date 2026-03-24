<?php
require_once("../config/db.php");

$id = $_POST['prodotto'];
$num = $_POST['quantita'];

$r = mysqli_query($conn, "SELECT quantita FROM riserva WHERE id_prodotto=$id");
$row = mysqli_fetch_assoc($r);

if ($row['quantita'] < $num) die("Riserva insufficiente");

$nuova = $row['quantita'] - $num;

mysqli_query($conn, "UPDATE riserva SET quantita=$nuova WHERE id_prodotto=$id");

mysqli_query($conn, "INSERT INTO confezioni(id_prodotto,quantita,data_confezionamento,giacenza)
VALUES($id,$num,NOW(),$num)");
?>