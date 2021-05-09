<?php 

$img= '';

if(isset($_GET['img'])){


$img = $_GET['img'];

if(file_exists($img)){

		$file = $img;
		
		$filetype=filetype($img);

		$filename=basename($file);

		header ("Content-Type: ".$filetype);

		header ("Content-Length: ".filesize($file));

		header ("Content-Disposition: attachment; filename=".$filename);

		readfile($file);

}




}




?>