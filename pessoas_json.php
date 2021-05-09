<?php 
	include 'config.php';

	$page = '';
	$limit = '';

	if(isset($_GET['page']) && isset($_GET['limit'])){
			$page =$_GET['page'];
			$limit = $_GET['limit'];

			$result = $mysqli->query("SELECT * from pessoa limit $page, $limit ");
			$array_size = $mysqli->query("select * from pessoa")->num_rows;
		
			$array_json = array();
	
			while ($linha = $result->fetch_assoc()) {
				$array_json[] = $linha;
			}
			
			echo json_encode($array_json, JSON_UNESCAPED_SLASHES, JSON_PRETTY_PRINT);
	}
