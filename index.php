<?php
include 'config.php';
include 'constant_img.php';
/* 	$foto = $_FILES['foto']['tmp_name'];
list($width, $height) = getimagesize($foto);
$nova_img = imagecreatetruecolor(128, 128);
$img = imagecreatefromjpeg($foto);	
$n_width = 128;
$n_height = 128;
imagecopyresized($nova_img, $img, 0, 0, 0, 0, $n_width, $n_height, $width, $height);
imagejpeg($nova_img, $target_file); 	
*/

/*
if(isset($_GET['select_pag'])){
$itens_por_pagina  = $_GET['select_pag'];
}
if(isset($_GET['pagina']))
$pagina  = intval($_GET['pagina']);
*/

// editar
$nome_edit = '';
$idade_edit = '';
$id_edit = '';

//editar
$q = isset($_GET['q']);
if ($q == 'edit' && isset($_GET['id'])) {
	$id = $_GET['id'];
	$sql = "SELECT nome, idade FROM pessoa where id = '$id'";
	$result = $mysqli->query($sql);
	$dados = $result->fetch_array(MYSQLI_NUM);
	$nome_edit = $dados[0];
	$idade_edit = $dados[1];
	$id_edit = $_GET['id'];
} // fim edit

// inicio da logica paginacao
$itens_por_pagina = 5;
$pagina = 0;
$result = $mysqli->query("select * from pessoa LIMIT $pagina , $itens_por_pagina");
$lista = $result->fetch_assoc();
$lista_size = $mysqli->query("select * from pessoa")->num_rows;
$num_pag = ceil($lista_size / $itens_por_pagina);
//fim da logica de paginação inicial

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Estudo PHP JQUERY</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" href="./css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/style.css">
	<script src="./js/script.js"></script>
</head>

<body>


	<div class="container-fluid">


		<div class="row">

			<div class="col-sm-12">
				<div class="jumbotron ">
					<h2 class="text-center">Paginação com PHP!</h2>
					<h6 class="text-center">Sistema destinado ao aprendizado e queimar o tempo vago.</h6>
				</div>
				<div class="progress" style="display: none;">
					<div class="progress-bar">
						<label id="loading-label">Loading... </label>
						<span id="porcentagem"></span>
					</div>
				</div>
				<div id="msg-erro-img"></div>
				<div id="msg-cadastro"></div>
				<!-- loading -->
				<div class="d-flex justify-content-center">
					<div class="spinner-border" style="display: none;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
				<div class="coluna_form">
					<form id="form_salvar" action="index.php" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="id" value="<?php echo $id_edit; ?>" />
						<div class="form-group">
							<input required="true" type="text" placeholder="Nome" value="<?php echo $nome_edit; ?>" class="form-control mb-2 col-sm-6 offset-3" name="nome" />
						</div>
						<div class="form-group">
							<input required="true" type="number" value="<?php echo $idade_edit; ?>" min="0" placeholder="Idade" class="form-control col-sm-2 offset-3  mb-2" name="idade" />
						</div>
						<div class="form-group">
							<input type="file" accept="image/png, image/jpeg" placeholder="Selecione uma foto" class="form-control mb-2 col-sm-4 offset-3" name="foto" />
						</div>

						<div class="form-group text-center">
							<input type="submit" class="btn btn-primary mb-2" value="Salvar">
							<a href="index.php" class="btn btn-primary mb-2">Limpar</a>
						</div>
					</form>


					<div class="text-center mt-2">
						<input id="pesquisa" type="text" class="align-center form-control offset-3 
			col-sm-5" placeholder="Pesquise..." name="busca">
					</div>


					<div class="float-left ml-2 mt-2 form-group col-sm-3">
						<select class="form-control" name="select_pag" id="select_pag">
							<option value="5">5</option>
							<option value="10">10</option>
							<option value="15">15</option>
						</select>
					</div>
				</div>
			</div>
		</div>
		<div id="remover_todos" style="display: none;">
			<div class="alert alert-danger alert-dismissible fade show" role="alert">
				<h4 class="text-center">Todos registros selecionados foram apagados!</h4>
			</div>
		</div>
		<div id="selecione_registro_remover_todos"></div>
		<div id="msg-delete"></div>

		<div class="div_pag">
			<div class="col-lg-12 row justify-content-center">
				<table class="table table-striped table-hover">
					<thead class="thead-dark">
						<tr>
							<td>Id<img class="foto_sort" data="id" width="<?php echo IMG_SORT_WIDTH; ?>" height="<?php echo IMG_SORT_HEIGHT; ?> src=" ./icons/d-sort.png" /></td>
							<td>Foto</td>
							<td>Nome<img class="foto_sort" data="nome" width="<?php echo IMG_SORT_WIDTH; ?>" height="<?php echo IMG_SORT_HEIGHT; ?>" src="./icons/d-sort.png" /></td>
							<td>Idade<img class="foto_sort" data="idade" width="<?php echo IMG_SORT_WIDTH; ?>" height="<?php echo IMG_SORT_HEIGHT; ?>" src="./icons/d-sort.png" /></td>
							<td>Editar</td>
							<td>
								Remover
								<img id="list_controle" width="20" height="20" src="./icons/lista-controle.png" alt="">
								<br />
								<button id="selecionar_todos" style="display: none; margin-top: 5px" class="btn-outline-secondary">
									<img width="20px" height="20px" src="./icons/todos-selecionados.png" alt="">
									Selecionar Todos
								</button>
								<br />
								<button id="apagar_todos" style="display: none; margin-top:5px" type="button" class="btn-outline-danger">
									<img width="20px" height="20px" src="./icons/excluir.png" alt="">
									Apagar Todos
								</button>
							</td>
							<td>
								Download
							</td>
						</tr>
					</thead>

					<tbody id="corpo">

					</tbody>
				</table>

				<?php echo "<p id='total_registro' class='offset-md-10 mb-3' >Total de registros: " . $lista_size . ".</p>"; ?>

				<div class="row justify-content-center">
					<ul class="pagination">
						<li class="page-item">
							<a data="<?php echo '0 &' . $itens_por_pagina; ?>" class="btn-ajax active btn btn-primary">
								<img src="./icons/ultimo.png" width="16" height="16" alt="" />
								Primeiro
							</a>
						</li>

						<?php

						for ($i = 0; $i < $num_pag; $i++) { ?>
							<li class="page-item">
								<a class="btn btn-primary btn-ajax" data="<?php echo ($i * $itens_por_pagina) . '&' . $itens_por_pagina; ?>">
									<?php echo ($i + 1);  ?>
								</a>
							</li>
						<?php } ?>

						<li class="page-item">
							<a class="btn btn-primary btn-ajax" data="<?php echo ($num_pag - 1) * $itens_por_pagina . '&' . $itens_por_pagina; ?>">
								Último
								<img src="./icons/primeiro.png" width="16" height="16" alt="">
							</a>
						</li>

					</ul>
				</div>

			</div>
		</div>
		<div class="modal" data-toggle="modal" tabindex="-1">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">ATUALIZAR PESSOA</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">

						<div id="msg-atualizacao"></div>
						<div id="modal-content"></div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary btn-update">Atualizar</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div style="display: none;" class="alert-atualizado alert alert-success alert-dismissible fade show" role="alert">
		<button id="btn-alert-atualizado" type="button" class="close">
			<span>&times;</span>
		</button>
		<h4 class="text-center">Atualizado com sucesso!</h4>
	</div>

	<div style="display: none;" class="alert-cadastrado alert alert-success alert-dismissible fade show" role="alert">
		<button id="btn-alert-cadastrado" type="button" class="close">
			<span>&times;</span>
		</button>
		<h4 class="text-center">Cadastrado com sucesso!</h4>
	</div>

	<div style="display: none;" class="alert-deletado alert alert-danger alert-dismissible fade show" role="alert">
		<button id="btn-alert-deletado" type="button" class="close">
			<span>&times;</span>
		</button>
		<h4 class="text-center">Deletado com sucesso!</h4>
	</div>
	<div style="display: none;" class="selecione-registro alert alert-danger alert-dismissible fade show" role="alert">
		<button id="btn-selecione-registro" type="button" class="close">
			<span>&times;</span>
		</button>
		<h4 class="text-center">Selecione algum registro para remover!</h4>
	</div>
</body>

</html>