<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/styles/main.css" rel="stylesheet">
    <link href="https://framework-gb.cdn.gob.mx/gm/v3/assets/images/favicon.ico" rel="shortcut icon">
    <script src="https://framework-gb.cdn.gob.mx/gm/v3/assets/js/gobmx.js"></script>
    <link rel="stylesheet" href="/dgmoss/assets/css/style_info_page.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <?php
        include('../home/header.php');
    ?>
</head>
<body>

    <div class="img-banner">
        <img src="/dgmoss/assets/img/backs/secGob.jpg" alt="Imagen del lugar" class="img-fluid">
    </div>

    <div class="container contacto">
        <h2 class="text-center">Contacto</h2>
        <div class="row seccion">
            <!-- Información -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <strong>Dirección General de Modernización <br> del Sector Salud</strong>
                    </div>
                    <div class="card-body">
                        <strong> <i class="bi bi-geo-alt-fill"></i> Dirección:</strong><br>
                        C. Agrarismo 227, Escandón II Secc,<br>
                        Miguel Hidalgo, 11800 Ciudad de México, CDMX
                        </p>
                        <p><strong> <i class="bi bi-telephone-fill"></i> Teléfono:</strong> 55-6392-4300</p>
                        <p><strong> <i class="bi bi-headset"></i> Atención Ciudadana:</strong> 6392-4300 Ext. 52415</p>
                        <p><strong> <i class="bi bi-envelope-fill"></i> Email:</strong> <a href="mailto:dgmoss@salud.gob.mx">dgmoss@salud.gob.mx</a></p>
                    </div>
                </div>
             </div>
            <!-- Mapa -->
            <div class="col-md-6 mapa text-center" id="map"></div>
            <div class="texto-map">
                <a href="https://www.google.com/maps/place/C.+Agrarismo+227,+Escand%C3%B3n+II+Secc,+Miguel+Hidalgo,+11800+Ciudad+de+M%C3%A9xico,+CDMX/@19.3992944,-99.1741245,18z/data=!3m1!4b1!4m6!3m5!1s0x85d1ff6e81ec2a39:0x13ae382bb09f4594!8m2!3d19.3992929!4d-99.173359!16s%2Fg%2F11yjhm7b_7?entry=ttu&g_ep=EgoyMDI1MDkyNC4wIKXMDSoASAFQAw%3D%3D" target="_blank">
                    Ver en Google Maps
                </a>
            </div>
        </div>
        
    </div>
    <script src="/dgmoss/assets/js/mapa.js"></script>
</body>
</html>
