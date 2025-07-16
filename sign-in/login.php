<?php

//1. Llamamos a la BD
require_once(__DIR__ . "/conexion.php");

$_error = ""; //Variable para guardar errores (Se muestra en el formulario)

if($_SERVER["REQUEST_METHOD"] === "POST"){ //2. Solo si el usuario a enviado el formulario
    $correo = trim($_POST["correo"] ?? ""); // Limpiamos espacios y limpiamos error si no viene
    $password = trim($_POST["contraseña"] ?? "");

    //3. Validamos que no esten vacíos
    if(empty($correo) || empty($password)){
        $_error = "Por favor complete todos los campos";
    } else {
        //4. Preparamos la consulta para evitar inyecciones SQL
        $stmt = $conexion->prepare("SELECT id_usuario, nombre_usuario, contraseña FROM USUARIOS WHERE correo = ?");
        $stmt->bind_param("s", $correo); // "s" indica string

        //5. Ejecuta la consulta y obtenemos  resultados
        $stmt->execute(); 
        $resultado = $stmt->get_result(); 

        //6.  hay un usuario con ese correo
        if($resultado->num_rows === 1){ 
            $usuario = $resultado->fetch_assoc(); //Esta funcion convierte el resultado en un arreglo asociativo


            //7. Verificamos que la contraseña ingresada coincida con la hash almacenada
            if(password_verify($password, $usuario['contraseña'])){
                //Iniciamos sesión
                //session_start();
                //Si es correcta la contraseña: Guardamos en sesión
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
                $_SESSION['correo'] = $correo;

                //8. Redirigimos al panel de administración
                header("Location: /dgmoss-project/admin/panel.php");
                exit(); //Aquí se detiene el codigo inmediatamente
            } else {
                $_error = "La contraseña es incorrecta";
            }
        } else {
            $_error = "No se encontro una cuenta con ese correo";
        }
        $stmt->close(); //9. Cerramos statement (Cerrar la conexión con la BD) para liberar memoria
    }
}
?>