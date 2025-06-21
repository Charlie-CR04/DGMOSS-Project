<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PORTAL-DGMOSS</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style.css">
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <!-- Logo -->
            <a class="navbar-brand custom-brand" href="/dgmoss-project/" title="Ir a la página principal">
                <img src="/dgmoss-project/assets/img/Logo_GM1.png" alt="Logo DGMOSS" width="175" height="65" class="align">
            </a>
            
            <!-- Botón responsive para móvil (hamburguesa) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContenido">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!--Contenedor del menú de navegación-->
            <div class="collapse navbar-collapse" id="navbarContenido">
                <ul class="navbar-nav ms-auto">

                    <!-- Nosotros -->
                    <li class="nav-item">
                        <a class="nav-link" href="/dgmoss-project/info-page/nosotros.php" title="Ir a la página acerca de nosotros">Nosotros</a>
                    </li>

                    <!-- Direcciones -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownDirecciones" role="button" data-bs-toggle="dropdown" title="Desplegar menú de Direcciones">Direcciones</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dgmoss-project/direcciones/direccion1.php" title="Ir a la Dirección 1">Dirección 1</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direcciones/direccion2.php" title="Ir a la Dirección 2">Dirección 2</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direcciones/direccion3.php" title="Ir a la Dirección 3">Dirección 3</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/direcciones/direccion4.php" title="Ir a la Dirección 4">Dirección 4</a></li>
                        </ul>
                    </li>

                    <!-- Múltimedia-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMultimedia" role="button" data-bs-toggle="dropdown" title="Desplegar menú de Múltimedia">Múltimedia</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/dgmoss-project/multimedia/videos.php" title="Ir a la página de Videos">Videos</a></li>
                            <li><a class="dropdown-item" href="/dgmoss-project/multimedia/ligas_interes.php" title="Ir a la página de Ligas de Interés">Ligas de interés</a></li>
                        </ul>
                    </li>

                    <!-- Contacto -->
                    <li class="nav-item">
                        <a class="nav-link" href="/dgmoss-project/info-page/contacto.php" title="Ir a la pagina de Contacto">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>