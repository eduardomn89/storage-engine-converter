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
			<section class="content gen-margin">

				<article class="description-wrap">
					
					<p>Con esta aplicacion puedes modificar el motor de almacenamiento de InnoDB a MyISAM y viceversa de las tablas de una base de datos. Selecciona el tipo de motor de almacenamiento que quieres usar, selecciona la base de datos y da click en convertir para que la aplicacion coloque el motor de almacenamiento seleccionado en todas las tablas.</p>

				</article>
				
				<div class="page-msg">
					<?php
						
						echo $changeEngine;

					?>
				</div>

				<article class="engine-formWrap">
					
					<form method="post" name="" id="" class="engine-form">
						
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

				</article>
	
			</section>
		
		</div>

	</body>

</html>