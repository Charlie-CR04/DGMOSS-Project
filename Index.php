<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirección General de Modernización del Sector Salud | Gobierno | gob.mx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Krub:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="preload" href="/dgmoss-project/assets/css/style.css" as="style">
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
            <h1 class="hero-title">Dirección General de <br> Modernización del Sector Salud <br> (DGMoSS)</h1>
        </div>
    </section>
    <section class="hero-video-second">
        <div class="container py-4">
            <div class ="row justify-content-center">
                <div class="col-md-3 text-center text-md-start">
                    <a href="/dgmoss-project/info-page/nosotros.php" class="btn-second">
                        <h5 class="text-center">
                            ¿Qué es DGMoSS?
                        </h5>
                    </a>
                </div>
                <div class="col-md-9">
                    <p class="text-light text-justify text-md-start">La DGMoSS proporciona información basada en la mejor evidencia disponible para una adecuada toma de decisiones en materia de Tecnologías para la salud, en los servicios de salud en México.</p>
                </div>
            </div>
        </div>
    </section>

    <?php
        include('includes/direcciones_main.php');
    ?>

    <?php
        include('includes/colab.php');  
    ?>

    <?php
        include('includes/documentos.php');
    ?>

    <?php
        include('includes/redes_sociales.php');
    ?>

    <script src="/dgmoss-project/assets/js/script.js"></script>
</body>
    <?php
        include('includes/footer.php');
    ?>
</html>