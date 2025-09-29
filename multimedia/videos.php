<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videos</title>
    <!-- GOB -->
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/multimedia.css">
</head>
<body>
    <?php
    include('../home/header.php');
    include('breadcrumb.php');
    ?>
    <div class="container">
        <h1 class="text-center">Videos DGMOSS</h1>
        <p class="text-center">Bienvenido a la sección de videos de DGMOSS. </p>

        <section class="contenedor-videos">
            <div class="row videos">
                <div class="col-md-4 text-center">
                    <iframe src="https://www.youtube.com/embed/-l7GZcYTZ_w" 
                        frameborder="0"
                        title="Video 1"
                        allowfullscreen
                        width="100%"
                        height="250"></iframe>
                    <h5>En el 1er año de Gobierno #RutasDeLaSalud fortalece el abasto de medicamentos e insumos</h5>
                </div>
                <div class="col-md-4 text-center">
                    <iframe src="https://www.youtube.com/embed/uh9se0KYtfg" 
                        frameborder="0"
                        title="Video 2"
                        allowfullscreen
                        width="100%"
                        height="250"></iframe>
                    <h5>Tratamiento innovador a pacientes del Puente de la Concordia</h5>
                </div>
                <div class="col-md-4 text-center">
                    <iframe src="https://www.youtube.com/embed/4YTmDWH15xs" 
                        frameborder="0"
                        title="Video 3"
                        allowfullscreen
                        width="100%"
                        height="250"></iframe>
                    <h5> #MañaneraDelPueblo desde Palacio Nacional. Lunes 22 de septiembre 2025</h5>
                </div>
            </div>
        </section>
    </div>
</body>
</html>