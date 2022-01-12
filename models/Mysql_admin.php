<?php 

	class Mysql_admin extends Server{
		
		public function __construct(){
			
			parent::__construct();

		}

		public function get_queries_engineChange($db = '', $e1 = '', $e2 = ''){
 			
 			/*generar los strings de las consultas para modificacion de motor de almacenamiento
 			la sentencia para modificar el motor de almacenamiento es ALTER TABLE nombre de tabla a modificar ENGINE = tipo de motor 
 			con la consulta select se seleccionan las tablas que tengan el motor a modificar
 			la condicion es que busque las que tengan el tipo de motor, que pertenescan a la base de datos seleccionada
 			y que el table_type sea base table
 			con concat se guardara en sql_statements la consulta alter table de cada tabla que se encuentre
 			la consulta devolvera un array y en la clave sql_statements estaran las consultas alter table de cada una de las tablas encontradas*/

			$query = $this -> crud("SELECT CONCAT('ALTER TABLE',' ',table_name,' ','ENGINE=$e1;') AS sql_statements, TABLE_NAME from information_schema.TABLES AS tb WHERE ENGINE='$e2' AND table_schema = '$db' AND TABLE_TYPE = 'BASE TABLE'", 
								   'Consulta elaborada exitosamente',
								   'No se encontraron datos para elaborar la consulta',
								   'Error al elaborar la consulta');

			return $query;

		}

		public function change_storage_engine($db = '', $engine = ''){

			$this -> db = "information_schema";
			$this -> connect();

			$continue = true;

			if($db == 'ninguno' || $engine == 'ninguno'){

				return  Messages::status_notice('no-data', 'No selecciono motor de almacenamiento o base de datos');

			}else{

				switch ($engine) {
				
					case 'InnoDB':
						
						$e2 = 'MyISAM';
						$e1 = 'InnoDB';
					
					break;
					case 'MyISAM':
					
						$e2 = 'InnoDB';
						$e1 = 'MyISAM';
					
					break;
					default:
					
						$continue = false;
					
					break;
				
				}

				if($continue){

					//elaborar las consultas para modificar el engine
					$query = $this -> get_queries_engineChange($db, $e1, $e2);				

					$sql = "";//variable que contendra la consulta multiple
					$count = 1;
					$tablesName = array();//guarda el nombre de las tablas

					if($query['status'] == 'done'){

						$numRows = mysqli_num_rows($query['data']);//obtener el numero de consultas
						echo $numRows;
						if($numRows > 0){

							while($row = mysqli_fetch_assoc($query['data'])){
																
								$sql .= $row['sql_statements'];//elaborar consulta multiple
								$tablesName[$count-1] = $row['TABLE_NAME'];//almacenar nombre de tablas
								$count++;
							
							}

						}
						
						if($sql != ''){

							$this -> db = $db;//base de datos en la que modificara el engine de las tablas

							$this -> connect();

							//modificacion del engine
							if($numRows > 1){

								$query2 = $this -> multiquery($sql, 
														'Motor de almacenamiento modificado exitosamente',
												 		'No se encontraron datos a modificar',
												 		'Error al modificar engine ');

							}else{

								$query2 = $this -> crud($sql, 
														'Motor de almacenamiento modificado exitosamente',
												 		'No se encontraron datos a modificar',
												 		'Error al modificar engine ');

							}
							

							if($query2['status'] == 'done'){
								
								//si se modifico correcatmente llega aqui
								return Messages::status_notice($query['status'], $query2['notice']);
									
							}else{

								//si hubo un error llega aqui

								$foreignKeys = '';//almacena el constraint y el nombre de la tabla en la que se removera foreign key

								$change['notice'] = '';

								for($i = 0; $i < count($tablesName); $i++){

									//obtener todas las foreign key y nombre de la tabla a la que se le removera
									$getForeignKey = $this -> get_foreign_key($tablesName[$i]);

									if($getForeignKey['status'] == 'done'){
										
										//si se obtubieron correctamente se elabora el string con la consulta multiple
										if($getForeignKey['data']['tableName'] != ''){
											$foreignKeys .= 'alter table '.$getForeignKey['data']['tableName'].' drop foreign key '.$getForeignKey['data']['constraintName'].';';
										}

									}

								}

								if($foreignKeys != ''){

									//si se llega aqui minimo hay un foreign key a remover
									$this -> db = $db;
									
									$this -> connect();

									$change = $this -> multiquery($foreignKeys,
																  'Foreign keys eliminadas exitosamente',
																  'No se encontraron foreign keys para eliminar',
																  'Error al eliminar foreign keys');


									if($change['status'] == 'done'){

										$this -> change_storage_engine($db, $engine);

										return Messages::status_notice($change['status'], $change['notice']);

									}else{

										return Messages::status_notice($change['status'], $change['notice']);

		
									}

									

								}
								
								return Messages::status_notice($query2['status'], $query2['notice']);

							}
						
						}else{

							return  Messages::status_notice('no-data','No se encontraron tablas para convertir en la base de datos <strong>'.$db.'</strong>');

						}	
				
					}else{

						return Messages::status_notice($query['status'], $query['notice']); 

					}	
								

				}else{

					return Messages::status_notice('no-data', 'Tipo de motor de almacenamiento desconocido');

				}

			}
			

		}

		private function get_foreign_key($table = ''){

			$this -> db = "information_schema";
			$this -> connect();

			$continue = true;

			$query = $this -> crud("select CONSTRAINT_NAME, TABLE_NAME from KEY_COLUMN_USAGE where REFERENCED_TABLE_NAME='$table'",
									'LLaves foraneas obtenidas con exito',
									'NO se encontraron llaves foraneas',
									'Error al obtener llaves foraneas');

			if($query['status'] == 'done'){

				$constraintName = mysqli_fetch_assoc($query['data']);

				$data = array('tableName' => $constraintName['TABLE_NAME'],
							  'constraintName' => $constraintName['CONSTRAINT_NAME']);

				return Status::get_status('done', $query['notice'], $data);

			}else{

				return Status::get_status($query['status'], $query['notice'], '');

			}
			
		}

		private function  format_select_optionsDB($optionValue = '', $optionTxt = ''){

			return "<option value='$optionValue'>$optionTxt</option>";

		}

		public function  show_select_optionsDB($data = ''){

			$options = '';

			while($row = mysqli_fetch_assoc($data)){

				$options .= $this -> format_select_optionsDB($row['schema_name'], $row['schema_name']);

			}

			return $options;
			
		}

		public function get_databases(){

			$this -> db = "information_schema";
			$this -> connect();

			$query = $this -> crud("select schema_name from schemata where schema_name!='information_schema'",
									'Bases de datos obtenidas con exito',
									'No se hayaeon bases de datos ',
									'Error al obtener las bases de datos');

			if($query['status'] == 'done'){

				$data = '';
				$db = $query['data'];

				return Status::get_status('done', $query['notice'], $db);

			}else{

				return Status::get_status($query['status'], $query['notice'], '');

			}
			
		}
	
	}

?>