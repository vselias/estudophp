<?php
include 'config.php';
include 'constant_img.php';
$page = '';
$limit = '';
if (isset($_GET['page']) && isset($_GET['limit']) && isset($_GET['pesquisa'])) {
      $pesquisa = $_GET['pesquisa'];
      $page = $_GET['page'];
      $limit = $_GET['limit'];
      $sql = "SELECT * from pessoa where nome LIKE '$pesquisa%' LIMIT $page , $limit";
      $result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
      $lista = $result->fetch_assoc();
      $lista_size = $result->num_rows;
}
?>

<tbody id="corpo">
      <?php
      if ($lista_size > 0)
            do {
      ?>
            <tr>
                  <td><?php echo $lista['id']; ?></td>
                  <td>
                        <img width="<?php echo IMG_PESSOA_WIDTH; ?>" height="<?php echo IMG_PESSOA_HEIGHT; ?>" src="<?php echo (strlen($lista['img']) > 0 ? $lista['img'] : './uploads/user.jpg')  ?>" />
                  </td>
                  <td><?php echo $lista['nome']; ?></td>
                  <td><?php echo $lista['idade']; ?></td>
                  <td>
                        <span data-id="<?php echo $lista['id']; ?>" class="btnEdit">
                              <img width="<?php echo IMG_EDIT_WIDTH; ?>" height="<?php echo IMG_EDIT_HEIGHT; ?>" src="./icons/edit.png">
                        </span>
                  </td>
                  <td class="text-center">
                        <span class="btnDelet" data-id="<?php echo $lista['id']; ?>">
                              <img width="<?php echo IMG_DELETE_WIDTH; ?>" height="<?php echo IMG_DELETE_HEIGHT; ?>" src="./icons/excluir.png">
                        </span>
                        <input class="checkBoxDel form-check-input" type="checkbox" value="<?php echo $lista['id']; ?>" style="display: none;" />
                  </td>
                  <td>
                        <a href="<?php echo ($lista['img'] != "" ? 'download.php?img=' . $lista['img'] : '#') ?>">
                              <?php echo ($lista['img'] != "" ? (explode("/", $lista['img'])[2]) : "#") ?>
                        </a>
                  </td>
            </tr>
      <?php } while ($lista = $result->fetch_assoc()); ?>

</tbody>