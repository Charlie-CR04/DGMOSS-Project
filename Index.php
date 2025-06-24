<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dirección General de Modernización del Sector Salud | Gobierno | gob.mx</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/dgmoss-project/assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <?php
        include('includes/header.php');
    ?>
</head>
<body>
    <section class="hero-section">
        <div class="hero-overlay">
            <h1 class="hero-title">Dirección General de Modernización del Sector Salud</h1>
        </div>
    </section>

<?php
    include('includes/carrusel.php');
?>

<?php
    include('includes/direcciones_main.php');
?>

<?php
    include('includes/documentos.php');
?>

<?php
    include('includes/carrusel2.php');
?>

<script src="/dgmoss-project/assets/js/script.js"></script>
</body>
<?php
    include('includes/footer.php');
?>
</html>