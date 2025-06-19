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
            <a class="navbar-brand custom-brand" href="/dgmoss-project/">
                <img src="/dgmoss-project/assets/img/Logo_GM.png" alt="Logo DGMOSS" width="200" height="50" class="align">
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
                        <a class="nav-link" href="#">Nosotros</a>
                    </li>

                    <!-- Direcciones -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownDirecciones" role="button" data-bs-toggle="dropdown">Direcciones</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Dirección 1</a></li>
                            <li><a class="dropdown-item" href="#">Dirección 2</a></li>
                            <li><a class="dropdown-item" href="#">Dirección 3</a></li>
                        </ul>
                    </li>

                    <!-- Múltimedia-->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownMultimedia" role="button" data-bs-toggle="dropdown">Múltimedia</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"></a>Ligas de interés</li>
                            <li><a class="dropdown-item" href="#">Videos</a></li>
                        </ul>
                    </li>

                    <!-- Contacto -->
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contacto</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>
</html>