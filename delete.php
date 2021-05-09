<?php
include 'config.php';

if (isset($_GET['id'])) {
      $id = $_GET['id'];
      $sql = "DELETE from pessoa where id = '$id'";
      if (mysqli_query($mysqli, $sql)) {
            $msg_remocao = '
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="text-center">Deletado com sucesso!</h4>
            </div>';
            if (mysqli_affected_rows($mysqli) >= 1)
                  echo $msg_remocao;
      }
}
