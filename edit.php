<?php
include 'config.php';

if (isset($_POST['id'])) {
      $id = $_POST['id'];

      $sql = "SELECT * from pessoa where id = '$id' ";

      if ($result = mysqli_query($mysqli, $sql)) {

            while ($row = mysqli_fetch_array($result)) {
?>
                  <div class="text-center">
                        <form id="form-update" enctype="multipart/form-data">
                              <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />
                              <label>Id</label>
                              <input type="text" readonly name="id" class="form-control" value="<?php echo $row['id']; ?>" />
                              <label>Nome</label>
                              <input type="text" class="form-control" name="nome" value="<?php echo $row['nome']; ?>" />
                              <label>Idade</label>
                              <input type="text" class="form-control" name="idade" value="<?php echo $row['idade']; ?>" />
                              <label>Foto</label>
                              <input type="file" class="form-control" name="foto" />
                        </form>
                  </div>
<?php
            }
      }
}

?>