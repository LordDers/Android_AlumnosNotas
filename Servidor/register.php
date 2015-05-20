<?php
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //preguntamos si el usuario y la contraseña esta vacia
    //sino muere
    if (empty($_POST['username']) || empty($_POST['password'])) {
        
        // creamos el JSON
        $response["success"] = 0;
        $response["message"] = "Por favor entre el usuario y el password";
        
        die(json_encode($response));
    }
    
    //si no ha muerto (die), nos fijamos si existe en la base de datos
    $query        = " SELECT 1 FROM usuarios WHERE username = :user";
    
    //actualizamos el :user
    $query_params = array(
        ':user' => $_POST['username']
    );
    
    //ejecutamos la consulta
    try {
        // estas son las dos consultas que se van a hacer en la bse de datos
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        
        $response["success"] = 0;
        $response["message"] = "Database Error1. Please Try Again!";
        die(json_encode($response));
    }
    
    //buscamos la información
    //como sabemos que el usuario ya existe lo matamos
    $row = $stmt->fetch();
    if ($row) {
        
        $response["success"] = 0;
        $response["message"] = "Lo siento el usuario ya existe1";
        die(json_encode($response));
    }
    
    //Si llegamos a este punto, es porque el usuario no existe
    //y lo insertamos (agregamos)
    $query = "INSERT INTO usuarios ( username, password, tipo ) VALUES ( :user, :pass, 'usuario' ) ";
    
    //actualizamos los token
    $query_params = array(
        ':user' => $_POST['username'],
        ':pass' => md5($_POST['password'])
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
    
    //si hemos llegado a este punto
    //es que el usuario se agregado satisfactoriamente
    $response["success"] = 1;
    $response["message"] = "El usuario se ha agregado correctamente";
    echo json_encode($response);   
    
} else {
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title> Registro </title>
    <style type="text/css">@import "css/login.css";</style>
</head>

<body>
    <div id="envoltura">
        <div id="mensaje"></div>
            <div id="contenedor" class="curva">
                <div id="cabecera" class="tac">
                    <p> Registro </p>
                </div>
                <div id="cuerpo">
                    <form id="form-login" action="register.php" method="post" autocomplete="off">
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
                            <input name="submit" type="submit" id="submit" value="Enviar" class="boton" />
                            <a href='login.php'> <input name="submit" type="submit" id="submit" value="Iniciar Sesión" class="boton" /></a>
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