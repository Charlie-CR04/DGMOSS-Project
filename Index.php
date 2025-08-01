<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirección General de Modernización del Sector Salud | Gobierno | gob.mx</title>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style_home.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/fonts.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/sub-navbar.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/hero-banner.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- GOB -->
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
</head>
<body>

    <?php
        include('includes/header.php');
    ?>

    <section class="hero-video-section">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="/dgmoss-project/assets/video/dgmoss.mp4" type="video/mp4">
            Tu navegador no soporta video HTML5
        </video>
        <div class="hero-video-overlay">
            <h1 class="hero-title">Dirección General de Modernización <br> del Sector Salud <br> DGMoSS</h1>
        </div>
    </section>
    <section class="hero-video-second">
        <div class="container py-4">
            <div class ="row justify-content-center">
                <div class="col-md-3 text-center text-md-start">
                    <a href="/dgmoss-project/direccion_1/direccion1.php" class="btn-second">
                        <h5 class="text-center">
                            ¿Qué es la DGMoSS?
                        </h5>
                    </a>
                </div>
                <div class="col-md-9">
                    <p class="text-light text-justify text-md-start">En la <strong>DGMoSS</strong> creemos firmemente que la salud digital debe ser un derecho universal, <br> no un privilegio.</p>
                </div>
            </div>
        </div>
    </section>

    <?php
        include(__DIR__ . '/includes/direcciones_main.php');
        include(__DIR__ . '/includes/colab.php');
        include(__DIR__ . '/includes/documentos_publi.php');
        include(__DIR__ . '/includes/redes_sociales.php');
    ?>
</body>
</html>