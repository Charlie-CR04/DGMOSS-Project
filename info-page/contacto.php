<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style.css">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style_info_page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <?php
        include('../includes/header.php');
    ?>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center">Contacto de la DGMoSS</h1>
    </div>

    <section class="contacto-dgmoss">
        <div class="container">
            <div class="row align-items-center mb-4">
                <!-- Tarjeta de contacto -->
                <div class="col-md-4 text-center">
                    <div class="contacto-main">
                        <div class="header-contacto-card">
                            <h5><i class="fas fa-address-card"></i>Datos</h5>
                        </div>
                        <div class="body-contacto-card">
                            <div class="contacto-item">
                                <i class="fas fa-map-marked-alt"></i>
                                <div class="direccion">
                                    <span class="contacto-label">Dirección:</span>
                                    <span class="contacto-value">Marina Nacional No. 60 P11 ala B Col. Tacuba, D.T. Miguel Hidalgo 11410 Ciudad de México</span>
                                </div>
                            </div>
                            <div class="contacto-item">
                                <i class="fas fa-phone"></i>
                                <div class="phone">
                                    <span class="contacto-label">Teléfono:</span>
                                    <span class="contacto-value">556392-4300</span>
                                </div>
                            </div>
                            <div class="contacto-item">
                                <i class="fas fa-users"></i>
                                <div class="atencion">
                                    <span class="contacto-label">Atención Ciudadana:</span>
                                    <span class="contacto-value"> 6392-4300 Ext. 52415</span>
                                </div>
                            </div>
                            <div class="contacto-item">
                                <i class="fas fa-envelope-square"></i>
                                <div class="mail">
                                    <span class="contacto-label">Email:</span>
                                    <span class="contacto-value">informes_dgmoss@salud.gob.mx</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>
<?php
    include('../includes/footer.php');
?> 
</html>