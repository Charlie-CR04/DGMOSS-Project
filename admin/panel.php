<?php
include(__DIR__ . '/../includes/auth.php'); 
requireAuth();

$isAdmin = (($_SESSION['rol'] ?? '') === 'admin');
$idDireccion = (int)($_SESSION['id_direccion'] ?? 0);

//  Dirección 4 no debe ver Categorías
$puedeVerCategorias = !($idDireccion === 4);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <link rel="stylesheet" href="/dgmoss/assets/css/panel.css">
</head>
<body>
    <?php include(__DIR__ . '/../admin/navbar_panel.php');?>

    <main class="panel-direcciones-container mt-5">
        <h2 class="titulo-panel">Panel de Administración</h2>
        <p class="text-center mb-5">Selecciona una opción:</p>

        <div class="container seccion-admin">
            <div class="row g-4">
                <?php if ($isAdmin): ?>
                    <!-- ADMIN -->
                    <div class="col-4 d-flex tarjet">
                        <a href="/dgmoss/admin/usuarios/usuarios.php" class="text-decoration-none text-black">
                            <img src="/dgmoss/assets/img/banners/2.png" alt="Panel usuarios" class="img-fluid">
                            <h5 class="text-center mt-5">Usuarios</h5>
                        </a>
                    </div>

                    <div class="col-4 d-flex tarjet">
                        <a href="/dgmoss/admin/direcciones/direcciones.php" class="text-decoration-none text-black">
                            <img src="/dgmoss/assets/img/banners/1.png" alt="Panel direcciones" class="img-fluid">
                            <h5 class="text-center mt-5">Direcciones</h5>
                        </a>
                    </div>

                    <?php if ($puedeVerCategorias): ?>
                        <div class="col-4 d-flex tarjet">
                            <a href="/dgmoss/admin/categorias/index_admin.php" class="text-decoration-none text-black">
                                <img src="/dgmoss/assets/img/banners/5.png" alt="Panel categorías" class="img-fluid">
                                <h5 class="text-center mt-5">Categorías</h5>
                            </a>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <!-- EDITOR -->
                    <div class="col-6 d-flex tarjet">
                        <a href="/dgmoss/admin/direcciones/editores.php" class="text-decoration-none text-black">
                            <img src="/dgmoss/assets/img/banners/4.png" alt="Mis documentos" class="img-fluid">
                            <h5 class="text-center mt-5">Mis Documentos</h5>
                        </a>
                    </div>

                    <?php if ($puedeVerCategorias): ?>
                        <div class="col-6 d-flex tarjet">
                            <a href="/dgmoss/admin/categorias/index_editor.php" class="text-decoration-none text-black">
                                <img src="/dgmoss/assets/img/banners/5.png" alt="Panel categorías" class="img-fluid">
                                <h5 class="text-center mt-5">Categorías</h5>
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>
</html>
