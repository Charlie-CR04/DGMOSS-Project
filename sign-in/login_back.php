<?php

//1. Llamamos a la BD
require_once(__DIR__ . "/../includes/conexion.php");

$_error = ""; //Variable para guardar errores (Se muestra en el formulario)

if($_SERVER["REQUEST_METHOD"] === "POST"){ //2. Solo si el usuario a enviado el formulario
    $correo = trim($_POST["correo"] ?? ""); // Limpiamos espacios y limpiamos error si no viene
    $password = trim($_POST["contraseña"] ?? "");

    //3. Validamos que no esten vacíos
    if(empty($correo) || empty($password)){
        $_error = "Por favor complete todos los campos";
    } else {
        $sql = "SELECT id_usuario, nombre_usuario, contraseña, rol, id_direccion
        FROM usuarios
        WHERE correo = ? LIMIT 1";

        //4. Preparamos la consulta para evitar inyecciones SQL
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $correo); // "s" indica string

        //5. Ejecuta la consulta y obtenemos  resultados
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 

        //6.  hay un usuario con ese correo
        if($resultado->num_rows === 1){ 
            $usuario = $resultado->fetch_assoc(); //Esta funcion convierte el resultado en un arreglo asociativo


            //7. Verificamos que la contraseña ingresada coincida con la hash almacenada
            if(password_verify($password, $usuario['contraseña'])){

                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                //Regenerar ID por seguridad
                session_regenerate_id(true);

                $_SESSION['id_usuario'] = (int)$usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['rol'] = $usuario['rol'];
                $_SESSION['id_direccion'] = $usuario['id_direccion'] ?? 0;

                //8. Redirigimos al panel de administración
                header("Location: /dgmoss/admin/panel.php", true, 303);
                exit(); //Aquí se detiene el codigo inmediatamente
            } else {
                $_error = "Credenciales inválidas";
            }
        } else {
            $_error = "Credenciales inválidas";
        }
        $stmt->close(); //9. Cerramos statement (Cerrar la conexión con la BD) para liberar memoria
    }
}
?>
