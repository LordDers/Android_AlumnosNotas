<?php
require("config.inc.php");

if (!empty($_POST)) {
    //obtenemos los usuarios respecto al usuario que llega por parámetro
    $query = " 
            SELECT
                username, 
                password,
                tipo
            FROM usuarios 
            WHERE 
                username = :username 
        ";
    
    $query_params = array(
        ':username' => $_POST['username']
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
        //en caso que no lo este, solo comparamos como acontinuación
        if (md5($_POST['password']) === $row['password']) {
            $login_ok = true;
        }

        if ($row['tipo'] == 'admin') {
            $tipo = true;
        }
    }

    if ($login_ok) {
        if ($tipo) {
            $response["success"] = 2;
            $response["message"] = "Login correcto admin!";
            die(json_encode($response));
        }
        else {
            $response["success"] = 1;
            $response["message"] = "Login correcto!";
            die(json_encode($response));
        }
    } else {
        $response["success"] = 0;
        $response["message"] = "Login INCORRECTO";
        die(json_encode($response));
    }
} else {
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<title> Login </title>
	<style type="text/css">@import "css/login.css";</style>
</head>

<body>
	<div id="envoltura">
		<div id="mensaje"></div>
			<div id="contenedor" class="curva">
				<div id="cabecera" class="tac">
                    <p> Login </p>
				</div>
				<div id="cuerpo">
					<form id="form-login" action="login.php" method="post" autocomplete="off">
						<p>
							<label for="usuario">Usuario:</label>
						</p>
						<p class="mb10">
							<input name="username" type="text" id="usuario" autofocus required />
						</p>
						<p>
							<label for="contrasenia">Contrase&ntilde;a:</label>
						</p>
						<p class="mb10">
							<input name="password" type="password" id="contrasenia" required />
						</p>
						<p>
							<input name="submit" type="submit" id="submit" value="Ingresar" class="boton" />
							<a href='register.php'> <input name="submit" type="submit" id="submit" value="Registrar" class="boton" /></a>
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