<!DOCTYPE html>
<html>

	<head>
		
		<meta charset="utf-8">

		<title>Storage engine converter</title>

		<link rel="stylesheet" type="text/css" href="views/theme/css/general.css">
		<link rel="stylesheet" type="text/css" href="views/theme/css/index.css">
	
	</head>
	<body>
		<div class="main-container">
			<header class="header">
				<div class="header-logo">
					Storage engine converter
				</div>
			</header>
			<section class="content">

				<div class="page-msg">
					<?php
						
						echo $changeEngine;

					?>
				</div>

				<form method="post" name="" id="" class="change-engineForm">
					
					<select name="engine" class="engine-name">
						<option value="ninguno">Seleccione una opcion</option>
						<option value="InnoDB">InnoDB</option>
						<option value="MyISAM">MySAM</option>
					</select>
					<select name="dataBase" class="db-name">
						<option value="ninguno">Seleccione una base de datos</option>
						<?php 

							echo $selectOptionsDB;
						
						 ?>
					</select>
					
					<input type="submit" name="convert" class="btn change-btn" value="Convertir">
				
				</form>

			</section>
		</div>

	</body>

</html>