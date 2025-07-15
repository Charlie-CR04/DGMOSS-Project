<?php

    //Carga de dependencias
    require __DIR__ . '/../vendor/autoload.php';

    //1. Validar que .env exista
    if(!file_exists(__DIR__ . '/.env')){
        die("Archivo .env no encontrado. Cópialo desde .env.example");
    }

    //2. Cargamos el .env de forma segura
    //Se usa esta variable para cargar las variables
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    try {
        $dotenv->safeLoad();

        //3. Cargamos credenciales
        $servername = $_ENV['DB_HOST'] ?? throw new Exception("DB_HOST no esta definido en .env, agreguelo");
        
        $username = $_ENV['DB_USER'] ?? throw new Exception("BD_USER no esta definido en .env, agreguelo");
            
        $password = $_ENV['DB_PASS'] ?? throw new Exception("BD_PASS no esta definida en .env, agreguela");
        
        $bd = $_ENV['DB_NAME'] ?? throw new Exception("BD_NAME no esta definida en .env, agreguela");

        //4. Conectar con la BD con manejo de errores
        $conexion = new mysqli($servername,$username,$password, $bd);
        //Check connection
        if($conexion->connect_error){
            throw new Exception("Conexión fallida: ". $conexion->connect_error);
        }

        //5. Configurar charset y timeout
        $conexion->set_charset("utf8mb4");
        $conexion->options(MYSQLI_OPT_CONNECT_TIMEOUT,5);

    } catch (Exception $e) {
        error_log($e->getMessage());   //Log Interno
        die("Error interno. Contacta al administrador."); //Mensaje al usuario
    }
?>