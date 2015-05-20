<?php
require("config.inc.php");

if (!empty($_POST)) {
    
    if ($_POST['asignatura'] != 'vacio') {

        $asignatura = $_POST['asignatura'];
        $query = " 
                SELECT
                    nota
                FROM $asignatura
                WHERE 
                    nombre = :nombre
                    and
                    apellido = :apellido 
            ";
        
        $query_params = array(
            ':nombre' => $_POST['nombre'],
            ':apellido' => $_POST['apellido']
        );
        
        try {
            $stmt   = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            
            $response["success"] = 0;
            $response["message"] = "Problema con la base de datos, vuelve a intetarlo";
            die(json_encode($response));
            
        }
        
        //la variable a continuación nos permitirará determinar 
        //si es o no la información correcta
        //la inicializamos en "false"
        $validated_info = false;
        
        //vamos a buscar a todas las filas
        $row = $stmt->fetch();
        if ($row) {
            $response["success"] = 1;
            $response["message"] = $asignatura . " " . $row['nota'];
            die(json_encode($response));
        } else {
            $response["success"] = 0;
            $response["message"] = $_POST['nombre'] . " " . $_POST['apellido'] . " no está en " . $asignatura;
            die(json_encode($response));
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "Debes seleccionar una asignatura";
        echo json_encode($response);
    }
} else {
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<title> Búsqueda </title>
	<style type="text/css">@import "css/login.css";</style>
</head>

<body>
	<div id="envoltura">
		<div id="mensaje"></div>
			<div id="contenedor" class="curva">
				<div id="cabecera" class="tac">
                    <p> Búsqueda </p>
				</div>
				<div id="cuerpo">
					<form id="form-login" action="pruebausuario.php" method="post" autocomplete="off">
						<p>
							<label for="nombre">Nombre:</label>
						</p>
						<p class="mb10">
							<input name="nombre" type="text" id="nombre" autofocus required />
						</p>
						<p>
							<label for="apellido">Apellido:</label>
						</p>
						<p class="mb10">
							<input name="apellido" type="text" id="apellido" required />
						</p>
                        <p>
                            <label for="asignatura">Asignatura:</label>
                        </p>
                        <p class="mb10">
                            <!--<input name="asignatura" type="text" id="asignatura" required />-->
                            <select name="asignatura">
                                <option value="vacio"> </option>
                                <option value="programacion"> Programación </option>
                                <option value="entornos"> Entornos </option>
                                <option value="bd"> Bases de Datos </option>
                                <option value="sistemas"> Sistemas </option>
                                <option value="lenguaje"> Lenguaje de Marcas </option>
                                <option value="ingles"> Inglés </option>
                                <option value="fol"> FOL </option>
                            </select>
                        </p>
						<p>
							<input name="submit" type="submit" id="submit" value="Buscar" class="boton" />
						</p>
					</form>
				</div>
				<div id="pie" class="tac">
					Sistema de Gesti&oacute;n de Contenidos
				</div>
			</div>
		</div>
	</div>
</body>

</html>


<?php
}
?>