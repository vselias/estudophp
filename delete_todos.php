<?php
include 'config.php';

if (isset($_GET['list_remove'])) {
    $list = $_GET['list_remove'];

    $qtd_removidos = 0;
    foreach ($list as $id) {
        $sql = "DELETE from pessoa where id='$id' ";
        $query = mysqli_query($mysqli,$sql);
        $qtd_removidos += mysqli_affected_rows($mysqli);
        
    }
    echo $qtd_removidos;
}
