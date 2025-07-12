<?php

    include(__DIR__ . "/../sign-in/db.php");

    if(isset($_POST['submit'])){
        if (!empty($_POST['correo']) && !empty($_POST['password'])) {
            $_correo = test_input($_POST['correo']);
            $_password = test_input($_POST['password']);
            $consulta = 
        }
    }


?>