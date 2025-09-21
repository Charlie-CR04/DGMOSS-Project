<?php
    include(__DIR__ . '/../includes/auth.php'); 
    requireAuth();

    $isAdmin = (($_SESSION['rol'] ?? '') === 'admin');
    $miDir = (int)($_SESSION['id_direccion'] ?? 0);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/panel.css">
        <!-- GOB -->
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/formulario-direcciones.css">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</head>
<body>
    <?php include(__DIR__ . '/../admin/navbar_panel.php');?>

    <main class="panel-direcciones-container mt-5">

        <h1 class="titulo-panel">Panel de Administración</h1>
        <p class="text-center mb-5">Selecciona una opción:</p>

        <div class="container seccion-admin">
            <div class="row g-4">
                <?php if($isAdmin): ?>
                    <!-- Admin -->
                    <div class="col-12 col-md-6 d-flex tarjet">
                        <a href="/dgmoss-project/admin/usuarios/usuarios.php" class="text-decoration-none text-black">
                            <img src="/dgmoss-project/assets/img/banners/2.png" alt="Panel usuarios" class="img-fluid">
                            <h5 class="text-center mt-5">Usuarios</h5>
                        </a>
                    </div>
                    <div class="col-12 col-md-6 d-flex tarjet">
                        <a href="/dgmoss-project/admin/direcciones/direcciones.php" class="text-decoration-none text-black">
                            <img src="/dgmoss-project/assets/img/banners/1.png" alt="Panel de direcciones" class="img-fluid">
                            <h5 class="text-center mt-5">Direcciones</h5>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Editores -->
                    <div class="col-12 col-md-6 offset-md-3 d-flex tarjet" >
                        <a href="/dgmoss-project/usuario/panel_usuario.php" class="text-decoration-none text-black">
                            <img src="/dgmoss-project/assets/img/banners/4.png" alt="Panel de documentos" class="img-fluid">
                            <h5 class="text-center mt-5">Documentos</h5>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

</body>
</html>