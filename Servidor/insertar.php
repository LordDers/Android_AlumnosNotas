<?php
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    
    if ($_POST['asignatura'] != 'vacio') {
        $asignatura = $_POST['asignatura'];
        $query = "INSERT INTO $asignatura ( nombre, apellido, nota ) VALUES ( :nombre, :apellido, :nota ) ";
        
        //actualizamos los token
        $query_params = array(
            ':nombre' => $_POST['nombre'],
            ':apellido' => $_POST['apellido'],
            ':nota' => $_POST['nota']
        );
        
        //ejecutamos la query y creamos el usuario
        try {
            $stmt   = $db->prepare($query);
            $result = $stmt->execute($query_params);
        }
        catch (PDOException $ex) {
            
            $response["success"] = 0;
            $response["message"] = "Error base de datos2. Porfavor vuelve a intentarlo";
            die(json_encode($response));
        }
        
        // llegado aquí, está bien
        $response["success"] = 1;
        $response["message"] = "Añadido";
        echo json_encode($response);
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
    <title> Prueba Admin </title>
    <style type="text/css">@import "css/login.css";</style>
</head>

<body>
    <div id="envoltura">
        <div id="mensaje"></div>
            <div id="contenedor" class="curva">
                <div id="cabecera" class="tac">
                    <p> Admin </p>
                </div>
                <div id="cuerpo">
                    <form id="form-login" action="pruebaadmin.php" method="post" autocomplete="off">
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
                            <label for="nota">Nota:</label>
                        </p>
                        <p class="mb10">
                            <input name="nota" type="text" id="nota" required />
                        </p>
                        <p>
                            <input name="submit" type="submit" id="submit" value="Enviar" class="boton" />
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