<?php
include 'config.php';


if (isset($_POST['nome']) && isset($_POST['idade'])) {

      $msg_atualizacao = '';
      $msg_erro_img = '';
      $msg_cadastro = '';
      $nome = $_POST['nome'];
      $idade = $_POST['idade'];
      $imagem = '';
      $target_dir = "./uploads/";
      $target_file = $target_dir . basename($_FILES["foto"]["name"]);

      if (file_exists($_FILES['foto']['tmp_name'])) {
            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES["foto"]["tmp_name"]);
            if ($check == false) {
                  /*   antigamente pegava essa mensagem e enviada para o callback do ajax e mostrava na view
                  agora eu pego a div da page e uso fadeTo  */
                  $msg_erro_img =
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4>Esse arquivo não é uma imagem, selecione uma imagem para salva-la!
                              </div>';
                  echo $msg_erro_img;
                  $imagem = '';
            } else {
                  $imagem = $target_file;
                  move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
            }
      }

      if ($_POST['id'] != null) {
            $id = $_POST['id'];
            $sql = "UPDATE pessoa SET nome = '$nome', idade = '$idade', img = '$imagem' where id = '$id'";
            if (mysqli_query($mysqli, $sql)) {
                  /*   antigamente pegava essa mensagem e enviada para o callback do ajax e mostrava na view
                  agora eu pego a div da page e uso fadeTo  */
                  $msg_atualizacao =
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="text-center">Atualizado com sucesso!</h4>
                        </div>';
            }
      } else {

            $sql = "INSERT INTO pessoa (nome, idade,img) VALUES('$nome','$idade', '$imagem')";

            if (mysqli_query($mysqli, $sql) == TRUE) {
                  /*   antigamente pegava essa mensagem e enviada para o callback do ajax e mostrava na view
                  agora eu pego a div da page e uso fadeTo  */
                  $msg_cadastro =
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="text-center">Cadastrado com sucesso!</h4>
                  </div>';
            }
      }

      $array = array(
            "msg_erro_img" => $msg_erro_img,
            "msg_atualizacao" => $msg_atualizacao,
            "msg_cadastro" => $msg_cadastro
      );

      echo json_encode($array);
}
