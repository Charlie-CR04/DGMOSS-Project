<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirección General de Modernización del Sector Salud | Gobierno | gob.mx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> 
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
</head>
<body>

    <?php
        include('includes/header.php');
    ?>

    <!--<section class="hero-section">
        <div class="hero-overlay">
            <h1 class="hero-title">Dirección General de <br> Modernización del Sector Salud</h1>
            <p>La DGMoSS proporciona información basada en la mejor evidencia disponible para una adecuada toma de decisiones <br> en materia de Tecnologías para la salud, en los servicios de salud en México.</p>
        </div>
    </section>-->

    <section class="hero-video-section">
        <video autoplay muted loop playsinline class="hero-video">
            <source src="/dgmoss-project/assets/video/back1.mp4" type="video/mp4">
            Tu navegador no soporta video HTML5
        </video>
        <div class="hero-video-overlay">
            <h1 class="hero-title">Dirección General de Modernización <br> del Sector Salud (DGMoSS)</h1>
        </div>
    </section>
    <section class="hero-video-second">
        <div class="container py-4">
            <div class ="row justify-content-center">
                <div class="col-md-3 text-center text-md-start">
                    <a href="/dgmoss-project/info-page/nosotros.php" class="btn-second">
                        <h5 class="text-center">
                            ¿Qué es la DGMoSS?
                        </h5>
                    </a>
                </div>
                <div class="col-md-9">
                    <p class="text-light text-justify text-md-start">La <strong>DGMoSS</strong> proporciona información basada en la mejor evidencia disponible para una adecuada toma de decisiones en materia de Tecnologías para la salud, en los servicios de salud en México.</p>
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

    <script src="/dgmoss-project/assets/js/script.js"></script>
</body>
    <?php
        include(__DIR__ . '/includes/footer.php');
    ?>
</html>