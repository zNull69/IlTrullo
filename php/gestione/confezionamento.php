<?php
require_once("../config/db.php");
require_once("../common/header.php");

$id = $_POST['prodotto'];
$num = $_POST['quantita'];

$r = mysqli_query($conn, "SELECT quantita FROM riserva WHERE id_prodotto=$id");
$row = mysqli_fetch_assoc($r);

if ($row['quantita'] < $num) die("Riserva insufficiente");

$nuova = $row['quantita'] - $num;

mysqli_query($conn, "UPDATE riserva SET quantita=$nuova WHERE id_prodotto=$id");

mysqli_query($conn, "INSERT INTO confezioni(id_prodotto,quantita,data_confezionamento,giacenza)
VALUES($id,$num,NOW(),$num)");

// Recuperare la data di produzione dalla riserva
$riserva_row = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT quantita, data_produzione FROM riserva WHERE id_prodotto=$id ORDER BY id DESC LIMIT 1"));

$data_prod_originale = $riserva_row['data_produzione'];

// Nell'INSERT aggiungere il campo:
mysqli_query($conn, "INSERT INTO confezioni(id_prodotto, quantita, data_confezionamento, 
    data_produzione_originale, giacenza)
    VALUES($id, $num, NOW(), '$data_prod_originale', $num)");
?>