<?php

include 'config.php';
include 'constant_img.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");

$itens_por_pagina = 5;
$pagina = 0;

$selec_pag = '';

if (isset($_GET['select_pag'])) {
	$itens_por_pagina  = $_GET['select_pag'];
	$selec_pag = $_GET['select_pag'];
}

if (isset($_GET['pagina']))
	$pagina  = $_GET['pagina'];

if ($selec_pag == '') {
	$itens_por_pagina = 5;
}

$result = $mysqli->query("select * from pessoa LIMIT $pagina , $itens_por_pagina");
$lista = $result->fetch_assoc();
$lista_size = $mysqli->query("select * from pessoa")->num_rows;
$num_pag = ceil($lista_size / $itens_por_pagina);

?>
<div class="div_pag mt-2">
	<div class="row justify-content-center mt-2">
		<div class="col-lg-12">
			<div id="loading"></div>
			<table class="table table-striped table-hover">
				<thead class="thead-dark">
					<tr>
						<td>Id<img class="foto_sort" data="id" width="16" height="16" src="./icons/d-sort.png" /></td>
						<td>Foto</td>
						<td>Nome <img class="foto_sort" data="nome" width="16" height="16" src="./icons/d-sort.png" /></td>
						<td>Idade <img class="foto_sort" data="idade" width="16" height="16" src="./icons/d-sort.png" /></td>
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
						<td>Download</td>
					</tr>
				</thead>
				<tbody id="corpo">
					<?php
					if ($lista_size > 0)
						do {
					?>
						<tr>
							<td> <?php echo $lista['id']; ?> </td>
							<td>
								<img class="img-pessoa" src="<?php echo strlen($lista['img']) > 0 ? $lista['img'] : './uploads/user.jpg'  ?>" />
							</td>
							<td> <?php echo $lista['nome']; ?> </td>
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
									<?php ($lista['img'] != "" ? print_r(explode("/", $lista['img'])[2]) : print_r("#")) ?>
								</a>
							</td>
						</tr>
					<?php
						} while ($lista = $result->fetch_assoc());
					?>
				</tbody>
			</table>
			<div class="row justify-content-center">
				<ul class="pagination">

					<li class="page-item">
						<a data="<?php echo '0 &' . $itens_por_pagina; ?>" class="btn-ajax btn btn-primary">
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
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>